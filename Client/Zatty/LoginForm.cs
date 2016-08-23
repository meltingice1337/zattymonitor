using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;
using Zatty.Utils;
using Zatty.Models;
using System.Threading;
using Zatty.Core;
using System.Runtime.InteropServices;
using System.Media;
namespace Zatty
{
    public partial class LoginForm : Form
    {
        public const int WM_NCLBUTTONDOWN = 0xA1;
        public const int HT_CAPTION = 0x2;

        [DllImportAttribute("user32.dll")]
        public static extern int SendMessage(IntPtr hWnd, int Msg, int wParam, int lParam);
        [DllImportAttribute("user32.dll")]
        public static extern bool ReleaseCapture();
        public LoginForm()
        {
            InitializeComponent();
        }

        private void btnLogin_Click(object sender, EventArgs e)
        {
            if (txtEmail.Text == "" || txtPassword.Text == "")
            {
                MessageBox.Show("You cannot leave Email or Password empty !", Constants.appName);
                return;
            }

                LoginResponse LR = Api.GetLogin(txtEmail.Text, txtPassword.Text);
                if (LR.status != 200)
                    MessageBox.Show(LR.message, Constants.appName);
                else
                {
                    this.Invoke((MethodInvoker)delegate
                    {
                        SettingsFile.Save(LR.send_key, LR.enc_key, LR.auth_key);
                    });

                    this.Hide();
                    new MainForm(true).ShowDialog();
                }
        }
        protected override void OnPaint(PaintEventArgs e)
        {
            base.OnPaint(e);
            Color color = (Color)ColorTranslator.FromHtml("#4e93f4");
            //custom painting here...
            SolidBrush brush = new SolidBrush(color);
            e.Graphics.FillRectangle(brush, new Rectangle { Width = this.Width, Height = 25, X = 0, Y = 0 });
        }
        private void LoginForm_MouseDown(object sender, MouseEventArgs e)
        {
            if (e.Button == MouseButtons.Left)
            {
                ReleaseCapture();
                SendMessage(Handle, WM_NCLBUTTONDOWN, HT_CAPTION, 0);
            }
        }

        private void LoginForm_Load(object sender, EventArgs e)
        {

        }

        private void button1_Click(object sender, EventArgs e)
        {
            Application.Exit();
        }
    }
}
