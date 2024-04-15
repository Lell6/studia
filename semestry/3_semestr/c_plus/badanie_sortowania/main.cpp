#include <iostream>
#include <cstdio>
#include <ctime>
#include <algorithm>
using namespace std;

int * tab_gen(int * tab, int tab_size){
    srand(time(NULL));

    for(int i = 0; i < tab_size; i++)
        tab[i] = rand()%tab_size;

    return tab;
}

int liniowe(int * tab, int tab_size, int tab_search){
    int tab_found = -1;

    for(int i = 0; i < tab_size; i++)
        if(tab[i] == tab_search) {
            tab_found = i;
            break;
        }

    return tab_found;
}

int main()
{
    int tab_size;

    cout<<"Podaj rozmiar tablicy: ";
    cin>>tab_size;

    int * tab = new int[tab_size];

    tab = tab_gen(tab,tab_size);
    sort(tab, tab + tab_size);

    int option, tab_search, tab_found;

    //cout<<"("<<tab[rand()%tab_size]<<")"<<endl<<endl;
    cout<<"Szukana wartosc: "; cin>>tab_search;

    cout<<"Rodzaj sortowania: "<<endl;
    cout<<"1 - liniowe"<<endl<<endl;
    cout<<"Opcja: ";

    cin>>option;
    clock_t start, finish;

    switch(option){
        case 1:
            start = clock();

            tab_found = liniowe(tab, tab_size, tab_search);

            finish = clock();
            break;
        default:
            break;
    }

    if(tab_found != -1)
        cout<<"Pozycja wartosci: "<<tab_found<<endl;
    else
        cout<<"Brak wartosci"<<endl;

    cout<<"Czas wyszukiwania: "<<(finish - start)<<" ms"<<endl;

    return 0;
}
