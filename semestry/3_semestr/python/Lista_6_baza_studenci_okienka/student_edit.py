from tkinter import *
from tkinter import messagebox
import sqlite3

from oceny_file import get_data_for_oceny
from student_edit_oceny import redagowanie_ocen
from student_edit_nazwy import redagowanie_nazwy
from student_edit_photo import redagowanie_sciezki

def wstawianie_nowych_danych(p_id, button_dane, button_oceny, okno_forma):
    student_id = p_id.get()

    if student_id:
        okno_forma.title("Redagowanie studenta o ID: " + str(student_id))

        db_connect = sqlite3.connect('dziennik.db')
        db_cursor = db_connect.cursor()
            
        db_cursor.execute('SELECT * FROM students WHERE rowid = {}'.format(str(student_id)))
        rekord = db_cursor.fetchone()
        db_connect.close()

        if rekord:
            messagebox.showinfo("Informacja", "Liczby podawać przez spacje", parent=okno_forma)
            okno_forma.title("Redagowanie studenta: " + str(student_id))

            Label(okno_forma, text="----Dane studenta----", width=30).grid(row=2, column=0, columnspan=2)
            Label(okno_forma, text="Nowe imie (" + rekord[0] + "): ", width=30).grid(row=3, column=0)
            Label(okno_forma, text="Nowe nazwisko (" + rekord[1] + "): ", width=30).grid(row=4, column=0)
            Label(okno_forma, text="Nowy numer albumu (" + str(rekord[2]) + "): ", width=30).grid(row=5, column=0)

            i = 7

            Label(okno_forma, text="----Lista przedmiotów----", width=30).grid(row=6, column=0, columnspan=2)

            zestaw_ocen = get_data_for_oceny(2, int(student_id)) #one record by id
            entry_list = []

            for name, value in zestaw_ocen.items():
                if(name != 'id'):
                    Label(okno_forma, text=name, width=30).grid(row=i, column=0)

                    entry = Entry(okno_forma, width=30)
                    entry.insert(0, value)
                    entry.grid(row=i, column=1)

                    entry_list.append(entry)
                    i = i + 1

            lokacja_zdjecia = rekord[-1]

            Label(okno_forma, text="----Plik obrazu----", width=30).grid(row=21, column=0, columnspan=2)
            obraz_path = Label(okno_forma)

            if lokacja_zdjecia == "-":
                obraz_path.configure(text="-", background="white", width=75)
            else:
                obraz_path.configure(text=lokacja_zdjecia, background="white")

            obraz_path.grid(row=22, column = 0, columnspan=2)

            Button(okno_forma, text="Zmień obraz", width=30, command= lambda idd = student_id, okno = okno_forma: redagowanie_sciezki(idd, okno)).grid(row=23, column=0, columnspan=2)
            Label(okno_forma, text="------------------", width=30).grid(row=24, column=0, columnspan=2)

            button_dane['state'] = NORMAL
            button_oceny['state'] = NORMAL

            button_oceny.configure(command= lambda e_list = entry_list, zest_ocen = zestaw_ocen, idd = student_id, okno = okno_forma: redagowanie_ocen(e_list, zest_ocen, idd, okno))
        else:
            messagebox.showerror("Nastąpił błąd", "Student o takim ID nie istnieje", parent=okno_forma)
            
        db_connect.close()

    else:
        messagebox.showerror("Nastąpił błąd", "Nie podano numeru ID", parent=okno_forma)
        

def create_edit_window():
    okno_forma = Toplevel()
    okno_forma.title("Redagowanie studenta")
    okno_forma.geometry("+550+200")
    okno_forma.focus_set()

    Label(okno_forma, text="Podaj id studenta", width=16).grid(row=0,column=0)
    student_id = Entry(okno_forma, width=30)

    Label(okno_forma, text="Nowe imie (Nie podano ID): ", width=30).grid(row=3, column=0)
    imie = Entry(okno_forma, width=30)

    Label(okno_forma, text="Nowe nazwisko (Nie podano ID): ", width=30).grid(row=4, column=0)
    nazwisko = Entry(okno_forma, width=30)

    Label(okno_forma, text="Nowy numer albumu (Nie podano ID): ", width=30).grid(row=5, column=0)
    album = Entry(okno_forma, width=30)


    button_redagowanie = Button(okno_forma, text="Zmień dane osobowe", width=20, state=DISABLED, command= lambda 
                        i = imie, 
                        n = nazwisko, 
                        a = album, 
                        idd = student_id, 
                        okno = okno_forma: redagowanie_nazwy(i, n, a, idd, okno))
    
    button_redagowanie_ocen = Button(okno_forma, text="Zmień oceny", width=20, state=DISABLED)

    button_wstawianie_danych = Button(okno_forma, text="Wyświetl dane", command=lambda 
                                      st_id = student_id, 
                                      okno = okno_forma: wstawianie_nowych_danych(st_id, button_redagowanie, button_redagowanie_ocen, okno))
    button_return = Button(okno_forma, text="Wrócić do Menu", command= lambda: okno_forma.destroy())
    
    student_id.grid(row=0,column=1)

    button_wstawianie_danych.grid(row=1, column=0, columnspan=2)

    imie.grid(row=3, column=1)
    nazwisko.grid(row=4, column=1)
    album.grid(row=5, column=1)

    button_redagowanie.grid(row=20, column=0)
    button_redagowanie_ocen.grid(row=20, column=1)
    button_return.grid(row=100, column=0, columnspan=2)