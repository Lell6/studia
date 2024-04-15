#include <iostream>
#include "Lista_9_generate.cpp"

using namespace std;

int main()
{
    cout<<"Monopoly"<<endl<<endl;
    int option;
    int graczy;

    do
    {
        cout<<"1 - Start"<<endl;
        cout<<"2 - Wyjsc"<<endl;
        cin>>option;

        switch(option)
        {
        case 1:
            do
            {
                system("cls");
                cout<<"Liczba graczy: ";
                cin>>graczy;
            }while(graczy > 5 || graczy < 2);

            gen(graczy); //plik generate.cpp
            break;
        case 2:
            break;
        default:
            cout<<"Nieprawidlowa wartosc"<<endl;
            continue;
        }
    }while(option != 2);

   return 0;
}

