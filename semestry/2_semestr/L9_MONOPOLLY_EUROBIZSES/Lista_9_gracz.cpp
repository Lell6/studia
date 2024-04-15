#define MAX_M 7000
using namespace std;

class pole_graczy // graczy
{
  public:
	string nazwa;
    char gracz_znak;
	int pos = 0; //pozycja
	bool status = true; // w grze/bankrot

	pole_graczy(int i)
    {
        cout<<"Podaj imie "<<i+1<<" gracza: ";
        cin>>nazwa;
        ilosc_pieniedzy = 3000;
        gracz_znak = 35+i;
    };

    void rzut()
    {
        int oczki_1;
        int oczki_2;
        int suma_oczek = 0;
        int sproba = 1;

        do
        {
            oczki_1 = rand()%6 + 1;
            oczki_2 = rand()%6 + 1;

            if(oczki_1 == oczki_2)
            {
                cout<<"Liczba oczek jest jednakowa, ";

                if(sproba == 2)
                {
                    cout<<"gracz traci kolej"<<endl<<endl;
                    return;
                }
                else
                {
                    cout<<"rzucamy ponownie"<<endl<<endl;
                    suma_oczek = oczki_1 + oczki_2;
                    sproba++;
                }
            }
            else
            {
                cout<<"Rzut wykonano"<<endl;
                cout<<"Oczka: "<<oczki_1<<", "<<oczki_2<<endl;
                cout<<"Suma oczek wynosi: "<<oczki_1+oczki_2<<endl<<endl;

                pos += oczki_1 + oczki_2;
                sproba = 1;

                if(pos-39 > 0)
                    pos = pos-39;
            }
        }while(oczki_1 == oczki_2);
    }

    void zmiana_majatku(int cena)
    {
        ilosc_pieniedzy -= cena;
    };

    int sprawdzenie_majatku(int majatek_przekroczony)
    {
        if(ilosc_pieniedzy <= 0)
        {
            status = false;
            return 0;
        }
        else if(ilosc_pieniedzy >= MAX_M)
            majatek_przekroczony++;

        return majatek_przekroczony;
    };

    friend class pole_miasta;
    friend class pole_szansa;
    friend class pole_energia;
    friend class pole_koleje;
    friend class pole_parking;
    friend void gracz_info(pole_graczy* gracz[],int licz_graczy,int majatek);

  private:
    int ilosc_pieniedzy;
};


