using System;
using System.Collections.Generic;
using System.Text;
using System.Security.Cryptography;
using System.IO;
using System.Runtime.InteropServices;
using Zatty.Utils;
namespace Zatty.Security
{
    class Decryption
    {
        public static string Decrypt(string text, string eKey)
        {
            byte[] result = null;
            byte[] T = Convert.FromBase64String(text);
            byte[] IV = GetIV(T);
            byte[] data = GetChipertext(T);
            var Key = GenerateKey(eKey, IV,Constants.kIterations);
            using (MemoryStream ms = new MemoryStream())
            {
                using (RijndaelManaged AES = new RijndaelManaged())
                {
                    AES.Padding = Constants.kPaddingMode;
                    AES.Mode = Constants.kCipherMode;
                    AES.KeySize = Constants.keKeySize;
                    AES.BlockSize = Constants.kBlockSize;
                    AES.Key = Key;
                    AES.IV = IV;
                    using (var cs = new CryptoStream(ms, AES.CreateDecryptor(), CryptoStreamMode.Write))
                    {
                        cs.Write(data, 0, data.Length);
                        cs.Close();
                    }
                    result = ms.ToArray();
                }
            }

            return Encoding.UTF8.GetString(result);
        }

        private static byte[] GenerateKey(string password, byte[] salt, int iterationCount)
        {
            byte[] hashValue;
            var valueToHash = string.IsNullOrEmpty(password) ? string.Empty : password;
            var pbkdf2 = new Rfc2898DeriveBytes(valueToHash, salt, iterationCount);
                hashValue = pbkdf2.GetBytes(32);
            return hashValue;
        }
        private static byte[] GetIV(byte[] data)
        {
            byte[] result = new byte[Constants.kIVSize];
            Buffer.BlockCopy(data, Constants.kaKeySize, result, 0, Constants.kIVSize);
            return result;
        }
        private static byte[] GetChipertext(byte[] data)
        {
            byte[] result = new byte[data.Length - Constants.kIVSize - Constants.kaKeySize];
            Buffer.BlockCopy(data, Constants.kIVSize + Constants.kaKeySize, result, 0, data.Length - Constants.kIVSize - Constants.kaKeySize);
            return result;
        }
    }
}