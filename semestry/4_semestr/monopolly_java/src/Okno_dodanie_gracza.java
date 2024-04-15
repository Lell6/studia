import javax.swing.*;

public class Okno_dodanie_gracza {
    public void dodanie_gracza(Gracz[] lista_graczy, int liczba_graczy){
        JFrame okno_dodanie = new JFrame();
        okno_dodanie.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);

        JLabel[] info_imie_gracza = new JLabel[liczba_graczy];
        JTextField[] input_imie_gracza = new JTextField[liczba_graczy];

        JButton cofnij = new JButton("Cofnij zmiany");
        JButton zatwierdz = new JButton("Zatwierdz");

        for(int i = 1; i <= liczba_graczy; i++){
            info_imie_gracza[i-1] = new JLabel("Imie gracza" + i);
            input_imie_gracza[i-1] = new JTextField();

            info_imie_gracza[i-1].setBounds(50,10+(i*50),250,20);
            input_imie_gracza[i-1].setBounds(50,30+(i*50),250,20);
        }

        zatwierdz.setBounds(50,310,100,30);
        cofnij.setBounds(160,310,140,30);

        zatwierdz.addActionListener( e -> {
            for(int i = 0; i < liczba_graczy; i++){
                String imie = input_imie_gracza[i].getText();

                lista_graczy[i] = new Gracz(imie, 3000, i);
            }
            okno_dodanie.dispose();

            Generate_elements generate = new Generate_elements();
            generate.generate_pola(lista_graczy, liczba_graczy);
        });

        for(int i = 0; i < liczba_graczy; i++){
            okno_dodanie.add(info_imie_gracza[i]);
            okno_dodanie.add(input_imie_gracza[i]);
        }

        okno_dodanie.add(zatwierdz);
        okno_dodanie.add(cofnij);

        okno_dodanie.setSize(400,400);
        okno_dodanie.setLayout(null);
        okno_dodanie.setVisible(true);
    }
}
