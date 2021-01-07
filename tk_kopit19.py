from propinsi import *
from tkinter import filedialog
from tkinter import *
from PIL import ImageTk, Image
import webbrowser


path = 'C:/python/kopit_19//GUI/Images/logo.jpg'


main_app = Tk()
main_app.title('Kopit_19 | Province Indonesia Statistic')
main_app.geometry('500x500')
main_app.configure(background='black')

img = ImageTk.PhotoImage(Image.open(path))
panel = Label(main_app, image=img)
panel.place(relx=0.5,rely=0.5,anchor=S)

main_app.iconbitmap(r'C:/python/kopit_19/GUI/Images/logo.ico')

btnDetail = Button(main_app,text='Lihat detail', width = 10, command = province)
btnDetail.place(relx=0.5,rely=0.6, anchor=CENTER)

desc = Button(main_app, text='my github', width= 10, command = webrowser)
desc.place(relx=0.5,rely=0.8,anchor=S)

main_app.mainloop()

