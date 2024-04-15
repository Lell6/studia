using namespace std;

class pole_nazwa_cena
{
  public:
    string nazwa;
    int cena = 300;
    int cena_domku;
    int cena_hotel;
    int pos_pola;
    char znak = 196;
    char graczy[5] = {'.','.','.','.','.'};
    char zabudowa[5] = {znak,znak,znak,znak,znak};

    void show_nazwa()
    {
        cout<<nazwa;
    };

    void show_cena()
    {
        cout<<cena;
    };

    virtual void wykonaj(pole_graczy* gracz[],pole_bankier* bankier,int num_gracz,int max_gracz,int pos_p)
    {
        cout<<"###"<<endl;
    };
};

class pole_miasta:public pole_nazwa_cena
{
  public:
    string kraj;
    string miasto;
	int liczba_domki = -1;
	int liczba_hoteli = 0;

	pole_miasta(string kr, string mst, int cn) //kraj, miasto, cena
    {
        if(kr == "Hiszpania" && mst == "Barcelona")
            nazwa = kr + " -" + mst;
        else
            nazwa = kr + " - " + mst;

        kraj = kr;
        miasto = mst;
        cena = cn;
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
                    kupno(gracz[i],bankier,0);
                    return;
                }
            }
        }
    };

    void kupno(pole_graczy* gracz,pole_bankier* bankier,int budowla)
    {
        int i;
        char znak_bok = 196;

        if(budowla == 0)
        {
            wlasciciel = gracz->gracz_znak; // pole

            zabudowa[0] = wlasciciel;
            for(i = 1; i <= liczba_domki; i++)
                zabudowa[i] = 30;

            gracz->zmiana_majatku(cena/2);
            bankier->ilosc_pieniedzy += cena/2;

            liczba_domki++;
        }
        else if(budowla == 1)
        {
            wlasciciel = gracz->gracz_znak; // domki

            zabudowa[0] = wlasciciel;
            for(i = 1; i <= liczba_domki; i++)
                zabudowa[i] = 30;

            gracz->zmiana_majatku(cena_domku);
            bankier->ilosc_pieniedzy += cena_domku;

            liczba_domki++;
        }
        else
        {
            wlasciciel = gracz->gracz_znak; // hotel

            zabudowa[0] = wlasciciel;
            zabudowa[1] = 31;

            for(i = 2; i <= liczba_domki; i++)
                zabudowa[i] = znak_bok;

            gracz->zmiana_majatku(cena_domku);
            bankier->ilosc_pieniedzy += cena_domku;

            liczba_domki = -1;
        }
    };

    void oplata(pole_graczy* gracz_kto, pole_graczy* gracz_komu)
    {
        gracz_kto->zmiana_majatku(cena+(cena_domku*0,5*liczba_domki));
        gracz_komu->zmiana_majatku((cena+(cena_domku*0,5*liczba_domki))*(-1));
    };

    void wykonaj(pole_graczy* gracz[],pole_bankier* bankier,int num_gracz,int max_gracz,int pos_p) override//zamieniamy wirtualna metode
    {
        char option;
        int war = max_gracz; // max_gracz jest jednoczesnie warunkiem do kredytu
        cout<<"Jestes na polu 'Miasto'"<<endl;

        if(wlasciciel == gracz[num_gracz]->gracz_znak)
        {
            if(war != 0)
            {
                if(liczba_domki == 0)
                    cout<<"Masz 0 domkow, ";
                else if(liczba_domki == 1)
                    cout<<"Masz 1 domek, ";
                else if(liczba_domki > 1 || liczba_domki < 5)
                {
                    cout<<"Masz "<<liczba_domki<<" domki, ";

                    if(liczba_domki != 4)
                        kupno(gracz[num_gracz],bankier,1);
                    else
                        kupno(gracz[num_gracz],bankier,2);

                    kupno(gracz[num_gracz],bankier,1);
                }
                else
                {
                    if(bankier->wlsc_krd[num_gracz] != gracz[num_gracz]->gracz_znak) // czy gracz nie posiada kredyt
                    {
                        cout<<"Chcesz otrzymac kredyt hipoteczny (t/n)?"<<endl;
                        cin>>option;

                        if(option == 't')
                        {
                            bankier->kredyt(gracz[num_gracz],num_gracz,pos_p);
                            wlasciciel = bankier->znak;

                            bankier->cena_kredyt[num_gracz] = cena*(liczba_domki+1);
                            gracz[num_gracz]->zmiana_majatku(bankier->cena_kredyt[num_gracz]*(-1));
                            bankier->ilosc_pieniedzy -= bankier->cena_kredyt[num_gracz];

                            bankier->ilosc_pieniedzy += cena_domku*liczba_domki;
                            gracz[num_gracz]->zmiana_majatku(cena_domku*liczba_domki);

                            liczba_domki = -1;
                            liczba_hoteli = 0;
                        }
                    }
                    else
                    {
                        cout<<"Chcesz splacic kredyt hipotetyczny (t/n)?"<<endl;
                        cin>>option;

                        if(option == 't')
                        {
                            wlasciciel = gracz[num_gracz]->gracz_znak;

                            bankier->cena_kredyt[num_gracz] = 0;;
                            gracz[num_gracz]->zmiana_majatku(bankier->cena_kredyt[num_gracz] + (bankier->cena_kredyt[num_gracz]*0,1));
                            bankier->ilosc_pieniedzy -= bankier->cena_kredyt[num_gracz] + (bankier->cena_kredyt[num_gracz]*0,1);

                            bankier->pos_krd[num_gracz] = 0;
                            bankier->wlsc_krd[num_gracz] = '.';

                            liczba_domki = -1;
                            liczba_hoteli = 0;
                        }
                    }
                }

            }
        }
        else
        {
            if(liczba_domki == -1)
            {
                cout<<"Na polu nie ma domkow, ";
                if(wlasciciel == 45)
                {
                    cout<<"nie ma rowniez wlasciciela"<<endl;
                    cout<<"Chcesz kupic pole (t/n)?"<<endl;
                    cin>>option;

                    if(option == 't')
                        kupno(gracz[num_gracz],bankier,0);
                    else
                        licytacja(gracz,bankier,num_gracz,max_gracz);
                }
            }
            else
            {
                cout<<"Musisz zaplacic wlascicielu za postoj"<<endl;
                int p = 0;

                while(gracz[p]->gracz_znak != wlasciciel)
                    p++;

                oplata(gracz[num_gracz],gracz[p]);
            }
        }
    };

    friend int sprawdzenie_wlascicieli(pole_graczy* gracz[], pole_miasta* miasto[],int num_gracz, pole_bankier* bankier,int war);

  private:
    char wlasciciel = 45;
};
