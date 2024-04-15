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
    public partial class Okno_pokaz_filtr : Form
    {
        public Samochod[] samochody;

        public Okno_pokaz_filtr(ref Samochod[] samochody)
        {
            this.samochody = samochody;
            InitializeComponent();
        }
        public void button_show_info(String info)
        {
            MessageBox.Show(info);
        }
    }
}
