from tkinter import *
from tkinter import messagebox

from oceny_file import get_data_for_oceny, update_data_for_oceny, update_data_for_nazwy

def zmiana_nazw_przedmiotow(okno_lista, pola, liczba_przedmiotow, nazwy):
    nowe_nazwy = []
    oceny = [0]

    for elem in pola:
        nazwa = []

        nazwa.append(elem.get())
        nazwa.append(0)
        
        nowe_nazwy.append(nazwa)

    zestaw_ocen = get_data_for_oceny(1)

    if zestaw_ocen:
        zestaw_ocen_jednego = zestaw_ocen[0]

        for i in range(liczba_przedmiotow): #check if nowe_nazwy is in zestaw_ocen
            nowe_nazwy[i][1] = 0

            for name in zestaw_ocen_jednego:
                if(nowe_nazwy[i][0].lower() == name.lower()):
                    nowe_nazwy[i][1] = nowe_nazwy[i][1] + 1

        for i in range(len(nowe_nazwy)-3, len(nowe_nazwy)): #add 3 new
            len_nazwa = len(nowe_nazwy[i][0])

            if(nowe_nazwy[i][1] == 0 and len_nazwa > 0 and len_nazwa < 21):
                nowy_przedmiot = nowe_nazwy[i][0]

                for j in range(len(zestaw_ocen)):
                    zestaw_ocen[j][nowy_przedmiot] = oceny

        for i in range(len(nowe_nazwy)-3):
            len_nazwa = len(nowe_nazwy[i][0])

            zestaw_ocen_stary = zestaw_ocen
            zestaw_ocen_stary_jednego = zestaw_ocen_stary[0]
            
            for j in range(len(zestaw_ocen_stary_jednego)):
                if(j == i+1 and len_nazwa > 0):
                    nazwa_do_wymiany = nazwy[j]
                    break
                else:
                    nazwa_do_wymiany = None

            if(nowe_nazwy[i][1] == 0 and len_nazwa > 0 and len_nazwa < 21):   
                nowy_przedmiot = nowe_nazwy[i][0]
                zestaw_ocen = []

                for j in range(len(zestaw_ocen_stary)):
                    zestaw_ocen_jednego = {}

                    for name, value in zestaw_ocen_stary[j].items():
                        if nazwa_do_wymiany and name == nazwa_do_wymiany:
                            zestaw_ocen_jednego[nowy_przedmiot] = value
                        else:
                            zestaw_ocen_jednego[name] = value

                    zestaw_ocen.append(zestaw_ocen_jednego)


        update_data_for_oceny(0,zestaw_ocen) # save whole for every student

    dublicate = 0
    changes = 0

    for i in range(len(nowe_nazwy)-3, len(nowe_nazwy)): #add 3 new
        len_nazwa = len(nowe_nazwy[i][0])

        if(nowe_nazwy[i][1] == 0 and len_nazwa > 0 and len_nazwa < 21):
            nowy_przedmiot = nowe_nazwy[i][0]
            nazwy.append(nowy_przedmiot)
        else:
            dublicate = dublicate + 1
        
        changes = changes + 1

    for i in range(len(nowe_nazwy)-3): # change others
        len_nazwa = len(nowe_nazwy[i][0])

        if(nowe_nazwy[i][1] == 0 and len_nazwa > 0 and len_nazwa < 21):
            nazwy[i+1] = nowe_nazwy[i][0]
        else:
            dublicate = dublicate + 1

        changes = changes + 1

    update_data_for_nazwy(nazwy)

    info = "Powiodło się"
    text = "Przedmioty zostały zredagowane"

    if(dublicate > 0 and dublicate == changes):
        messagebox.showerror("Nastąpił błąd", "Nazwy przedmiotów się powtarzają", parent=okno_lista)
    elif(dublicate < changes):
        text = text + "\nCzęść nazw nie dodana z powodu powtórzenia się"

    messagebox.showinfo("Powiodło się", "Przedmioty zostały zredagowane", parent=okno_lista)

    for i in range(98):
        for elem in okno_lista.grid_slaves(row=i,column=0):
            elem.destroy()
    
    i = 1
    for name in nazwy:
        if(name != 'id'):
            Label(okno_lista, text=name, width=35).grid(row=i, column=0)
            i = i + 1