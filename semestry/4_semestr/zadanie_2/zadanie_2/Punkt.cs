using System;
using System.Windows.Forms;

namespace zadanie_2
{
    public class Punkt
    {
        public double punkt_x;
        public double punkt_y;

        public Punkt()
        {
            Random rand = new Random();

            punkt_x = rand.NextDouble() * 100.0;
            punkt_y = rand.NextDouble() * 100.0;
        }

        public void Zmien_punkt()
        {
            Random rand = new Random();

            punkt_x = rand.NextDouble() * 100.0;
            punkt_y = rand.NextDouble() * 100.0;
        }

        public void Pokaz_punkt()
        {
            MessageBox.Show($"Punkt ({punkt_x}, {punkt_y})");
        }
    }
}
