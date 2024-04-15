import javax.swing.*;

public class pole_ogolne
{
    public String nazwa;
    public int cena;
    public int pozycja_na_planszy;

    public pole_ogolne(String nazwa, int cena, int pozycja_pola)
    {
        this.nazwa = nazwa;
        this.cena = cena;
        this.pozycja_na_planszy = pozycja_pola;
    }

    public String pokaz_dane_pola()
    {
        return "Nazwa: " + this.nazwa + "\nCena: " + this.cena;
    }
}

abstract class pole_do_kupienia extends pole_ogolne
{
    public int wlasciciel_id;

    public pole_do_kupienia(String nazwa, int cena, int pozycja_pola)
    {
        super(nazwa, cena, pozycja_pola);
        wlasciciel_id = -1;
    }

    public void kupno_pola(Gracz kupujacy, Bankier sprzedawca, int cena)
    {
        this.wlasciciel_id = kupujacy.identyfikator_liczbowy;

        kupujacy.wyplata(cena);
        sprzedawca.wplata(cena);
    }

    public void oplata_za_postoj(Gracz gracz_stojacy, Gracz gracz_wlasciciel, int cena)
    {
        gracz_stojacy.wyplata(cena);
        gracz_wlasciciel.wplata(cena);
    }

    public void licytacja_pola(Gracz[] wszyscy_graczy, int identyfikator_niekupujacego, Bankier bankier, int cena){
        Okna_do_wyswietlania okno = new Okna_do_wyswietlania();

        for (Gracz gracz : wszyscy_graczy) {
            if (gracz.identyfikator_liczbowy != identyfikator_niekupujacego)
            {
                char option = okno.okno_licytacja(gracz, this);

                if(option == '1'){
                    kupno_pola(gracz, bankier, cena);
                }
            }
        }
    }

    public String wykonaj_info(Gracz gracz_w_rundzie){

        String info = "Jestes na polu " + this.nazwa + "\n\n";
        info += pokaz_dane_pola();

        if(this.wlasciciel_id != -1 && gracz_w_rundzie.identyfikator_liczbowy != this.wlasciciel_id)
        {
            info += "\n\nMusisz zaplacic za postoj";
            return info;
        }
        else if(this.wlasciciel_id == -1)
        {
            info += "\n\nChcesz go kupic?";
            return info;
        } else {
            info += "\n\nJestes wlaścicielem tego pola\n\n";
            return info;
        }
    }

    public void wykonaj(char option,
                        Gracz[] wszyscy_graczy,
                        Gracz gracz_w_rundzie,
                        Bankier bankier){

        if(option == '1'){
            kupno_pola(gracz_w_rundzie, bankier, cena);
        } else if(option == '2'){
            licytacja_pola(wszyscy_graczy, gracz_w_rundzie.identyfikator_liczbowy, bankier, (int)this.cena/2);
        } else if(option == '0'){
            Gracz gracz_wlasciciel = wszyscy_graczy[this.wlasciciel_id];
            oplata_za_postoj(gracz_w_rundzie, gracz_wlasciciel, (int)this.cena/2);
        }
    }

    @Override
    public String pokaz_dane_pola()
    {
        return super.pokaz_dane_pola() + "\nID Wlasciciela: " + this.wlasciciel_id;
    }
}