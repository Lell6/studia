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
    public partial class Okno_dodaj : Form
    {
        public Samochod[] samochody;
        public Okno_dodaj(ref Samochod[] samochody)
        {
            this.samochody = samochody;
            InitializeComponent();
        }

        private void button_add_Click(object sender, EventArgs e)
        {
            Array.Resize(ref this.samochody, this.samochody.Length + 1);

            int i = 0;
            while (this.samochody[i] != null)
            {
                i++;
            }

            int id = i;
            int new_rok;
            float new_przebieg;
            float new_moc;
            float new_spal;
            float new_waga;
            float new_cena;

            String new_marka = marka.Text;
            String new_model = model.Text;
            String new_obudowa = obudowa.Text;

            Int32.TryParse(rok.Text, out new_rok);
            float.TryParse(moc_silnika.Text, out new_moc);
            float.TryParse(przebieg.Text, out new_przebieg);
            float.TryParse(waga.Text, out new_waga);
            float.TryParse(cena.Text, out new_cena);
            float.TryParse(spalanie.Text, out new_spal);

            if (radioButton1.Checked == true) // tworzymy rodzinny
            {
                this.samochody[i] = new Samochod_rodzinny(id, new_marka, new_model, new_obudowa, new_rok, new_przebieg, new_moc, new_spal, new_waga, new_cena);
                MessageBox.Show("Nowy samochód został dodany");
            } 
            else if(radioButton2.Checked == true) // tworzymy sportowy
            {
                this.samochody[i] = new Samochod_sportowy(id, new_marka, new_model, new_obudowa, new_rok, new_przebieg, new_moc, new_spal, new_waga, new_cena);
                MessageBox.Show("Nowy samochód został dodany");
            }
            else if (radioButton3.Checked == true) //tworzymy terenowy
            {
                this.samochody[i] = new Samochod_terenowy(id, new_marka, new_model, new_obudowa, new_rok, new_przebieg, new_moc, new_spal, new_waga, new_cena);
                MessageBox.Show("Nowy samochód został dodany");
            }
            else
            {
                MessageBox.Show("Podaj rodzaj samochodu");
            }
        }

        private void button_reset_Click(object sender, EventArgs e)
        {
            foreach (Control control in Controls)
            {
                if (control is TextBox textBox)
                {
                    textBox.Text = "";
                }
            }
        }

        private void button_exit_Click(object sender, EventArgs e)
        {
            this.Close();
        }
    }
}
