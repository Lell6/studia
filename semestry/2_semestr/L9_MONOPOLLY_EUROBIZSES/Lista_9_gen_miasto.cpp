#include <iostream>
#include <vector>
#include "Lista_9_miasta.cpp"

using namespace std;

pole_miasta* gen_miasto(pole_miasta* wsk[])
{
    int i, skok = 0;
    int cena = 1;
    vector<string> kraje = {"Grecja", "Wlochy", "Hiszpania", "Anglia", "Benelux", "Szwecja", "RFN", "Austria"};

	vector<string> miasta = {"Saloniki", "Ateny",				   //grecja
							 "Neapol", "Mediolan", "Rzym",		   //wloczy
							 "Barcelona", "Sewilla", "Madryt",	   //hiszpania
							 "Liverpool", "Glasgow", "Londyn",	   //anglia
							 "Rotterdam", "Bruksela", "Amsterdam", //benelux
							 "Malmo", "Goteborg", "Sztokholm",	   //szwecja
							 "Frankfurt", "Kolonia", "Bonn",	   // rfn
							 "Innsbruck", "Wieden"};			   //austria


    for(auto element : kraje) // kraje/miasta/cena
    {
        if(element == "Grecja")
        {
            for(i = 0; i < 2; i++)
            {
                wsk[i] = new pole_miasta(element,miasta[i],120);
                wsk[i]->cena_domku = 100;
                wsk[i]->cena_hotel = 200;
            }
            cena = 200;
        }
        else if(element != "Austria")
        {
            for(i = 2; i < 4; i++)
            {
                wsk[i+skok] = new pole_miasta(element,miasta[i+skok],cena);
                if(element == "Wlochy")
                {
                    wsk[i+skok]->cena_domku = 100;
                    wsk[i+skok]->cena_hotel = 200;
                }
                else if(element == "Hiszpania" || element == "Anglia")
                {
                    wsk[i+skok]->cena_domku = 200;
                    wsk[i+skok]->cena_hotel = 400;
                }
                else if(element == "Benelux" || element == "Szwecja")
                {
                    wsk[i+skok]->cena_domku = 300;
                    wsk[i+skok]->cena_hotel = 600;
                }
                else if(element == "RFN")
                {
                    wsk[i+skok]->cena_domku = 400;
                    wsk[i+skok]->cena_hotel = 800;
                }
            }

            cena+=40;

            wsk[i+skok] = new pole_miasta(element,miasta[i+skok],cena);

            if(element == "Wlochy")
            {
                wsk[i+skok]->cena_domku = 100;
                wsk[i+skok]->cena_hotel = 200;
            }
            else if(element == "Hiszpania" || element == "Anglia")
            {
                wsk[i+skok]->cena_domku = 200;
                wsk[i+skok]->cena_hotel = 400;
            }
            else if(element == "Benelux" || element == "Szwecja")
            {
                wsk[i+skok]->cena_domku = 300;
                wsk[i+skok]->cena_hotel = 600;
            }
            else if(element == "RFN")
            {
                wsk[i+skok]->cena_domku = 400;
                wsk[i+skok]->cena_hotel = 800;
            }

            cena+=40;

            skok+=3;
        }
        else
        {
            cena = 1;
            for(i = 21; i > 19; i--)
            {
                wsk[i] = new pole_miasta(element,miasta[i],700+(100*cena));
                wsk[i]->cena_domku = 400;
                wsk[i]->cena_hotel = 800;
                cena--;
            }
        }
    }

    return *wsk;
}
