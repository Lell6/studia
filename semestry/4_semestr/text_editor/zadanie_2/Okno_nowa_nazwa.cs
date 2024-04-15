using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace zadanie_2
{
    public partial class Okno_nowa_nazwa : Form
    {
        public string nowy_plik;
        public string zawartosc_nowego_pliku;
        public Okno_nowa_nazwa()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if(textBox1.Text != "")
            {
                nowy_plik = textBox1.Text;
                zawartosc_nowego_pliku = "";
                Close();
            }
        }
    }
}
