import api

url = "http://localhost:3001/"

author = {
    "email": "michal@example.com",
    "pass": "example"
}

admin = {
    "email": "michal.skop@example.com",
    "pass": "example"
}

# as admin
api.login(admin['email'],admin['pass'])

data = {
    "name": "Radnice",
    "classification": "city halls",
    "founding_date": "2014-10-01"
}
r = api.post("organizations",data=data)
org_id = int(api.post_id(r))

data = {
    "name": "Plasy",
    "classification": "city hall",
    "founding_date": "2014-10-01",
    "parent_id": org_id
}
r = api.post("organizations",data=data)
org_id = int(api.post_id(r))

params = {
    "email": "eq." + author['email']
}
user = api.get_one("users",params=params)

data = {
    "organization_id": org_id,
    "user_id": user['id'],
    "active": True
}
api.post("organizations_users",data=data)

# as author
api.login(author['email'],author['pass'])

data = {
    "name": "Nezávislí pro Plasko",
    "classification": "political group",
    "founding_date": "2014-10-01",
    "parent_id": org_id
}
r = api.post("organizations",data=data)
party_id = int(api.post_id(r))

data = {
    "organization_id": party_id,
    "user_id": user['id'],
    "active": True
}
api.post("organizations_users",data=data)

data = [
    {
        "given_name": "Petr",
        "family_name": "Škop"
    },{
        "given_name": "Emanuel",
        "family_name": "Belbl"
    }
]
r = api.post("people",data=data)


people = api.get_all("people")
people_ids = []
for person in people:
    people_ids.append(person['id'])

data = []
for idd in people_ids:
    data.append({
        "person_id": idd,
        "organization_id": party_id,
        "start_date": "2014-10-01"
    })
r = api.post("memberships",data=data)

data = {
    "name": "City Clean Bill",
    "description": "Possibly several paragraphs of description of what the bill is about in html format",
    "date": "2015-02-20T00:00:00+00:00",
    "date_precision": 10,
    "organization_id": org_id,
    "user_id": user['id'],
    "attributes": {
      "links": [
        {
          "link": "http://example.com/doc1.html",
          "text": "Text of the bill"
        },
        {
          "link": "https://example.com/doc2.html",
          "text": "Official decision"
        }
      ]
    }
}
r = api.post("motions",data=data)
motion_id = int(api.post_id(r))

data = {
    "motion_id": motion_id,
    "identifier": "X-123",
    "start_date": "2015-02-20T00:00:00+00:00",
    "date_precision": 10,
}
r = api.post("vote_events",data=data)
ve_id = int(api.post_id(r))



data = []
for idd in people_ids:
    data.append({
        "person_id": idd,
        "vote_event_id": ve_id,
        "organization_id": party_id,
        "option": "yes"
    })
r = api.post("votes",data=data)
