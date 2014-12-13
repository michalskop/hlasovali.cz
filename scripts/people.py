'''creates people from API
see people-example.json
'''

import json
import slugify

url = "https://spreadsheets.google.com/feeds/list/1uH1IuaSrc0V6MITGG6CKn4yk5Mt9GpeLrN4dwszdRhI/2/public/full?alt=json"
r = requests.get(url)
r.raise_for_status()
spreadsheet = json.loads(r.text)

people = {}
for row in spreadsheet["feed"]["entry"]:
    person = {}
    person['id'] = slugify.slugify(row['gsx$name']['$t'].strip())
    person['name'] = row['gsx$name']['$t'].strip()
    person['gender'] = row['gsx$gender']['$t'].strip()
    #person['image'] = row['gsx$image']['$t'].strip()
    person['party_id'] = slugify.slugify(row['gsx$organizationabbreviation']['$t'].strip())
    people[person['id']] = person

with open("../www/json/people.json", "w") as outfile:
    json.dump(people,outfile)
