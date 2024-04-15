using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.IO;
using System.Linq;
using System.Security;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace zadanie_2
{
    public partial class Okno_menu : Form
    {
        OpenFileDialog openFileDialog;
        public Okno_menu()
        {
            openFileDialog = new OpenFileDialog();
            InitializeComponent();
        }

        public void SetText(string text)
        {
            textBox1.Text = text;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if(openFileDialog.ShowDialog() == DialogResult.OK)
            {
                try
                {
                    label1.Text = openFileDialog.FileName;

                    using(StreamReader reader = new StreamReader(label1.Text)){
                        textBox1.Text = reader.ReadToEnd();
                    }
                }
                catch (SecurityException ex)
                {
                    MessageBox.Show($"Błąd wczytywania pliku ({ex.Message})");
                }
                finally
                {
                    openFileDialog.Dispose(); // Close the OpenFileDialog
                }
            }
        }

        private void button5_Click(object sender, EventArgs e)
        {
            try
            {
                if (label1.Text.Contains("\\"))
                {
                    using (var sw = new StreamWriter(label1.Text))
                    {
                        openFileDialog.Dispose(); // Close the OpenFileDialog
                        sw.Write(textBox1.Text);
                    }
                }
                else
                {
                    SaveFileDialog saveFileDialog = new SaveFileDialog();
                    saveFileDialog.FileName = "Wybierz folder";

                    saveFileDialog.CheckFileExists = false;
                    saveFileDialog.CheckPathExists = false;

                    if(saveFileDialog.ShowDialog() == DialogResult.OK)
                    {
                        string path = Path.GetDirectoryName(saveFileDialog.FileName);

                        using (var sw = new StreamWriter(path + "\\" + label1.Text + ".txt"))
                        {
                            openFileDialog.Dispose(); // Close the OpenFileDialog
                            sw.Write(textBox1.Text);
                        }
                    }
                }

                MessageBox.Show("Plik Został zapisany");
            }
            catch (Exception ex)
            {
                MessageBox.Show($"Błąd zapisu pliku ({ex.Message})");
            }
        }

        private void button2_Click(object sender, EventArgs e)
        {
            Okno_nowa_nazwa okno = new Okno_nowa_nazwa();
            okno.FormClosed += New_name_Closed;

            okno.ShowDialog();
            Refresh();
        }

        private void New_name_Closed(object sender, FormClosedEventArgs e)
        {
            Okno_nowa_nazwa okno = (Okno_nowa_nazwa)sender;
            label1.Text = okno.nowy_plik;
            textBox1.Text = okno.zawartosc_nowego_pliku;
        }

        private void button3_Click(object sender, EventArgs e)
        {
            if(textBox1.Text != "")
            {
                Okno_szukany_wyraz okno = new Okno_szukany_wyraz(textBox1);
                okno.ShowDialog();
            }
            else
            {
                MessageBox.Show("Nie wybrano plik");
            }
        }

        private void button4_Click(object sender, EventArgs e)
        {
            if(textBox1.Text != "")
            {
                Okno_znajdz_zamien okno = new Okno_znajdz_zamien(textBox1);
                okno.ShowDialog();
            }
            else
            {
                MessageBox.Show("Nie wybrano plik");
            }
        }
    }
}
