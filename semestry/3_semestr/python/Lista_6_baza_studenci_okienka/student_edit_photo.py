from tkinter import *
from tkinter import messagebox
from tkinter import filedialog
import sqlite3

def zmiana_sciezki(okno_photo, student_id, pole_photo): # podawanie ścieżki własnoręcz
    path =  pole_photo.get()

    if path:
        db_connect = sqlite3.connect('dziennik.db')
        db_cursor = db_connect.cursor()
            
        db_cursor.execute('SELECT * FROM students WHERE rowid = {}'.format(int(student_id)))
        update_query = "UPDATE students SET photo = ? WHERE rowid = ?"

        values = (path, student_id)
        db_cursor.execute(update_query, values)
        db_connect.commit()

        db_connect.close()

        messagebox.showinfo("Powiodło się", "Zdjęcie zostało zmienione", parent=okno_photo)
    else:
        messagebox.showerror("Nastąpił błąd", "Brak wypełnionego pola", parent=okno_photo)


def redagowanie_sciezki(student_id, okno_forma):
    path = filedialog.askopenfilename(
        title="Wybierz plik",
        filetypes=[("Obraz", ["*.png", "*.jpg", "*.jpeg", "*.bmp", "*.tiff"] )]
    )
    if path == "":
        path = "-"

    db_connect = sqlite3.connect('dziennik.db')
    db_cursor = db_connect.cursor()
            
    db_cursor.execute('SELECT * FROM students WHERE rowid = {}'.format(int(student_id)))
    update_query = "UPDATE students SET photo = ? WHERE rowid = ?"

    values = (path, student_id)
    db_cursor.execute(update_query, values)
    db_connect.commit()

    db_connect.close()

    print(path)

    if path != "-":
        messagebox.showinfo("Powiodło się", "Zdjęcie zostało zmienione", parent=okno_forma)
    else:
        messagebox.showinfo("Info", "Zdjęcie zostało usunięte", parent=okno_forma)