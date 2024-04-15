
using System.Windows.Forms;

namespace komis
{
    partial class Okno_pokaz_wszystkie
    {
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        private void InitializeComponent()
        {
            this.label1 = new System.Windows.Forms.Label();

            this.labels_info = new System.Windows.Forms.Label[samochody.Length];
            this.buttons_info = new System.Windows.Forms.Button[samochody.Length];

            this.SuspendLayout();
            int count = 0;

            for (int i = 0; i < samochody.Length; i++)
            {
                if (samochody[i] == null)
                {
                    count++;
                    continue;
                }

                string info = samochody[i].Pisz_dodatki();

                this.labels_info[i] = new System.Windows.Forms.Label();

                this.labels_info[i].AutoSize = true;
                this.labels_info[i].Font = new System.Drawing.Font("Microsoft Sans Serif", 12F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(204)));
                this.labels_info[i].Location = new System.Drawing.Point(12, 120*(i+1-count));
                this.labels_info[i].Name = "label" + (i+1);
                this.labels_info[i].Size = new System.Drawing.Size(140, 30);
                this.labels_info[i].Text = samochody[i].Pisz();


                this.buttons_info[i] = new System.Windows.Forms.Button();

                this.buttons_info[i].Location = new System.Drawing.Point(250, 120*(i + 1 - count));
                this.buttons_info[i].Name = "button" + (i+1);
                this.buttons_info[i].Size = new System.Drawing.Size(120, 100);
                this.buttons_info[i].Text = "Szczegóły";
                this.buttons_info[i].UseVisualStyleBackColor = true;
                this.buttons_info[i].Click += (sender, e) => this.button_show_info(info);

                if (i+1 % 2 == 0)
                {
                    this.labels_info[i].TabIndex = i+1;
                } 
                else
                {
                    this.buttons_info[i].TabIndex = i+1;
                }
            }
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Font = new System.Drawing.Font("Microsoft Sans Serif", 24F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(204)));
            this.label1.Location = new System.Drawing.Point(12, 15);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(141, 20);
            this.label1.TabIndex = 0;
            this.label1.Text = "Lista samochodów";
            // 
            // Okno_pokaz_wszystkie
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(400, 400);

            this.Controls.Add(this.label1);

            for(int i = 0; i < samochody.Length; i++)
            {
                this.Controls.Add(this.labels_info[i]);
                this.Controls.Add(this.buttons_info[i]);
            }

            this.Name = "Okno_pokaz_wszystkie";
            this.Text = "Dostepne samochody";
            this.AutoScroll = true;
            this.ResumeLayout(false);
            this.PerformLayout();
        }

        #endregion

        private System.Windows.Forms.Label label1;

        private System.Windows.Forms.Label[] labels_info;
        private System.Windows.Forms.Button[] buttons_info;
    }
}