from tkinter import *
from tkinter import ttk
from tkinter import messagebox
from PIL import Image, ImageTk
import sqlite3

from oceny_file import get_data_for_oceny

def srednia(okno_oceny, oceny):
    Label(okno_oceny, text="---Średnie ocen---").grid(row=6,column=0, columnspan=2)

    i = 7
    suma_ogolna = 0
    ilosc_ogolna = 0

    for elem in oceny:
        suma = 0
        ilosc = 0
        for j in range(1, len(elem)):
            suma = suma + elem[j]

            ilosc = ilosc + 1

        srednia = str(round(suma/ilosc, 3))

        suma_ogolna = suma_ogolna + suma
        ilosc_ogolna = ilosc_ogolna + ilosc

        i = i + 1
        
        Label(okno_oceny, text=elem[0]).grid(row=i, column=0)
        Label(okno_oceny, text=srednia).grid(row=i, column=1)

    srednia = round(suma_ogolna / ilosc_ogolna, 3)

    Label(okno_oceny, text="Średnia ze wszystkich przedmiotów").grid(row=i+1, column=0)
    Label(okno_oceny, text = srednia).grid(row=i+1, column=1)

def create_tabela(okno_oceny, zawartosc):
    kolumny = ['k_przedmiot']
    nazwy_kolumn = ['Przedmiot']

    longest = max(zawartosc, key=len)

    for i in range(1,len(longest)):
        kolumny.append('k_ocena' + str(i))
        nazwy_kolumn.append('ocena ' + str(i))

    tabela = ttk.Treeview(okno_oceny, columns=kolumny, show="headings", height=8)

    for el in kolumny:
        if el == 'k_przedmiot':
            tabela.column(el, width=150)
        else:
            tabela.column(el, width=60)

    for i in range(len(kolumny)):
        tabela.heading(kolumny[i], text=nazwy_kolumn[i])

    for rekord in zawartosc:
        tabela.insert('', END, value=rekord)

    tabela.grid(row=1, column=0, columnspan=2, sticky='nsew')
    scrollbar= ttk.Scrollbar(okno_oceny, orient=VERTICAL, command=tabela.yview)
    tabela.configure(yscroll=scrollbar.set)
    scrollbar.grid(row=1, column=2, sticky='ns')



def show_id(okno_oceny, zawartosc):
    tabela_oceny = okno_oceny.grid_slaves(row=1,column=0)
    scrollbar = okno_oceny.grid_slaves(row=1,column=1)

    for elem in tabela_oceny:
        elem.destroy()

    for elem in scrollbar:
        elem.destroy()

    create_tabela(okno_oceny, zawartosc)


def show_grades(okno_oceny, zawartosc):
    tabela_oceny = okno_oceny.grid_slaves(row=1,column=0)
    scrollbar = okno_oceny.grid_slaves(row=1,column=1)

    for elem in tabela_oceny:
        elem.destroy()

    for elem in scrollbar:
        elem.destroy()

    zawartosc.sort(key = lambda x: x[1])
    create_tabela(okno_oceny, zawartosc)



def show_abc(okno_oceny, zawartosc):
    tabela_oceny = okno_oceny.grid_slaves(row=1,column=0)
    scrollbar = okno_oceny.grid_slaves(row=1,column=1)

    for elem in tabela_oceny:
        elem.destroy()

    for elem in scrollbar:
        elem.destroy()

    zawartosc.sort()
    create_tabela(okno_oceny, zawartosc)


def pokaz_oceny(student_id, okno_lista):
    if student_id:
        db_connect = sqlite3.connect('dziennik.db')
        db_cursor = db_connect.cursor()
            
        db_cursor.execute('SELECT * FROM students WHERE rowid = {}'.format(str(student_id)))
        rekord = db_cursor.fetchone()
        db_connect.close()

        if rekord:
            okno_oceny = Toplevel()
            okno_oceny.title("Oceny studenta " + str(student_id))
            okno_oceny.geometry("+550+200")
            okno_oceny.focus_set()

            Label(okno_oceny, text="-----Oceny-----").grid(row=0,column=0,columnspan=2)

            zestaw_ocen = get_data_for_oceny(2, student_id)

            zawartosc = []

            for name in zestaw_ocen:
                zawartosc_przedmiot = []

                if(name != 'id'):
                    zawartosc_przedmiot.append(name)

                    for elem in zestaw_ocen[name]:
                        zawartosc_przedmiot.append(elem)
                        
                    zawartosc.append(zawartosc_przedmiot)

            #wysokosc = len(zawartosc) height of tabela in rows

            kolumny = ['k_przedmiot']
            nazwy_kolumn = ['Przedmiot']

            longest = max(zawartosc, key=len)

            for i in range(1,len(longest)):
                kolumny.append('k_ocena' + str(i))
                nazwy_kolumn.append('ocena ' + str(i))

            tabela = ttk.Treeview(okno_oceny, columns=kolumny, show="headings", height=8)

            for el in kolumny:
                if el == 'k_przedmiot':
                    tabela.column(el, width=150)
                else:
                    tabela.column(el, width=60)

            for i in range(len(kolumny)):
                tabela.heading(kolumny[i], text=nazwy_kolumn[i])

            for rekord in zawartosc:
                tabela.insert('', END, value=rekord)

            tabela.grid(row=1, column=0, columnspan=2, sticky='nsew')
            scrollbar= ttk.Scrollbar(okno_oceny, orient=VERTICAL, command=tabela.yview)
            tabela.configure(yscroll=scrollbar.set)
            scrollbar.grid(row=1, column=2, sticky='ns')

            
            Label(okno_oceny, text="---------------------").grid(row=99,column=0)

            Button(okno_oceny, text="Oblicz średnią", command=lambda okno = okno_oceny, oceny = zawartosc: srednia(okno, oceny)).grid(row=100, column=0)
            
            Button(okno_oceny, text="Wyświetl jak dodawane", command=lambda okno = okno_oceny, oceny = zawartosc: show_id(okno, oceny)).grid(row=97, column=0)
            Button(okno_oceny, text="Wyświetl aflabetycznie", command=lambda okno = okno_oceny, oceny = zawartosc: show_abc(okno, oceny)).grid(row=98, column=0)
            Button(okno_oceny, text="Wyświetl na podstawie ocen", command=lambda okno = okno_oceny, oceny = zawartosc: show_grades(okno, oceny)).grid(row=99, column=0)

            Button(okno_oceny, text="Wrócić do menu", command=okno_oceny.destroy).grid(row=100, column=1)
        else:
            messagebox.showerror("Nastąpił błąd", "Student o takim ID nie istnieje", parent=okno_lista)
    else:
        messagebox.showerror("Nastąpił błąd", "Nie podano numeru ID", parent=okno_lista)