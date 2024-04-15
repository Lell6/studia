#include <iostream>
#include <list>
#include <vector>
#include <fstream>

using namespace std;

void zapisz(list<student> rekordy)
{
    ofstream plik("dane.bin", ios::binary);

    system("cls");
    if(plik.is_open()){
        for(auto& el : rekordy){
            plik.write((char*)&el, sizeof(el));
        }
        plik.close();
        cout<<"Rekordy zostaly zapisane do pliku"<<endl<<endl;
    }
    else
        cout<<"Blad odczytu pliku"<<endl<<endl;
}

list<student> odczytaj(list<student> rekordy)
{
    ifstream plik("dane.bin", ios::binary);

    system("cls");
    if(plik.is_open()){
        student temp = student(1);

        while(plik.read((char*)&temp, sizeof(temp))){
            rekordy.push_back(temp);
        }
        plik.close();

        cout<<"Rekordy zostaly pobrane z pliku"<<endl;
    }
    else
        cout<<"Blad odczytu pliku";

    return rekordy;
}
