using System;
using System.Windows.Forms;

namespace zadanie_2
{
    public class Trojkat
    {
        public Punkt wspolrzedna_1;
        public Punkt wspolrzedna_2;
        public Punkt wspolrzedna_3;

        public string typ;
        public Trojkat()
        {
            wspolrzedna_1 = new Punkt();
            wspolrzedna_2 = new Punkt();
            wspolrzedna_3 = new Punkt();

            typ = Podanie_typu();
        }
        public double Dlugosc_boku(Punkt poczatek, Punkt koniec)
        {
            return Math.Sqrt(Math.Pow(koniec.punkt_x - poczatek.punkt_x, 2) + Math.Pow(koniec.punkt_y - poczatek.punkt_y, 2));
        }

        public bool Rowne_boki(double bok_a, double bok_b, double blad = 0.000001)
        {
            return Math.Abs(bok_a - bok_b) < blad;
        }

        public String Podanie_typu()
        {
            double[] dlugosc_bokow = new double[3];
            dlugosc_bokow[0] = Dlugosc_boku(wspolrzedna_1, wspolrzedna_2);
            dlugosc_bokow[1] = Dlugosc_boku(wspolrzedna_2, wspolrzedna_3);
            dlugosc_bokow[2] = Dlugosc_boku(wspolrzedna_1, wspolrzedna_3);

            if (Rowne_boki(dlugosc_bokow[0], dlugosc_bokow[1]) && Rowne_boki(dlugosc_bokow[2], dlugosc_bokow[1]))
            {
                return "rownoboczny";
            }
            else if (Rowne_boki(dlugosc_bokow[0], dlugosc_bokow[1]) || Rowne_boki(dlugosc_bokow[2], dlugosc_bokow[1]))
            {
                return "rownoramienny";
            }
            else if (!Rowne_boki(dlugosc_bokow[0], dlugosc_bokow[1]) && !Rowne_boki(dlugosc_bokow[2], dlugosc_bokow[1]))
            {
                return "inny";
            }

            return "";
        }
        
        public void Zmien_trojkat()
        {
            wspolrzedna_1.Zmien_punkt();
            wspolrzedna_2.Zmien_punkt();
            wspolrzedna_3.Zmien_punkt();

            typ = Podanie_typu();
        }

        public void Pokaz_trojkat()
        {
            MessageBox.Show($"Wszpółrzędna 1: ({wspolrzedna_1.punkt_x}, {wspolrzedna_1.punkt_y})\n" +
                            $"Wszpółrzędna 2: ({wspolrzedna_2.punkt_x}, {wspolrzedna_2.punkt_y})\n" + 
                            $"Wszpółrzędna 3: ({wspolrzedna_3.punkt_x}, {wspolrzedna_3.punkt_y})\n" + 
                            $"Typ trójkąta: {typ}");
        }
    }
}
