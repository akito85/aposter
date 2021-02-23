import smtplib, ssl, certifi
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart

message = MIMEMultipart('alternative')
message['Subject'] = 'test'
message['From'] = 'fonfon.1337@gmail.com'
message['To'] = 'akito.evol@gmail.com'
password = 'f33lfine-0x80'

html =  '''<html>text</html>'''

content = MIMEText(html, 'html')

message.attach(content)

raw = message.as_string().encode('UTF-8')

context = ssl.create_default_context()
with smtplib.SMTP_SSL('smtp.gmail.com', 465, context = context) as server:
    server.login('fonfon.1337@gmail.com', 'f33lfine-0x80')
    server.sendmail('fonfon.1337@gmail.com', 'akito.evol@gmail.com', raw)