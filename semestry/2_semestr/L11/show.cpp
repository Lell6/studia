#include <iostream>
#include <list>
#include <vector>
#include <algorithm>

using namespace std;

void show(list<student>::iterator wsk, int i)
{
    cout<<"Student: "<<i<<endl;
        cout<<"    Numer albumu: "<<wsk->nr_albumu<<endl;
        cout<<"    Imie: "<<wsk->imie<<endl;
        cout<<"    Nazwisko: "<<wsk->nazwisko<<endl;
        cout<<"    Numer semestru: "<<wsk->nr_semestru<<endl;
        cout<<endl;

        cout<<"    Semestry: "<<endl<<endl;
        i = 1;
        for(vector<semestr>::iterator wsk_s = wsk->semestry.begin(); wsk_s != wsk->semestry.end(); ++wsk_s){
            cout<<"     "<<i<<endl;

            for(vector<oceny_student>::iterator wsk_o = wsk_s->oceny.begin(); wsk_o != wsk_s->oceny.end(); ++wsk_o){
                cout<<"      Przedmiot: "<<wsk_o->przedmiot<<endl;
                cout<<"      Stopien oceny: "<<wsk_o->wartosc<<endl;
                cout<<"      Data wystawienia: "<<wsk_o->data<<endl;
                cout<<"      Imie prowadzacego: "<<wsk_o->imie_prowadzacy<<endl;
                cout<<"      Nazwisko prowadzacego: "<<wsk_o->nazwisko_prowadzacy<<endl<<endl;
            }
            i++;
        }
}

void wyswietl(list<student> rekordy)
{
    unsigned rozm = rekordy.size();

    system("cls");
    int i = 1;
    cout<<"Lista studentow: "<<endl<<endl;

    for(list<student>::iterator wsk = rekordy.begin(); wsk != rekordy.end(); wsk++){
        show(wsk, i);
        i++;
    }
}
