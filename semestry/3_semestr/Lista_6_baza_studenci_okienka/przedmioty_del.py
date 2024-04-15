from tkinter import *
from tkinter import messagebox

from oceny_file import get_data_for_oceny, update_data_for_oceny, update_data_for_nazwy, get_data_for_nazwy

def usuwanie(okno_lista, nazwy, pole_nazwy):
    nazwa_do_usuwania = pole_nazwy.get()

    if nazwa_do_usuwania:
        check = messagebox.askquestion("Potwierdzenie", "Napewno chcesz usunąć dany przedmiot " + nazwa_do_usuwania + " ?", parent=okno_lista)

        if check == "yes":
            nazwy.remove(nazwa_do_usuwania)
            update_data_for_nazwy(nazwy)

            zestaw_ocen = get_data_for_oceny(1)

            for j in range(len(zestaw_ocen)):
                zestaw_ocen[j].pop(nazwa_do_usuwania, None)

            update_data_for_oceny(0,zestaw_ocen)

            messagebox.showinfo("Powiodło się", "Przedmiot " + nazwa_do_usuwania + " został usunięty", parent=okno_lista)

            for i in range(98):
                for elem in okno_lista.grid_slaves(row=i,column=0):
                    elem.destroy()

            Label(okno_lista, text="----Stare nazwy----", width=20).grid(row=0, column=0)

            i = 1
            for name in nazwy:
                if(name != 'id'):
                    Label(okno_lista, text=name, width=35).grid(row=i, column=0)
                    i = i + 1

            Label(okno_lista, text="------------------", width=20).grid(row=99, column=0)
        else:
            messagebox.showinfo("Info", "Skasowano usuwanie", parent=okno_lista)

    else:
        messagebox.showerror("Nastąpił błąd", "Nie podano nazwy", parent=okno_lista)


def usun_przedmiot(okno_lista):
    for elem in okno_lista.grid_slaves(column=1):
        elem.destroy()

    i = 1
    for elem in okno_lista.grid_slaves(column=0):
        if(elem.cget("text") == "Nowy przedmiot"):
            elem.destroy()

    nazwy = get_data_for_nazwy()

    Label(okno_lista, text="----Nazwa przedmiotu----", width=35).grid(row=0, column=1)

    nazwa_usuwanie = Entry(okno_lista, width=35)
    nazwa_usuwanie.grid(row=1, column=1)

    Button(okno_lista, text="Usuń przedmiot", width=35, command = lambda 
           okno = okno_lista,
           name = nazwy, 
           pole = nazwa_usuwanie: usuwanie(okno, name, pole)).grid(row=2,column=1)