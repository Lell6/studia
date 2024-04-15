using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace zadanie_3
{
    public partial class Okno_wykres_parabola : Form
    {
        float wart_a;
        float wart_b;
        public Okno_wykres_parabola(float wart_a, float wart_b)
        {
            this.wart_a = wart_a;
            this.wart_b = wart_b;

            InitializeComponent();
        }
    }
}
