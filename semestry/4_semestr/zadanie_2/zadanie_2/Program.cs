using System;
using System.Windows.Forms;

namespace zadanie_2
{
    static class Program
    {
        [STAThread]
        static void Main(string[] args)
        {
            Random rand = new Random();

            Punkt punkt = new Punkt();
            Trojkat trojkat = new Trojkat();
            Kolo kolo = new Kolo();
            Wielobok wielobok = new Wielobok(rand.Next(4,20));

            Application.SetHighDpiMode(HighDpiMode.SystemAware);
            Application.EnableVisualStyles();
            Application.SetCompatibleTextRenderingDefault(false);
            Application.Run(new Form1(punkt, trojkat, kolo, wielobok));
        }
    }
}
