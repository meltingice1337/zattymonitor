/*
 * Created by SharpDevelop.
 * User: Admin
 * Date: 10/6/2015
 * Time: 9:05 AM
 * 
 * To change this template use Tools | Options | Coding | Edit Standard Headers.
 */
using System;
using System.Windows.Forms;
using Zatty.Utils;
namespace Zatty
{
	/// <summary>
	/// Class with program entry point.
	/// </summary>
	internal sealed class Program
	{
		/// <summary>
		/// Program entry point.
		/// </summary>
		[STAThread]
		private static void Main(string[] args)
		{
			Application.EnableVisualStyles();
			Application.SetCompatibleTextRenderingDefault(false);

            var data = SettingsFile.Read();
            if(data == null)
            {
                Application.Run(new LoginForm());
            }
            else
            {
                Application.Run(new MainForm(false));
            }
		}
		
	}
}
