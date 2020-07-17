#!/usr/bin/env python3

from bs4 import BeautifulSoup
import re
import requests
import time
import sys
import datetime
import json

html = open('./temp/temp.txt', 'r') # executed only by php will work
raw = BeautifulSoup(html.read(), 'lxml')

table = raw.find('tbody')

data = []
rows = table.find_all('tr')
for row in rows:
    cols = row.find_all('td')
    cols = [ele.text.strip() for ele in cols]
    data.append([ele for ele in cols if ele])

json_string = json.dumps(data,indent=4, sort_keys=True)
print(json_string)