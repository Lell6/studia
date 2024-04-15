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
    public partial class Okno_edytuj_samochod : Form
    {
        public Samochod samochod;
        public Samochod_rodzinny samochod_r = null;
        public Samochod_terenowy samochod_t = null;
        public Samochod_sportowy samochod_s = null;

        public Okno_edytuj_samochod(ref Samochod samochod)
        {
            this.samochod = samochod;
            InitializeComponent();
        }

        public void button_edit_Click(object sender, EventArgs e)
        {
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

            this.samochod.Zmien_dane(new_marka, new_model, new_obudowa, new_rok, new_przebieg, new_moc, new_spal, new_waga, new_cena);

            if (radioButton1.Checked)
            {
                samochod_r = new Samochod_rodzinny(samochod);
            }
            else if (radioButton2.Checked)
            {
                samochod_s = new Samochod_sportowy(samochod);
            }
            else if (radioButton3.Checked)
            {
                samochod_t = new Samochod_terenowy(samochod);
            }

            MessageBox.Show("Samochód został zredagowany");
            this.Close();
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
