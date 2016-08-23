using System;
using System.Collections.Generic;
using System.Text;
using Microsoft.Win32;
using System.Windows.Forms;
namespace Zatty.Utils
{
    class Startup
    {
       public static void AddToStartup()
        {
            RegistryKey add = Registry.CurrentUser.OpenSubKey("SOFTWARE\\Microsoft\\Windows\\CurrentVersion\\Run", true);
            add.SetValue("Zatty Monitor",  Application.ExecutablePath.ToString());
        }
    }
}
