-- special tables for hlasovali.cz


-- Sequence: public.people_id_seq

-- DROP SEQUENCE public.people_id_seq;

CREATE SEQUENCE public.people_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;
ALTER TABLE public.people_id_seq
  OWNER TO postgres;

-- Table: public.people

-- DROP TABLE public.people;

CREATE TABLE public.people
(
  id bigint NOT NULL DEFAULT nextval('people_id_seq'::regclass),
  given_name text,
  family_name text NOT NULL,
  attributes jsonb,
  CONSTRAINT people_pkey PRIMARY KEY (id),
  CONSTRAINT people_given_name_family_name_attributes_key UNIQUE (given_name, family_name, attributes)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.people
  OWNER TO postgres;

  -- Sequence: public.organizations_id_seq

  -- DROP SEQUENCE public.organizations_id_seq;

  CREATE SEQUENCE public.organizations_id_seq
    INCREMENT 1
    MINVALUE 1
    MAXVALUE 9223372036854775807
    START 1
    CACHE 1;
  ALTER TABLE public.organizations_id_seq
    OWNER TO postgres;

-- Table: public.organizations

-- DROP TABLE public.organizations;

CREATE TABLE public.organizations
(
  id bigint NOT NULL DEFAULT nextval('organizations_id_seq'::regclass),
  name text NOT NULL,
  classification text NOT NULL,
  parent_id bigint,
  founding_date timestamptz,
  dissolution_date timestamptz,
  attributes jsonb,
  CONSTRAINT organizations_pkey PRIMARY KEY (id),
  CONSTRAINT organizations_name_classification_parent_id_key UNIQUE (name, classification, parent_id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.organizations
  OWNER TO postgres;

-- Sequence: public.memberships_id_seq

-- DROP SEQUENCE public.memberships_id_seq;

CREATE SEQUENCE public.memberships_id_seq
    INCREMENT 1
    MINVALUE 1
    MAXVALUE 9223372036854775807
    START 1
    CACHE 1;
ALTER TABLE public.memberships_id_seq
    OWNER TO postgres;

  -- Table: public.memberships

  -- DROP TABLE public.memberships;

 CREATE TABLE public.memberships
  (
  id bigint NOT NULL DEFAULT nextval('memberships_id_seq'::regclass),
  person_id bigint,
  organization_id bigint,
  start_date timestamptz not null DEFAULT NOW(),
  end_date timestamptz not null DEFAULT 'infinity',
  CONSTRAINT memberships_pkey PRIMARY KEY (id),
  CONSTRAINT memberships_organization_id_fkey FOREIGN KEY (organization_id)
      REFERENCES public.organizations (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT memberships_person_id_fkey FOREIGN KEY (person_id)
     REFERENCES public.people (id) MATCH SIMPLE
     ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT memberships_ukey UNIQUE (person_id, organization_id, start_date)
  )
  WITH (
  OIDS=FALSE
  );
  ALTER TABLE public.memberships
  OWNER TO postgres;

-- Sequence: public.motions_id_seq

-- DROP SEQUENCE public.motions_id_seq;

CREATE SEQUENCE public.motions_id_seq
      INCREMENT 1
      MINVALUE 1
      MAXVALUE 9223372036854775807
      START 1
      CACHE 1;
  ALTER TABLE public.motions_id_seq
      OWNER TO postgres;

-- Table: public.motions

-- DROP TABLE public.motions;

CREATE TABLE public.motions
(
    id bigint NOT NULL DEFAULT nextval('motions_id_seq'::regclass),
    name text NOT NULL,
    description text,
    date timestamptz,
    date_precision smallint,
    organization_id bigint NOT NULL,
    --vote_events int[],
    user_id bigint NOT NULL,
    attributes jsonb,
    CONSTRAINT motions_pkey PRIMARY KEY (id),
    CONSTRAINT motions_user_id_fkey FOREIGN KEY (user_id)
        REFERENCES basic_auth.users (id) MATCH SIMPLE
        ON UPDATE NO ACTION ON DELETE NO ACTION,
    CONSTRAINT motions_organization_id_fkey FOREIGN KEY (organization_id)
        REFERENCES public.organizations (id) MATCH SIMPLE
        ON UPDATE NO ACTION ON DELETE NO ACTION,
    CONSTRAINT motions_ukey UNIQUE (name, date, user_id, organization_id)
)
WITH (
OIDS=FALSE
);
ALTER TABLE public.motions
OWNER TO postgres;

-- Sequence: public.vote_events_id_seq

-- DROP SEQUENCE public.vote_events_id_seq;

CREATE SEQUENCE public.vote_events_id_seq
      INCREMENT 1
      MINVALUE 1
      MAXVALUE 9223372036854775807
      START 1
      CACHE 1;
  ALTER TABLE public.vote_events_id_seq
      OWNER TO postgres;

-- Table: public.vote_events

-- DROP TABLE public.vote_events;

CREATE TABLE public.vote_events
(
    id bigint NOT NULL DEFAULT nextval('vote_events_id_seq'::regclass),
    motion_id bigint NOT NULL,
    identifier text,
    start_date timestamptz NOT NULL,
    date_precision smallint,
    attributes jsonb,
    CONSTRAINT vote_events_pkey PRIMARY KEY (id),
    CONSTRAINT vote_events_motion_id_fkey FOREIGN KEY (motion_id)
        REFERENCES public.motions (id) MATCH SIMPLE
        ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (
OIDS=FALSE
);
ALTER TABLE public.vote_events
OWNER TO postgres;

-- Sequence: public.votes_id_seq

-- DROP SEQUENCE public.votes_id_seq;

CREATE SEQUENCE public.votes_id_seq
      INCREMENT 1
      MINVALUE 1
      MAXVALUE 9223372036854775807
      START 1
      CACHE 1;
  ALTER TABLE public.votes_id_seq
      OWNER TO postgres;

-- Table: public.votes

-- DROP TABLE public.votes;

CREATE TABLE public.votes
(
    id bigint NOT NULL DEFAULT nextval('votes_id_seq'::regclass),
    vote_event_id bigint NOT NULL,
    person_id bigint NOT NULL,
    option text NOT NULL,
    organization_id bigint NOT NULL,
    CONSTRAINT votes_pkey PRIMARY KEY (id),
    CONSTRAINT votes_person_id_fkey FOREIGN KEY (person_id)
        REFERENCES public.people (id) MATCH SIMPLE
        ON UPDATE NO ACTION ON DELETE NO ACTION,
    CONSTRAINT votes_vote_event_id_fkey FOREIGN KEY (vote_event_id)
        REFERENCES public.vote_events (id) MATCH SIMPLE
        ON UPDATE NO ACTION ON DELETE NO ACTION,
    CONSTRAINT votes_organization_id_fkey FOREIGN KEY (organization_id)
        REFERENCES public.organizations (id) MATCH SIMPLE
        ON UPDATE NO ACTION ON DELETE NO ACTION,
    CONSTRAINT votes_vote_event_id_person_id_key UNIQUE (vote_event_id, person_id)
)
WITH (
OIDS=FALSE
);
ALTER TABLE public.votes
OWNER TO postgres;

-- Table: public.organizations_users

-- DROP TABLE public.organizations_users;

CREATE TABLE public.organizations_users
(
    organization_id bigint NOT NULL,
    user_id bigint NOT NULL,
    active boolean NOT NULL default FALSE,
    CONSTRAINT organizations_users_pkey PRIMARY KEY (organization_id, user_id),
    CONSTRAINT organizations_users_organization_id_fkey FOREIGN KEY (organization_id)
        REFERENCES public.organizations (id) MATCH SIMPLE
        ON UPDATE NO ACTION ON DELETE NO ACTION,
    CONSTRAINT organizations_users_user_id_fkey FOREIGN KEY (user_id)
        REFERENCES basic_auth.users (id)
        ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
OIDS=FALSE
);
ALTER TABLE public.organizations_users
OWNER TO postgres;

-- Sequence: public.votes_id_seq

-- DROP SEQUENCE public.votes_id_seq;

CREATE SEQUENCE public.tags_id_seq
      INCREMENT 1
      MINVALUE 1
      MAXVALUE 9223372036854775807
      START 1
      CACHE 1;
  ALTER TABLE public.tags_id_seq
      OWNER TO postgres;

CREATE TABLE public.tags
(
    id bigint NOT NULL DEFAULT nextval('tags_id_seq'::regclass),
    tag text NOT NULL,
    motion_id bigint NOT NULL,
    active boolean NOT NULL default FALSE,
    CONSTRAINT tag_pkey PRIMARY KEY (tag, motion_id),
    CONSTRAINT tag_motion_id_fkey FOREIGN KEY (motion_id)
        REFERENCES public.motions (id) MATCH SIMPLE
        ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (
OIDS=FALSE
);
ALTER TABLE public.tags
OWNER TO postgres;

-- ####### #     # #     #  #####  ####### ### ####### #     #  #####
-- #       #     # ##    # #     #    #     #  #     # ##    # #     #
-- #       #     # # #   # #          #     #  #     # # #   # #
-- #####   #     # #  #  # #          #     #  #     # #  #  #  #####
-- #       #     # #   # # #          #     #  #     # #   # #       #
-- #       #     # #    ## #     #    #     #  #     # #    ## #     #
-- #        #####  #     #  #####     #    ### ####### #     #  #####


-- Only user with rights to update the organization
CREATE OR REPLACE FUNCTION organizations_users_update_check()
  RETURNS trigger AS
$func$
BEGIN
    IF (
        SELECT count(*) FROM public.organizations_users as ou
        LEFT JOIN basic_auth.users as u
        ON ou.user_id = u.id
        WHERE ou.active
        AND (ou.organization_id = OLD.id)
        AND (ou.user_id = basic_auth.current_user_id())
    ) = 0 THEN
        raise invalid_authorization_specification using message = 'current user is not allowed to do it';
    END IF;
    RETURN NEW;
END
$func$
LANGUAGE plpgsql;

-- Only user with rights to update refered organization can update/delete something
CREATE OR REPLACE FUNCTION organizations_users_update_something_check()
  RETURNS trigger AS
$func$
BEGIN
    IF (
        SELECT count(*) FROM public.organizations_users as ou
        LEFT JOIN basic_auth.users as u
        ON ou.user_id = u.id
        WHERE ou.active
        AND (ou.organization_id = OLD.organization_id)
        AND (ou.user_id = basic_auth.current_user_id())
    ) = 0 THEN
        raise invalid_authorization_specification using message = 'current user is not allowed to do it';
    END IF;
    RETURN NEW;
END
$func$
LANGUAGE plpgsql;

-- Only user with rights to update refered organization can update/delete something
CREATE OR REPLACE FUNCTION organizations_users_delete_something_check()
  RETURNS trigger AS
$func$
BEGIN
    IF (
        SELECT count(*) FROM public.organizations_users as ou
        LEFT JOIN basic_auth.users as u
        ON ou.user_id = u.id
        WHERE ou.active
        AND (ou.organization_id = OLD.organization_id)
        AND (ou.user_id = basic_auth.current_user_id())
    ) = 0 THEN
        raise invalid_authorization_specification using message = 'current user is not allowed to do it';
    END IF;
    RETURN OLD;
END
$func$
LANGUAGE plpgsql;

-- Only user with rights to update refered organization can insert something
CREATE OR REPLACE FUNCTION organizations_users_new_check()
  RETURNS trigger AS
$func$
BEGIN
    IF (
        SELECT count(*) FROM public.organizations_users as ou
        WHERE ou.active
        AND (ou.organization_id = NEW.organization_id)
        AND (ou.user_id = basic_auth.current_user_id())
    ) = 0 THEN
        raise invalid_authorization_specification using message = 'current user is not allowed to do it';
    END IF;
    RETURN NEW;
END
$func$
LANGUAGE plpgsql;

-- Only user with rights to update refered parent organization can give rights to themselves to update the organization itself
CREATE OR REPLACE FUNCTION organizations_users_parent_new_check()
  RETURNS trigger AS
$func$
BEGIN
    IF (NOT (
             basic_auth.current_role() = 'admin'
    ) AND  (
        SELECT count(*) FROM public.organizations as o
        LEFT JOIN public.organizations as op
        ON o.parent_id = op.id
        LEFT JOIN public.organizations_users as ou
        ON op.id = ou.organization_id
        WHERE ou.active
        AND (o.id = NEW.organization_id)
        AND (ou.user_id = basic_auth.current_user_id())
    ) = 0 )
    THEN
        raise invalid_authorization_specification using message = 'current user is not allowed to do it!';
    END IF;
    RETURN NEW;
END
$func$
LANGUAGE plpgsql;

-- CREATE OR REPLACE FUNCTION organizations_users_admin_check()
--     RETURNS trigger AS
-- $func$
-- BEGIN
--     IF (NOT (
--         basic_auth.current_role() = 'admin'
--     ) AND (
--         SELECT classification
--         FROM organizations as o
--         WHERE NEW.organization_id = o.id
--         LIMIT 1
--     ) = 'city hall')
--      THEN
--         raise invalid_authorization_specification using message = 'only admin can do that';
--     END IF;
--     RETURN NEW;
-- END
-- $func$
-- LANGUAGE plpgsql;

-- Only admin can create a new top-level organization (without parent)
CREATE OR REPLACE FUNCTION organizations_insert_admin_check()
    RETURNS trigger AS
$func$
BEGIN
    IF (
        NOT (basic_auth.current_role() = 'admin')
    AND
        NEW.parent_id IS NULL
    ) THEN
        raise invalid_authorization_specification using message = 'only admin can do that';
    END IF;
    RETURN NEW;
END
$func$
LANGUAGE plpgsql;

-- Only user with rights to modify parent organization can create its child organization
CREATE OR REPLACE FUNCTION organizations_insert_suborganization_check()
    RETURNS trigger AS
$func$
BEGIN
    IF (
        basic_auth.current_role() = 'admin'
    OR (
            (NEW.parent_id IS NOT NULL)
        AND
            (SELECT count(*)
            FROM organizations_users as ou
            WHERE NEW.parent_id = ou.organization_id
            AND basic_auth.current_user_id() = ou.user_id
            AND ou.active
            ) > 0
        )
    ) THEN
        RETURN NEW;
    ELSE
        raise invalid_authorization_specification using message = 'current user is not allowed to create a child organization for given parent_id';
    END IF;
END
$func$
LANGUAGE plpgsql;

--Only user with rights to update a motion can insert its tags/vote events
CREATE OR REPLACE FUNCTION motions_array_insert_check()
    RETURNS trigger AS
$func$
BEGIN
    IF
            (basic_auth.current_role()) = 'admin'
        OR
            (SELECT count(*)
            FROM motions as m
            WHERE basic_auth.current_user_id() = m.user_id
            AND m.id = NEW.motion_id) > 0
    THEN
        RETURN NEW;
    ELSE
        raise invalid_authorization_specification using message = 'current user is not allowed to create new tags or vote events for given motion';
    END IF;
END
$func$
LANGUAGE plpgsql;

--Only user with rights to update a motion can update its tags or vote events
CREATE OR REPLACE FUNCTION motions_array_update_check()
    RETURNS trigger AS
$func$
BEGIN
    IF
            (basic_auth.current_role()) = 'admin'
        OR
            (SELECT count(*)
            FROM motions as m
            WHERE basic_auth.current_user_id() = m.user_id
            AND m.id = OLD.motion_id) > 0
    THEN
        RETURN NEW;
    ELSE
        raise invalid_authorization_specification using message = 'current user is not allowed to update tags or vote events for given motion';
    END IF;
END
$func$
LANGUAGE plpgsql;

--Only user with rights to update a motion can delete its tags or vote events
CREATE OR REPLACE FUNCTION motions_array_delete_check()
    RETURNS trigger AS
$func$
BEGIN
    IF
            (basic_auth.current_role()) = 'admin'
        OR
            (SELECT count(*)
            FROM motions as m
            WHERE basic_auth.current_user_id() = m.user_id
            AND m.id = OLD.motion_id) > 0
    THEN
        RETURN OLD;
    ELSE
        raise invalid_authorization_specification using message = 'current user is not allowed to update tags or vote events for given motion';
    END IF;
END
$func$
LANGUAGE plpgsql;

--Only users with rights to update the motion (the vote event) can create its votes
CREATE OR REPLACE FUNCTION vote_insert_check()
    RETURNS trigger AS
$func$
BEGIN
    IF
            (basic_auth.current_role()) = 'admin'
        OR
            (
                SELECT count(*)
                FROM vote_events as ve
                LEFT JOIN motions as m
                ON ve.motion_id = m.id
                WHERE basic_auth.current_user_id() = m.user_id
                AND ve.id = NEW.vote_event_id
            ) > 0
    THEN
        RETURN NEW;
    ELSE
        raise invalid_authorization_specification using message = 'current user is not allowed to insert new votes for given vote event (motion)';
    END IF;
END
$func$
LANGUAGE plpgsql;

--Only users with rights to update the motion (the vote event) can update its votes
CREATE OR REPLACE FUNCTION vote_update_check()
    RETURNS trigger AS
$func$
BEGIN
    IF
            (basic_auth.current_role()) = 'admin'
        OR
            (
                SELECT count(*)
                FROM vote_events as ve
                LEFT JOIN motions as m
                ON ve.motion_id = m.id
                WHERE basic_auth.current_user_id() = m.user_id
                AND ve.id = OLD.vote_event_id
            ) > 0
    THEN
        RETURN NEW;
    ELSE
        raise invalid_authorization_specification using message = 'current user is not allowed to update new votes for given vote event (motion)';
    END IF;
END
$func$
LANGUAGE plpgsql;

--Only users with rights to update the motion (the vote event) can update its votes
CREATE OR REPLACE FUNCTION vote_delete_check()
    RETURNS trigger AS
$func$
BEGIN
    IF
            (basic_auth.current_role()) = 'admin'
        OR
            (
                SELECT count(*)
                FROM vote_events as ve
                LEFT JOIN motions as m
                ON ve.motion_id = m.id
                WHERE basic_auth.current_user_id() = m.user_id
                AND ve.id = OLD.vote_event_id
            ) > 0
    THEN
        RETURN OLD;
    ELSE
        raise invalid_authorization_specification using message = 'current user is not allowed to update new votes for given vote event (motion)';
    END IF;
END
$func$
LANGUAGE plpgsql;

-- ####### ######  ###  #####   #####  ####### ######   #####
--    #    #     #  #  #     # #     # #       #     # #     #
--    #    #     #  #  #       #       #       #     # #
--    #    ######   #  #  #### #  #### #####   ######   #####
--    #    #   #    #  #     # #     # #       #   #         #
--    #    #    #   #  #     # #     # #       #    #  #     #
--    #    #     # ###  #####   #####  ####### #     #  #####

-- Only admin can create a new top-level organization (without parent)
CREATE TRIGGER organization_insert_check
BEFORE INSERT ON organizations
FOR EACH ROW EXECUTE PROCEDURE organizations_insert_admin_check();

-- Only user with rights to update parent organization can create its child organization
CREATE TRIGGER suborganization_insert_check
BEFORE INSERT ON organizations
FOR EACH ROW EXECUTE PROCEDURE organizations_insert_suborganization_check();

-- Only user with rights to update the organization can update it
CREATE TRIGGER organization_update_check
BEFORE UPDATE OR DELETE ON organizations
FOR EACH ROW EXECUTE PROCEDURE organizations_users_update_check();

--Only user with rigths to update the organization can create its memberships
CREATE TRIGGER membership_new_check
BEFORE INSERT ON memberships
FOR EACH ROW EXECUTE PROCEDURE organizations_users_new_check();

--Only user with rights to update the organization can update its memberships
CREATE TRIGGER membership_update_check
BEFORE UPDATE ON memberships
FOR EACH ROW EXECUTE PROCEDURE organizations_users_update_something_check();

--Only user with rights to update the organization can delete its memberships
CREATE TRIGGER membership_delete_check
BEFORE DELETE ON memberships
FOR EACH ROW EXECUTE PROCEDURE organizations_users_delete_something_check();

--Only user with rights to update the organization can insert new motion of the organization
CREATE TRIGGER motion_new_check
BEFORE INSERT ON motions
FOR EACH ROW EXECUTE PROCEDURE organizations_users_new_check();

--Only user with rights to update the organization can uppdate a motion of the organization
CREATE TRIGGER motion_update_check
BEFORE UPDATE ON motions
FOR EACH ROW EXECUTE PROCEDURE organizations_users_update_something_check();

--Only user with rights to update the organization can uppdate a motion of the organization
CREATE TRIGGER motion_delete_check
BEFORE DELETE ON motions
FOR EACH ROW EXECUTE PROCEDURE organizations_users_delete_something_check();

--Only user with rights to update a motion can insert its tags
CREATE TRIGGER tags_insert_check
BEFORE INSERT ON tags
FOR EACH ROW EXECUTE PROCEDURE motions_array_insert_check();

--Only user with rights to update a motion can update its tags
CREATE TRIGGER tags_update_check
BEFORE UPDATE ON tags
FOR EACH ROW EXECUTE PROCEDURE motions_array_update_check();

--Only users with rights to update the motion can create its vote events.
CREATE TRIGGER vote_events_insert_check
BEFORE INSERT ON vote_events
FOR EACH ROW EXECUTE PROCEDURE motions_array_insert_check();

--Only users with rights to update the motion can update its vote events.
CREATE TRIGGER vote_events_update_check
BEFORE UPDATE ON vote_events
FOR EACH ROW EXECUTE PROCEDURE motions_array_update_check();

--Only users with rights to update the motion can delete its vote events.
CREATE TRIGGER vote_events_delete_check
BEFORE DELETE ON vote_events
FOR EACH ROW EXECUTE PROCEDURE motions_array_delete_check();

--Only users with rights to update the motion (the vote event) can create its votes
CREATE TRIGGER vote_insert_check
BEFORE INSERT ON votes
FOR EACH ROW EXECUTE PROCEDURE vote_insert_check();

--Only users with rights to update the motion (the vote event) can update its votes
CREATE TRIGGER vote_update_check
BEFORE UPDATE ON votes
FOR EACH ROW EXECUTE PROCEDURE vote_update_check();

--Only users with rights to update the motion (the vote event) can delete its votes
CREATE TRIGGER vote_delete_check
BEFORE DELETE ON votes
FOR EACH ROW EXECUTE PROCEDURE vote_delete_check();

-- Only user with rights to update refered parent organization can give rights to themselves to update the organization itself
CREATE TRIGGER organizations_users_insert_check
BEFORE INSERT ON organizations_users
FOR EACH ROW EXECUTE PROCEDURE organizations_users_parent_new_check();


-- CREATE TRIGGER organizations_users_check
-- BEFORE INSERT OR UPDATE OR DELETE ON organizations_users
-- FOR EACH ROW EXECUTE PROCEDURE organizations_users_admin_check();





-- Row level security
ALTER TABLE public.motions ENABLE ROW LEVEL SECURITY;
drop policy if exists authors_eigenedit on motions;
create policy authors_eigenedit on motions
  using (true)
  with check (
    motions.user_id = basic_auth.current_user_id()
    OR
    (SELECT role FROM basic_auth.users
    WHERE id=basic_auth.current_user_id()
    LIMIT 1) = 'admin'
);


-- #     # ### ####### #     #  #####
-- #     #  #  #       #  #  # #     #
-- #     #  #  #       #  #  # #
-- #     #  #  #####   #  #  #  #####
--  #   #   #  #       #  #  #       #
--   # #    #  #       #  #  # #     #
--    #    ### #######  ## ##   #####

-- Current organizations
create or replace view current_organizations as
select *
from organizations
where founding_date < NOW() and ((dissolution_date is null) or (dissolution_date > NOW()));

-- Current people
create or replace view current_people_in_organizations as
select
    p.given_name as given_name,
    p.family_name as family_name,
    m.person_id as person_id,
    m.start_date as start_date,
    m.end_date as end_date,
    m.organization_id as organization_id,
    o.name as name,
    o.classification as classification,
    o.founding_date as founding_date,
    o.dissolution_date as dissolution_date,
    p.attributes as person_attributes,
    o.attributes as organization_attributes,
    o.parent_id as parent_id
from people as p
left join memberships as m
on p.id = m.person_id
left join organizations as o
on o.id = m.organization_id
where (m.start_date < NOW()) and (m.end_date > NOW());

-- People
create or replace view people_in_organizations as
select
    p.given_name as given_name,
    p.family_name as family_name,
    m.person_id as person_id,
    m.start_date as start_date,
    m.end_date as end_date,
    m.organization_id as organization_id,
    o.name as name,
    o.classification as classification,
    o.founding_date as founding_date,
    o.dissolution_date as dissolution_date,
    p.attributes as person_attributes,
    o.attributes as organization_attributes,
    o.parent_id as parent_id
from people as p
left join memberships as m
on p.id = m.person_id
left join organizations as o
on o.id = m.organization_id;

-- vote events with their motions
create or replace view vote_events_motions as
SELECT
    ve.id as vote_event_id,
    ve.identifier as vote_event_identifier,
    ve.start_date as vote_event_start_date,
    ve.date_precision as vote_event_date_precision,
    ve.attributes as vote_event_attributes,
    m.id as motion_id,
    m.name as motion_name,
    m.description as motion_description,
    m.date as motion_date,
    m.date_precision as motion_date_precision,
    m.organization_id as organization_id,
    m.user_id as user_id,
    m.attributes as motion_attributes
FROM vote_events as ve
LEFT JOIN motions as m
ON ve.motion_id = m.id;

-- info about vote event
create or replace view public.vote_events_information as
    SELECT
        o.id as organization_id,
        o.name as organization_name,
        o.classification as organization_classification,
        o.founding_date as organization_founding_date,
        o.dissolution_date as organization_dissolution_date,
        o.attributes as organization_attributes,
        o.parent_id as parent_id,
        ve.id as vote_event_id,
        ve.start_date as vote_event_start_date,
        ve.date_precision as vote_event_date_precision,
        ve.attributes as vote_event_attributes,
        m.id as motion_id,
        m.name as motion_name,
        m.description as motion_description,
        m.date as motion_date,
        m.date_precision as motion_date_precision,
        m.attributes as motion_attributes,
        pu.id as user_id,
        pu.name as user_name,
        pu.attributes as user_attributes
    FROM vote_events as ve
    LEFT JOIN motions as m
    ON ve.motion_id = m.id
    LEFT JOIN organizations as o
    ON m.organization_id = o.id
    LEFT JOIN public_users as pu
    ON m.user_id = pu.id;

-- publicly available info about users
create or replace view public.users as
  select
        id,
        name,
        attributes
  from basic_auth.users;

-- political groups (parties) with most votes
create or replace view public.organizations_with_number_of_votes as
    select
        t.count,
        o2.*
    from
        (select
            count(*) as count,
            v.organization_id
        from votes as v
        left join organizations as o
        on v.organization_id = o.id
        group by v.organization_id
        ) as t
    left join organizations as o2
    on t.organization_id = o2.id;

-- people with organization (party), in which they ever voted
create or replace view public.people_voted_in_organizations as
    select
        p.id as person_id,
        p.given_name as person_given_name,
        p.family_name as person_family_name,
        p.attributes as person_attributes,
        o.id as organization_id,
        o.name as organization_name,
        o.classification as organization_classification,
        o.parent_id as organization_parent_id,
        o.founding_date as organization_founding_date,
        o.dissolution_date as organization_dissolution_date,
        o.attributes as organization_attributes
    from
        (select person_id, organization_id from votes
        group by person_id, organization_id) as t
    left join people as p
    on t.person_id = p.id
    left join organizations as o
    on o.id = t.organization_id;

-- select votes with info about people and parties
create or replace view public.votes_people_organizations as
    select
        p.id as person_id,
        p.given_name as person_given_name,
        p.family_name as person_family_name,
        p.attributes as person_attributes,
        o.id as organization_id,
        o.name as organization_name,
        o.classification as organization_classification,
        o.parent_id as organization_parent_id,
        o.founding_date as organization_founding_date,
        o.dissolution_date as organization_dissolution_date,
        o.attributes as organization_attributes,
        ve.id as vote_event_id,
        ve.motion_id as vote_event_motion_id,
        ve.start_date as vote_event_start_date,
        ve.date_precision as vote_event_date_precision,
        ve.attributes as vote_event_attributes,
        v.option as vote_option
    from votes as v
    left join people as p
    on v.person_id = p.id
    left join organizations as o
    on v.organization_id = o.id
    left join vote_events as ve
    on v.vote_event_id = ve.id;



 -- ######  ###  #####  #     # #######  #####
 -- #     #  #  #     # #     #    #    #     #
 -- #     #  #  #       #     #    #    #
 -- ######   #  #  #### #######    #     #####
 -- #   #    #  #     # #     #    #          #
 -- #    #   #  #     # #     #    #    #     #
 -- #     # ###  #####  #     #    #     #####
grant usage, select on all sequences in schema public to author, admin;

grant select, insert, update
      on all tables in schema public to author;

grant delete
    on public.memberships, public.motions, public.organizations, public.people, public.vote_events, public.votes to author;

grant select, insert, update, delete
    on all tables in schema public to admin;

grant select, insert, update
    on all tables in schema basic_auth to author;

grant select, insert, update, delete
    on all tables in schema basic_auth to admin;

-- REVOKE insert, update, delete
--     on organizations_users FROM author;

REVOKE insert, update, delete
    on public.public_users FROM author;

grant select
      on all tables in schema public to anon;




--grant usage on schema public, basic_auth to anon, author, admin;
