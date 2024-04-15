public class Miasta extends pole_do_kupienia{
    public String kraj;
    public String miasto;
    public int cena_domku;
    public int cena_hotelu;
    private int liczba_domkow_pa_polu;
    private int liczba_hoteli_pa_polu;
    public char []zabudowa;

    public Miasta(String nazwa, int cena, int pozycja_pola, String nazwa_kraju, String nazwa_miasta, int cena_domku, int cena_hotelu){
        super(nazwa, cena, pozycja_pola);
        this.kraj = nazwa_kraju;
        this.miasto = nazwa_miasta;
        this.cena_domku = cena_domku;
        this.cena_hotelu = cena_hotelu;

        this.zabudowa = new char[]{'-','-','-','-','-'};
    }

    @Override
    public String pokaz_dane_pola(){
        StringBuilder napis = new StringBuilder(super.pokaz_dane_pola() + "\nCena jednego domku: " + cena_domku + "\nCena hotelu: " + cena_hotelu + "\nZabudowa:  | ");

        for(char elem : zabudowa){
            napis.append(elem).append(" | ");
        }

        return napis.toString();
    }

    public void kupno_zabudowy_na_polu(Gracz kupujacy, Bankier sprzedawca, int cena, char rodzaj_zabudowy){
        this.wlasciciel_id = kupujacy.identyfikator_liczbowy;
        int i = 0;

        if(rodzaj_zabudowy == 'D'){
            while(this.zabudowa[i] != '-'){ i+=1; }

            this.liczba_domkow_pa_polu += 1;
            kupujacy.liczba_domkow +=1 ;
            this.zabudowa[i] = rodzaj_zabudowy;

        } else if(rodzaj_zabudowy == 'H'){
            for(char elem : zabudowa){elem = '-';}

            this.liczba_domkow_pa_polu = 0;
            this.liczba_hoteli_pa_polu = 1;

            kupujacy.liczba_domkow = 0;
            kupujacy.liczba_hoteli = 1;

            this.zabudowa[0] = rodzaj_zabudowy;
        }

        kupujacy.wyplata(cena);
        sprzedawca.wplata(cena);
    }

    @Override
    public String wykonaj_info(Gracz gracz_w_rundzie){

        String info = "Jestes na polu: " + this.nazwa + "\n\n";
        info += pokaz_dane_pola();

        if(this.liczba_hoteli_pa_polu == 0 && this.wlasciciel_id == -1){
            info += "\n\nChcesz kupic miasto?";
            return info;
        } else if(this.wlasciciel_id == gracz_w_rundzie.identyfikator_liczbowy){
            if(this.liczba_domkow_pa_polu < 4){
                info += "\n\nChcesz kupic domek?";
            } else if(this.liczba_domkow_pa_polu == 4){
                info += "\n\nChcesz kupic hotel?";
            }
            return info;
        } else {
            int cena;
            info += "\n\nPlacisz za postoj";
            return info;
        }
    }

    @Override
    public void wykonaj(char option,
                        Gracz[] wszyscy_graczy,
                        Gracz gracz_w_rundzie,
                        Bankier bankier){

        if(option == '1'){
            char rodzaj_zabudowy;
            int cena_zabudowy;

            if(this.liczba_domkow_pa_polu == 0){
                kupno_pola(gracz_w_rundzie,bankier,this.cena);
            }
            else if(this.liczba_domkow_pa_polu < 4) {
                rodzaj_zabudowy = 'D';
                cena_zabudowy = this.cena;

                kupno_zabudowy_na_polu(gracz_w_rundzie,bankier,cena_zabudowy,rodzaj_zabudowy);
            } else if(this.liczba_domkow_pa_polu == 4){
                rodzaj_zabudowy = 'H';
                cena_zabudowy = this.cena_hotelu;

                kupno_zabudowy_na_polu(gracz_w_rundzie,bankier,cena_zabudowy,rodzaj_zabudowy);
            }
        } else if(option == '2'){
            if(this.wlasciciel_id != gracz_w_rundzie.identyfikator_liczbowy){
                licytacja_pola(wszyscy_graczy,gracz_w_rundzie.identyfikator_liczbowy,bankier,(int)cena/2);
            }
        } else if(option == '0'){
            Gracz gracz_wlasciciel = wszyscy_graczy[this.wlasciciel_id];

            if(this.liczba_domkow_pa_polu == 0){
                oplata_za_postoj(gracz_w_rundzie, gracz_wlasciciel, (int)cena/2);
            }
            else if(this.liczba_domkow_pa_polu > 0){
                cena = (int)(this.cena_domku * 0.5)*liczba_domkow_pa_polu;
                oplata_za_postoj(gracz_w_rundzie, gracz_wlasciciel, cena);
            } else if(this.liczba_hoteli_pa_polu == 1){
                cena = (int)(this.cena_hotelu * 0.3);
                oplata_za_postoj(gracz_w_rundzie, gracz_wlasciciel, cena);
            }
        }
    }
}
