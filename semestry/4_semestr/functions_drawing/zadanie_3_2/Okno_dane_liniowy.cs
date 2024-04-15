using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using zadanie_3_2;

namespace zadanie_3
{
    public partial class Okno_dane_liniowy : Form
    {
        public Okno_dane_liniowy()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (textBox1.Text != "" && textBox2.Text != "")
            {
                float wart_a, wart_b;

                float.TryParse(textBox2.Text, out wart_a);
                float.TryParse(textBox1.Text, out wart_b);

                Okno_wykres_liniowy okno = new Okno_wykres_liniowy(wart_a, wart_b);
                okno.ShowDialog();
            }
        }
    }
}
