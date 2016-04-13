'''creates terms from API and updates issue.json
run after issue.py and vote-events.py
see terms-example.json
'''
import datetime
from operator import itemgetter

with open('../www/json/issue.json') as data_file:
    issue = json.load(data_file)
with open('../www/json/vote-events.json') as data_file:
    vote_events = json.load(data_file)

terms = {}
years = {}
for ve in issue['vote_events']:
    vee = issue['vote_events'][ve]
    if issue['vote_events'][ve]['available_vote_event']:
        year = datetime.datetime.strftime(datetime.datetime.strptime(vote_events[vee['identifier']]['start_date'],"%Y-%m-%d %H:%M:%S"),"%Y")
        years[year] = {
            "name": str(year),
            "start_date": str(year) + '-01-01 00:00:00',
            "end_date": str(year) + '-12-31 23:59:59',
            "identifier": year,
            "type": "year"
        }
        start_date = datetime.datetime.strftime(datetime.datetime.strptime(vote_events[vee['identifier']]['start_date'],"%Y-%m-%d %H:%M:%S"),"%Y-%m-%d")
        
        organization = vpapi.get("organizations",where={"classification": "chamber","founding_date":{"$lte":start_date},"$or":[{"dissolution_date":{"$gte":start_date}},{"dissolution_date": {"$exists": False}}]})
        since = organization["_items"][0]["founding_date"] + " 00:00:00"
        sinceyear =  datetime.datetime.strftime(datetime.datetime.strptime(since,"%Y-%m-%d %H:%M:%S"),"%Y")
        try:
            until = organization["_items"][0]["dissolution_date"] + " 23:59:59"
            untilyear = datetime.datetime.strftime(datetime.datetime.strptime(until,"%Y-%m-%d %H:%M:%S"),"%Y")
        except:
            until = ''
            untilyear = ''
        name = sinceyear + ' - ' + untilyear
        name = name.strip()
        terms[name] = {
            "name": name,
            "start_date": since,
            "type": "parliamentary_term"
        }
        if until != '':
            terms[name]["end_date"] = until

termsli = []
yearsli = []
for key in terms:
    termsli.append(terms[key])
for key in years:
    yearsli.append(years[key])
terms = sorted(termsli, key=itemgetter('name'), reverse=True)
years = sorted(yearsli, key=itemgetter("name"), reverse=True) 
i = len(terms)
for term in terms:
    term['identifier'] = str(i)
    i = i - 1

out = terms + years

with open("../www/json/terms.json", "w") as outfile:
    json.dump(out,outfile) 

#127.0.0.1:5000/cz/psp/organizations?where={"classification": "chamber","founding_date":{"$lte":"2001-02-27"},"dissolution_date":{"$gte":"2001-02-27"}}



#127.0.0.1:5000/cz/psp/organizations?where={"classification": "chamber","founding_date":{"$lte":"2014-02-27"},"$or":[{"dissolution_date":{"$gte":"2014-02-27"}},{"dissolution_date": {"$exists": false}}]}
