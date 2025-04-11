using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using System.IO;
using System.Diagnostics;

namespace total_commander
{
    public partial class Okno_glowne : Form
    {
        public Okno_glowne()
        {
            InitializeComponent();
            Directory.SetCurrentDirectory(@"C:\");

            PopulateTree1View(@"C:\");
            PopulateTree2View(@"C:\");
        }

        private void PopulateTree1View(string directoryPath)
        {
            DirectoryInfo dirInfo = new DirectoryInfo(directoryPath);

            try
            {
                listView1.Items.Clear();
                listView1.Columns.Clear();

                listView1.Columns.Add("Nazwa", 250);
                listView1.Columns.Add("Typ", 100);
                listView1.Columns.Add("Ostatnnia modyfikacja", 175);

                //if its root directory
                if (dirInfo.Parent != null || !Path.GetPathRoot(directoryPath).Equals(directoryPath, StringComparison.OrdinalIgnoreCase))
                {
                    ListViewItem parentItem = new ListViewItem("..\\");
                    parentItem.SubItems.Add("Folder");
                    parentItem.SubItems.Add("");
                    parentItem.Tag = dirInfo.Parent.FullName;
                    listView1.Items.Add(parentItem);
                }

                //anything else
                foreach (var directory in dirInfo.GetDirectories())
                {
                    ListViewItem item = new ListViewItem(directory.Name);
                    item.SubItems.Add("Folder");
                    item.SubItems.Add(directory.LastWriteTime.ToString());
                    item.Tag = directory.FullName;
                    listView1.Items.Add(item);
                }

                foreach (var file in dirInfo.GetFiles())
                {
                    ListViewItem item = new ListViewItem(file.Name);

                    item.SubItems.Add(file.Extension.Remove(0, 1));
                    item.SubItems.Add(file.LastWriteTime.ToString());
                    item.Tag = file.FullName;
                    listView1.Items.Add(item);
                }

                label1.Text = directoryPath;
                Directory.SetCurrentDirectory(directoryPath);
            }
            catch (Exception ex)
            {
                MessageBox.Show($"Błąd: {ex.Message}");
                if (dirInfo.Parent != null)
                {
                    PopulateTree1View(dirInfo.Parent.FullName);
                }
            }
        }

        private void PopulateTree2View(string directoryPath)
        {

            DirectoryInfo dirInfo = new DirectoryInfo(directoryPath);

            try
            {
                listView2.Items.Clear();
                listView2.Columns.Clear();

                listView2.Columns.Add("Nazwa", 250);
                listView2.Columns.Add("Typ", 100);
                listView2.Columns.Add("Ostatnia modyfikacja", 175);

                //if its root directory
                if (dirInfo.Parent != null)
                {
                    ListViewItem parentItem = new ListViewItem("..\\");
                    parentItem.SubItems.Add("Folder");
                    parentItem.SubItems.Add("");
                    parentItem.Tag = dirInfo.Parent.FullName;
                    listView2.Items.Add(parentItem);
                }

                //anything else
                foreach (var directory in dirInfo.GetDirectories())
                {
                    ListViewItem item = new ListViewItem(directory.Name);
                    item.SubItems.Add("Folder");
                    item.SubItems.Add(directory.LastWriteTime.ToString());
                    item.Tag = directory.FullName;
                    listView2.Items.Add(item);
                }

                foreach (var file in dirInfo.GetFiles())
                {
                    ListViewItem item = new ListViewItem(file.Name);

                    item.SubItems.Add(file.Extension.Remove(0, 1));
                    item.SubItems.Add(file.LastWriteTime.ToString());
                    item.Tag = file.FullName;
                    listView2.Items.Add(item);
                }

                label2.Text = directoryPath;
                Directory.SetCurrentDirectory(directoryPath);
            }
            catch (Exception ex)
            {
                MessageBox.Show($"Błąd: {ex.Message}");
                if (dirInfo.Parent != null)
                {
                    PopulateTree2View(dirInfo.Parent.FullName);
                }
            }
        }

        private ListView.SelectedListViewItemCollection get_elements()
        {
            if(listView1.SelectedItems.Count > 0)
            {
                return listView1.SelectedItems;
            }
            else if(listView2.SelectedItems.Count > 0)
            {
                return listView2.SelectedItems;
            }
            else
            {
                return null;
            }
        }
        private string search_folder_file(string start_path, string element_to_search)
        {
            DirectoryInfo directory = new DirectoryInfo(start_path);

            if (element_to_search == "..\\" && directory.Parent != null)
            {
                return directory.Parent.FullName;
            }

            var matchingDirectory = directory.GetDirectories().FirstOrDefault(folder => folder.Name == element_to_search);
            return matchingDirectory != null ? matchingDirectory.FullName : string.Empty;
        }

        private void Change_path_1(object sender, MouseEventArgs e)
        {
            ListViewItem item = listView1.GetItemAt(e.X, e.Y);

            if (item != null)
            {
                string folder_file_name = item.Text;
                string start_path = label1.Text;
                string folder_file_path = search_folder_file(start_path, folder_file_name);

                if(folder_file_path != "")
                {
                    PopulateTree1View(folder_file_path);
                }
                else
                {
                    MessageBox.Show("Wygrano plik");
                }
            }
        }

        private void Change_path_2(object sender, MouseEventArgs e)
        {
            ListViewItem item = listView2.GetItemAt(e.X, e.Y);

            if (item != null)
            {
                string folder_file_name = item.Text;
                string start_path = label2.Text;
                string folder_file_path = search_folder_file(start_path, folder_file_name);

                if (folder_file_path != "")
                {
                    PopulateTree2View(folder_file_path);
                }
                else
                {
                    MessageBox.Show("Wygrano plik");
                }
            }
        }
        private void button4_Click(object sender, EventArgs e)
        {
            ListView.SelectedListViewItemCollection zestaw_danych = get_elements();

            if(zestaw_danych != null)
            {
                foreach (ListViewItem item in zestaw_danych)
                {
                    MessageBox.Show(search_folder_file(label2.Text, item.Text));
                }
            }
            else
            {
                MessageBox.Show("Nie wybrano elementów");
            }
        }

        private void listView1_ItemSelectionChanged(object sender, ListViewItemSelectionChangedEventArgs e)
        {
            listView2.SelectedItems.Clear();
        }
        private void listView2_ItemSelectionChanged(object sender, ListViewItemSelectionChangedEventArgs e)
        {
            listView1.SelectedItems.Clear();
        }

        private void button2_Click(object sender, EventArgs e)
        {
            
        }
    }
}
