import pickle

def get_data_for_oceny(option,student_id=0):
    zestaw_ocen = []

    with open('oceny.dat', 'rb') as plik_oceny:
        try:
            while True:
                dane = pickle.load(plik_oceny)
                zestaw_ocen.append(dane)
        except EOFError:
            pass

    if(option == 2): #one record by id
        zestaw_ocen_studenta = next((item for item in zestaw_ocen if item.get('id') == student_id), None)
        return zestaw_ocen_studenta
    
    if(option == 1): #all records
        return zestaw_ocen

def get_data_for_nazwy():
    with open('nazwy_przedmiotow.dat', 'rb') as plik_przedmioty:
        try:
            while True:
                dane = pickle.load(plik_przedmioty)
        except EOFError:
            pass

    return dane

def del_data_for_oceny(student_id):
    zestaw_ocen = get_data_for_oceny(1) #all records

    for elem in zestaw_ocen:
        if(elem['id'] == student_id):
            zestaw_ocen.remove(elem)
            break

    with open('oceny.dat', 'wb') as plik_oceny:
        for dane in zestaw_ocen:
            pickle.dump(dane, plik_oceny)

def add_data_for_oceny(student_id):
    oceny = [0]
    zestaw_ocen = {}

    with open('nazwy_przedmiotow.dat', 'rb') as plik_przedmioty:
        nazwy_przedmiotow = pickle.load(plik_przedmioty)

    for nazwa in nazwy_przedmiotow:
        if(nazwa == 'id'):
            zestaw_ocen[nazwa] = student_id
        else:
            zestaw_ocen[nazwa] = oceny

    with open('oceny.dat', 'ab') as plik_oceny:
        pickle.dump(zestaw_ocen, plik_oceny)

def update_data_for_oceny(student_id, zestaw_ocen_studenta):
    if(int(student_id) > 0):
        zestaw_ocen = get_data_for_oceny(1)

        for i in range(len(zestaw_ocen)):
            if zestaw_ocen[i]['id'] == int(student_id):
                zestaw_ocen[i] = zestaw_ocen_studenta
                break

        with open('oceny.dat', 'wb') as plik_oceny:
            for dane in zestaw_ocen:
                pickle.dump(dane, plik_oceny)
    
    if(int(student_id) == 0):  # save whole for every student
        with open('oceny.dat', 'wb') as plik_oceny:
            for dane in zestaw_ocen_studenta:
                pickle.dump(dane, plik_oceny)

def update_data_for_nazwy(dane):
    with open('nazwy_przedmiotow.dat', 'wb') as plik_przedmioty:
        pickle.dump(dane, plik_przedmioty)