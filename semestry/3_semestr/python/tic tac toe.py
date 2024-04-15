from tkinter import *

size = 5

def koniec_gry():
    okno_gry.destroy() 

def pole_reset(buttons, info):
    for i in range(size):
        for j in range(size):
            buttons[i][j]['state'] = NORMAL
            buttons[i][j]['text'] = '-'

    info.configure(text="Kolej gracza x")    

def check_combinations(buttons, znak, info):
    check_hor = 0
    check_ver = 0
    check_diag_left = 0
    check_diag_right = 0

    for i in range(3):
        for j in range(3):
            if(buttons[0+i][0+j]['text'] == znak and buttons[0+i][1+j]['text'] == znak and buttons[0+i][2+j]['text'] == znak):
                check_hor = check_hor + 1

    for i in range(3):
        for j in range(3):
            if(buttons[0+i][0+j]['text'] == znak and buttons[1+i][0+j]['text'] == znak and buttons[2+i][0+j]['text'] == znak):
                check_ver = check_ver + 1

    for i in range(3):
        for j in range(3):
            if(buttons[0+i][0+j]['text'] == znak and buttons[1+i][1+j]['text'] == znak and buttons[2+i][2+j]['text'] == znak):
                check_diag_left = check_diag_left + 1

    for i in range(3):
        for j in range(3):
            if(buttons[0+i][4-j]['text'] == znak and buttons[1+i][3-j]['text'] == znak and buttons[2+i][2-j]['text'] == znak):
                check_diag_right = check_diag_right + 1

    check = []

    check.append(check_hor)
    check.append(check_ver)
    check.append(check_diag_left)
    check.append(check_diag_right)

    for i in range(4):
        if(check[i] > 0):
            check[i] = znak

    if 'o' in check:
        info.configure(text="O wygral")
        return 1
    
    elif 'x' in check:
        info.configure(text="X wygral")
        return 1

def przycisk(curr_button, buttons, info):
    global znak
    global count

    curr_button['text'] = znak
    curr_button['state'] = DISABLED
            
    if check_combinations(buttons, znak, info) == 1:
        for i in range(size):
            for j in range(size):
                buttons[i][j]['state'] = DISABLED
        return

    if(znak == 'x'):
        znak = 'o'
    else:
        znak = 'x'
    
    info.configure(text="Kolej gracza: " + znak)

    count = count + 1

    if(count == size*size):
        koniec_gry()

okno_gry = Tk()
okno_gry.title("Kółko krzyżyk")

wygrana = False
znak = 'x'
count = 0

button = [None] * size

for i in range(size):
    button[i] = [None] * size

for i in range(size):
    for j in range(size):
        button[i][j] = Button(okno_gry, text="-", state=NORMAL, width=3, height=1, font=('Arial 15 bold'))
        button[i][j].grid(row=i, column=j)

info = Label(okno_gry, text="Kolej gracza x")
info.grid(row=size + 1, column=0, columnspan=size)

for i in range(size):
    for j in range(size):
        button[i][j].configure(command=lambda row = i, col = j: przycisk(button[row][col], button, info))

button_reset = Button(okno_gry, text="Zaczac od nowa", state=NORMAL, font=('Arial 10'), command=lambda: pole_reset(button, info))
button_reset.grid(row=size + 2, column = 0, columnspan=size)


okno_gry.mainloop()