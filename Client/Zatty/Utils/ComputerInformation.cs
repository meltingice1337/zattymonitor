using System;
using System.Collections.Generic;
using System.Text;
using System.Management;
namespace Zatty.Utils
{
    class ComputerInformation
    {
        public static string ComputerName()
        {
            return System.Environment.MachineName;
        }
        public static string GetOSName()
        {
            string result = string.Empty;
            ManagementObjectSearcher searcher = new ManagementObjectSearcher("SELECT Caption FROM Win32_OperatingSystem");
            foreach (ManagementObject os in searcher.Get())
            {
                result = os["Caption"].ToString();
                break;
            }
            return result + GetCpuArch();
        }
        private static string GetCpuArch()
        {
            ManagementScope scope = new ManagementScope();
            ObjectQuery query = new ObjectQuery("SELECT Architecture FROM Win32_Processor");
            ManagementObjectSearcher search = new ManagementObjectSearcher(scope, query);
            ManagementObjectCollection results = search.Get();

            ManagementObjectCollection.ManagementObjectEnumerator e = results.GetEnumerator();
            e.MoveNext();
            ushort arch = (ushort)e.Current["Architecture"];

            switch (arch)
            {
                case 0:
                    return "x86";
                case 1:
                    return "MIPS";
                case 2:
                    return "Alpha";
                case 3:
                    return "PowerPC";
                case 6:
                    return "Itanium";
                case 9:
                    return "x64";
                default:
                    return "";
            }
        }
    }
}
