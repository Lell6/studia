using System;
using System.Windows.Forms;

namespace zadanie_2
{
    public class Kolo
    {
        public Punkt srodek_kola;
        public double promien;

        public Kolo()
        {
            srodek_kola = new Punkt();
            Random rand = new Random();

            Console.WriteLine("Podaj promien: ");
            promien = rand.NextDouble() * 100.0;
        }

        public void Zmien_kolo()
        {
            Random rand = new Random();

            srodek_kola.Zmien_punkt();
            promien = rand.NextDouble() * 100.0;
        }
        public void Pokaz_kolo()
        {
            MessageBox.Show($"Środek koła: ({srodek_kola.punkt_x}, {srodek_kola.punkt_y})\nPromień koła: {promien}");
        }
    }
}
