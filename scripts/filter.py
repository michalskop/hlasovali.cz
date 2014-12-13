'''creates file for filtering
using people.json and party.json
'''

import json
from urllib.parse   import quote

data = []
with open("../www/json/parties.json") as infile:
    parties = json.load(infile)

with open("../www/json/people.json") as infile:
    people = json.load(infile)

with open("../www/json/issue.json") as infile:
    issue = json.load(infile)

categories = {}
for vek in issue['vote_events']:
    ve = issue['vote_events'][vek]
    for tag in ve['subcategory']:
        try:
            categories[tag]
        except:
            categories[tag] = {"name":tag, "count": 0 }
        categories[tag]['count'] = categories[tag]['count'] + 1

categoriesli = []
for k in categories:
    categoriesli.append(categories[k])
for ve in sorted(categoriesli, key=categoriesli.count, reverse=True):
    if ve['name'] != '':
        data.append({'name': ve['name'], 'link': '?tag='+ quote(ve['name']) })
    
for k in parties:
    party = parties[k]
    data.append({'name': party['abbreviation'] + ' - ' + party['name'], 'link': 'party.php?party=' + k})

for k in people:
    person = people[k]
    for identifier in person['identifiers']:
        if identifier['scheme'] == 'psp.cz/osoby':
            idd = identifier['identifier']
    data.append({'name': person['name'], 'link': 'person.php?identifier=' + idd})

with open("../www/json/filter.json", "w") as outfile:
    json.dump(data,outfile)
    
