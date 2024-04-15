from tkinter import *
from tkinter import messagebox
import sqlite3
from oceny_file import del_data_for_oceny

def usuwanie(p_id, okno_forma):
    student_id = p_id.get()

    if student_id:

        db_connect = sqlite3.connect('dziennik.db')
        db_cursor = db_connect.cursor()
    
        db_cursor.execute('SELECT * FROM students WHERE rowid = {}'.format(str(student_id)))
        rekord = db_cursor.fetchone()

        if rekord:
            dane = "Imie: {}\nNazwisko: {}\nNr Albumu: {}".format(rekord[0],rekord[1],rekord[2])
            option = messagebox.askquestion("Podtwierdzenie", "Chcesz usunąć następujący rekord?\n\n" + dane , parent=okno_forma)

            if(option == 'yes'):
                db_cursor.execute('DELETE FROM students WHERE rowid = ' + str(student_id))
                db_connect.commit()
                del_data_for_oceny(student_id)

                messagebox.showinfo("Powiodło się", "Student został usunięty", parent=okno_forma)
            else:
                messagebox.showinfo("Info", "Skasowano usuwanie", parent=okno_forma)
        else:
            messagebox.showerror("Nastąpił błąd", "Student o takin ID nie istnieje", parent=okno_forma)
  
        db_connect.close()
    else:
        messagebox.showerror("Nastąpił błąd", "Nie podano numeru ID", parent=okno_forma)

def create_del_window():
    okno_forma = Toplevel()
    okno_forma.title("Usuwanie studenta")
    okno_forma.geometry("+550+200")
    okno_forma.focus_set()

    Label(okno_forma, text="Id studenta do usunięcia", width=20).grid(row=0, column=0)
    student_id = Entry(okno_forma, width=30)

    button_remove = Button(okno_forma, text="Usuń", command=lambda ID = student_id, okno = okno_forma: usuwanie(ID, okno))
    button_return = Button(okno_forma, text="Wrócić do Menu", command= lambda: okno_forma.destroy())

    student_id.grid(row=0, column=1)
    button_remove.grid(row=1, column=0, columnspan=2)
    button_return.grid(row=2, column=0, columnspan=2)