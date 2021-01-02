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
    
    if command == '/start':
        telegram_bot.sendMessage(chat_id, "Welcome to COVID-19 Bot. \n \nThis bot will show you about COVID-19 information arround the globe. This bot can give you the number of confirmed, death, actived, and recovered in case of COVID-19 in many country. \n \nTo use this bot you can type /(country name) \nex: /indonesia, /germany, /greece, ect. \n \nThis data was get from kawalcorona.com.")

    elif command == '/amerika':
        id = 0
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + 
        str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/indonesia':
        id = 19
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + 
        str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/japan':
        id = 41
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) +
        str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/india':
        id = 1
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/greece':
        id = 62
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/egypt':
        id = 63
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/malaysia':
        id = 72
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/china':
        id = 78
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/southkorea':
        id = 85
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/australia':
        id = 79
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/uruguay':
        id = 88
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/thailand':
        id = 111
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/iceland':
        id = 121
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/vietnam':
        id = 139
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/brunei':
        id = 157
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/brazil':
        id = 2
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/russia':
        id = 3
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/france':
        id = 4
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/uk':
        id = 5
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/turkey':
        id = 6
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/italy':
        id = 7
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/spain':
        id = 8
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/germany':
        id = 9
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/argentina':
        id = 11
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/mexico':
        id = 12
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/poland':
        id = 13
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/southafrica':
        id = 16
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/netherlands':
        id = 18
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    elif command == '/belgium':
        id = 21
        telegram_bot.sendMessage(chat_id, name(id) + str('\n') + confirmed(id) + str('\n') + death(id) + str('\n') + active(id) + str('\n') + recovered(id))

    else:
        telegram_bot.sendMessage(chat_id, "Please type the right keyword. To use this bot you can type /(country name) \nex: /indonesia, /germany, /greece, ect. If the information of country that you want to know isn't display, don't shy to contact us.")

def name(id):
    return "Country : " + dataJson[id]['attributes']['Country_Region']

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

telegram_bot = telepot.Bot('1434627853:AAFtTLIAzjhe_Sl_goX93WIPWaB8Bd_yHsM')
print(telegram_bot.getMe)
MessageLoop(telegram_bot, action).run_as_thread()
print('Bot sedang berjalan...')
while 1:
    time.sleep(10)
