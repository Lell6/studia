using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace komis
{
    static class Program
    {
        [STAThread]
        static void Main()
        {
            Samochod[] samochody = new Samochod[0];

            Application.EnableVisualStyles();
            Application.SetCompatibleTextRenderingDefault(false);
            Application.Run(new Okno_menu(ref samochody));
        }
    }
}
