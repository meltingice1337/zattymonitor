using System;
using System.Collections.Generic;
using System.Text;
using System.Security.Cryptography;
using System.IO;
using System.Runtime.InteropServices;
using Zatty.Utils;
namespace Zatty.Security
{
    class Encryption
    {
        public static string Encrypt(string text, string eKey, string aKey)
        {

            byte[] ciphertext = null;
            byte[] data = Encoding.Default.GetBytes(text);
            byte[] IV = GenerateIV();
            var Key = GenerateKey(eKey, IV, Constants.kIterations);
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
                    using (var cs = new CryptoStream(ms, AES.CreateEncryptor(), CryptoStreamMode.Write))
                    {
                        cs.Write(data, 0, data.Length);
                        cs.Close();
                    }
                    ciphertext = ms.ToArray();
                }
            }
            byte[] ivm = new Byte[Constants.kIVSize + ciphertext.Length];
            Buffer.BlockCopy(IV, 0, ivm, 0, Constants.kIVSize);
            Buffer.BlockCopy(ciphertext, 0, ivm, Constants.kIVSize, ciphertext.Length);
            byte[] result = Autenthication.SignMessage(aKey, ivm);
            return Convert.ToBase64String(result);
        }

        private static byte[] GenerateKey(string password, byte[] salt, int iterationCount)
        {
            byte[] hashValue;
            var valueToHash = string.IsNullOrEmpty(password) ? string.Empty : password;
            var pbkdf2 = new Rfc2898DeriveBytes(valueToHash, salt, iterationCount);

            hashValue = pbkdf2.GetBytes(32);
            return hashValue;
        }
        private static byte[] GenerateIV()
        {
            int length = 32;
            byte[] randBytes =  new byte[length];
            RNGCryptoServiceProvider rand = new RNGCryptoServiceProvider();
            rand.GetBytes(randBytes);
            return randBytes;
        }
    }
}