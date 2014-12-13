'''creates issue from google sheet
see issue-example.json
'''

import json
import requests
import slugify

url = "https://spreadsheets.google.com/feeds/list/1uH1IuaSrc0V6MITGG6CKn4yk5Mt9GpeLrN4dwszdRhI/1/public/full?alt=json"
r = requests.get(url)
r.raise_for_status()

spreadsheet = json.loads(r.text)

def pro2position(pro):
    if pro == "pro":
        return 1
    if pro == "proti":
        return -1
    else:
        return 0
  

   
issue = {
    "id": slugify.slugify(spreadsheet["feed"]["entry"][0]['gsx$name']['$t']),
    "title": spreadsheet["feed"]["entry"][0]['gsx$name']['$t'],
    "organization": spreadsheet["feed"]["entry"][0]['gsx$name']['$t'],
    "organization_link": spreadsheet["feed"]["entry"][0]['gsx$organizationlink']['$t'],
    "author": spreadsheet["feed"]["entry"][0]['gsx$author']['$t'],
    "author_link": spreadsheet["feed"]["entry"][0]['gsx$authorlink']['$t'],
    "pro_issue": 1,
    "vote_events": {}
}

url = "https://spreadsheets.google.com/feeds/list/1uH1IuaSrc0V6MITGG6CKn4yk5Mt9GpeLrN4dwszdRhI/4/public/full?alt=json"
r = requests.get(url)
r.raise_for_status()
spreadsheet = json.loads(r.text)

i = 0   #last term only
for row in spreadsheet["feed"]["entry"]:
    item = {}
    item["identifier"] = row["gsx$identifier"]["$t"].strip()
    item["date"] = row["gsx$date"]["$t"].strip()
    item["name"] = row["gsx$name"]["$t"].strip()
    item["description"] = row["gsx$description"]["$t"].strip()
    #item["pro_issue"] = pro2position(row["gsx$prospěšněproproti"]["$t"].strip())
    #item["weight"] = row["gsx$váhadefault1"]["$t"].strip()
    #if item["weight"] == "":
    #    item["weight"] = "1"
    subcat = row["gsx$tags"]["$t"].strip()
    item["subcategory"] = []
    if subcat != "":
        for sc in subcat.split(','):
            item["subcategory"].append(sc.strip())
    item["links"] = []
    #if row["gsx$odkaznatisk"]["$t"].strip() != "":
    #    item["links"].append({"url": row["gsx$odkaznatisk"]["$t"].strip(), "note": "Tisk"})
    #if item["pro_issue"] != 0 and item["identifier"] != "":
    issue["vote_events"][item["identifier"]] = item
    i = i + 1
    # last term only:
#    if (i > 39):
#        break

with open("../www/json/issue.json", "w") as outfile:
    json.dump(issue,outfile)
