from tkinter import *
from tkinter import messagebox

from oceny_file import update_data_for_oceny

def redagowanie_ocen(entry_list, zestaw_ocen, student_id, okno_forma):    
    i = 0
    count = 0
    wartosci_ocen = [0, 2, 3, 3.5, 4, 4.5, 5, 5.5]
    
    for name in zestaw_ocen:
        if(name != 'id'):
            oceny_char = entry_list[i].get()
            oceny_char = oceny_char.split()

            try:
                for elem in oceny_char:
                    int(elem)

                check_int = True
            except ValueError:
                check_int = False

            try:
                for elem in oceny_char:
                    float(elem)

                check_float = True
            except ValueError:
                check_float = False


            if(check_int or check_float):
                oceny = []

                for elem in oceny_char:
                    if(len(elem) == 1):
                        oceny.append(int(elem))
                    elif(len(elem) == 3):
                        oceny.append(float(elem))

                for elem in oceny:
                    if(elem not in wartosci_ocen):
                        count = count + 1
                
                if(count > 0):
                    messagebox.showerror("Nastąpił błąd", "Liczby nie są z wymaganego zakresu", parent=okno_forma)
                    break
                else:
                    zestaw_ocen[name] = oceny

                i = i + 1
            else:
                messagebox.showerror("Nastąpił błąd", "Należy podawać liczby", parent=okno_forma)
                break

    if(count == 0 and (check_int == True or check_float == True)):
        update_data_for_oceny(student_id, zestaw_ocen)

        messagebox.showinfo("Powiodło się", "Oceny zostały zredagowane", parent=okno_forma)
        i = 0