using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace zadanie_1
{
    public partial class Okno_menu : Form
    {
        public Okno_menu()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            Okno_gry okno = new Okno_gry("komp");
            okno.ShowDialog();
        }

        private void button2_Click(object sender, EventArgs e)
        {
            Okno_gry okno = new Okno_gry("guy");
            okno.ShowDialog();
        }
    }
}
