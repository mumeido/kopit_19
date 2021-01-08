from tkinter import *
import json
import requests


def indonesia():

    r = requests.get('https://api.kawalcorona.com/indonesia')
    dataJson = r.json()


    indo_app = Tk()
    indo_app.geometry('600x350')
    indo_app.title('Indonesia Details')
    indo_app.configure(background='black')

    nameindo = Label(
        indo_app, text=dataJson[0]['name'], background="black", fg="white")
    nameindo.place(relx=0.5,rely=0.05,anchor=CENTER)

    postindo = Label(indo_app, text="Kasus Positif",
                     background="black", fg="white")
    postindo.place(relx=0.5, rely=0.20, anchor=CENTER)
    posindo = Label(
        indo_app, text=dataJson[0]['positif'], background="black", fg="white")
    posindo.place(relx=0.5, rely=0.27, anchor=CENTER)

    deathtindo = Label(indo_app, text="Kasus Positif",
                       background="black", fg="white")
    deathtindo.place(relx=0.5, rely=0.40, anchor=CENTER)
    deathindo = Label(
        indo_app, text=dataJson[0]['sembuh'], background="black", fg="white")
    deathindo.place(relx=0.5, rely=0.47, anchor=CENTER)

    recotindo = Label(indo_app, text="Kasus Positif",
                      background="black", fg="white")
    recotindo.place(relx=0.5, rely=0.60, anchor=CENTER)
    recoindo = Label(
        indo_app, text=dataJson[0]['meninggal'], background="black", fg="white")
    recoindo.place(relx=0.5, rely=0.67, anchor=CENTER)

    rawattindo = Label(indo_app, text="Kasus Positif",
                       background="black", fg="white")
    rawattindo.place(relx=0.5, rely=0.80, anchor=CENTER)
    rawatindo = Label(
        indo_app, text=dataJson[0]['dirawat'], background="black", fg="white")
    rawatindo.place(relx=0.5, rely=0.87, anchor=CENTER)
