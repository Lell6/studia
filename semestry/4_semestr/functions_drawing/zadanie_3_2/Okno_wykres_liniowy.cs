
namespace zadanie_3_2
{
    public partial class Okno_wykres_liniowy : Form
    {
        float wart_a;
        float wart_b;

        public Okno_wykres_liniowy(float wart_a, float wart_b)
        {
            this.wart_a = wart_a;
            this.wart_b = wart_b;

            InitializeComponent();
        }
    }
}
