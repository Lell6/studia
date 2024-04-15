import javax.swing.*;

public class Main {
    public static void main(String[] args) {
        JFrame Okno_glowne = new JFrame();
        Okno_glowne.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);

        JButton start_gry = new JButton("Start");
        JButton wyjscie_z_gry = new JButton("Wyjsc z gry");

        JLabel info_liczba_graczy = new JLabel("Liczba graczy");
        final JTextField input_liczba_graczy = new JTextField();
        JButton zacznij_gre = new JButton("Zacznij grę");

        info_liczba_graczy.setBounds(25,10,100,10);
        input_liczba_graczy.setBounds(25,25, 100,30);

        start_gry.setBounds(25,25,100,30);
        wyjscie_z_gry.setBounds(25,60,100,30);
        zacznij_gre.setBounds(25,60,100,30);

        info_liczba_graczy.setVisible(false);
        input_liczba_graczy.setVisible(false);
        zacznij_gre.setVisible(false);

        wyjscie_z_gry.addActionListener(e -> System.exit(0));

        start_gry.addActionListener(e -> {
            start_gry.setVisible(false);
            start_gry.setEnabled(false);
            info_liczba_graczy.setVisible(true);
            input_liczba_graczy.setVisible(true);
            zacznij_gre.setVisible(true);

            wyjscie_z_gry.setBounds(25,120,100,30);
        });

        zacznij_gre.addActionListener(e -> {
            String str_liczba_graczy = input_liczba_graczy.getText();
            int liczba_graczy = Integer.parseInt(str_liczba_graczy);

            if(liczba_graczy < 6 && liczba_graczy > 1){
                Okno_glowne.dispose();

                Gracz[] graczy = new Gracz[liczba_graczy];

                Okno_dodanie_gracza tworzenie_graczy = new Okno_dodanie_gracza();
                tworzenie_graczy.dodanie_gracza(graczy, liczba_graczy);
            } else {
                JOptionPane.showMessageDialog(null, "Za malo / za duzo graczy");
            }
        });

        Okno_glowne.add(info_liczba_graczy);
        Okno_glowne.add(input_liczba_graczy);
        Okno_glowne.add(start_gry);
        Okno_glowne.add(wyjscie_z_gry);
        Okno_glowne.add(zacznij_gre);

        Okno_glowne.setSize(200,200);
        Okno_glowne.setLayout(null);
        Okno_glowne.setVisible(true);
    }
}