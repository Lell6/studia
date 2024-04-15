import javax.swing.*;
import java.util.Random;

public class Generate_elements {
    public Miasta[] generate_pole_miasta(Miasta[] lista_miast){
        int i, skok = 0;
        int cena_pola = 1;

        String[] kraje = {"Grecja", "Wlochy", "Hiszpania", "Anglia", "Benelux", "Szwecja", "RFN", "Austria"};
        String[] miasta = { "Saloniki", "Ateny",				    //grecja
                            "Neapol", "Mediolan", "Rzym",		    //wlochy
                            "Barcelona", "Sewilla", "Madryt",	    //hiszpania
                            "Liverpool", "Glasgow", "Londyn",	    //anglia
                            "Rotterdam", "Bruksela", "Amsterdam",   //benelux
                            "Malmo", "Goteborg", "Sztokholm",	    //szwecja
                            "Frankfurt", "Kolonia", "Bonn",	        // rfn
                            "Innsbruck", "Wieden"};

        for(String nazwa_kraju : kraje){
            if(nazwa_kraju.equals("Grecja"))
            {
                for(i = 0; i < 2; i++){
                    String nazwa = nazwa_kraju + " - " + miasta[i];
                    lista_miast[i] = new Miasta(nazwa, 120, 0, nazwa_kraju, miasta[i], 100, 200);
                }
                cena_pola = 200;
            }
            else if(!nazwa_kraju.equals("Austria"))
            {
                for(i = 2; i < 4; i++)
                {
                    String nazwa = nazwa_kraju + " - " + miasta[i + skok];
                    switch (nazwa_kraju) {
                        case "Wlochy" ->
                                lista_miast[i + skok] = new Miasta(nazwa, cena_pola, 0, nazwa_kraju, miasta[i+skok], 100, 200);
                        case "Hiszpania", "Anglia" ->
                                lista_miast[i + skok] = new Miasta(nazwa, cena_pola, 0, nazwa_kraju, miasta[i+skok], 200, 400);
                        case "Benelux", "Szwecja" ->
                                lista_miast[i + skok] = new Miasta(nazwa, cena_pola, 0, nazwa_kraju, miasta[i+skok], 300, 600);
                        case "RFN" ->
                                lista_miast[i + skok] = new Miasta(nazwa, cena_pola, 0, nazwa_kraju, miasta[i+skok], 400, 800);
                    }
                }

                cena_pola+=40;

                String nazwa = nazwa_kraju + " - " + miasta[i + skok];
                switch (nazwa_kraju) {
                    case "Wlochy" ->
                            lista_miast[i + skok] = new Miasta(nazwa, cena_pola, 0, nazwa_kraju, miasta[i+skok], 100, 200);
                    case "Hiszpania", "Anglia" ->
                            lista_miast[i + skok] = new Miasta(nazwa, cena_pola, 0, nazwa_kraju, miasta[i+skok], 200, 400);
                    case "Benelux", "Szwecja" ->
                            lista_miast[i + skok] = new Miasta(nazwa, cena_pola, 0, nazwa_kraju, miasta[i+skok], 300, 600);
                    case "RFN" ->
                            lista_miast[i + skok] = new Miasta(nazwa, cena_pola, 0, nazwa_kraju, miasta[i+skok], 400, 800);
                }

                cena_pola+=40;
                skok+=3;
            }
            else
            {
                cena_pola = 1;
                for(i = 21; i > 19; i--){
                    String nazwa = nazwa_kraju + " - " + miasta[i];

                    lista_miast[i] = new Miasta(nazwa, 700+(100*cena_pola), 0, nazwa_kraju, miasta[i], 400, 800);
                    cena_pola--;
                }
            }
        }

        return lista_miast;
    }

    public Koleja[] generate_pole_koleja(Koleja[] lista_kolei){
        String[] rodzaj_kolei = {"Koleje polnoczne",
                "Koleje poludniowe",
                "Koleje zachodnie",
                "Koleje wschodnie"};

        for(int i = 0; i < lista_kolei.length; i++){
            lista_kolei[i] = new Koleja(rodzaj_kolei[i]);
        }

        return lista_kolei;
    }

    public Energia[] generate_pole_energia(Energia[] lista_energia){
        Random rand = new Random();
        int los;

        for(int i = 0; i < lista_energia.length; i++){
            if(i == 0){
                lista_energia[i] = new Energia("Start", -200);
            } else {
                los = rand.nextInt(2);
                lista_energia[i] = (los == 1) ? new Energia("Siec wodociagow", 300) : new Energia("Elektrownia", 300);
            }
        }

        return lista_energia;
    }

    public Gracz[] generate_graczy(Gracz[] lista_graczy, int liczba_graczy){

        return lista_graczy;
    }

    public void generate_pola(Gracz[] wszyscy_graczy, int liczba_graczy){
        Bankier bankier = new Bankier("Bankier", 10000*liczba_graczy);

        Miasta[] miasta = new Miasta[22];
        miasta = generate_pole_miasta(miasta);

        Koleja[] koleja = new Koleja[4];
        koleja = generate_pole_koleja(koleja);

        Energia[] energia = new Energia[14];
        energia = generate_pole_energia(energia);

        pole_do_kupienia[] plansza = new pole_do_kupienia[40];

        plansza[0] = energia[0];
        plansza[1] = miasta[0];
        plansza[2] = energia[1];
        plansza[3] = miasta[1];
        plansza[4] = energia[2];
        plansza[5] = koleja[1];
        plansza[6] = miasta[2];
        plansza[7] = energia[3];
        plansza[8] = miasta[3];
        plansza[9] = miasta[4];
        plansza[10] = energia[4];
        plansza[11] = miasta[5];
        plansza[12] = energia[5];
        plansza[13] = miasta[6];
        plansza[14] = miasta[7];
        plansza[15] = koleja[2];
        plansza[16] = miasta[8];
        plansza[17] = energia[6];
        plansza[18] = miasta[9];
        plansza[19] = miasta[10];
        plansza[20] = energia[7];
        plansza[21] = miasta[11];
        plansza[22] = energia[8];
        plansza[23] = miasta[12];
        plansza[24] = miasta[13];
        plansza[25] = koleja[0];
        plansza[26] = miasta[14];
        plansza[27] = miasta[15];
        plansza[28] = energia[9];
        plansza[29] = miasta[16];
        plansza[30] = energia[10];
        plansza[31] = miasta[17];
        plansza[32] = miasta[18];
        plansza[33] = energia[11];
        plansza[34] = miasta[19];
        plansza[35] = koleja[3];
        plansza[36] = energia[12];
        plansza[37] = miasta[20];
        plansza[38] = energia[13];
        plansza[39] = miasta[21];

        Przebieg_gry rozrywka = new Przebieg_gry();
        rozrywka.gra(koleja, plansza, wszyscy_graczy, bankier);
    }
}
