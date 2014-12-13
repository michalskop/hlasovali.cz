'''creates organizations from API
see organization-example.json
'''

import json
import requests
import slugify

url = "https://spreadsheets.google.com/feeds/list/1uH1IuaSrc0V6MITGG6CKn4yk5Mt9GpeLrN4dwszdRhI/3/public/full?alt=json"
r = requests.get(url)
r.raise_for_status()
spreadsheet = json.loads(r.text)

organizations = {}
for row in spreadsheet["feed"]["entry"]:
    org = {}
    org['id'] = slugify.slugify(row['gsx$abbreviation']['$t'].strip())
    org['name'] = row['gsx$name']['$t'].strip()
    org['abbreviation'] = row['gsx$abbreviation']['$t'].strip()
    organizations[org['id']] = org
        
with open("../www/json/organizations.json", "w") as outfile:
    json.dump(organizations,outfile)
