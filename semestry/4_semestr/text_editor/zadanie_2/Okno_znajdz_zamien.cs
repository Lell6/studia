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
    public partial class Okno_znajdz_zamien : Form
    {
        RichTextBox przeszukiwany_tekst;
        string wyraz_szukany;
        string wyraz_zamiana;

        public Okno_znajdz_zamien(RichTextBox tekst)
        {
            przeszukiwany_tekst = tekst;
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if(textBox2.Text != "" && textBox1.Text != "")
            {
                wyraz_szukany = textBox1.Text;
                wyraz_zamiana = textBox2.Text;

                przeszukiwany_tekst.Text = przeszukiwany_tekst.Text.Replace(wyraz_szukany, wyraz_zamiana);
                Close();
            } 
            else
            {
                MessageBox.Show("Puste pola");
            }
        }
    }
}
