from tkinter import *
from tkinter import messagebox
import sqlite3

def redagowanie_nazwy(p_imie, p_nazwisko, p_album, p_id, okno_forma):
    student_id = p_id.get()
    variables = {'imie': p_imie.get(), 'nazwisko': p_nazwisko.get(), 'album': p_album.get()}

    if any(value != '' for value in variables.values()):

        db_connect = sqlite3.connect('dziennik.db')
        db_cursor = db_connect.cursor()

        if variables['album']:
            db_cursor.execute('SELECT * FROM students WHERE album = ' + str(variables['album']))

        if db_cursor.fetchone():
            messagebox.showerror("Nastąpił błąd", "Istnieje już rekord o takim numerze albumu", parent=okno_forma)
        else:
            for name, value in variables.items():
                if value != '':
                    update_query = "UPDATE students SET " + name + " = ? WHERE rowid = ?"
                    values = (value, student_id)
                    db_cursor.execute(update_query, values)
                    db_connect.commit()
            
            db_connect.close()

            messagebox.showinfo("Powiodło się", "Student został zredagowany", parent=okno_forma)
    else:
        messagebox.showerror("Nastąpił błąd", "Brak wypełnionych pól", parent=okno_forma)