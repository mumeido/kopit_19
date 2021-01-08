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

    elif command == '/indonesia':
        id = 19
        telegram_bot.sendMessage(chat_id, nameIndo(id) + str('\n') + confirmedIndo(id) +
                                 str('\n') + deathIndo(id) + str('\n') + activeIndo(id) + str('\n') + recoveredIndo(id))
    elif command == '/jakarta':
        id = 0
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                    deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/jawabarat':
        id = 1
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/jawatimur':
        id = 2
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/jawatengah':
        id = 3
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/sulawesiselatan':
        id = 4
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/kalimantantimur':
        id = 5
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/riau':
        id = 6
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/sumaterabarat':
        id = 7
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/banten':
        id = 8
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/sumaterautara':
        id = 9
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/bali':
        id = 10
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/kalimantanselatan':
        id = 11
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/jogja':
        id = 12
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/papua':
        id = 13
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/sumateraselatan':
        id = 14
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/kalimantantengah':
        id = 15
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/sulawesiutara':
        id = 16
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/aceh':
        id = 17
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/sulawesitenggara':
        id = 18
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/kepulauanriau':
        id = 19
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/lampung':
        id = 20
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/papuabarat':
        id = 21
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/ntb':
        id = 22
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/maluku':
        id = 23
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/kalimantanutara':
        id = 24
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/sulawesitengah':
        id = 25
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/bengkulu':
        id = 26
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/gorontalo':
        id = 27
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/jambi':
        id = 28
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/kalimantanbarat':
        id = 29
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/malukuutara':
        id = 30
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/bangkabelitung':
        id = 31
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/ntt':
        id = 32
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    elif command == '/sulawesibarat':
        id = 33
        telegram_bot.sendMessage(chat_id, nameProvi(id) + str('\n') + confirmedProvi(id) + str('\n') +
                                 deathProvi(id) + str('\n') + recoverdProvi(id))
    else:
        telegram_bot.sendMessage(
            chat_id, "Please type the right keyword. To use this bot you can type /(country name) \nex: /indonesia, /germany, /greece, ect. If the information of country that you want to know isn't display, don't shy to contact us.")


def nameIndo(id):
    return "Country : " + dataJson[id]['attributes']['Country_Region']

def nameProvi(id):
    return "Provinsi : " + dataProvi[id]['attributes']['Provinsi']

def confirmedIndo(id):
    return "Confirmed : " + str(dataJson[id]['attributes']['Confirmed'])

def confirmedProvi(id):
    return "Confirmed : " + str(dataProvi[id]['attributes']['Kasus_Posi'])

def deathIndo(id):
    return "Deaths : " + str(dataJson[id]['attributes']['Deaths'])

def deathProvi(id):
    return "Deaths : " + str(dataProvi[id]['attributes']['Kasus_Meni'])

def activeIndo(id):
    return "Active : " + str(dataJson[id]['attributes']['Active'])


def recoveredIndo(id):
    return "Recovered : " + str(dataJson[id]['attributes']['Recovered'])

def recoverdProvi(id):
    return "Recovered : " + str(dataProvi[id]['attributes']['Kasus_Semb'])


now = datetime.datetime.now()

r = requests.get('https://api.kawalcorona.com/')
data = r.text
dataJson = json.loads(data)

j = requests.get('https://api.kawalcorona.com/indonesia/provinsi/')
data2 = j.text
dataProvi = json.loads(data2)

telegram_bot = telepot.Bot('URR API KEY')
print(telegram_bot.getMe)
MessageLoop(telegram_bot, action).run_as_thread()
print('Bot sedang berjalan...')
while 1:
    time.sleep(10)
