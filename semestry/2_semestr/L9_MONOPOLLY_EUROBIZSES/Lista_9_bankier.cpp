#define MAX_M 7000
using namespace std;

class pole_bankier
{
public:
    string nazwa = "Bankier";
    int ilosc_pieniedzy = 30000;
    bool status = true;
    int cena_kredyt[5] = {0,0,0,0,0};
    int pos_krd[5] = {0,0,0,0,0};
    char wlsc_krd[5] = {'.','.','.','.','.'};
    char znak = 219;

    pole_bankier(int majatek)
    {
        ilosc_pieniedzy -= majatek;
    };

    void sprawdzenie_majatku()
    {
        if(ilosc_pieniedzy <=0)
            status = false;
    }

    void kredyt(pole_graczy* gracz, int num_gracz, int pos_p)
    {
        cout<<"Oddajesz pole banku"<<endl;

        pos_krd[num_gracz] = pos_p;
        wlsc_krd[num_gracz] = gracz->gracz_znak;
    }
};


