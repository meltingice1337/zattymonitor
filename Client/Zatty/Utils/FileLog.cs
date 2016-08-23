using System;
using System.Collections.Generic;
using System.Text;
using System.IO;
namespace Zatty.Utils
{
    class FileLog
    {
        public static string path = "log.dat";
        public static void Save(Activity e)
        {
            File.AppendAllText(path, fastJSON.JSON.ToJSON(e) + Environment.NewLine);
        } 
        public static void Delete()
        {
            File.Delete(path);
        }
        public static List<Activity> Read()
        {
            if (File.Exists(path))
            {
                List<Activity> result = new List<Activity>();
                string line;
                System.IO.StreamReader file = new System.IO.StreamReader(path);
                while ((line = file.ReadLine()) != null)
                {
                    result.Add(fastJSON.JSON.ToObject<Activity>(line));
                }
                file.Close();
                return result;
            }
            return null;
        }
    }
}
