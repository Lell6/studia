#include <iostream>
#include <iomanip>
#include <string>
#include <algorithm>

#define MIN 20
using namespace std;

void wypisz_dolny_bok(pole_nazwa_cena* ptr, char znak_bok, int pos_a[], char znak_gr, int i)
{
    int j;

    for(j = 0; j < 5; j++)
    {
        if(i == pos_a[j])
            ptr->graczy[j] = znak_gr;
        else
            ptr->graczy[j] = '.';
    }

    for(j = 0; j < 5; j++) // wypisywanie wlascicieli pol
    {
            if(ptr->nazwa == "Szansa"
               || ptr->nazwa == "Ryzyko"
               || ptr->nazwa == "Parking strezowy"
               || ptr->nazwa == "Darmowy parking"
               || ptr->nazwa == "Start")
            cout<<znak_bok;
            else
                cout<<ptr->zabudowa[j];
    }

    for(j = 0; j < 10; j++)
        cout<<znak_bok;

    for(j = 0; j < 5; j++)
        cout<<ptr->graczy[j];

    cout<<"|";
}

void gracz_info(pole_graczy* gracz[],int licz_graczy,int majatek)
{
    int i;
    int dlugosc;

    cout<<" Majatek banku: "<<majatek<<" zl"<<endl<<endl;
    cout<<"Imie    - |";
    for(i = 0; i < licz_graczy; i++)
    {
        cout<<gracz[i]->nazwa;
        dlugosc = gracz[i]->nazwa.length();

        if(dlugosc <= MIN)
            cout<<setw(20-dlugosc)<<"";

        cout<<"|   ";
    }

    cout<<endl<<"Znak    - |";
    for(i = 0; i < licz_graczy; i++)
    {
        cout<<gracz[i]->gracz_znak;

        if(dlugosc <= MIN)
            cout<<setw(19)<<"";

        cout<<"|   ";
    }

    cout<<endl<<"Status  - |";
    for(i = 0; i < licz_graczy; i++)
    {
        cout<<gracz[i]->status;

        if(dlugosc <= MIN)
            cout<<setw(19)<<"";

        cout<<"|   ";
    }

    cout<<endl<<"Majatek - |";
    for(i = 0; i < licz_graczy; i++)
    {
        string s = to_string(gracz[i]->ilosc_pieniedzy);
        dlugosc = s.size();

        cout<<gracz[i]->ilosc_pieniedzy<<" zl";

        if(dlugosc <= MIN)
            cout<<setw(20-dlugosc-3)<<"";

        cout<<"|   ";
    }

    cout<<endl;
    for(int k = 0; k < 10+(27*licz_graczy); k++)
        cout<<"-";

    cout<<endl<<endl;
}

void wypisz_cena(pole_nazwa_cena* ptr) //rzad ceny
{
    int i;
    int dlugosc;

    int l = ptr->nazwa.length();
    string s = to_string(ptr->cena);

        if(l%2 == 0)
        {
            l=l/2;
            if(s.length() == 3)
            {
                cout<<setw(l-1)<<"";
                cout<<ptr->cena;
                cout<<setw(l-2)<<"";
            }
            else if(s.length() == 4)
            {
                cout<<setw(l-2)<<"";
                cout<<ptr->cena;
                cout<<setw(l-2)<<"";
            }
            else
            {
                cout<<setw(l)<<"";
                cout<<ptr->cena;
                cout<<setw(l-1)<<"";
            }
        }
        else if(l%2 == 1)
        {
            l=l/2;
            if(s.length() == 3)
            {
                cout<<setw(l-1)<<"";
                cout<<ptr->cena;
                cout<<setw(l-1)<<"";
            }
            else if(s.length() == 4)
            {
                cout<<setw(l-1)<<"";
                cout<<ptr->cena;
                cout<<setw(l-2)<<"";
            }
            else
            {
                cout<<setw(l)<<"";
                cout<<ptr->cena;
                cout<<setw(l)<<"";
            }
        }

        dlugosc = ptr->nazwa.length();

        if(dlugosc <= MIN)
            cout<<setw(20-dlugosc)<<"";

        cout<<"|";
}

void wypisz_rzad(pole_nazwa_cena* ptr[], int pocz,char znak_gr,int pos_a[],char znak_bok)
{
    int i,j;
    int dlugosc;
    char znak = 192;

    for(i = 0; i < 11; i++) // rzad nazwy
    {
        if(pocz == 10) i *= -1;

        cout<<ptr[pocz+i]->nazwa;
        dlugosc = ptr[pocz+i]->nazwa.length();

        if(dlugosc <= MIN)
            cout<<setw(20-dlugosc)<<"";

        cout<<"|";

        if(pocz == 10)
            i *= -1;
    }

    cout<<endl<<"|";

    for(i = 0; i < 11; i++) //rzad ceny
    {
        if(pocz == 10 && i != 0) i *= -1;
        wypisz_cena(ptr[pocz+i]);

        if(pocz == 10)
            i *= -1;
    }

    cout<<endl<<"|";

    for(i = 0; i < 11; i++) // rzad wiadomosci o polu
    {
        if(pocz == 10 && i != 0) i *= -1;

        wypisz_dolny_bok(ptr[pocz+i],znak_bok,pos_a,znak_gr,pocz+i);

        if(pocz == 10)
            i *= -1;
    }
    cout<<endl;

    if(pocz != 10)
        cout<<"|";
    else
        cout<<znak;

    for(i = 0; i < 11; i++) //rzad dolny/pusty
    {
        int max_w;

        if(i == 10)
            max_w = 20;
        else
            max_w = 21;

        for(j = 0; j < max_w; j++)
        {
            if(j == max_w-1 && pocz != 10)
            {
                if(i != 0 && i != 9 && i != 10)
                {
                    znak = 193;
                    cout<<znak;
                }
                else if(i == 0 || i == 9)
                {
                    znak = 197;
                    cout<<znak;
                }
                else
                    cout<<znak_bok;
            }
            else if(j == max_w-1 && pocz == 10)
            {
                if(i != 10)
                {
                    znak = 193;
                    cout<<znak;
                }
                else
                    cout<<znak_bok;
            }
            else
                cout<<znak_bok;
        }
    }

    znak = 217;
    if(pocz != 10)
        cout<<"|"<<endl;
    else
        cout<<znak<<endl;

}

void wypisz(pole_nazwa_cena* ptr[],char znak_gr,int pos_a[]) //cale pole
{
    string poziom;
    char znak_kat = 218, znak_bok = 196;
    int dlugosc;
    int i,j;

    system("cls");
    cout<<znak_kat;
    for(i = 0; i < 11; i++) // krawedz gorna
    {
        dlugosc = MIN;

        znak_kat = 194;
        for(j = 0; j < dlugosc; j++)
        {
            if(j == 0 && i != 0)
                cout<<znak_kat<<znak_bok;
            else
                cout<<znak_bok;
        }

        if(i != 0 && i != 10)
            for(j = 0; j < dlugosc; j++)
                poziom = poziom + " ";

        if(i != 0 && i != 10)
            poziom = poziom + " ";
    }

    if(!poziom.empty())
            poziom.erase(poziom.size() - 1);

    znak_kat = 191;
    cout<<znak_kat<<endl<<"|";

    wypisz_rzad(ptr,20,znak_gr,pos_a,znak_bok); // pola gorne

    for(i = 19; i >= 11; i--)//rzad nazwy pol bocznych
    {
        cout<<"|";

        cout<<ptr[i]->nazwa;
        dlugosc = ptr[i]->nazwa.length();

        if(dlugosc <= MIN)
            cout<<setw(20-dlugosc)<<"";

        cout<<"|"<<poziom<<"|";

        cout<<ptr[50-i]->nazwa;
        dlugosc = ptr[50-i]->nazwa.length();

        if(dlugosc <= MIN)
            cout<<setw(20-dlugosc)<<"";

        cout<<"|"<<endl<<"|";

        wypisz_cena(ptr[i]);
        cout<<poziom<<"|";
        wypisz_cena(ptr[50-i]);
        cout<<endl;

        cout<<"|";  //rzad dolny pol bocznych

        wypisz_dolny_bok(ptr[i],znak_bok,pos_a,znak_gr,i);

        cout<<poziom<<"|";

        wypisz_dolny_bok(ptr[50-i],znak_bok,pos_a,znak_gr,50-i);
        cout<<endl<<"|";

        if(i == 11)
            replace(poziom.begin(),poziom.end(),' ',znak_bok);

        for(j = 0; j < 20; j++)
            cout<<znak_bok;

        znak_kat = 197;
        cout<<znak_kat<<poziom<<znak_kat;

        for(j = 0; j < 20; j++)
            cout<<znak_bok;

        cout<<"|"<<endl;
    }

    cout<<"|";
    wypisz_rzad(ptr,10,znak_gr,pos_a,znak_bok);

    cout<<endl;
}

