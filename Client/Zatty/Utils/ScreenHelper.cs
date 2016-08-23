using System;
using System.Collections.Generic;
using System.Text;
using System.Drawing;
using System.Drawing.Imaging;
using System.Windows.Forms;
using System.IO;
namespace Zatty.Utils
{
    class ScreenHelper
    {
        private const int SRCCOPY = 0x00CC0020;

        private static Bitmap CaptureScreen(int screenNumber)
        {
            Rectangle bounds = GetBounds(screenNumber);
            Bitmap screen = new Bitmap(bounds.Width, bounds.Height, PixelFormat.Format32bppPArgb);

            using (Graphics g = Graphics.FromImage(screen))
            {
                IntPtr destDeviceContext = g.GetHdc();
                IntPtr srcDeviceContext = NativeMethods.CreateDC("DISPLAY", null, null, IntPtr.Zero);

                NativeMethods.BitBlt(destDeviceContext, 0, 0, bounds.Width, bounds.Height, srcDeviceContext, bounds.X,
                    bounds.Y, SRCCOPY);

                NativeMethods.DeleteDC(srcDeviceContext);
                g.ReleaseHdc(destDeviceContext);
            }

            return screen;
        }
        public static string TakeScreenshot()
        {
            return Convert.ToBase64String(ImageToByte(CaptureScreen(0)));
        }
        private static byte[] ImageToByte(Image img)
        {
            byte[] byteArray = new byte[0];
            using (MemoryStream stream = new MemoryStream())
            {
                img.Save(stream, System.Drawing.Imaging.ImageFormat.Png);
                stream.Close();

                byteArray = stream.ToArray();
            }
            return byteArray;
        }
        public static Rectangle GetBounds(int screenNumber)
        {
            return Screen.AllScreens[screenNumber].Bounds;
        }
    }
}
