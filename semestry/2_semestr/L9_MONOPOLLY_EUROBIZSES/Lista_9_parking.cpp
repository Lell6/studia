using namespace std;

class pole_parking:public pole_nazwa_cena
{
  public:
    pole_parking(int i)
    {
        int los = rand()%2;

        if(los == 0)
        {
            nazwa = "Parking strezowy";
            cena = -400;
        }

        else
        {
            nazwa = "Darmowy parking";
            cena = 0;
        }
    };

    void wykonaj(pole_graczy* gracz[],pole_bankier* bankier,int num_gracz,int max_gracz,int pos_p) override //zamieniamy wirtualna metode
    {
        char option;

        cout<<"Jestes na polu 'Parking'"<<endl;

        if(nazwa == "Parking strezowy")
        {
            cout<<"Oddajesz 400"<<" zl"<<endl;
            bankier->ilosc_pieniedzy += 400;
            gracz[num_gracz]->ilosc_pieniedzy -= 400;
        }
        else
            cout<<"Nic sie nie stalo..."<<endl;
    };
};



