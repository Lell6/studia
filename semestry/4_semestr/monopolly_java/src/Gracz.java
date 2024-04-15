import java.util.Random;

public class Gracz extends uczestnik_gry {
    public int pozycja_na_planszy;
    public int liczba_domkow;
    public int liczba_hoteli;
    public int identyfikator_liczbowy;

    public Gracz(String imie, int ilosc_pieniedzy, int numer) {
        super(imie, ilosc_pieniedzy);

        this.pozycja_na_planszy = 0;
        this.liczba_domkow = 0;
        this.liczba_hoteli = 0;
        this.identyfikator_liczbowy = numer;
    }

    public boolean sprawdzenie_majatku(){
        this.uczestniczy_w_grze = this.daj_majatek() > 0 && this.daj_majatek() < 7000;
        return this.uczestniczy_w_grze;
    }

    public String wykonanie_rzutu() {
        Random rand = new Random();

        int oczki_rzut_1 = 0;
        int oczki_rzut_2 = 0;
        int sproba = 1;

        while (oczki_rzut_1 == oczki_rzut_2) {
            oczki_rzut_1 = rand.nextInt(6);
            oczki_rzut_2 = rand.nextInt(6);

            if (oczki_rzut_1 == oczki_rzut_2) {
                String info = "Liczba oczek jest jednakowa, ";

                if (sproba == 2) {
                    info += "gracz traci kolej\n";
                    return info;
                } else {
                    info += "rzucamy ponownie\n";
                    sproba += 1;
                }
            } else {
                int suma_oczek = oczki_rzut_1 + oczki_rzut_2;
                String info = "Rzut wykonano\n";

                sproba = 1;
                this.pozycja_na_planszy += suma_oczek;

                if (this.pozycja_na_planszy - 39 > 0) {
                    this.pozycja_na_planszy -= 39;
                }

                info = info + "Oczki: " + oczki_rzut_1 + " + " + oczki_rzut_2;
                info += "\nSuma oczek: " + suma_oczek;
                return info;
            }
        }

        return "";
    }

    public void usunac_z_gry(pole_do_kupienia[] plansza) {
        for (pole_do_kupienia pole : plansza) {
            if (pole.wlasciciel_id == this.identyfikator_liczbowy) {
                pole.wlasciciel_id = -1;
            }
        }
    }

    public String wypisz_uczestnika_dodatki(){
        return "Znak gracza: " + this.identyfikator_liczbowy +
                "\n" + "Liczba kupionych domkow: " + this.liczba_domkow +
                "\n" + "Liczba kupionych hoteli: " + this.liczba_hoteli +
                "\n" + "Pozycja na planszy: " + this.pozycja_na_planszy;
    }
}