using namespace std;

int sprawdzenie_wlascicieli(pole_graczy* gracz[], pole_miasta* miasto[],int num_gracz, pole_bankier* bankier,int war)
{
    int i,j;
    char option;
    int wlasnosc[8] = {0,0,0,0,0,0,0,0};

    for(i = 0; i < 2; i++) //grecja
        if(miasto[i]->wlasciciel == gracz[i]->gracz_znak)
            wlasnosc[0]++;

    for(i = 20; i < 22; i++) //austria
        if(miasto[i]->wlasciciel == gracz[i]->gracz_znak)
            wlasnosc[8]++;

    for(i = 1; i <= 6; i++) //pozostale
        for(j = 0; j < 3; j++)
            if(miasto[(i*3-1)+j]->wlasciciel == gracz[i]->gracz_znak)
                wlasnosc[i]++;

    if(wlasnosc[0] == 2 || wlasnosc[7] == 2)
    {
        cout<<"Kupiles wszystkie miasta w kraju "<<miasto[i]->kraj<<endl;

        cout<<"Chcesz kupic domek/hotel (t/n)?"<<endl;
        cin>>option;

        if(option == 't')
        {
            cout<<"Wybierz miasto, gdzie kupisz domek lub hotel (";

            if(wlasnosc[0] == 2)
                cout<<"0, 1): ";
            else if(wlasnosc[7] == 2)
                cout<<"20, 21): ";

            cin>>i;
            miasto[i]->wykonaj(gracz,bankier,num_gracz,war,0);
        }
        else
            return 8;
    }
    else
    {
        for(j = 1; j < 7; j++)
            if(wlasnosc[j] == 3)
                cout<<"Kupiles wszystkie miasta w kraju "<<miasto[i]->kraj;
    }

    return 8;
}

int sprawdzenie_kolej(pole_graczy* gracz[], pole_koleje* kolej[])
{
    int i, j = 0;
    int cena_calosc = 25;
    int p = 0;

    for(i = 0; i < 4; i++)
    {
        while(gracz[p]->gracz_znak != kolej[i]->wlasciciel)
            p++;
    }

    for(i = 0; j < 3; j++)
        if(gracz[p]->gracz_znak == kolej[i]->wlasciciel)
            j++;

    if(j != 0)
    {
        for(i = 1; i < j; i++)
            cena_calosc *= 2;
    }

    return cena_calosc;
}
