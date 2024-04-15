using System;
using System.Windows.Forms;

namespace zadanie_2
{
    public partial class Form1 : Form
    {
        private Button button_pokaz_wszystkie;
        private Button button_zmien_dane;
        private Button button_punkt_show;
        private Button button_trojkat_show;
        private Button button_kolo_show;
        private Button button_wielobok_show;
        private Button button_wroc;

        public Form1(Punkt punkt, Trojkat trojkat, Kolo kolo, Wielobok wielobok)
        {
            this.Text = "Objekty płaskie";
            this.Size = new System.Drawing.Size(166, 250);

            this.StartPosition = FormStartPosition.CenterScreen;

            button_pokaz_wszystkie = new Button();
            button_zmien_dane = new Button();

            button_pokaz_wszystkie.Text = "Pokaż objekty";
            button_zmien_dane.Text = "Zmien wartości";

            button_pokaz_wszystkie.Width = 150;
            button_pokaz_wszystkie.Height = 30;

            button_zmien_dane.Width = 150;
            button_zmien_dane.Height = 30;

            button_pokaz_wszystkie.Location = new System.Drawing.Point(0, 0);
            button_zmien_dane.Location = new System.Drawing.Point(0, 40);

            button_pokaz_wszystkie.Click += (sender, e) => Button_All_Objects(punkt, trojkat, kolo, wielobok, "pokaz");
            button_zmien_dane.Click += (sender, e) => Button_All_Objects(punkt, trojkat, kolo, wielobok, "zmien");

            this.Controls.Add(button_zmien_dane);
            this.Controls.Add(button_pokaz_wszystkie);
        }

        public void Button_All_Objects(Punkt punkt, Trojkat trojkat, Kolo kolo, Wielobok wielobok, string opcja)
        {
            button_pokaz_wszystkie.Visible = false;
            button_zmien_dane.Visible = false;

            button_punkt_show = new Button();
            button_trojkat_show = new Button();
            button_kolo_show = new Button();
            button_wielobok_show = new Button();
            button_wroc = new Button();

            button_punkt_show.Location = new System.Drawing.Point(0,0);
            button_trojkat_show.Location = new System.Drawing.Point(0,40);
            button_kolo_show.Location = new System.Drawing.Point(0,80);
            button_wielobok_show.Location = new System.Drawing.Point(0,120);
            button_wroc.Location = new System.Drawing.Point(0, 170);

            button_punkt_show.Width = 150;
            button_punkt_show.Height = 30;

            button_trojkat_show.Width = 150;
            button_trojkat_show.Height = 30;

            button_kolo_show.Width = 150;
            button_kolo_show.Height = 30;

            button_wielobok_show.Width = 150;
            button_wielobok_show.Height = 30;

            button_wroc.Width = 150;
            button_wroc.Height = 30;

            button_punkt_show.Text = "Punkt";
            button_trojkat_show.Text = "Trojkat";
            button_kolo_show.Text = "Koło";
            button_wielobok_show.Text = "Wielobok";
            button_wroc.Text = "Wroc do menu";

            button_punkt_show.Click += (sender, e) => pokaz_punkt(punkt, opcja);
            button_trojkat_show.Click += (sender, e) => pokaz_trojkat(trojkat, opcja);
            button_kolo_show.Click += (sender, e) => pokaz_kolo(kolo, opcja);
            button_wielobok_show.Click += (sender, e) => pokaz_wielobok(wielobok, opcja);
            button_wroc.Click += Menu;

            this.Controls.Add(button_punkt_show);
            this.Controls.Add(button_trojkat_show);
            this.Controls.Add(button_kolo_show);
            this.Controls.Add(button_wielobok_show);
            this.Controls.Add(button_wroc);
        }

        public void Menu(object sender, EventArgs e)
        {
            button_punkt_show.Visible = false;
            button_trojkat_show.Visible = false;
            button_kolo_show.Visible = false;
            button_wielobok_show.Visible = false;

            button_wroc.Visible = false;

            button_pokaz_wszystkie.Visible = true;
            button_zmien_dane.Visible = true;
        }

        public void pokaz_punkt(Punkt punkt, string opcja)
        {
            if(opcja == "pokaz")
            {
                punkt.Pokaz_punkt();
            } 
            else if(opcja == "zmien")
            {
                punkt.Zmien_punkt();
                MessageBox.Show("dane zostały zmienione");
            }
        }

        public void pokaz_trojkat(Trojkat trojkat, string opcja)
        {
            if (opcja == "pokaz")
            {
                trojkat.Pokaz_trojkat();
            }
            else if (opcja == "zmien")
            {
                trojkat.Zmien_trojkat();
                MessageBox.Show("dane zostały zmienione");
            }
        }

        public void pokaz_kolo(Kolo kolo, string opcja)
        {
            if (opcja == "pokaz")
            {
                kolo.Pokaz_kolo();
            }
            else if (opcja == "zmien")
            {
                kolo.Zmien_kolo();
                MessageBox.Show("dane zostały zmienione");
            }
        }
        public void pokaz_wielobok(Wielobok wielobok, string opcja)
        {
            if (opcja == "pokaz")
            {
                wielobok.Pokaz_wielobok();
            }
            else if (opcja == "zmien")
            {
                wielobok.Zmien_wielobok();
                MessageBox.Show("dane zostały zmienione");
            }
        }
    }
}
