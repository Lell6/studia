from tkinter import *
import sqlite3
import pickle

from student_add import create_add_window
from student_del import create_del_window
from student_edit import create_edit_window
from student_show import pokaz_lista_studentow
from przedmioty_lista import zarzadzaj_lista_przedmiotow

db_connect = sqlite3.connect('dziennik.db')
db_cursor = db_connect.cursor()

sql_query = "SELECT count(name) FROM sqlite_master WHERE type='table' AND name='students'"
db_cursor.execute(sql_query)

if db_cursor.fetchone()[0] != 1:
    sql_query = "CREATE TABLE students (imie text, nazwisko text, album integer, photo text)"
    db_cursor.execute(sql_query)
    db_connect.commit()
    db_connect.close()

    nazwy_przedmiotow = ['id', 'Matematyka', 'Fizyka', 'Angielski', 'Programowanie', 'Projekt']

    with open('nazwy_przedmiotow.dat', 'wb') as plik_przedmioty:
        pickle.dump(nazwy_przedmiotow, plik_przedmioty)

    with open('oceny.dat', 'wb') as plik_oceny:
        pass

okno_main = Tk()
okno_main.title("Dziennik")
okno_main.geometry("+400+200")

def db_students_buttons():
    if button_add.winfo_ismapped():

        button_add.grid_forget()
        button_edit.grid_forget()
        button_del.grid_forget()
        label.grid_forget()
    else:
        label.grid(row=3)
        button_add.grid(row=4)
        button_edit.grid(row=5)
        button_del.grid(row=6)


Button(okno_main, text="Lista studentow", width=30, height=2, command= pokaz_lista_studentow).grid(row=0)
Button(okno_main, text="Zarządzaj studentami", width=30, height=2, command= db_students_buttons).grid(row=1)
Button(okno_main, text="Zarządzaj listą przedmiotów", width=30, height=2, command=zarzadzaj_lista_przedmiotow).grid(row=2)

label = Label(okno_main, text="-------------------------", width=30)

button_add = Button(okno_main, text="Dodaj studenta", width=30, height=2, command= lambda: create_add_window())
button_edit = Button(okno_main, text="Edytuj studenta", width=30, height=2, command= lambda: create_edit_window())
button_del = Button(okno_main, text="Usuń studenta", width=30, height=2, command= lambda: create_del_window())

Label(okno_main, text="-------------------------", width=30).grid(row=7)
Button(okno_main, text="Wyjść z dziennika", width=30, height=2, command= okno_main.destroy).grid(row=8)

label.grid_forget()
button_add.grid_forget()
button_edit.grid_forget()
button_del.grid_forget()

okno_main.mainloop()