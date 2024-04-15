import java.util.Random;
public class uczestnik_gry {
    public String imie;
    private int ilosc_pieniedzy;
    public boolean uczestniczy_w_grze = true;

    public uczestnik_gry(String imie, int ilosc_pieniedzy){
        this.imie = imie;
        this.ilosc_pieniedzy = ilosc_pieniedzy;
    }
    public void wyplata(int cena){
        this.ilosc_pieniedzy -= cena;
    }
    public void wplata(int cena){
        this.ilosc_pieniedzy += cena;
    }
    public String wypisz_uczestnika(){
        String status = (uczestniczy_w_grze) ? "tak" : "nie";
        return "Imie: " + this.imie + "\n" + "Majatek: " + this.ilosc_pieniedzy + "\n" + "Uczestnik: " + status;
    }

    public int daj_majatek(){
        return ilosc_pieniedzy;
    }
}