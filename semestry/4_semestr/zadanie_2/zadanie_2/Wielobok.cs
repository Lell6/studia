using System;
using System.Windows.Forms;

namespace zadanie_2
{
    public class Wielobok
    {
        public Punkt[] wspolrzedne;

        public Wielobok(int liczba_katow)
        {
            wspolrzedne = new Punkt[liczba_katow];
            for (int i = 0; i < wspolrzedne.Length; i++)
            {
                wspolrzedne[i] = new Punkt();
            }
        }
        public void Zmien_wielobok()
        {
            for (int i = 0; i < wspolrzedne.Length; i++)
            {
                wspolrzedne[i].Zmien_punkt();
            }
        }

        public void Pokaz_wielobok()
        {
            string info = "";

            for(int i = 0; i < wspolrzedne.Length; i++)
            {
                info += $"Wszpółrzędna {i+1}: ({wspolrzedne[i].punkt_x}, {wspolrzedne[i].punkt_y})\n";
            }

            MessageBox.Show(info);
        }
    }
}
