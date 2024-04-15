#include <iostream>
using namespace std;

class pole_energia:public pole_nazwa_cena
{
  public:

    pole_energia(int i)
    {
        int los = rand()%2;

        if(los == 0)
            nazwa = "Siec Wodociagow";
        else
            nazwa = "Elektrownia";
    };

    void licytacja(pole_graczy* gracz[], pole_bankier* bankier,int num_gracz,int max_gracz)
    {
        int i;
        char option;
        for(i = 0; i < max_gracz; i++)
        {
            if(i != num_gracz)
            {
                cout<<"Gracz "<<gracz[i]->gracz_znak<<endl;
                cout<<"Chcesz kupic pole (t/n)?"<<endl;
                cin>>option;

                if(option == 't')
                {
                    kupno(gracz[i],bankier);
                    return;
                }
            }
        }
    };

    void kupno(pole_graczy* gracz,pole_bankier* bankier)
    {
        wlasciciel = gracz->gracz_znak;

        zabudowa[0] = wlasciciel;
        gracz->zmiana_majatku(cena);
        bankier->ilosc_pieniedzy += cena;
    }
    void oplata(pole_graczy* gracz_kto, pole_graczy* gracz_komu)
    {
        gracz_kto->zmiana_majatku(cena);
        gracz_komu->zmiana_majatku(cena*(-1));
    };

    void wykonaj(pole_graczy* gracz[],pole_bankier* bankier,int num_gracz,int max_gracz,int pos_p) override //zamieniamy wirtualna metode
    {
        char option;
        int p = 0;

        cout<<"Jestes na polu '"<<nazwa<<"'"<<endl;

        if(wlasciciel != 45 && gracz[num_gracz]->gracz_znak != wlasciciel)
        {
            cout<<"Musisz zaplacic wlascicielu za postoj"<<endl;

            while(gracz[p]->gracz_znak != wlasciciel)
                p++;

            oplata(gracz[num_gracz],gracz[p]);
        }
        else if(wlasciciel == 45)
        {
            cout<<"Chcesz go kupic (t/n)?"<<endl;
            cin>>option;

            if(option == 't')
                kupno(gracz[num_gracz],bankier);
            else
                licytacja(gracz,bankier,num_gracz,max_gracz);
        }
    };

  private:
    char wlasciciel = 45;
};
