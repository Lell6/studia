from tkinter import *
from tkinter import messagebox
import sqlite3

from oceny_file import add_data_for_oceny

def dodawanie(imie, nazwisko, album, okno_forma):

    try:
        int(album.get())
        check = True
    except ValueError:
        check = False

    if check:
        check_pole = 0

        if imie.get() == "":
            check_pole = check_pole + 1
        if nazwisko.get() == "":
            check_pole = check_pole + 1

        if(check_pole == 0):
            db_connect = sqlite3.connect('dziennik.db')
            db_cursor = db_connect.cursor()
            
            db_cursor.execute('SELECT * FROM students WHERE album = ' + album.get()) #sprawdzenie istnienia rekordu z takim numerem

            if db_cursor.fetchone():
                messagebox.showerror("Nastąpił błąd", "Istnieje już rekord o takim numerze albumu", parent=okno_forma)
            else:
                db_cursor.execute("INSERT INTO students VALUES (:p_imie, :p_nazwisko, :p_album, :p_photo)",
                                {
                                'p_imie':imie.get(),
                                'p_nazwisko':nazwisko.get(),
                                'p_album':int(album.get()),
                                'p_photo':"-"
                                })

                db_connect.commit()

                student_id = db_cursor.lastrowid
                add_data_for_oceny(student_id)

                messagebox.showinfo("Powiodło się", "Student został dodany do bazy danych", parent=okno_forma)
            
            db_connect.close()
        else:
            messagebox.showerror("Nastąpił błąd", "Wszyskie pola muszą być wypełnione", parent=okno_forma)
    else:
        messagebox.showerror("Nastąpił błąd", "Album musi być liczbą", parent=okno_forma)

def create_add_window():
    okno_forma = Toplevel()
    okno_forma.title("Dodawanie studenta")
    okno_forma.geometry("+550+200")
    okno_forma.focus_set()

    Label(okno_forma, text="Imie: ", width=12).grid(row=0, column=0)
    imie = Entry(okno_forma, width=30)

    Label(okno_forma, text="Nazwisko", width=12).grid(row=1, column=0)
    nazwisko = Entry(okno_forma, width=30)

    Label(okno_forma, text="Numer Albumu", width=12).grid(row=2, column=0)
    album = Entry(okno_forma, width=30)

    button_dodaj = Button(okno_forma, text="Dodaj", width=40, command=lambda im = imie, nazw = nazwisko, alb = album, okno = okno_forma: dodawanie(im, nazw, alb, okno))
    button_return = Button(okno_forma, text="Wrócić do Menu", command= lambda: okno_forma.destroy())

    imie.grid(row=0, column=1)
    nazwisko.grid(row=1, column=1)
    album.grid(row=2, column=1)
    button_dodaj.grid(row=3, column=0, columnspan=2)
    button_return.grid(row=4, column=0, columnspan=2)    