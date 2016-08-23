using System;
using System.Collections.Generic;
using System.Text;
using System.IO;
namespace Zatty.Utils
{
    class SettingsFile
    {
        public static string path = "settings.dat";
        public static void Save(string sendkey, string enckey, string authkey)
        {
            File.WriteAllText(path, sendkey + Environment.NewLine + enckey + Environment.NewLine + authkey);
        }

        public static List<string> Read()
        {
            if (File.Exists(path))
            {
                List<string> result = new List<string>();
                string line;
                System.IO.StreamReader file = new System.IO.StreamReader(path);
                while ((line = file.ReadLine()) != null)
                {
                    result.Add(line);
                }
                file.Close();
                return result;
            }
            return null;

        }
    }
}
