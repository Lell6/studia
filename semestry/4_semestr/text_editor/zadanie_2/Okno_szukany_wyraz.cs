using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace zadanie_2
{
    public partial class Okno_szukany_wyraz : Form
    {
        RichTextBox przeszukiwany_tekst;
        string wyraz;
        public Okno_szukany_wyraz(RichTextBox tekst)
        {
            przeszukiwany_tekst = tekst;
            InitializeComponent();
        }
        private void button1_Click(object sender, EventArgs e)
        {
            wyraz = textBox1.Text;
            int i = 0;

            przeszukiwany_tekst.Select(0, przeszukiwany_tekst.Text.Length);
            przeszukiwany_tekst.SelectionColor = Color.Black;

            while (i < przeszukiwany_tekst.TextLength)
            {
                int poczatek_wyrazu = przeszukiwany_tekst.Find(wyraz, i, RichTextBoxFinds.WholeWord);
                if(poczatek_wyrazu == -1)
                {
                    break;
                }

                przeszukiwany_tekst.Select(poczatek_wyrazu, wyraz.Length);
                przeszukiwany_tekst.SelectionColor = Color.Red;

                i = poczatek_wyrazu + wyraz.Length;
            }
            Close();
        }
    }
}
