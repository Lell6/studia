from tkinter import *
from tkinter import messagebox

from oceny_file import get_data_for_nazwy
from przedmioty_edit import zmiana_nazw_przedmiotow

def dodaj_zmien_przedmiot(okno_lista):
    messagebox.showinfo("Info", "Do 3 nowych przedmiotów na raz\nMaksymalna długość nazwy - 20 znaków", parent=okno_lista)
    
    for elem in okno_lista.grid_slaves(column=1):
        elem.destroy()

    Label(okno_lista, text="----Nowe nazwy----", width=35).grid(row=0, column=1)

    entries = []
    nazwy = get_data_for_nazwy()

    num = len(nazwy) - 1
    
    for i in range(1,num+4):
        entries.append(None)

    for i in range(len(entries)): 
        entries[i] = Entry(okno_lista, width=35)
        entries[i].grid(row=i+1, column = 1)

    for i in range(1,4):
        Label(okno_lista, text="Nowy przedmiot", width=35).grid(row=num+i, column=0)

    Button(okno_lista, text="Dodaj / Zmień nazwy", command=lambda 
           okno = okno_lista, 
           pola = entries, 
           liczba_przedmiotow = num,
           name = nazwy: zmiana_nazw_przedmiotow(okno, pola, liczba_przedmiotow, name)).grid(row=100, column=1)