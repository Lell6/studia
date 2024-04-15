using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace komis
{
    public class Samochod
    {
        public int id;
        public String marka;
        public String model;
        public String rodzaj_obudowy;
        public int rok_wydania;
        public float przebieg;

        public float moc_silnika;
        public float spalanie;

        public float waga;
        public float cena;

        public Samochod() { }

        public Samochod(Samochod samochod)
        {
            this.id = samochod.id;
            this.marka = samochod.marka;
            this.model = samochod.model;
            this.rodzaj_obudowy = samochod.rodzaj_obudowy;
            this.rok_wydania = samochod.rok_wydania;
            this.przebieg = samochod.przebieg;
            this.moc_silnika = samochod.moc_silnika;
            this.spalanie = samochod.spalanie;
            this.waga = samochod.waga;
            this.cena = samochod.cena;
        }
        public Samochod(int id, String marka, String model, String obudowa, int rok, float przebieg, float moc, float spal, float waga, float cena)
        {
            this.id = id;
            this.marka = marka;
            this.model = model;
            this.rodzaj_obudowy = obudowa;
            this.rok_wydania = rok;
            this.przebieg = przebieg;
            this.moc_silnika = moc;
            this.spalanie = spal;
            this.waga = waga;
            this.cena = cena;
        }
        public virtual String Pisz()
        {
            String info = "";

            info += $"Samochód\n";
            info += $"Marka: {this.marka}\n";
            info += $"Model: {this.model}\n";
            info += $"Cena: {this.cena} zł\n";

            return info;
        }
        public virtual String Pisz_dodatki()
        {
            String info = "";

            info += $"Dodatki\n";
            info += $"ID w komisie: {this.id}\n";
            info += $"Rok wydania: {this.rok_wydania} r.\n";
            info += $"Przebieg: {this.przebieg} km\n";
            info += $"Moc silnika: {this.moc_silnika} kW\n";
            info += $"Spalanie: {this.spalanie} litrów na 100km\n";
            info += $"Waga: {this.waga} t\n";

            return info;
        }

        public void Zmien_dane(String marka, String model, String obudowa, int rok, float przebieg, float moc, float spal, float waga, float cena)
        {
            this.marka = marka;
            this.model = model;
            this.rodzaj_obudowy = obudowa;
            this.rok_wydania = rok;
            this.przebieg = przebieg;
            this.moc_silnika = moc;
            this.spalanie = spal;
            this.waga = waga;
            this.cena = cena;
            Samochod_sportowy samochod = new Samochod_sportowy();
        }
        public virtual String daj_typ()
        {
            return "";
        }
    }

    public class Samochod_sportowy : Samochod
    {
        public bool jest_sportowy;

        public Samochod_sportowy() { }

        public Samochod_sportowy(Samochod samochod):base(samochod)
        {
            this.jest_sportowy = true;
        }
        public Samochod_sportowy(int id, String marka, String model, String obudowa, int rok, float przebieg, float moc, float spal, float waga, float cena) :base(id, marka, model, obudowa, rok, przebieg, moc, spal, waga, cena)
        {
            this.jest_sportowy = true;
        }

        public override String Pisz()
        {
            String info = base.Pisz();

            info += "Rodzaj samochodu: sportowy\n";
            return info;
        }

        public override String daj_typ()
        {
            return "sportowy";
        }
    }

    public class Samochod_rodzinny : Samochod
    {
        public bool jest_rodzinny;
        public Samochod_rodzinny() { }
        public Samochod_rodzinny(Samochod samochod) : base(samochod)
        {
            this.jest_rodzinny = true;
        }
        public Samochod_rodzinny(int id, String marka, String model, String obudowa, int rok, float przebieg, float moc, float spal, float waga, float cena) : base(id, marka, model, obudowa, rok, przebieg, moc, spal, waga, cena)
        {
            this.jest_rodzinny = true;
        }
        public override String Pisz()
        {
            String info = base.Pisz();

            info += "Rodzaj samochodu: rodzinny\n";
            return info;
        }
        public override String daj_typ()
        {
            return "rodzinny";
        }
    }
    public class Samochod_terenowy : Samochod
    {
        public bool jest_terenowy;
        public Samochod_terenowy() { }
        public Samochod_terenowy(Samochod samochod) : base(samochod)
        {
            this.jest_terenowy = true;
        }
        public Samochod_terenowy(int id, String marka, String model, String obudowa, int rok, float przebieg, float moc, float spal, float waga, float cena) : base(id, marka, model, obudowa, rok, przebieg, moc, spal, waga, cena)
        {
            this.jest_terenowy = true;
        }
        public override String Pisz()
        {
            String info = base.Pisz();

            info += "Rodzaj samochodu: terenowy\n";
            return info;
        }
        public override String daj_typ()
        {
            return "terenowy";
        }
    }
}
