using System;
using System.Collections.Generic;
using System.Text;
using System.Net;
using System.Reflection;
using System.IO;
using System.Web;
using Zatty.Models;
using Zatty.Utils;
namespace Zatty.Core 
{
    class Api
    {
       public static LoginResponse GetLogin(string email, string password)
        {
           using(WebClient wc = new WebClient())
           {
               try
               {
                   string data = wc.DownloadString(string.Format(Constants.LoginApi + "?email={0}&password={1}", email, password));
                   var deserliazed_data = fastJSON.JSON.ToObject<LoginResponse>(data);
                   deserliazed_data.status = 200;
                   return deserliazed_data;
               }
               catch( WebException ex)
               {
                   string data =  new StreamReader(ex.Response.GetResponseStream()).ReadToEnd();
                   try
                   {
                       var deserliazed_data = fastJSON.JSON.ToObject<LoginResponse>(data);
                       deserliazed_data.status = (int)((HttpWebResponse)ex.Response).StatusCode;
                       return deserliazed_data;
                   }
                   catch { 
                       return new LoginResponse { status = 500 , message = "No internet connection"};
                   }
               }
           }
        }

       public static SendResponse Send(string type, string data, string sendKey, string eKey, string aKey)
       {
           using (WebClient wc = new WebClient())
           {
               try
               {
                   var json = new Send { type = type, data = data };

                   string encrpytedMessage = Security.Encryption.Encrypt(fastJSON.JSON.ToJSON(json), eKey, aKey);
                   string paramaters = "key=" + sendKey + "&data=" +HttpUtility.UrlEncode(encrpytedMessage);

                   wc.Headers[HttpRequestHeader.ContentType] = "application/x-www-form-urlencoded";
                   string response = wc.UploadString(Constants.CommandApi, paramaters);

                   if (!Security.Autenthication.VerifyMessage(aKey, response))
                       return new SendResponse { status = 500, message = "The message has been tampered with" };
                   var decMessage = Security.Decryption.Decrypt(response, eKey);
                   try
                   {
                       var deserliazed_response = fastJSON.JSON.ToObject<SendResponse>(Security.Decryption.Decrypt(response, eKey));
                       deserliazed_response.status = 200;
                       return deserliazed_response;
                   }
                   catch
                   {
                       return new SendResponse { status = 500, message = "The message cannot be decrypted" };
                   }
               }
               catch (WebException ex)
               {
                   string response = new StreamReader(ex.Response.GetResponseStream()).ReadToEnd();
                   try
                   {
                       if (!Security.Autenthication.VerifyMessage(aKey, response))
                           return new SendResponse { status = 500, message = "The message has been tampered with" };
                       try
                       {
                           var deserliazed_response = fastJSON.JSON.ToObject<SendResponse>(Security.Decryption.Decrypt(response, eKey));

                           deserliazed_response.status = (int)((HttpWebResponse)ex.Response).StatusCode;
                           return deserliazed_response;
                       }
                       catch
                       {
                           return new SendResponse { status = 500, message = "The message cannot be decrypted" };
                       }
                   }
                   catch
                   {
                       return new SendResponse { status = 500, message = "No internet connection" };
                   }
               }
           }
       }
    }
}
