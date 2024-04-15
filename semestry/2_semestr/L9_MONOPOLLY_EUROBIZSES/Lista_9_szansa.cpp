using namespace std;

class pole_szansa:public pole_nazwa_cena
{
  public:
    pole_szansa(int i)
    {
        if(i == 0)
        {
            nazwa = "Start";
            cena = 100;
        }
        else
        {
            if(i%2 == 0)
            {
                nazwa = "Szansa";
                cena = 100;
            }
            else
            {
                nazwa = "Ryzyko";
                cena = -100;
            }
        }
    };

    void wykonaj(pole_graczy* gracz[],pole_bankier* bankier,int num_gracz,int max_gracz,int pos_p) override //zamieniamy wirtualna metode
    {
        char option;
        int cena_pst;

        cout<<"Jestes na polu ";

        if(nazwa == "Szansa")
        {
            cout<<"'Szansa'"<<endl;
            cena_pst = rand()%250+50;

            cout<<"Otrzymujesz "<<cena_pst<<" zl"<<endl;
            bankier->ilosc_pieniedzy -= cena_pst;
            gracz[num_gracz]->ilosc_pieniedzy += cena_pst;
        }
        else if(nazwa == "Ryzyko")
        {
            cout<<"'Ryzyko'"<<endl;
            cena_pst = rand()%250+50;

            cout<<"Tracisz "<<cena_pst<<" zl"<<endl;
            bankier->ilosc_pieniedzy += cena_pst;
            gracz[num_gracz]->ilosc_pieniedzy -= cena_pst;
        }
        else
        {
            cout<<"'Start'"<<endl;
            cena_pst = 400;

            cout<<"Otrzymujesz "<<cena_pst<<" zl"<<endl;
            bankier->ilosc_pieniedzy -= cena_pst;
            gracz[num_gracz]->ilosc_pieniedzy += cena_pst;
        }
    };
};
