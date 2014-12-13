'''creates vote-events from API and updates issue.json
see vote-events-example.json
'''

import json
import slugify

url = "https://spreadsheets.google.com/feeds/list/1uH1IuaSrc0V6MITGG6CKn4yk5Mt9GpeLrN4dwszdRhI/4/public/full?alt=json"
r = requests.get(url)
r.raise_for_status()
spreadsheet = json.loads(r.text)

people = json.load(open("../www/json/people.json"))
parties = json.load(open("../www/json/parties.json"))

def vote2vote(v):
    if v == 'ano':
        return 'yes'
    if v == 'ne':
        return 'no'
    if v == 'zdržel se':
        return 'abstain'
    return 'not present'

vote_events = {}
for row in spreadsheet["feed"]["entry"]:
    vote_event = {}
    vote_event['identifier'] = row['gsx$identifier']['$t'].strip()
    vote_event['start_date'] = row['gsx$date']['$t'].strip()
    vote_event['motion'] = {
        'id': row['gsx$identifier']['$t'].strip(),
        'requirement': 'simple majority',
        'link': row['gsx$motionlink']['$t']
    }
    votes = []
    for key in people:
        vote = {}
        p = people[key]['name'].lower().replace(' ','')
        vote['voter_id'] = key
        vote['group_id'] = people[key]['party_id']
        vote['option'] = vote2vote(row['gsx$'+p]['$t'].strip())
        #print(row['gsx$'+p]['$t'])
        votes.append(vote)
    vote_event['votes'] = votes
    vote_events[vote_event['identifier']] = vote_event
    #raise(Exception)


#{
#  "58303": { #identifier as key for easier access
#    "id": "53e916c4a874087103fa75ac",
#    "motion": { #embedded
#      "id": "53e916c4a874087103fa75ac",
#      "requirement": "simple majority",
#      "text": "Novela z. - daňový řád", 
#    },
#    "start_date": "2012-12-12T14:04:00",
#    "identifier": "58303",
#    "result": "pass",
#    "votes": [  #embedded
#      {
#        "voter_id": "53e916c4a874087103fa75ac",
#        "group_id": "53e916c4a874087103fa75ac",
#        "option": "yes"
#      },


with open("../www/json/vote-events.json", "w") as outfile:
    json.dump(vote_events,outfile)
