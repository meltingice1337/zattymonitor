using System;
using System.Diagnostics;
using System.Timers;
using System.Collections.Generic;
using System.Runtime.InteropServices;
using System.Drawing;
using System.IO;
using Zatty.Utils;
public class ActivtyRecord
{


    public delegate void newEntryHandler(Activity e);
    public event newEntryHandler newEntry;
 

    private const int kInterval = 1 * 1000;
	public List<Activity> activities;
    private Timer tim;

    public ActivtyRecord()
    {
        tim = new Timer(); 
        activities = new List<Activity>();
        tim.Interval = kInterval;
        tim.Elapsed +=tim_Elapsed;
    }

    void tim_Elapsed(object sender, ElapsedEventArgs e)
    {
 
        var act = getActivity(NativeMethods.GetForegroundWindow(), DateTime.Now.ToString());
        if(act != null)
        {
            newEntry(act);
        }
    }

    public void Start()
    {
        tim.Start();
    }


    private Activity getActivity(IntPtr w, string t)
    {
        Process[] processes = Process.GetProcesses();
        foreach (Process process in processes)
        {
            if (process.MainWindowHandle == w)
            {
                return (new Activity { processName = process.ProcessName, time = t, windowName = process.MainWindowTitle });

            }
        }
        return null;
    }
    private byte[] IconToBytes(Icon icon)
    {
        using (MemoryStream ms = new MemoryStream())
        {
            icon.Save(ms);
            return ms.ToArray();
        }
    }
}