from tkinter import *
from tkinter import ttk
from tkinter import messagebox
from PIL import Image, ImageTk
import sqlite3

from oceny_file import get_data_for_oceny
from student_show_oceny import pokaz_oceny
from student_show_photo import pokaz_zdjecie


def pokaz_lista_studentow():
    student_id = None
    
    def pobieranie_z_wybranego(button_oceny, button_obraz, event):
        for items in tabela.selection():
            wiersz = tabela.item(items)
            dane = wiersz['values']

            button_oceny.configure(command= lambda idd = dane[0], okno = okno_lista: pokaz_oceny(idd, okno))
            button_obraz.configure(command = lambda okno = okno_lista, idd = dane[0]: pokaz_zdjecie(okno, idd))

    okno_lista = Toplevel()
    okno_lista.title("Lista studentow")
    okno_lista.geometry("+550+200")
    okno_lista.focus_set()

    kolumny = ('k_rowid', 'k_imie', 'k_nazwisko', 'k_album', 'k_photo')
    nazwy_kolumn = ('id', 'imie', 'nazwisko', 'numer album', 'obraz')

    tabela = ttk.Treeview(okno_lista, columns=kolumny, show="headings", height=5)

    for el in kolumny:
        if el == 'k_rowid':
            tabela.column(el, width=10)
        elif el == 'k_photo':
            tabela.column(el, width=200)
        else:
            tabela.column(el, width=100)

    for i in range(len(kolumny)):
        tabela.heading(kolumny[i], text=nazwy_kolumn[i])

    db_connect = sqlite3.connect('dziennik.db')
    db_cursor = db_connect.cursor()
    db_cursor.execute('SELECT rowid, * FROM students')

    zawartosc_bazy = db_cursor.fetchall()

    for rekord in zawartosc_bazy:
        tabela.insert('', END, value=rekord)

    db_connect.commit()
    db_connect.close()

    tabela.grid(row=0, column=0, sticky='nsew')
    scrollbar= ttk.Scrollbar(okno_lista, orient=VERTICAL, command=tabela.yview)
    tabela.configure(yscroll=scrollbar.set)
    scrollbar.grid(row=0, column=1, sticky='ns')

    #Label(okno_lista, text="Podaj id studenta", width=30).grid(row=1,column=0)
    #student_id = Entry(okno_lista, width=30)

    Label(okno_lista, text="-----------------------------------------------").grid(row=3,column=0)
    button_oceny = Button(okno_lista, text="Pokazać oceny wybranego studenta", command= lambda idd = student_id, okno = okno_lista: pokaz_oceny(idd, okno))
    button_obraz = Button(okno_lista, text="Pokazać zdjęcie wybranego studenta", command = lambda okno = okno_lista, idd = student_id: pokaz_zdjecie(okno, idd))

    Label(okno_lista, text="-----------------").grid(row=102,column=0)
    Button(okno_lista, text="Wrócić do menu", command=okno_lista.destroy).grid(row=103, column=0)

    button_oceny.grid(row=100, column=0)
    button_obraz.grid(row=101, column=0)

    tabela.bind('<<TreeviewSelect>>', lambda event, but_o = button_oceny, but_z = button_obraz: pobieranie_z_wybranego(but_o, but_z, event))

    #student_id.grid(row=2,column=0)