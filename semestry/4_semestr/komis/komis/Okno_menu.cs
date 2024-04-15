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
    public partial class Okno_menu : Form
    {
        public Samochod[] samochody;
        public Okno_menu(ref Samochod[] samochody)
        {
            this.samochody = samochody;
            InitializeComponent();
        }

        private void button_dodaj_Click(object sender, EventArgs e)
        {
            Okno_dodaj okno = new Okno_dodaj(ref this.samochody);
            okno.FormClosed += Okno_Closed_add;

            okno.ShowDialog();
            Refresh();
        }

        private void button_usun_Click(object sender, EventArgs e)
        {
            if (this.samochody.Length != 0)
            {
                Okno_usun okno = new Okno_usun(ref this.samochody);
                okno.FormClosed += Okno_Closed_del;

                okno.ShowDialog();
                Refresh();
            }
            else
            {
                MessageBox.Show("Nie ma dodanych samochodów");
            }
        }

        private void button_edytuj_Click(object sender, EventArgs e)
        {
            if (this.samochody.Length != 0)
            {
                Okno_edytuj okno = new Okno_edytuj(ref this.samochody);
                okno.FormClosed += Okno_Closed_edit;

                okno.ShowDialog();
                Refresh();
            }
            else
            {
                MessageBox.Show("Nie ma dodanych samochodów");
            }
        }

        private void Okno_Closed_add(object sender, FormClosedEventArgs e)
        {
            Okno_dodaj okno = (Okno_dodaj)sender;
            samochody = okno.samochody;
        }
        private void Okno_Closed_del(object sender, FormClosedEventArgs e)
        {
            Okno_usun okno = (Okno_usun)sender;
            samochody = okno.samochody;
        }
        private void Okno_Closed_edit(object sender, FormClosedEventArgs e)
        {
            Okno_edytuj okno = (Okno_edytuj)sender;
            samochody = okno.samochody;
        }

        private void button_show_all_Click(Samochod[] samochody)
        {
            if(this.samochody.Length != 0)
            {
                Okno_pokaz_wszystkie okno = new Okno_pokaz_wszystkie(ref samochody);
                okno.ShowDialog();
            }
            else
            {
                MessageBox.Show("Nie ma dodanych samochodów");
            }
        }

        private void button_wyszukaj_Click(Samochod[] samochody)
        {
            if (this.samochody.Length != 0)
            {
                Okno_wyszukaj okno = new Okno_wyszukaj(ref samochody);
                okno.ShowDialog();
            }
            else
            {
                MessageBox.Show("Nie ma dodanych samochodów");
            }
        }

        private void button_exit_Click(object sender, EventArgs e)
        {
            this.Close();
        }
    }
}
