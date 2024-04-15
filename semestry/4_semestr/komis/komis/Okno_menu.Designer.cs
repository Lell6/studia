
namespace komis
{
    partial class Okno_menu
    {
        /// <summary>
        /// Обязательная переменная конструктора.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Освободить все используемые ресурсы.
        /// </summary>
        /// <param name="disposing">истинно, если управляемый ресурс должен быть удален; иначе ложно.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Код, автоматически созданный конструктором форм Windows

        private void InitializeComponent()
        {
            this.button_dodaj = new System.Windows.Forms.Button();
            this.button_usun = new System.Windows.Forms.Button();
            this.button_edytuj = new System.Windows.Forms.Button();
            this.button_show_all = new System.Windows.Forms.Button();
            this.button_wyszukaj = new System.Windows.Forms.Button();
            this.button_exit = new System.Windows.Forms.Button();
            this.SuspendLayout();
            // 
            // button_dodaj
            // 
            this.button_dodaj.Location = new System.Drawing.Point(12, 15);
            this.button_dodaj.Name = "button_dodaj";
            this.button_dodaj.Size = new System.Drawing.Size(110, 30);
            this.button_dodaj.TabIndex = 0;
            this.button_dodaj.Text = "Dodaj";
            this.button_dodaj.UseVisualStyleBackColor = true;
            this.button_dodaj.Click += new System.EventHandler(this.button_dodaj_Click);
            // 
            // button_usun
            // 
            this.button_usun.Location = new System.Drawing.Point(128, 15);
            this.button_usun.Name = "button_usun";
            this.button_usun.Size = new System.Drawing.Size(110, 30);
            this.button_usun.TabIndex = 1;
            this.button_usun.Text = "Usuń";
            this.button_usun.UseVisualStyleBackColor = true;
            this.button_usun.Click += new System.EventHandler(this.button_usun_Click);
            // 
            // button_edytuj
            // 
            this.button_edytuj.Location = new System.Drawing.Point(244, 15);
            this.button_edytuj.Name = "button_edytuj";
            this.button_edytuj.Size = new System.Drawing.Size(110, 30);
            this.button_edytuj.TabIndex = 2;
            this.button_edytuj.Text = "Edytuj";
            this.button_edytuj.UseVisualStyleBackColor = true;
            this.button_edytuj.Click += new System.EventHandler(this.button_edytuj_Click);
            // 
            // button_show_all
            // 
            this.button_show_all.Location = new System.Drawing.Point(12, 70);
            this.button_show_all.Name = "button_show_all";
            this.button_show_all.Size = new System.Drawing.Size(110, 30);
            this.button_show_all.TabIndex = 3;
            this.button_show_all.Text = "Pokaż wszystkie";
            this.button_show_all.UseVisualStyleBackColor = true;
            this.button_show_all.Click += (sender, e) => this.button_show_all_Click(samochody);
            // 
            // button_wyszukaj
            // 
            this.button_wyszukaj.Location = new System.Drawing.Point(128, 70);
            this.button_wyszukaj.Name = "button_wyszukaj";
            this.button_wyszukaj.Size = new System.Drawing.Size(110, 30);
            this.button_wyszukaj.TabIndex = 4;
            this.button_wyszukaj.Text = "Wyszukaj";
            this.button_wyszukaj.UseVisualStyleBackColor = true;
            this.button_wyszukaj.Click += (sender, e) => this.button_wyszukaj_Click(samochody);
            // 
            // button_exit
            // 
            this.button_exit.Location = new System.Drawing.Point(12, 171);
            this.button_exit.Name = "button_exit";
            this.button_exit.Size = new System.Drawing.Size(110, 30);
            this.button_exit.TabIndex = 5;
            this.button_exit.Text = "Wyjdź z programu";
            this.button_exit.UseVisualStyleBackColor = true;
            this.button_exit.Click += new System.EventHandler(this.button_exit_Click);
            // 
            // Okno_menu
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(368, 212);
            this.Controls.Add(this.button_exit);
            this.Controls.Add(this.button_wyszukaj);
            this.Controls.Add(this.button_show_all);
            this.Controls.Add(this.button_edytuj);
            this.Controls.Add(this.button_usun);
            this.Controls.Add(this.button_dodaj);
            this.Name = "Okno_menu";
            this.Text = "Komis";
            this.ResumeLayout(false);
        }

        #endregion

        private System.Windows.Forms.Button button_dodaj;
        private System.Windows.Forms.Button button_usun;
        private System.Windows.Forms.Button button_edytuj;
        private System.Windows.Forms.Button button_show_all;
        private System.Windows.Forms.Button button_wyszukaj;
        private System.Windows.Forms.Button button_exit;
    }
}

