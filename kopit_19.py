import datetime
import time
from telepot.loop import MessageLoop
import telepot
import json
import requests

def action(msg):
    chat_id = msg['chat']['id']
    command = msg['text']
    print('Received: %s' % command)

    if command == '/india':
        id = 1
        telegram_bot.sendMessage(chat_id, name(
            id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + 
            active(id) + str('\n') + recovered(id))
    elif command == '/amerika':
        id = 0
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + 
        str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))
    elif command == '/indonesia':
        id = 19
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + 
        str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))
    elif command == '/jepang':
        id = 41
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) +
        str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

def name(id):
    return "Negara : " + dataJson[id]['attributes']['Country_Region']


def confirmed(id):
    return "Confirmed : " + str(dataJson[id]['attributes']['Confirmed'])


def death(id):
    return "Deaths : " + str(dataJson[id]['attributes']['Deaths'])

def active(id):
    return "Active : " + str(dataJson[id]['attributes']['Active'])

def recovered(id):
    return "Recovered : " + str(dataJson[id]['attributes']['Recovered'])


now = datetime.datetime.now()

r = requests.get('https://api.kawalcorona.com/')
data = r.text
dataJson = json.loads(data)

telegram_bot = telepot.Bot('1456306147:AAHWlYqBhKRiwOTsyKngDS7R4TcMf3ZrUUM')
print(telegram_bot.getMe)
MessageLoop(telegram_bot, action).run_as_thread()
print('Bot sedang berjalan...')
while 1:
    time.sleep(10)
