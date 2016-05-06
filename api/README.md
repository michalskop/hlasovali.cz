# API
API is using Postgrest, see http://postgrest.com/api/reading/ for detailed description of usage and available operators.

The API request must be in following format:

    https://api.hlasovali.cz/resource?optional_parameters

with specified header:

    Content-Type: application/json

## Authentication
### Signup
    POST /rpc/signup

with body:

```json
{"name":"Joe Europe", "email": "author@example.com", "pass": "example_password"}
```

### Verification
Done by admins
- log in
- `GET /users?verified=eq.0` (e.g., new user's id is _2_)
- `POST /rpc/verify_by_admin` with data {"id":_2_}

### Login
    POST /rpc/login

with body:

```json
{"email":"author@example.com", "pass": "example_password"}
```

Retrieve _token_ (e.g., "_G92b2xpdC5ldSIsInJvbGUiOiJhZG1pbiJ9.zWBW75LGRmGjQ87_")

**Update authors**
Add header

    Authorization: Bearer _token_

Update:

    PATCH /users?id=eq.2

with data (e.g.)
```json
{
    "name": "Bob Z. Author"
}
```

Warning: `PATCH /users` changes all authors and the admin!

## Resources
The structure of the underlying DB: https://rawgit.com/michalskop/hlasovali.cz/master/api/schema/relationships.html

Only authors and admins can POST new data.

### Authorization
Add this header to the request:

    Authorization: Bearer _token_

(e.g., Authorization: Bearer G92b2xpdC5ldSIsInJvbGUiOiJhZG1pbiJ9.zWBW75LGRmGjQ87)

### Organizations
**New parent organization** (only admin can do it):

    POST /organizations

with data

```json
{
    "name": "City halls",
    "classification": "city halls",
}
```

Get the id of the parent organization:

    GET /organizations?classitication=eq.city halls

e.g., _1_

**New city hall** (only admin can add a new city hall):

    POST /organization

with data

```json
{
    "name": "Plasy",
    "classification": "city hall",
    "founding_date": "2014-10-01",
    "parent_id": 1
}
```

Add author(s) (only admin can add an author to a city hall):

    POST /organizations_users

with data

```json
{
    "organization_id": 2,
    "user_id": 2,
    "active": true
}
```

**New political group (party)** (only authors with rights for the city hall can do it):

    POST /organization

with data

```json
{
    "name": "Nezávislí pro Plasko",
    "classification": "political group",
    "founding_date": "2013-10-01",
    "parent_id": 2,
    "attributes": {
        "abbreviation": "NpP"
    }
}
```
And allow appropriate users (e.g., yourself) to update the party (as in _New City Hall_) TODO issue #1

**Get current organizations** (e.g., current parties in a one city hall)

    GET /current_organizations?parent_id=eq.1  

### People (representatives)
**Add new person (representative)** (any author or admin can add people)

    POST /people

with data

```json
{
    "given_name": "Petr",
    "family_name": "Doe",
    "attributes": {}
}
```

Note: attributes should be always added (hence empty json dictionary) as they serve also for distinguishing among people with the same name.

**Get current people in an organization** (e.g., in a city hall)

    GET /current_people_in_organizations?parent_id=eq.3

### Memberships
**Add people to organizations** (only authors with rights for the city hall can do it):

- `GET people?family_name=eq.Doe&given_name=eq.Petr` (to get the person_id)
- `GET organizations?parent_id=2&name=Nezávislí pro Plasko` (to get the organization_id)

    POST /memberships

with data

```json
{
    "person_id": 1,
    "organization_id": 2,
    "start_date": "2013-10-01"
}
```

**Get current members**

    GET /current_people_in_organizations?organization_id=eq.2

**Get members from any time**

    GET /people_in_organizations?organization_id=eq.2

### Motions
Only users (authors) with rights to update the organization can create its motions.

    POST /motions

with data
```json
{
    "name": "City Clean Bill",
    "description": "Possibly several paragraphs of description of what the bill is about in html format",
    "organization_id": 2,
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

### Tags
Only users with rights to update the motion can create its tags.

    POST /tags

with data
```json
[
  {
    "id": 1,
    "tag": "dogs",
    "motion_id": 1,
    "active": true
  },
  {
    "id": 2,
    "tag": "cats",
    "motion_id": 1,
    "active": true
  }
]
```

## Vote events
One motion can have several vote events.

Only users with rights to update the motion can create its vote events.


**Create a new vote event**
    POST /vote-events

with data
```json
{
    "motion_id": 1,
    "identifier": "2015-105",
    "start_date": "2015-02-20",
    "date_precision": 10
}
```

**Get all vote events for a motion**
    GET /vote_events_motions?

## Votes (individual votes)
Only users with rights to update the motion (vote event) can create its votes.

1. Get the id of the city hall (organization)
2. Get the person's id and party's id (using `current_people_in_organizations` or `people_in_organizations`)
3. Get the vote_event_id (using `vote_events_motions`)

    POST /votes

with data
```json
{
    "vote_event_id": 1,
    "person_id": 1,
    "option": "yes",
    "organization_id": 3
}
```
Note: _option_ may be _yes_, _no_, _abstain_, _absent_, _not voting_; see http://www.popoloproject.com/specs/vote.html
