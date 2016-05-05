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
- `GET /users` (e.g., new user's id is _2_)
- `POST /rpc/verify_by_admin` with data {"id":_2_}

### Login
    POST /rpc/login

with body:

```json
{"email":"author@example.com", "pass": "example_password"}
```

Retrieve _token_ (e.g., "_G92b2xpdC5ldSIsInJvbGUiOiJhZG1pbiJ9.zWBW75LGRmGjQ87_")


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
    "parent_id": 2
}
```

**Get current organizations**

    GET /current_organizations?parent_id=eq.1  

### People
**Add new person** (any author or admin can add people)

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

    GET people_in_organizations?organization_id=eq.2
