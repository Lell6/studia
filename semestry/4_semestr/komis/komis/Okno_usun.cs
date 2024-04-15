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
    public partial class Okno_usun : Form
    {
        public Samochod[] samochody;
        public Okno_usun(ref Samochod[] samochody)
        {
            this.samochody = samochody;
            InitializeComponent();
        }

        public void button_show_info(String info)
        {
            MessageBox.Show(info);
        }

        public void button_delete(int ID)
        {
            samochody[ID] = null;
            MessageBox.Show("Samochód został usunięty");
            Close();
        }
    }
}
