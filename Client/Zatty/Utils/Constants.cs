using System;
using System.Collections.Generic;
using System.Text;
using System.Security.Cryptography;
using System.IO;
namespace Zatty.Utils
{
    class Constants
    {
        public const PaddingMode kPaddingMode = PaddingMode.PKCS7;
        public const CipherMode kCipherMode = CipherMode.CBC;
        public const int keKeySize = 256;
        public const int kBlockSize = 256;
        public const int kIVSize = 32;
        public const int kaKeySize = 32;
        public const int kIterations = 1000;

        public const string appName = "ZattyMonitor";

        public const string LoginApi = "https://zattymonitor.hrfp.ch/api/login";
        public const string CommandApi = "https://zattymonitor.hrfp.ch/api/send";
    }
}
