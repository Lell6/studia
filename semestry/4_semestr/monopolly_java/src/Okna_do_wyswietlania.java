import javax.swing.*;

public class Okna_do_wyswietlania {

    public void okno_rzut(Gracz gracz_w_rundzie){
        String kolej_gracza = "(ID - " + gracz_w_rundzie.identyfikator_liczbowy + ") Kolej gracza: " + gracz_w_rundzie.imie;
        JOptionPane.showMessageDialog(null,
                gracz_w_rundzie.wykonanie_rzutu(),
                kolej_gracza,
                JOptionPane.INFORMATION_MESSAGE);
    }

    public char okno_pole(Gracz gracz_w_rundzie, pole_do_kupienia pole_obecne){
        int id_wlasciciela = pole_obecne.wlasciciel_id;
        String gra_info;

        String kolej_gracza = "(ID - " + gracz_w_rundzie.identyfikator_liczbowy + ") Kolej gracza: " + gracz_w_rundzie.imie;

        if(id_wlasciciela == -1){
            gra_info = JOptionPane.showInputDialog(null,
                    "Masz " + gracz_w_rundzie.daj_majatek()  + "zł. \n\n" +
                            pole_obecne.wykonaj_info(gracz_w_rundzie) +
                            "\n1 - tak\n" +
                            "2 - nie\n",
                    kolej_gracza,
                    JOptionPane.INFORMATION_MESSAGE);
        } else {
            JOptionPane.showMessageDialog(null,
                    "Masz " + gracz_w_rundzie.daj_majatek()  + "zł. \n\n" + pole_obecne.wykonaj_info(gracz_w_rundzie),
                    kolej_gracza,
                    JOptionPane.INFORMATION_MESSAGE);
            return '0';
        }

        return gra_info.charAt(0);
    }

    public char okno_pole_miasto(Gracz gracz_w_rundzie, pole_do_kupienia pole_obecne){
        int id_wlasciciela = pole_obecne.wlasciciel_id;
        String gra_info;

        String kolej_gracza = "(ID - " + gracz_w_rundzie.identyfikator_liczbowy + ") Kolej gracza: " + gracz_w_rundzie.imie;

        if(id_wlasciciela == -1 || id_wlasciciela == gracz_w_rundzie.identyfikator_liczbowy){
            gra_info = JOptionPane.showInputDialog(null,
                    "Masz " + gracz_w_rundzie.daj_majatek()  + "zł. \n\n" +
                            pole_obecne.wykonaj_info(gracz_w_rundzie) +
                            "\n1 - tak\n" +
                            "2 - nie\n",
                    kolej_gracza,
                    JOptionPane.INFORMATION_MESSAGE);
        } else {
            JOptionPane.showMessageDialog(null,
                    "Masz " + gracz_w_rundzie.daj_majatek()  + "zł. \n\n" + pole_obecne.wykonaj_info(gracz_w_rundzie),
                    kolej_gracza,
                    JOptionPane.INFORMATION_MESSAGE);
            return '0';
        }

        return gra_info.charAt(0);
    }

    public char okno_licytacja(Gracz gracz_w_rundzie, pole_do_kupienia pole_obecne){
        String kolej_gracza = "(ID - " + gracz_w_rundzie.identyfikator_liczbowy + ") Kolej gracza: " + gracz_w_rundzie.imie;
        String gra_info = JOptionPane.showInputDialog(null,
                "Masz " + gracz_w_rundzie.daj_majatek()  + "zł. \n\n" +
                        "Licytacja pola " + pole_obecne.nazwa + "\nCena: "+ (int)pole_obecne.cena/2 +"\n\nChcesz kupic pole?" +
                        "\n1 - tak\n" +
                        "2 - nie\n",
                kolej_gracza,
                JOptionPane.INFORMATION_MESSAGE);

        return gra_info.charAt(0);
    }

    public char okno_kredyt(String option, Gracz gracz_w_rundzie){
        String kolej_gracza = "Kolej gracza: " + gracz_w_rundzie.identyfikator_liczbowy + " - " + gracz_w_rundzie.imie;
        String gra_info;

        if(option.equals("dac_kredyt")){
            gra_info = JOptionPane.showInputDialog(null,
                    "Potrzebujesz pozyczki?" +
                            "\n1 - tak\n" +
                            "2 - nie\n",
                    kolej_gracza,
                    JOptionPane.INFORMATION_MESSAGE);

        } else if(option .equals("zabrac_kredyt")){
            gra_info = JOptionPane.showInputDialog(null,
                    "Splacasz pozyczke?" +
                            "\n1 - tak\n" +
                            "2 - nie\n",
                    kolej_gracza,
                    JOptionPane.INFORMATION_MESSAGE);
        } else {
            gra_info = "0";
        }

        return gra_info.charAt(0);
    }

    public void okno_wygrana(Gracz gracz_w_rundzie){
        String kolej_gracza = "(ID - " + gracz_w_rundzie.identyfikator_liczbowy + ") Kolej gracza: " + gracz_w_rundzie.imie;
        JOptionPane.showMessageDialog(null,
                "Wygraleś ;)",
                kolej_gracza,
                JOptionPane.INFORMATION_MESSAGE);
    }

    public void okno_przegrana(Gracz gracz_w_rundzie){
        String kolej_gracza = "(ID - " + gracz_w_rundzie.identyfikator_liczbowy + ") Kolej gracza: " + gracz_w_rundzie.imie;
        JOptionPane.showMessageDialog(null,
                "Przegrałeś :(",
                kolej_gracza,
                JOptionPane.INFORMATION_MESSAGE);
    }

    public void okno_koniec_gry(Gracz[] wszyscy_graczy){
        String tytul = "Koniec gry";
        StringBuilder majatek_graczy = new StringBuilder();
        int i = 0;

        for(Gracz gracz : wszyscy_graczy){
            majatek_graczy.append("Gracz ").append(i).append(": ").append(gracz.daj_majatek()).append("\n");
            i++;
        }

        JOptionPane.showMessageDialog(null,
                majatek_graczy.toString(),
                tytul,
                JOptionPane.INFORMATION_MESSAGE);
    }
}
