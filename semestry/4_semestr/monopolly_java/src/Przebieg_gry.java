import javax.swing.*;
import java.awt.*;
import java.awt.event.*;

public class Przebieg_gry {
    int pozycja_po_rzucie = 0;
    int pozycja_przed_rzutem = 0;
    boolean[] usuniete_graczy = new boolean[]{false,false,false,false,false};
    int liczba_usunietych_graczy = 0;

    public void gra(Koleja[] koleja, pole_do_kupienia[] plansza,
                    Gracz[] wszyscy_graczy,
                    Bankier bankier) {

        Okna_do_wyswietlania okno = new Okna_do_wyswietlania();

        while(bankier.uczestniczy_w_grze && liczba_usunietych_graczy < wszyscy_graczy.length-1){
            for (Gracz gracz_w_rundzie : wszyscy_graczy) {
                usuniete_graczy[gracz_w_rundzie.identyfikator_liczbowy] = !gracz_w_rundzie.sprawdzenie_majatku();

                if(usuniete_graczy[gracz_w_rundzie.identyfikator_liczbowy]){
                    if(gracz_w_rundzie.daj_majatek() > 7000){
                        okno.okno_wygrana(gracz_w_rundzie);
                    } else if(gracz_w_rundzie.daj_majatek() <= 0){
                        okno.okno_przegrana(gracz_w_rundzie);
                    }

                    liczba_usunietych_graczy += 1;
                    System.out.println(usuniete_graczy[gracz_w_rundzie.identyfikator_liczbowy]);
                    continue;
                }
                bankier.sprawdzenie_majatku();

                if(!bankier.uczestniczy_w_grze){
                    okno.okno_koniec_gry(wszyscy_graczy);
                    return;
                }

                pozycja_przed_rzutem = gracz_w_rundzie.pozycja_na_planszy;
                okno.okno_rzut(gracz_w_rundzie);
                pozycja_po_rzucie = gracz_w_rundzie.pozycja_na_planszy;

                if(pozycja_przed_rzutem != pozycja_po_rzucie){
                    pole_do_kupienia pole_obecne = plansza[gracz_w_rundzie.pozycja_na_planszy];

                    char option;
                    if(!pole_obecne.nazwa.contains("-")){
                        option = okno.okno_pole(gracz_w_rundzie, pole_obecne);
                    } else {
                        option = okno.okno_pole_miasto(gracz_w_rundzie, pole_obecne);
                    }

                    sprawdzenie_ceny_kolei(koleja, gracz_w_rundzie);
                    pole_obecne.wykonaj(option, wszyscy_graczy, gracz_w_rundzie, bankier);
                    bankier.obsluga_kredytu(gracz_w_rundzie);
                }
            }
            liczba_usunietych_graczy = 0;
        }
    }

    public void sprawdzenie_ceny_kolei(Koleja[] koleja, Gracz gracz_w_rundzie){
        int cena_kolei = 25;
        int i = 1;

        for(Koleja kolej : koleja){
            if(kolej.wlasciciel_id == gracz_w_rundzie.identyfikator_liczbowy){
                i++;
            }
        }

        for(int j = 0; j < koleja.length; j++){
            koleja[j].cena = cena_kolei * i;
        }
    }
}
