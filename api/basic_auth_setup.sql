-- We create a database schema especially for auth information. We'll also need the postgres extension pgcrypto.

create extension if not exists pgcrypto;

CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- We put things inside the basic_auth schema to hide
-- them from public view. Certain public procs/views will
-- refer to helpers and tables inside.
create schema if not exists basic_auth;

-- Next a table to store the mapping from usernames and passwords to database roles. The code below includes triggers and functions to encrypt the password and ensure the role exists.

CREATE SEQUENCE basic_auth.users_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;
ALTER TABLE basic_auth.users_id_seq
  OWNER TO postgres;

create table if not exists
basic_auth.users (
  id       bigint NOT NULL DEFAULT nextval('basic_auth.users_id_seq'::regclass),
  email    text check ( email ~* '^.+@.+\..+$' ),
  pass     text not null check (length(pass) < 512),
  role     name not null check (length(role) < 512),
  verified boolean not null default false,
  name      text not null,
  attributes    jsonb,
  CONSTRAINT users_pkey PRIMARY KEY (id),
  constraint email_pkey UNIQUE (email)
  -- If you like add more columns, or a json column
);

create or replace function
basic_auth.check_role_exists() returns trigger
  language plpgsql
  as $$
begin
  if not exists (select 1 from pg_roles as r where r.rolname = new.role) then
    raise foreign_key_violation using message =
      'unknown database role: ' || new.role;
    return null;
  end if;
  return new;
end
$$;

drop trigger if exists ensure_user_role_exists on basic_auth.users;
create constraint trigger ensure_user_role_exists
  after insert or update on basic_auth.users
  for each row
  execute procedure basic_auth.check_role_exists();

create or replace function
basic_auth.encrypt_pass() returns trigger
  language plpgsql
  as $$
begin
  if tg_op = 'INSERT' or new.pass <> old.pass then
    new.pass = crypt(new.pass, gen_salt('bf'));
  end if;
  return new;
end
$$;

drop trigger if exists encrypt_pass on basic_auth.users;
create trigger encrypt_pass
  before insert or update on basic_auth.users
  for each row
  execute procedure basic_auth.encrypt_pass();

-- With the table in place we can make a helper to check passwords. It returns the database role for a user if the email and password are correct.

create or replace function
basic_auth.user_role(email text, pass text) returns name
  language plpgsql
  as $$
begin
  return (
  select role from basic_auth.users
   where users.email = user_role.email
     and users.pass = crypt(user_role.pass, users.pass)
  );
end;
$$;

-- Password Reset
-- When a user requests a password reset or signs up we create a token they will use later to prove their identity. The tokens go in this table.

drop type if exists token_type_enum cascade;
create type token_type_enum as enum ('validation', 'reset');

create table if not exists
basic_auth.tokens (
  token       uuid primary key,
  token_type  token_type_enum not null,
  email       text not null references basic_auth.users (email)
                on delete cascade on update cascade,
  created_at  timestamptz not null default current_date
);

-- In the main schema (as opposed to the basic_auth schema) we expose a password reset request function. HTTP clients will call it. The function takes the email address of the user.
create or replace function
request_password_reset(email text) returns void
  language plpgsql
  as $$
declare
  tok uuid;
begin
  delete from basic_auth.tokens
   where token_type = 'reset'
     and tokens.email = request_password_reset.email;

  select gen_random_uuid() into tok;
  insert into basic_auth.tokens (token, token_type, email)
         values (tok, 'reset', request_password_reset.email);
  perform pg_notify('reset',
    json_build_object(
      'email', request_password_reset.email,
      'token', tok,
      'token_type', 'reset'
    )::text
  );
end;
$$;

-- This function does not send any emails. It sends a postgres NOTIFY command. External programs such as a mailer listen for this event and do the work. The most robust way to process these signals is by pushing them onto work queues. Here are two programs to do that:
--     aweber/pgsql-listen-exchange for RabbitMQ
--     SpiderOak/skeeter for ZeroMQ
-- For experimentation you don't need that though. Here's a sample Node program that listens for the events and logs them to stdout.
-- var PS = require('pg-pubsub');
-- if(process.argv.length !== 3) {
--   console.log("USAGE: DB_URL");
--   process.exit(2);
-- }
-- var url  = process.argv[2],
--     ps   = new PS(url);
-- // password reset request events
-- ps.addChannel('reset', console.log);
-- // email validation required event
-- ps.addChannel('validate', console.log);
-- // modify me to send emails

-- Once the user has a reset token they can use it as an argument to the password reset function, calling it through the PostgREST RPC interface.
create or replace function
reset_password(email text, token uuid, pass text)
  returns void
  language plpgsql
  as $$
declare
  tok uuid;
begin
  if exists(select 1 from basic_auth.tokens
             where tokens.email = reset_password.email
               and tokens.token = reset_password.token
               and token_type = 'reset') then
    update basic_auth.users set pass=reset_password.pass
     where users.email = reset_password.email;

    delete from basic_auth.tokens
     where tokens.email = reset_password.email
       and tokens.token = reset_password.token
       and token_type = 'reset';
  else
    raise invalid_password using message =
      'invalid user or token';
  end if;
  delete from basic_auth.tokens
   where token_type = 'reset'
     and tokens.email = reset_password.email;

  select uuid_generate_v4() into tok;
  insert into basic_auth.tokens (token, token_type, email)
         values (tok, 'reset', reset_password.email);
  perform pg_notify('reset',
    json_build_object(
      'email', reset_password.email,
      'token', tok
    )::text
  );
end;
$$;

-- Email Validation
-- This is similar to password resets. Once again we generate a token. It differs in that there is a trigger to send validations when a new login is added to the users table.
create or replace function
basic_auth.send_validation() returns trigger
  language plpgsql
  as $$
declare
  tok uuid;
begin
  select uuid_generate_v4() into tok;
  insert into basic_auth.tokens (token, token_type, email)
         values (tok, 'validation', new.email);
  perform pg_notify('validate',
    json_build_object(
      'email', new.email,
      'token', tok,
      'token_type', 'validation'
    )::text
  );
  return new;
end
$$;

drop trigger if exists send_validation on basic_auth.users;
create trigger send_validation
  after insert on basic_auth.users
  for each row
  execute procedure basic_auth.send_validation();

  -- Editing Own User
  -- We'll construct a redacted view for users. It hides passwords and shows only those users whose roles the currently logged in user has db permission to access.
  create or replace view users as
select
        actual.id as id,
        actual.name as name,
        actual.role as role,
       '***'::text as pass,
       actual.email as email,
       actual.verified as verified,
       actual.attributes as attributes
from basic_auth.users as actual,
     (select rolname
        from pg_authid
       where pg_has_role(current_user, oid, 'member')
     ) as member_of
where actual.role = member_of.rolname;
  -- can also add restriction that current_setting('postgrest.claims.email')
  -- is equal to email so that user can only see themselves

-- Using this view clients can see themeslves and any other users with the right db roles. This view does not yet support inserts or updates because not all the columns refer directly to underlying columns. Nor do we want it to be auto-updatable because it would allow an escalation of privileges. Someone could update their own row and change their role to become more powerful.
-- We'll handle updates with a trigger, but we'll need a helper function to prevent an escalation of privileges.
create or replace function
basic_auth.clearance_for_role(u name) returns void as
$$
declare
  ok boolean;
begin
  select exists (
    select rolname
      from pg_authid
     where pg_has_role(current_user, oid, 'member')
       and rolname = u
  ) into ok;
  if not ok then
    raise invalid_password using message =
      'current user not member of role ' || u;
  end if;
end
$$ LANGUAGE plpgsql;

-- With the above function we can now make a safe trigger to allow user updates.
create or replace function
update_users() returns trigger
language plpgsql
AS $$
begin
  if tg_op = 'INSERT' then
    perform basic_auth.clearance_for_role(new.role);

    insert into basic_auth.users
      (name, role, pass, email, attributes, verified)
    values
      (new.name, new.role, new.pass, new.email, new.attributes,
      coalesce(new.verified, false));
    return new;
  elsif tg_op = 'UPDATE' then
    -- no need to check clearance for old.role because
    -- an ineligible row would not have been available to update (http 404)
    if not (basic_auth.current_role() = 'admin') then
        perform basic_auth.clearance_for_role(new.role);
    end if;

    update basic_auth.users as u set
      name   = new.name,
      --email  = new.email,
      role   = new.role,
      pass   = new.pass,
      attributes = new.attributes,
      verified = coalesce(new.verified, old.verified, false)
      where id = old.id;
    return new;
  elsif tg_op = 'DELETE' then
    -- no need to check clearance for old.role (see previous case)

    delete from basic_auth.users
     where basic_auth.email = old.email;
    return null;
  end if;
end
$$;

drop trigger if exists update_users on users;
create trigger update_users
  instead of insert or update or delete on
    users for each row execute procedure update_users();

-- Permissions 1
-- Basic table-level permissions. We'll add an the authenticator role which can't do anything itself other than switch into other roles as directed by JWT.
create role anon;
create role authenticator noinherit;
grant anon to authenticator;

grant usage on schema public, basic_auth to anon;

-- On top of the authenticator and anon access granted in the previous example, blogs have an author role with extra permissions.
create role author;
grant author to authenticator;
create role admin;
grant admin to authenticator;

grant select on pg_authid to author, admin;


-- Finally add a public function people can use to sign up. You can hard code a default db role in it. It alters the underlying basic_auth.users so you can set whatever role you want without restriction.
create or replace function
signup(email text, pass text, name text) returns void
as $$
  insert into basic_auth.users (email, pass, role, name) values
    (signup.email, signup.pass, 'author', signup.name);
$$ language sql;


-- Generating JWT
-- As mentioned at the start, clients authenticate with JWT. PostgREST has a special convention to allow your sql functions to return JWT. Any function that returns a type whose name ends in jwt_claims will have its return value encoded. For instance, let's make a login function which consults our users table.
-- First create a return type:
drop type if exists basic_auth.jwt_claims cascade;
create type basic_auth.jwt_claims AS (role text, email text);

-- And now the function:
create or replace function
login(email text, pass text) returns basic_auth.jwt_claims
  language plpgsql
  as $$
declare
  _role name;
  _verified boolean;
  _email text;
  result basic_auth.jwt_claims;
begin
  _email := email;
  select basic_auth.user_role(email, pass) into _role;
  select verified from basic_auth.users as u where u.email=_email limit 1 into _verified;
  if _role is null then
    raise invalid_password using message = 'invalid user or password';
  end if;
  -- TODO; check verified flag if you care whether users
  -- have validated their emails
  if not _verified then
    raise invalid_authorization_specification using message = 'user is not verified';
  end if;
  select _role as role, login.email as email into result;
  return result;
end;
$$;

-- An API request to login would look like this.
-- POST /rpc/login
-- { "email": "foo@bar.com", "pass": "foobar" }
-- Response
-- {
--   "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbWFpbCI6ImZvb0BiYXIuY29tIiwicm9sZSI6ImF1dGhvciJ9.KHwYdK9dAMAg-MGCQXuDiFuvbmW-y8FjfYIcMrETnto"
-- }
-- Try decoding the token at jwt.io. (It was encoded with a secret of secret which is the default.) To use this token in a future API request include it in an Authorization request header.
-- Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbWFpbCI6ImZvb0BiYXIuY29tIiwicm9sZSI6ImF1dGhvciJ9.KHwYdK9dAMAg-MGCQXuDiFuvbmW-y8FjfYIcMrETnto

-- Same-Role Users
-- You may not want a separate db role for every user. You can distinguish one user from another in SQL by examining the JWT claims which PostgREST makes available in the SQL variable postgrest.claims. Here's a function to get the email of the currently authenticated user.
create or replace function
basic_auth.current_email() returns text
  language plpgsql
  as $$
begin
  return current_setting('postgrest.claims.email');
exception
  -- handle unrecognized configuration parameter error
  when undefined_object then return '';
end;
$$;
-- Remember that the login function set the claims email and role. You can modify login to set other claims as well if they are useful for your other SQL functions to reference later.

-- Function: basic_auth.current_user_id()

-- DROP FUNCTION basic_auth.current_user_id();

CREATE OR REPLACE FUNCTION basic_auth.current_user_id()
  RETURNS bigint AS
$BODY$
begin
  return (SELECT id FROM basic_auth.users as u
      WHERE current_setting('postgrest.claims.email') = u.email
      LIMIT 1);
exception
  -- handle unrecognized configuration parameter error
  when undefined_object then return NULL;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION basic_auth.current_user_id()
  OWNER TO postgres;

  -- Function: basic_auth.current_role()

  -- DROP FUNCTION basic_auth.current_role();

  CREATE OR REPLACE FUNCTION basic_auth.current_role()
    RETURNS text AS
  $BODY$
  begin
    return (SELECT role FROM basic_auth.users as u
        WHERE current_setting('postgrest.claims.email') = u.email
        LIMIT 1);
  exception
    -- handle unrecognized configuration parameter error
    when undefined_object then return NULL;
  end;
  $BODY$
    LANGUAGE plpgsql VOLATILE
    COST 100;
  ALTER FUNCTION basic_auth.current_role()
    OWNER TO postgres;

-- Function: verify_by_admin(id bigint)

-- DROP FUNCTION verify_by_admin(bigint);

create or replace function verify_by_admin(id bigint) returns void as
$BODY$
declare
    _id bigint;
BEGIN
    _id := id;
    if basic_auth.current_role()='admin' then
        UPDATE basic_auth.users as u SET verified=true WHERE u.id=_id;
    else
        raise invalid_authorization_specification using message = 'only admin can do that';
    end if;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION verify_by_admin(bigint)
  OWNER TO postgres;

-- Permissions 2
-- Basic table-level permissions. We'll add an the authenticator role which can't do anything itself other than switch into other roles as directed by JWT.
-- anon can create new logins
-- allow signup
grant usage on sequence basic_auth.users_id_seq to anon;

grant insert on table basic_auth.users, basic_auth.tokens to anon;
grant select on table pg_authid, basic_auth.users to anon;
grant execute on function
  login(text,text),
  request_password_reset(text),
  reset_password(text,uuid,text),
  signup(text, text, text)
  to anon;

-- Conclusion
-- This section explained the implementation details for building a password based authentication system in pure sql. The next example will put it to work in a multi-tenant blogging API.



-- Finally we need to modify the users view from the previous example. This is because all authors share a single db role. We could have chosen to assign a new role for every author (all inheriting from author) but we choose to tell them apart by their email addresses. The addition below prevents authors from seeing each others' info in the users view.
create or replace view public.users as
  select
        actual.id as id,
        actual.name as name,
        actual.role as role,
         '***'::text as pass,
         actual.email as email,
         actual.verified as verified,
         actual.attributes as attributes
  from basic_auth.users as actual
  WHERE
      basic_auth.current_user_id() = actual.id
      OR
      (SELECT role FROM basic_auth.users as u
          WHERE basic_auth.current_user_id() = u.id
          LIMIT 1
      ) = 'admin';

create or replace view public.current_user as
    select
        actual.id as id,
        actual.name as name,
        actual.role as role,
         '***'::text as pass,
         actual.email as email,
         actual.verified as verified,
         actual.attributes as attributes
    from basic_auth.users as actual
    WHERE
      basic_auth.current_user_id() = actual.id;



  -- authors can edit comments/posts
  grant select, insert, update, delete
    on basic_auth.tokens, basic_auth.users to author;

-- setting other rights

grant usage on schema public, basic_auth to admin;
grant usage on schema public, basic_auth to author;

    ALTER TABLE public.users
      OWNER TO postgres;
    GRANT ALL ON TABLE public.users TO postgres;
    GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE public.users TO author;
    GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE public.users TO admin;
    GRANT SELECT ON TABLE public.users TO anon;

    ALTER TABLE public.current_user
      OWNER TO postgres;
    GRANT ALL ON TABLE public.current_user TO postgres;
    GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE public.current_user TO author;
    GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE public.current_user TO admin;
    GRANT SELECT ON TABLE public.current_user TO anon;
