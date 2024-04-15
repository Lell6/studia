using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace komis
{
    public partial class Okno_wyszukaj : Form
    {
        Samochod[] samochody;
        Samochod[] samochody_filtrowane = new Samochod[0];
        public Okno_wyszukaj(ref Samochod[] samochody)
        {
            this.samochody = samochody;
            InitializeComponent();
        }

        public void add_to_array(int i)
        {
            int j = 0;
            Array.Resize(ref samochody_filtrowane, samochody_filtrowane.Length + 1);
            while (samochody_filtrowane[j] != null)
            {
                j++;
            }

            samochody_filtrowane[j] = samochody[i];
        }

        public void filtruj(Samochod[] samochody, String filtr)
        {
            samochody_filtrowane = new Samochod[0];

            for (int i = 0; i < samochody.Length; i++)
            {
                if (filtr == "rodzinny" && samochody[i].daj_typ() == "rodzinny")
                {
                    add_to_array(i);
                }
                else if(filtr == "sportowy" && samochody[i].daj_typ() == "sportowy")
                {
                    add_to_array(i);
                }
                else if (filtr == "terenowy" && samochody[i].daj_typ() == "terenowy")
                {
                    add_to_array(i);
                }
            }
        }
        public void button_input_filter(object sender, EventArgs e)
        {
            Okno_input_filtr okno;

            if (radioButton1.Checked)
            {
                okno = new Okno_input_filtr("marka", ref samochody);
                okno.Show();
            }
            else if (radioButton2.Checked)
            {
                okno = new Okno_input_filtr("model", ref samochody);
                okno.Show();
            }
            else if (radioButton3.Checked)
            {
                okno = new Okno_input_filtr("rok", ref samochody);
                okno.Show();
            }
            else if (radioButton4.Checked)
            {
                okno = new Okno_input_filtr("cena", ref samochody);
                okno.Show();
            }
            else if (radioButton5.Checked)
            {
                okno = new Okno_input_filtr("id", ref samochody);
                okno.Show();
            }
            else if (radioButton6.Checked)
            {
                filtruj(samochody, "terenowy");
                Okno_pokaz_filtr okno_filtr = new Okno_pokaz_filtr(ref samochody_filtrowane);
                okno_filtr.Show();
            }
            else if (radioButton7.Checked)
            {
                filtruj(samochody, "sportowy");
                Okno_pokaz_filtr okno_filtr = new Okno_pokaz_filtr(ref samochody_filtrowane);
                okno_filtr.Show();
            }
            else if (radioButton8.Checked)
            {
                filtruj(samochody, "rodzinny");
                Okno_pokaz_filtr okno_filtr = new Okno_pokaz_filtr(ref samochody_filtrowane);
                okno_filtr.Show();
            }
        }
    }
}
