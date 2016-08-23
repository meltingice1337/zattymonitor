using System;
using System.Collections.Generic;
using System.Text;

namespace Zatty.Models
{
    public class LoginResponse
    {
        public int status { get; set; }
        public string message { get; set; }
        public string enc_key { get; set; }
        public string auth_key { get; set; }
        public string send_key { get; set; }
    }
}
