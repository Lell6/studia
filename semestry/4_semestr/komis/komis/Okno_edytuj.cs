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
    public partial class Okno_edytuj : Form
    {
        public Samochod[] samochody;
        public int ID;
        public Okno_edytuj(ref Samochod[] samochody)
        {
            this.samochody = samochody;
            InitializeComponent();
        }

        public void button_edit(int ID)
        {
            this.ID = ID;
            Okno_edytuj_samochod okno = new Okno_edytuj_samochod(ref samochody[ID]);
            okno.FormClosed += Okno_Closed_edit;

            okno.Show();
            Close();
        }
        public void button_show_info(String info)
        {
            MessageBox.Show(info);
        }

        private void Okno_Closed_edit(object sender, FormClosedEventArgs e)
        {
            Okno_edytuj_samochod okno = (Okno_edytuj_samochod)sender;

            if(okno.samochod_r != null)
            {
                samochody[ID] = okno.samochod_r;
            }
            else if(okno.samochod_t != null)
            {
                samochody[ID] = okno.samochod_t;
            }
            else if (okno.samochod_s != null)
            {
                samochody[ID] = okno.samochod_s;
            }
        }
    }
}
