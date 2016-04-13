## Common
Address

    http(s)://api.example.com/

Headers

    Content-Type: application/json

## Create new admin
Manually in DB using
```json
{
    "email": "admin@example.com",
    "pass": "secret",
    "name": "Alice Admin",
    "verified": true
}
```

## Login

    POST /rpc/signup

 with data
 ```json
 {
     "email": "admin@example.com",
     "pass": "secret"
 }
 ```
 Get `token` from the reply, for example (simplified) _admin-token_

## Create a new author

    POST /rpc/signup

with data
```json
{
    "email": "author@example.com",
    "pass": "secret",
    "name": "Bob Author"
}
```
### Validate new author's emails
By themselves: TODO

By admin: Log in as admin first.

    GET /users?verified=eq.0

or

    GET /users?email=eq.author@example.com

Get author's `id` from the reply, for example _2_

    POST /rpc/verify_by_admin

with headers (add)

    Authorization: Bearer _admin-token_

with data
```json
{
    "id": 2
}
```

## Update user (including password)
### Update yourself as author
Log in as author and get the token, for example _author-token_

    PATCH /users

with headers (additional)

    Authorization: Bearer _author-token_

with data (for example)
```json
{
    "name": "Bob Z. Author"
}
```
### Update yourself or authors as admin
Log in as admin and get the token, for example _admin-token_

Get the users

    GET /users

Choose the user to update (e.g., with id _2_) and

    PATCH /users?id=eq.2

with headers (additional)

    Authorization: Bearer _admin-token_

with data (for example)
```json
{
    "name": "Bob Z. Author"
}
```
Warning: `PATCH /users` changes all authors and the admin!

## Forgotten password
Themselves: TODO

By admin: see _Update yourself or authors as admin_

## Get user id

    GET /users

## Create new City Hall
Only admin can create a new City Hall.

Log in as admin and get the token, for example _admin-token_

    POST /organizations

with headers (additional)

    Authorization: Bearer _admin-token_

with data (for example)
```json
{
    "name":"Plasy",
    "classification": "city hall",
    "founding_date": "2014-10-01"
}
```
Get own id (_Get user id_) (e.g, _1_)

Get id of the new City Hall (e.g., _13_):

    GET /organizations?name=Plasy

Allow yourself (or other users) to update the city hall:

    POST /organizations_users

with headers (additional)

    Authorization: Bearer _admin-token_

with data
```json
{
    "organization_id": 13,
    "user_id": 1,
    "active": true
}
```
## Create new Party (Political Group)
Only user with rights to update given City Hall can create parties as its suborganizations.

    POST /organizations

with headers (additional)

    Authorization: Bearer _author-token_

with data
```json
{
    "name":"Party X",
    "classification": "political group",
    "founding_date": "2014-10-01",
    "parent_id": 13,
    "attributes": {
        "abbreviation": "X"
    }
}
```
And allow yourself (or other users) to update the party (as in _Create new City Hall_)

## Create new person (representative)
Both admins and authors can create new people.

    POST /people

with headers (additional)

    Authorization: Bearer _author-token_

with data
```json
{
    "give_name":"Adolf",
    "last_name": "Representative"
}
```
## Create new membership
Only user with rights to update the organization can create its memberships

    POST /membership

with headers (additional)

    Authorization: Bearer _author-token_

with data
```json
{
    "organization_id": 13,
    "person_id": 1,
    "start_date": "2014-10-01"
}
```

## Create new motion
Only user with rights to update the organization can create its motions. First, get own user id (e.g., _2_) and the id of the organization/city hall (e.g., _13_).

    POST /motions

with headers (additional)

    Authorization: Bearer _author-token_

with data
```json
{
    "name": "City Clean Bill",
    "description": "Possibly several paragraphs of description of what the bill is about in html format",
    "organization_id": 13,
    "date": "2016-02-20",
    "date_precision": 10,
    "user_id": 2,
    "attributes": {
        "links":[
            {"link": "http://example.com/doc1.html", "text": "Text of the bill"},
            {"link": "https://example.com/doc2.html", "text": "Official decision"}
        ]
    }
}
```
## Create new tag
Only users with rights to update the motion can create its tags. First, get the id of the motion (e.g., _2_) and the authorization token.

    POST /tags

with headers (additional)

    Authorization: Bearer _author-token_

with data
```json
{
    "tag": "dogs",
    "motion_id": 2,
    "active": true
}
```
## Create vote event
A motion can have several vote events.

Only users with rights to update the motion can create its vote events. First, get the id of the motion (e.g., _2_) and the authorization token.

    POST /vote-events

with headers (additional)

    Authorization: Bearer _author-token_

with data
```json
{
    "motion_id": 2,
    "date": "2016-02-20",
    "date_precision": 10
}
```
## Create new vote
Only users with rights to update the motion (vote event) can create its votes.

1. Get the id of the city hall (organization)
2. Get the id of the party (organization with parent_id eq. the city hall's id)
3. Get the person' id (using `current_people_in_organizations` or `people_in_organizations`)
4. Get the vote_event_id (using `vote_events_motions`)

    POST /votes

with headers (additional)

    Authorization: Bearer _author-token_

with data
```json
{
    "vote_event_id": 2,
    "person_id": 1,
    "option": "yes",
    "organization_id": 13
}
```
