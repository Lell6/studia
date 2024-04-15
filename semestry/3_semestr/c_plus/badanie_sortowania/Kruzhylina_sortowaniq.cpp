#include <iostream>
#include <cstdio>
#include <chrono>
#include <ctime>
#include <math.h>
#include <algorithm>
using namespace std;

int * tab_gen(int * tab, int tab_size){
    srand(time(NULL));

    for(int i = 0; i < tab_size; i++)
        tab[i] = (rand() * rand()) % tab_size;

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

int binarne(int * tab, int tab_search, int i_beg, int i_end){
    int tab_found = -1;
    int tab_size = i_end;

    int i_middle = floor((i_beg + i_end-1)/2);

    while(tab[i_middle] != tab_search){

        if(tab[i_middle] > tab_search){
            i_end = i_middle;
            i_middle = floor((i_beg + i_end-1)/2);
            //cout<<"bigger"<<endl;

        } else if(tab[i_middle] < tab_search){
            i_beg = i_middle + 1;
            i_middle = floor((i_beg + i_end-1)/2);
            //cout<<"smaller"<<endl;
        }
        if(i_middle == 0 || i_middle == tab_size-1)
            break;

    }

    if(tab[i_middle] == tab_search)
        tab_found = i_middle;
    else
        tab_found = -1;

    return tab_found;
}

int main()
{
    int tab_size;
    srand(time(NULL));

    cout<<"Podaj rozmiar tablicy: ";
    cin>>tab_size;

    int * tab = new int[tab_size];

    tab = tab_gen(tab,tab_size);
    sort(tab, tab + tab_size);

    int option, tab_search, tab_found;
    long long indx = (rand() * rand()) % tab_size;

    /*for(int i = 0; i < tab_size; i++){
        cout<<i<<" - "<<tab[i]<<endl;
    }*/

    cout<<"("<<indx<<" - "<<tab[indx]<<")"<<endl<<endl;
    cout<<"Szukana wartosc: "; cin>>tab_search;

    typedef std::chrono::high_resolution_clock Time;
    typedef std::chrono::nanoseconds ns;
    typedef std::chrono::duration<float> fsec;
    chrono::time_point<chrono::system_clock> t_start, t_end;

    t_start = chrono::high_resolution_clock::now();
        tab_found = liniowe(tab, tab_size, tab_search);
    t_end = chrono::high_resolution_clock::now();

    fsec fs = t_end - t_start;
    ns time = std::chrono::duration_cast<ns>(fs);

    if(tab_found != -1)
        cout<<"Pozycja wartosci: "<<tab_found<<endl;
    else
        cout<<"Brak wartosci"<<endl;

    cout<<"Czas wyszukiwania: "<<time.count()<<" ns"<<endl<<endl;

    t_start = chrono::high_resolution_clock::now();
        tab_found = binarne(tab, tab_search, 0, tab_size);
    t_end = chrono::high_resolution_clock::now();

    fs = t_end - t_start;
    time = std::chrono::duration_cast<ns>(fs);

    if(tab_found != -1)
        cout<<"Pozycja wartosci: "<<tab_found<<endl;
    else
        cout<<"Brak wartosci"<<endl;

    cout<<"Czas wyszukiwania: "<<time.count()<<" ns"<<endl;

    return 0;
}
