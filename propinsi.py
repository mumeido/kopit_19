from tkinter import *
import json
import requests
import webbrowser



def province():
    root = Tk()
    root.geometry('600x950')
    root.title('Details Data')


    prov = StringVar()
    prov = Label(root, text='PROVINCE')
    prov.grid(row=0, column=0, padx=5, sticky=W)

    prov = StringVar()
    prov = Label(root, text='POSITIVE')
    prov.grid(row=0, column=1, padx=5)

    prov = StringVar()
    prov = Label(root, text='DEATH')
    prov.grid(row=0, column=2, padx=5)

    prov = StringVar()
    prov = Label(root, text='RECOVERED')
    prov.grid(row=0, column=3, padx=5)

    url_api = requests.get('https://api.kawalcorona.com/indonesia/provinsi/')
    json = url_api.json()

    i = 0
    tabel = 1

    for row in json:
        provData = Label(root, text=json[i]['attributes']['Provinsi'])
        provData.grid(row=tabel, column=0, padx=5, sticky=W)

        posData = Label(root, text=json[i]['attributes']['Kasus_Posi'])
        posData.grid(row=tabel, column=1, padx=5)

        deathData = Label(root, text=json[i]['attributes']['Kasus_Meni'])
        deathData.grid(row=tabel, column=2, padx=5)

        recData = Label(root, text=json[i]['attributes']['Kasus_Semb'])
        recData.grid(row=tabel, column=3, padx=5)

        i += 1
        tabel +=1

def webrowser():
    chrome_path = 'C:/Program Files (x86)/Google/Chrome/Application/chrome.exe %s'
    github_url = "https://github.com/mumeido"
    webbrowser.get(chrome_path).open(github_url)
    



















