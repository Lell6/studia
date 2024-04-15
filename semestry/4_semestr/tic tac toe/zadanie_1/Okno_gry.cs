using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace zadanie_1
{
    public partial class Okno_gry : Form
    {
        string gracz = "-";
        string opcja;

        int[] wygrana = new int[8];
        string znak_wygranej = "-";

        int[] index_blisko_wygranej = {-1, -1};
        string znak_blisko_wygranej = "-";

        Button[][] lista_pol = new Button[3][];

        public Okno_gry(string opcja)
        {
            this.opcja = opcja;
            InitializeComponent();
            generate_lista_pol();
        }

        public void generate_lista_pol()
        {
            lista_pol[0] = new Button[3];
            lista_pol[1] = new Button[3];
            lista_pol[2] = new Button[3];

            lista_pol[0][0] = button1;
            lista_pol[0][1] = button2;
            lista_pol[0][2] = button3;
            lista_pol[1][0] = button4;
            lista_pol[1][1] = button5;
            lista_pol[1][2] = button6;
            lista_pol[2][0] = button7;
            lista_pol[2][1] = button8;
            lista_pol[2][2] = button9;
        }

        public void gra_reset()
        {
            for(int i = 0; i < 3; i++)
            {
                for(int j = 0; j < 3; j++)
                {
                    lista_pol[i][j].Enabled = true;
                    lista_pol[i][j].Text = "-";
                }
            }

            textBox1.Text = "";

            for(int i = 0; i < 8; i++)
            {
                wygrana[i] = 0;
            }

            znak_wygranej = "-";
            gracz = "-";

            index_blisko_wygranej[0] = -1;
            index_blisko_wygranej[1] = -1;

            znak_blisko_wygranej = "-";
        }

        public void dodaj_do_wygranej(string znak)
        {
            for(int i = 0; i < 3; i++)
            {
                if (lista_pol[i][0].Text == znak && lista_pol[i][1].Text == znak && lista_pol[i][2].Text == znak)
                {
                    wygrana[i] = 1;
                }
            }
            for (int i = 0; i < 3; i++)
            {
                if (lista_pol[0][i].Text == znak && lista_pol[1][i].Text == znak && lista_pol[2][i].Text == znak)
                {
                    wygrana[i+3] = 1;
                }
            }
            if (lista_pol[0][0].Text == znak && lista_pol[1][1].Text == znak && lista_pol[2][2].Text == znak)
            {
                wygrana[6] = 1;
            }
            if (lista_pol[2][0].Text == znak && lista_pol[1][1].Text == znak && lista_pol[0][2].Text == znak)
            {
                wygrana[7] = 1;
            }

            if (wygrana.Contains(1))
            {
                znak_wygranej = znak;
            }
        }

        public void zapisz_pole(int i, int j, string znak)
        {
            if (lista_pol[i][j].Enabled)
            {
                index_blisko_wygranej[0] = i;
                index_blisko_wygranej[1] = j;

                znak_blisko_wygranej = znak;
            }
            else
            {
                Random rand = new Random();
                index_blisko_wygranej[0] = rand.Next(3);
                index_blisko_wygranej[1] = rand.Next(3);

                while (!lista_pol[index_blisko_wygranej[0]][index_blisko_wygranej[1]].Enabled)
                {
                    index_blisko_wygranej[0] = rand.Next(3);
                    index_blisko_wygranej[1] = rand.Next(3);
                }
                znak_blisko_wygranej = znak;
            }
        }

        public void dodaj_do_blisko_wygranej(string znak)
        {
            for (int i = 0; i < 3; i++)
            {
                //rows
                if (lista_pol[i][0].Text == znak && lista_pol[i][1].Text == znak)
                {
                    zapisz_pole(i, 2, znak);
                }
                else if (lista_pol[i][1].Text == znak && lista_pol[i][2].Text == znak)
                {
                    zapisz_pole(i, 0, znak);
                }
                else if (lista_pol[i][0].Text == znak && lista_pol[i][2].Text == znak)
                {
                    zapisz_pole(i, 1, znak);
                }

                //colls
                if (lista_pol[0][i].Text == znak && lista_pol[1][i].Text == znak)
                {
                    zapisz_pole(2, i, znak);
                }
                else if (lista_pol[1][i].Text == znak && lista_pol[2][i].Text == znak)
                {
                    zapisz_pole(0, i, znak);
                }
                else if (lista_pol[0][i].Text == znak && lista_pol[2][i].Text == znak)
                {
                    zapisz_pole(1, i, znak);
                }
            }

            //diagonal \
            if (lista_pol[0][0].Text == znak && lista_pol[1][1].Text == znak)
            {
                zapisz_pole(2, 2, znak);
            }
            else if (lista_pol[1][1].Text == znak && lista_pol[2][2].Text == znak)
            {
                zapisz_pole(0, 0, znak);
            }
            else if (lista_pol[0][0].Text == znak && lista_pol[2][2].Text == znak)
            {
                zapisz_pole(1, 1, znak);
            }

            //diagonal /
            if (lista_pol[2][0].Text == znak && lista_pol[1][1].Text == znak)
            {
                zapisz_pole(0, 2, znak);
            }
            else if (lista_pol[1][1].Text == znak && lista_pol[0][2].Text == znak)
            {
                zapisz_pole(2, 0, znak);
            }
            else if (lista_pol[2][0].Text == znak && lista_pol[0][2].Text == znak)
            {
                zapisz_pole(1, 1, znak);
            }
        }

        public void sprawdz_pola()
        {
            bool kontynuuj = true;
            int count = 0;

            for(int i = 0; i < 3; i++)
            {
                for(int j = 0; j < 3; j++)
                {
                    if (!lista_pol[i][j].Enabled)
                    {
                        count++;
                    }
                }
            }

            kontynuuj = (count != 9);

            dodaj_do_wygranej(gracz);

            if(znak_wygranej == "X")
            {
                MessageBox.Show("Gracz X wygrał");
                gra_reset();
            }
            else if(znak_wygranej == "O")
            {
                MessageBox.Show("Gracz O wygrał");
                gra_reset();
            }
            else if (!kontynuuj)
            {
                MessageBox.Show("Gra skończona");
                gra_reset();
            }
        }

        public void przebieg_gry()
        {
            string dane_pol = "";

            for(int i = 0; i < 3; i++)
            {
                for(int j = 0; j < 3; j++)
                {
                    dane_pol += lista_pol[i][j].Text + "      ";
                }
                dane_pol += Environment.NewLine;
            }

            textBox1.Text += Environment.NewLine + dane_pol;
        }

        public Button wybierz_pole()
        {
            Button wybrane_pole = null;

            dodaj_do_blisko_wygranej("X");
            if(znak_blisko_wygranej == "X") // blokujemy gracza
            {
                wybrane_pole = lista_pol[index_blisko_wygranej[0]][index_blisko_wygranej[1]];
            }
            else
            {
                dodaj_do_blisko_wygranej("O");
                if (znak_blisko_wygranej == "O") // stawiamy pole
                {
                    wybrane_pole = lista_pol[index_blisko_wygranej[0]][index_blisko_wygranej[1]];
                }
                else
                {
                    Random rand = new Random();
                    int i = rand.Next(3);
                    int j = rand.Next(3);

                    while (!lista_pol[i][j].Enabled)
                    {
                        i = rand.Next(3);
                        j = rand.Next(3);
                    }

                    wybrane_pole = lista_pol[i][j];
                }
            }            

            return wybrane_pole;
        }

        private void pole_Click(object sender, EventArgs e)
        {
            if(opcja == "guy")
            {
                if (gracz == "-" || gracz == "O")
                {
                    gracz = "X";
                    label2.Text = gracz;

                    Button pole_do_zmiany = sender as Button;
                    pole_do_zmiany.Text = label2.Text;
                    pole_do_zmiany.Enabled = false;

                    przebieg_gry();
                    label2.Text = "O";

                    sprawdz_pola();
                }
                else if (gracz == "X")
                {
                    gracz = "O";
                    label2.Text = gracz;
                    Button pole_do_zmiany;

                    pole_do_zmiany = sender as Button;

                    pole_do_zmiany.Text = label2.Text;
                    pole_do_zmiany.Enabled = false;

                    przebieg_gry();
                    label2.Text = "X";

                    sprawdz_pola();
                }

            }
            else if(opcja == "komp")
            {
                gracz = "X";
                label2.Text = gracz;

                Button pole_do_zmiany = sender as Button;
                pole_do_zmiany.Text = label2.Text;
                pole_do_zmiany.Enabled = false;

                przebieg_gry();
                label2.Text = "O";
                sprawdz_pola();

                gracz = "O";

                label2.Text = gracz;

                Button pole_do_zmiany_2 = wybierz_pole();

                pole_do_zmiany_2.Text = label2.Text;
                pole_do_zmiany_2.Enabled = false;

                przebieg_gry();
                label2.Text = "X";
                sprawdz_pola();
            }
        }

        private void button11_Click(object sender, EventArgs e)
        {
            gra_reset();
        }
    }
}
