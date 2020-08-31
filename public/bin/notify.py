#!/usr/bin/env python3

from bs4 import BeautifulSoup
import urllib3
import time
import locale
import smtplib, ssl, certifi
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart

def send_notification(sender, receiver, subject, html):
    message = MIMEMultipart('alternative')
    message['Subject'] = subject
    message['From'] = sender
    message['To'] = receiver
    password = 'f33lfine-0x80'

    html =  html

    content = MIMEText(html, 'html')

    message.attach(content)

    raw = message.as_string().encode('UTF-8')

    context = ssl.create_default_context()
    with smtplib.SMTP_SSL('smtp.gmail.com', 465, context = context) as server:
        server.login(sender, password)
        server.sendmail(
            sender, receiver, raw
        )

# Get the url
url = f'https://bppk.kemenkeu.go.id/content/pengumuman?page=0&size=41'
req = urllib3.PoolManager(
        cert_reqs='CERT_REQUIRED',
        ca_certs=certifi.where()
    )
res = req.request('GET', url)
raw = BeautifulSoup(res.data, 'lxml')

# Get the content of the announcement by class
rows = raw.find_all('div', {'class': 'flex-card is-post light-bordered'})

# Check the date for today and set the locale to Indonesian
locale.setlocale(locale.LC_TIME, 'id_ID')
today = time.strftime('%A, %d %B %Y')
# today = 'Kamis, 27 Agustus 2020'

# Filter for Pusdiklat DJKN only
data = []
for row in rows:
    title = row.find_all('h2')
    org = row.find_all('h4')
    link = row.find_all('a')
    if org[0].text == 'Pusdiklat Kekayaan Negara dan Perimbangan Keuangan':
        # Filter the date
        if today == org[1].text:
            data.append('<a href="https://bppk.kemenkeu.go.id' + link[0]['href'] + '">' + title[0].text + '</a><br>')
            # print(data)

html = ''
for i in data:
    html += str(i)

send_notification(
        'fonfon.1337@gmail.com',
        'murniaty86@kemenkeu.go.id',
        'Pengumuman BPPK: Pusdiklat KNPK ' + today,
        html
    )
send_notification(
        'fonfon.1337@gmail.com',
        'murniaty.lv@gmail.com',
        'Pengumuman BPPK: Pusdiklat KNPK ' + today,
        html
    )