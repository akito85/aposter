#!/usr/bin/env python3

from bs4 import BeautifulSoup
import re
import requests
import time
import sys
import datetime
import json

now = datetime.datetime.now()
organization_code = sys.argv[1]

# Var: satker_id, year
# https://semantik.bppk.kemenkeu.go.id/site/index?year=2020&satker_id=18

# Var: satker_id, year, ActivitySearch[name]
# https://semantik.bppk.kemenkeu.go.id/site/index?ActivitySearch%5Bname%5D=pelatihan+manajemen&ActivitySearch%5Bstart%5D=&ActivitySearch%5Bend%5D=&year=2020&satker_id=18

# Var: satker_id, year, ActivitySearch[name], ActivitySearch[start], ActivitySearch[end]
# https://semantik.bppk.kemenkeu.go.id/site/index?ActivitySearch[name]=pelatihan+manajemen&ActivitySearch[start]=&ActivitySearch[end]=&year=2020

# Var: ActivitySearch[start] & ActivitySearch[end] format yyyy-mm-dd
# https://semantik.bppk.kemenkeu.go.id/site/index?ActivitySearch%5Bname%5D=&ActivitySearch%5Bstart%5D=2020-11-23&ActivitySearch%5Bend%5D=&satker_id=&year=2020

# Var : page
# https://semantik.bppk.kemenkeu.go.id/site/index?year=2020&satker_id=18&page=5

# Get the total page
url = 'https://semantik.bppk.kemenkeu.go.id/site/index?year=' + str(now.year) + '&satker_id=' + str(organization_code)
page = requests.get(url)
html_page = BeautifulSoup(page.content, 'html.parser')

# Get the result from page 1
table = html_page.find('tbody')

# Map page one into array
data = []
rows = table.find_all('tr')
for row in rows:
    cols = row.find_all('td')
    cols = [ele.text.strip() for ele in cols]
    data.append([ele for ele in cols if ele])

# Get the total page from pagination in page 1
list_page = html_page.find('ul', {'class': 'pagination'})

# Map the rest of the page
if list_page is not None:
    total_page = len(list_page.find_all('a'))
    for p in range(2, total_page):
        url = 'https://semantik.bppk.kemenkeu.go.id/site/index?year=' + str(now.year) + '&satker_id=' + str(organization_code) + '&page=' + str(p)
        page = requests.get(url)
        html_page = BeautifulSoup(page.content, 'html.parser')

        table = html_page.find('tbody')
        rows = table.find_all('tr')
        for row in rows:
            cols = row.find_all('td')
            cols = [ele.text.strip() for ele in cols]
            data.append([ele for ele in cols if ele])

json_string = json.dumps(data,indent=4, sort_keys=True)
print(json_string)