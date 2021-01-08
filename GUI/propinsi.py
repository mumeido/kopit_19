from tkinter import *
import json
import requests
import webbrowser



def province():
    root = Tk()
    root.geometry('600x950')
    root.title('Details Data')
    root.configure(background='black')

    prov = StringVar()
    prov = Label(root, text='PROVINCE',background="black",fg="white")
    prov.grid(row=0, column=0, padx=5, sticky=W)

    prov = StringVar()
    prov = Label(root, text='POSITIVE', background="black", fg="white")
    prov.grid(row=0, column=1, padx=5)

    prov = StringVar()
    prov = Label(root, text='DEATH', background="black", fg="white")
    prov.grid(row=0, column=2, padx=5)

    prov = StringVar()
    prov = Label(root, text='RECOVERED', background="black", fg="white")
    prov.grid(row=0, column=3, padx=5)

    url_api = requests.get('https://api.kawalcorona.com/indonesia/provinsi/')
    json = url_api.json()

    i = 0
    tabel = 1

    for row in json:
        provData = Label(
            root, text=json[i]['attributes']['Provinsi'], background="black", fg="white")
        provData.grid(row=tabel, column=0, padx=5, sticky=W)

        posData = Label(
            root, text=json[i]['attributes']['Kasus_Posi'], background="black", fg="white")
        posData.grid(row=tabel, column=1, padx=5)

        deathData = Label(
            root, text=json[i]['attributes']['Kasus_Meni'], background="black", fg="white")
        deathData.grid(row=tabel, column=2, padx=5)

        recData = Label(
            root, text=json[i]['attributes']['Kasus_Semb'], background="black", fg="white")
        recData.grid(row=tabel, column=3, padx=5)

        i += 1
        tabel +=1

def webrowser():
    chrome_path = 'UR CHROME PATH %s'
    github_url = "https://github.com/mumeido"
    webbrowser.get(chrome_path).open(github_url)
