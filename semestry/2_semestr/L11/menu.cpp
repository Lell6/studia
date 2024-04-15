#include <iostream>
#include <list>
#include <vector>
#include <algorithm>

#include "klasy.cpp"
#include "show.cpp"
#include "pliki.cpp"
using namespace std;

int main(){
    int option;
    list<student> rekordy;

    do{
        cout<<"1 - Dodac nowy rekord studenta"<<endl;
        cout<<"2 - Usunac rekord studenta"<<endl;
        cout<<"3 - Znalezc rekord studenta"<<endl;
        cout<<"4 - Zmienic dane w rekordzie studenta"<<endl;
        cout<<"5 - Wyswietlic rekordy studentow"<<endl<<endl;

        cout<<"6 - Zapisac do pliku"<<endl;
        cout<<"7 - Odczytac z pliku"<<endl<<endl;
        cout<<"0 - Wyjsc z programu"<<endl<<endl;
        cout<<"Prosze wybrac opcje: "; cin>>option;

        switch(option)
        {
        case 1:
            {
                rekordy.push_back(student(0));

                system("cls");
                cout<<"Rekord zostal dodany"<<endl<<endl;
            }
            break;
        case 2:
            {
                int indeks;
                system("cls");
                cout<<"Prosze podac rekord do usuwania(numer albumu): "; cin>>indeks;

                for(list<student>::iterator wsk = rekordy.begin(); wsk != rekordy.end(); wsk++)
                    if(wsk->nr_albumu == indeks)
                        wsk = rekordy.erase(wsk);
            }
            break;
        case 3:
            {
                int indeks;
                system("cls");
                cout<<"Prosze podac rekord do wyszukiwania(nr albumu): "; cin>>indeks;

                for(list<student>::iterator wsk = rekordy.begin(); wsk != rekordy.end(); wsk++)
                    if(wsk->nr_albumu == indeks){
                        show(wsk,1);
                        break;
                    }
            }
            break;
        case 4:
            {
                int indeks;
                system("cls");
                cout<<"Prosze podac rekord do zmiany(nr albumu): "; cin>>indeks;

                for(list<student>::iterator wsk = rekordy.begin(); wsk != rekordy.end(); wsk++)
                    if(wsk->nr_albumu == indeks){
                        cout<<"1 - Zmiana danych studenta"<<endl;
                        cout<<"2 - Dodawanie semestru"<<endl<<endl;
                        cout<<"3 - Wrocic do menu"<<endl;
                        cout<<"Prosze wybrac opcje: "; cin>>option;

                        do{
                            if(option == 1){
                                cout<<"Prosze podac"<<endl;
                                cout<<"\t -Imie: "; cin>>wsk->imie;
                                cout<<"\t -Nazwisko: "; cin>>wsk->nazwisko;
                                cout<<"Dane zostaly zaktualizowane";
                                break;
                            }
                            else if(option == 2){
                                cout<<"Prosze podac"<<endl;
                                cout<<"\t -Numer semestru: "; cin>>wsk->nr_semestru;

                                (wsk->semestry).push_back(semestr(0));
                                cout<<"Dane zostaly zaktualizowane";
                                break;
                            }
                        }while(option != 3);
                        system("cls");
                        break;
                    }
            }
            break;
        case 5:
            wyswietl(rekordy);
            break;
        case 6:
            zapisz(rekordy);
            break;
        case 7:
            rekordy = odczytaj(rekordy);
            break;
        default:
            cout<<"Nieprawidlowa wartosc"<<endl;
            break;
        }
    }while(option != 0);
}
