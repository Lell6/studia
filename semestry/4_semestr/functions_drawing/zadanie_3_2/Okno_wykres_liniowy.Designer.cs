using System.Windows.Forms.DataVisualization.Charting;

namespace zadanie_3_2
{
    partial class Okno_wykres_liniowy
    {
        /// <summary>
        ///  Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        ///  Clean up any resources being used.
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

        /// <summary>
        ///  Required method for Designer support - do not modify
        ///  the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            SuspendLayout();
            Label wykres = new Label();
            // 
            // chart1
            // 
            Chart chart1 = new Chart();
            chart1.Size = new System.Drawing.Size(800, 800);

            ChartArea chartArea = new ChartArea();

            chartArea.AxisX.Title = "Oś OX";
            chartArea.AxisY.Title = "Oś OY";

            chartArea.AxisX.Interval = 1; // Step value for the X axis
            chartArea.AxisY.Interval = 10; // Step value for the Y axis

            chart1.ChartAreas.Add(chartArea);

            double minX = -15;
            double maxX = 15;
            double step = 1;

            Series series = new Series("Linear Function");
            series.ChartType = SeriesChartType.Line;


            for (double x = minX; x <= maxX; x += step)
            {
                double y = wart_a * x + wart_b;
                DataPoint point = new DataPoint(x, y);
                point.Label = $"({x}; {y})";
                series.Points.Add(point);
            }

            chart1.Dock = DockStyle.Fill; // Fill the available space in the form
            chart1.Anchor = AnchorStyles.Top | AnchorStyles.Bottom | AnchorStyles.Left | AnchorStyles.Right; // Anchor to all sides

            // 
            // Form1
            // 
            AutoScaleDimensions = new SizeF(8F, 20F);
            AutoScaleMode = AutoScaleMode.Font;
            ClientSize = new Size(800, 800);

            chart1.Series.Add(series);
            Controls.Add(chart1);

            Name = "Form1";
            Text = "Wykres liniowy";
            ((System.ComponentModel.ISupportInitialize)chart1).EndInit();
            ResumeLayout(false);
        }

        #endregion

        private System.Windows.Forms.DataVisualization.Charting.Chart chart1;
        private Label wykres;
    }
}
