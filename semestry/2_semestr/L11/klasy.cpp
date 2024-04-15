#include <iostream>
#include <list>
#include <vector>
#include <algorithm>

using namespace std;

class oceny_student{
public:
    int wartosc = 0;
    string przedmiot = "-1";
    string data = "-1";
    string imie_prowadzacy = "-1";
    string nazwisko_prowadzacy = "-1";

    oceny_student(int option){
        if(option == 0){
            cout<<"Prosze podac"<<endl;
            cout<<"\t -przedmiot: "; cin>>przedmiot;

            cout<<"\t -stopien oceny: ";
            do{
                cin>>wartosc;
            }while(wartosc > 5 || wartosc < 2);

            cout<<"\t -data: "; cin>>data;
            cout<<"\t -imie prowadzacego: "; cin>>imie_prowadzacy;
            cout<<"\t -nazwisko prowadzacego: "; cin>>nazwisko_prowadzacy;
        }
    }
};

class semestr{
public:
    int numer = -1;
    int licz_oc = -1;
    vector<oceny_student> oceny;

    semestr(int option){
        if(option == 0){
            cout<<"Liczba przedmiotow do zaliczenia: "; cin>>licz_oc;
            oceny.reserve(licz_oc);

            for(int i = 0; i < licz_oc; i++){
                system("cls");
                cout<<"Przedmiot "<<i+1<<endl;
                oceny.push_back(oceny_student(option));
            }
        }
        else{
            oceny.push_back(oceny_student(option));
        }
    }
};

class student{
public:
    int nr_albumu = -1;
    int nr_semestru = -1;
    string imie = "-1";
    string nazwisko = "-1";

    vector<semestr> semestry;

    student(int option){
        if(option == 0)
        {
            //system("cls");
            cout<<"Prosze podac"<<endl;
            cout<<"\t -Numer albumu: "; cin>>nr_albumu;
            cout<<"\t -Imie: "; cin>>imie;
            cout<<"\t -Nazwisko: "; cin>>nazwisko;
            cout<<"\t -Semestr: "; cin>>nr_semestru;

            semestry.push_back(semestr(option));
        }
        else{
            semestry.push_back(semestr(option));
        }
    }
};

