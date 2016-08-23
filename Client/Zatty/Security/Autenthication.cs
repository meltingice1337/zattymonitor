using System;
using System.Collections.Generic;
using System.Text;
using System.Security.Cryptography;
using System.Runtime.InteropServices;
using Zatty.Utils;
namespace Zatty.Security
{
    class Autenthication
    {

        public static bool VerifyMessage(string authKey, string m)
        {
            try
            {
                byte[] data = Convert.FromBase64String(m);
                byte[] key = Encoding.Default.GetBytes(authKey);
                byte[] hmac = ExtractHmac(data);
                byte[] message = ExtractMessage(data);
                byte[] computedHmac = HashHMAC(key, message);
                return ByteArrayCompare(computedHmac, hmac);
            }
            catch (Exception ex)
            {
                return false;
            }
        }
        public static byte[] SignMessage(string authKey, byte[] m)
        {
            byte[] key = Encoding.Default.GetBytes(authKey);
            byte[] result = new byte[m.Length + Constants.kaKeySize];
            Buffer.BlockCopy(m, 0, result, 32, m.Length);
            byte[] hmac = HashHMAC(key, m);
            Buffer.BlockCopy(hmac, 0, result, 0, 32);
            return result;
        }
        private static byte[] ExtractHmac(byte[] d)
        {
            byte[] result = new byte[Constants.kaKeySize];
            Buffer.BlockCopy(d, 0, result, 0, Constants.kaKeySize);
            return result;
        }
        private static byte[] ExtractMessage(byte[] d)
        {
            byte[] result = new byte[d.Length - 32];
            Buffer.BlockCopy(d, 32, result, 0, d.Length - 32);
            return result;
        }
        private static byte[] HashHMAC(byte[] key, byte[] message)
        {
            var hash = new HMACSHA256(key);
            return hash.ComputeHash(message);
        }
        private static bool ByteArrayCompare(byte[] b1, byte[] b2)
        {
            return NativeMethods.memcmp(b1, b2, b1.Length) == 0;
        }
    }
}
