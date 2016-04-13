'''creates parties from API
see party-example.json
partly manually
'''

import json
import slugify

url = "https://spreadsheets.google.com/feeds/list/1uH1IuaSrc0V6MITGG6CKn4yk5Mt9GpeLrN4dwszdRhI/3/public/full?alt=json"
r = requests.get(url)
r.raise_for_status()
spreadsheet = json.loads(r.text)

parties = {}
for row in spreadsheet["feed"]["entry"]:
    party = {}
    party['name'] = row['gsx$name']['$t'].strip()
    party['abbreviation'] = row['gsx$abbreviation']['$t'].strip()
    party['color'] = row['gsx$color']['$t'].strip()
    party['id'] = slugify.slugify(row['gsx$abbreviation']['$t'].strip())
    party['position'] = slugify.slugify(row['gsx$position']['$t'].strip())
    party['children'] = [party['id']]
    parties[party['id']] = party

with open("../www/json/parties.json", "w") as outfile:
    json.dump(parties,outfile)
