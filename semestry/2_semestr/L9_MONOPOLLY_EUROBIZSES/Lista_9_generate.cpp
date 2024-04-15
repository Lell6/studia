#include <iostream>
#include <time.h>

#include "Lista_9_gracz.cpp"
#include "Lista_9_bankier.cpp"
#include "Lista_9_gen_miasto.cpp"
#include "Lista_9_szansa.cpp"
#include "Lista_9_energia.cpp"
#include "Lista_9_koleja.cpp"
#include "Lista_9_parking.cpp"

#include "Lista_9_gra.cpp"

using namespace std;

void gen(int licz_graczy)
{
    pole_nazwa_cena* ptr[40]; // pozycje na polu

    pole_graczy* gracz[licz_graczy];
    pole_bankier* bankier;
    pole_szansa* szansa[8];
    pole_miasta* miasto[22];
    pole_energia* energia[4];
    pole_koleje* koleja[4];
    pole_parking* parking[3];

    srand(time(0));
    int i;

    for(i = 0; i < licz_graczy; i++)
        gracz[i] = new pole_graczy(i);

    bankier = new pole_bankier(0);

    *miasto = gen_miasto(miasto);

    for(i = 0; i < 8; i++)
        szansa[i] = new pole_szansa(i);

    for(i = 0; i < 4; i++)
        energia[i] = new pole_energia(i);

    for(i = 0; i < 4; i++)
        koleja[i] = new pole_koleje(i);

    for(i = 0; i < 3; i++)
        parking[i] = new pole_parking(i);

    ptr[0] = szansa[0]; // generowanie listy pol
    ptr[1] = miasto[0]; //-1
    ptr[2] = szansa[1];
    ptr[3] = miasto[1]; //-3
    ptr[4] = parking[0];
    ptr[5] = koleja[1];
    ptr[6] = miasto[2]; //-6
    ptr[7] = szansa[2];
    ptr[8] = miasto[3]; //-8
    ptr[9] = miasto[4]; //-9
    ptr[10] = energia[0];
    ptr[11] = miasto[5]; //-11
    ptr[12] = energia[1];
    ptr[13] = miasto[6]; //-13
    ptr[14] = miasto[7]; //-14
    ptr[15] = koleja[2];
    ptr[16] = miasto[8]; //-16
    ptr[17] = szansa[3];
    ptr[18] = miasto[9]; //-18
    ptr[19] = miasto[10]; //-19
    ptr[20] = parking[1];
    ptr[21] = miasto[11]; //-21
    ptr[22] = szansa[4];
    ptr[23] = miasto[12]; //-23
    ptr[24] = miasto[13]; //-24
    ptr[25] = koleja[0];
    ptr[26] = miasto[14]; //-26
    ptr[27] = miasto[15]; //-27
    ptr[28] = energia[2];
    ptr[29] = miasto[16]; //-29
    ptr[30] = szansa[5];
    ptr[31] = miasto[17]; //-31
    ptr[32] = miasto[18]; //-32
    ptr[33] = parking[2];
    ptr[34] = miasto[19]; //-34
    ptr[35] = koleja[3];
    ptr[36] = szansa[6];
    ptr[37] = miasto[20]; //-37
    ptr[38] = szansa[7];
    ptr[39] = miasto[21]; //-39

    for(i = 0; i < 40; i++)
        ptr[i]->pos_pola = i;

    gra(gracz,bankier,szansa,miasto,energia,koleja,parking,ptr,licz_graczy);
}
