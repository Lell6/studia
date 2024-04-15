#include <iostream>
#include <string>
#include <cstdio>
#include <conio.h>

#include "Lista_9_pole.cpp"
#include "Lista_9_sprawdzenie.cpp"

using namespace std;

void gra(pole_graczy* gracz[], pole_bankier* bankier, pole_szansa* szansa[], pole_miasta* miasto[], pole_energia* energia[], pole_koleje* koleja[], pole_parking* parking[],
         pole_nazwa_cena* ptr[],int licz_graczy)

{
    bool graczy_status = true; // co najmniej 2 graczy nie sa bankrotami
    int majatek_gracz = 0; // zaden gracz nie ma wyznaczonej kwoty pieniedzy
    int pos_a[5] = {0,0,0,0,0};
    int i, j, indx, pos_before, pos_after;

    while(bankier->status == true || graczy_status == true || majatek_gracz == 0) // w kolko az zmieni sie jeden z warunkow (1 - bankier posiada majatek)
    {
        for(i = 0; i < licz_graczy; i++)
        {
            wypisz(ptr,'o',pos_a);
            gracz_info(gracz,licz_graczy,bankier->ilosc_pieniedzy);
            cout<<"Teraz kolej gracza: "<<gracz[i]->gracz_znak<<endl<<endl;

            sprawdzenie_wlascicieli(gracz,miasto,i,bankier,0); // sprawdzamy cvzy gracz posiada wszystkie pola danego kraju
            /*for(j = 0; j < 4; j++)
            koleja[j]->cena = sprawdzenie_kolej(gracz,koleja);*/

            cout<<"Nacisnij klawisz, aby rzucic kostki"<<endl;
            _getch();

            pos_before = gracz[i]->pos;

            gracz[i]->rzut(); //rzucamy kostka
            pos_a[i] = gracz[i]->pos;

            if(pos_before != pos_a[i])
            {
                cout<<"Nacisnij klawisz, aby kontynuowac"<<endl;
                _getch();

                wypisz(ptr,'o',pos_a);
                gracz_info(gracz,licz_graczy,bankier->ilosc_pieniedzy);
                cout<<"Teraz kolej gracza: "<<gracz[i]->gracz_znak<<endl<<endl;

                majatek_gracz = gracz[i]->sprawdzenie_majatku(majatek_gracz);
                bankier->sprawdzenie_majatku();

                ptr[gracz[i]->pos]->wykonaj(gracz,bankier,i,licz_graczy,ptr[i]->pos_pola); //pobieramy pozycje gracza, wykonujemy operacje z polem
            }

            cout<<endl<<"Nacisnij klawisz, aby przejsz do kolejnego gracza"<<endl;
            _getch();
        }
    }
}

