
public class Bankier extends uczestnik_gry {
    private int[] cena_kredytow_graczy;

    public Bankier(String imie, int ilosc_pieniedzy) {
        super(imie, ilosc_pieniedzy);

        cena_kredytow_graczy = new int[]{0, 0, 0, 0, 0};
    }

    public void sprawdzenie_majatku(){
        this.uczestniczy_w_grze = this.daj_majatek() > 10000;
    }

    public void obsluga_kredytu(Gracz gracz_w_rundzie) {
        int id_gracza_liczbowy = gracz_w_rundzie.identyfikator_liczbowy;

        int liczba_domkow_gracza = gracz_w_rundzie.liczba_domkow;
        int liczba_hoteli_gracza = gracz_w_rundzie.liczba_hoteli;

        Okna_do_wyswietlania okno = new Okna_do_wyswietlania();

        if (cena_kredytow_graczy[gracz_w_rundzie.identyfikator_liczbowy] == 0 && (liczba_domkow_gracza > 0 || liczba_hoteli_gracza > 0)) {
            char option = okno.okno_kredyt("dac_kredyt", gracz_w_rundzie);

            if (option == '1') {
                int rozmiar_kredytu = gracz_w_rundzie.liczba_domkow * 200;

                this.cena_kredytow_graczy[id_gracza_liczbowy] = rozmiar_kredytu;

                gracz_w_rundzie.wplata(rozmiar_kredytu);
                this.wyplata(rozmiar_kredytu);
            }
        } else if (cena_kredytow_graczy[gracz_w_rundzie.identyfikator_liczbowy] != 0) {
            char option = okno.okno_kredyt("zabrac_kredyt", gracz_w_rundzie);

            if (option == '1') {
                int kredyt = cena_kredytow_graczy[id_gracza_liczbowy];
                int kredyt_do_oddania = (int) (kredyt + kredyt * 0.1);

                this.cena_kredytow_graczy[id_gracza_liczbowy] = 0;

                gracz_w_rundzie.wyplata(kredyt_do_oddania);
                this.wplata(kredyt_do_oddania);
            }
        }
    }
}