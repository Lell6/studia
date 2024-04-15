from tkinter import *

from oceny_file import get_data_for_nazwy
from przedmioty_del import usun_przedmiot
from przedmioty_add import dodaj_zmien_przedmiot
    
def zarzadzaj_lista_przedmiotow():
    okno_lista = Toplevel()
    okno_lista.title("Lista przedmiotów")
    okno_lista.geometry("+550+200")
    okno_lista.focus_set()

    nazwy = get_data_for_nazwy()

    Label(okno_lista, text="----Stare nazwy----", width=20).grid(row=0, column=0)

    i = 1
    for name in nazwy:
        if(name != 'id'):
            Label(okno_lista, text=name, width=35).grid(row=i, column=0)
            i = i + 1

    Label(okno_lista, text="------------------", width=20).grid(row=99, column=0)

    Button(okno_lista, text="Dodawanie/redagowanie przedmiotów", command= lambda 
           okno = okno_lista: dodaj_zmien_przedmiot(okno)).grid(row=100, column=0)
    
    Button(okno_lista, text="Usuń przedmiot", command= lambda 
           okno = okno_lista: usun_przedmiot(okno)).grid(row=102)
    
    Label(okno_lista, text="------------------", width=20).grid(row=103, column=0)    

    Button(okno_lista, text="Wyjść z listy", command= okno_lista.destroy).grid(row=104)