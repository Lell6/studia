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
    public partial class Okno_input_filtr : Form
    {
        public String filtr_wyszukania;
        public Samochod[] samochody;
        public Samochod[] samochody_filtrowane;
        public Okno_input_filtr(String filtr_wyszukania, ref Samochod[] samochody)
        {
            this.filtr_wyszukania = filtr_wyszukania;
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

        private void button1_Click(object sender, EventArgs e)
        {
            samochody_filtrowane = new Samochod[0];

            if (filtr.Text == "")
            {
                MessageBox.Show("Puste pole");
                return;
            }

            for(int i = 0; i < samochody.Length; i++)
            {
                if ((filtr_wyszukania == "marka" && samochody[i].marka == filtr.Text) || (filtr_wyszukania == "model" && samochody[i].model == filtr.Text))
                {
                    add_to_array(i);
                }
                else if(filtr_wyszukania == "rok")
                {
                    int rok;
                    Int32.TryParse(filtr.Text, out rok);

                    if(samochody[i].rok_wydania == rok)
                    {
                        add_to_array(i);
                    }
                }
                else if(filtr_wyszukania == "cena")
                {
                    int cena;
                    Int32.TryParse(filtr.Text, out cena);

                    if (samochody[i].cena == cena)
                    {
                        add_to_array(i);
                    }
                }
                else if(filtr_wyszukania == "id")
                {
                    int id;
                    Int32.TryParse(filtr.Text, out id);

                    if (id < samochody.Length && samochody[id] != null)
                    {
                        samochody_filtrowane = new Samochod[1];
                        samochody_filtrowane[0] = samochody[id];
                    }
                }
            }
            
            if(samochody_filtrowane.Length > 0)
            {
                Okno_pokaz_filtr okno = new Okno_pokaz_filtr(ref samochody_filtrowane);
                okno.Show();
            } else
            {
                MessageBox.Show("Brak wyników wyszukiwania");
            }
        }
    }
}
