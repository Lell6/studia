from tkinter import *
from tkinter import ttk
from tkinter import messagebox
from PIL import Image, ImageTk
import sqlite3

from oceny_file import get_data_for_oceny

def pokaz_zdjecie(okno_lista, student_id):
    if student_id:
        db_connect = sqlite3.connect('dziennik.db')
        db_cursor = db_connect.cursor()
            
        db_cursor.execute('SELECT * FROM students WHERE rowid = {}'.format(student_id))
        rekord = db_cursor.fetchone()
        db_connect.close()

        if rekord:
            path = rekord[-1]

            if(path != '-'):
                okno_photo = Toplevel()
                okno_photo.title("Zdjęcie studenta " + str(student_id))
                okno_photo.geometry("+550+200")
                okno_photo.focus_set()
                
                obraz_z_pliku = Image.open(path)

                wysokosc, szerokosc = obraz_z_pliku.size
                proporcje = wysokosc/szerokosc

                if(wysokosc > szerokosc):
                    wysokosc = 300
                    szerokosc = int(wysokosc * proporcje)
                else:
                    szerokosc = 300
                    wysokosc = int(szerokosc / proporcje)

                obraz_do_wyswietlania = obraz_z_pliku.resize((szerokosc,wysokosc))

                obraz = ImageTk.PhotoImage(obraz_do_wyswietlania)

                label = Label(okno_photo, image=obraz, bg="red", width = obraz.width(), height=obraz.height())
                label.image = obraz
                label.pack()
                okno_photo.update_idletasks()

                Button(okno_photo, text="Wyść do listy", width=20, command=okno_photo.destroy).pack()
            else:
                messagebox.showerror("Nastąpił błąd", "Student nie posiada zdjecia", parent=okno_lista)
        else:
            messagebox.showerror("Nastąpił błąd", "Student o takim ID nie istnieje", parent=okno_lista)
    else:
        messagebox.showerror("Nastąpił błąd", "Nie podano numeru ID", parent=okno_lista)