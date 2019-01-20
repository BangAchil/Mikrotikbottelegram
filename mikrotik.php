<?php
//=====================================================PLEASE NOT TO BE DELETED====================================================//

/*
 *  Base Code   : Banghasan
 *  moded    	  : BangAchil
 *  Email     	: kesumaerlangga@gmail.com
 *  Telegram  	: @bangachil
 *
 *  Name      	: Mikrotik bot telegram - php
 *  Fungsi    	: Monitoring mikortik api (Edit Rule Comingsoon )
 *  Pembuatan 	: November 2018
 *  version     : 3.1.0   last 1.0.0, 1.2.0, 1.3.0,  3.0.0
 *  Thnks to Banghasan
 *  ____________________________________________________________
*/

//=====================================================PLEASE NOT TO BE DELETED====================================================//

  //////// //////////PORT SERVICE API HARUS ENABLE DAN DEFAULT//////////////////////

 /*
 Command avalibe update time
 * /address
 * /pool
 * /ping
 * /Dhcp
 * /monitor
 * /traffic
 * /dns
 * /hotspot (aktif)(user)
 * /resource
 * /interface or (bride)
 *NEW
 * /neighbor
 * /ipbinding
 * +user
 * /remove user
 * other comingsoon
 */

 //Yang baru download silahkan ikuti langkah langkah berikut

 /************************************************************************************
 * ** methode long poolling** *
 * Perisapkan Sebuah PC atau sebuah vps
 * OS windows Linux other
 * Internet
 * InstalL Apliaksi WEBSERVER (OS WINSOWS XAMPP, APPSERV )
 * Copy file zip ini didalam sebuah folder root www/htdocs ()
 * extrack file
 * edit file data.json dengan notepad++ (recom) atau notepade
 * edit iprouter username dan pasword
 * Kemudian simpan
 * edit file mikrotik.php
 * edit token bot dan username bot
 * Kemudian simpan
 * Anda bisa langsung menjalankan bot
 * dengan cara menggunakan CMD bukan membukanya melalui webbrowser
 * Langkah - Langkah Running bot
 *   * Masuk ke tempat file mikbotam berada
 *   * tekan CTRL + klik kanan maouse
 *   * Kemudian sort cousor ke Open command window here
 *   * Muncul window CMD
 *   * Run bot dengan Mengetikan php mikrotik.php Kemudian Enter atau $ mikrotik.php
 *   * Jika anda melihat sebuah text
 *             FrameBot version 1.5
 *             Mode    : Long Polling
 *                 Debug   : ON
 *   * Selamat Bot anda berjalan
 * jika error pastikan komputer terhubung ke internet dan dapat melakukan ping ke mikrotik
 * Edit file mikrotik.php sesuai Kebutuhan anda happy coding
 *
 *****************************************************************************/

 /*
  * ** methode webhook hosting ** *
Persiapan Webhook

1.    Router wajib ip public /DNS cloud

2.    Hosting/Domain/ Cpanel / Web service

3.    Hosting dan domain WAJIB memiliki SSL

4.    Apa itu SSL https://telegra.ph/Pengertian-SSL-dan-Cara-Kerja-SSL-12-30

5.    Pastikan hosting Bisa terkoneksi dengan mirotik

6.    Baca Tutorial Buat Bot https://telegra.ph/MIKROTIK-Bot-Telegram-01-05

7.    Simpan Token Bot

8.   Extrack File yang di Download tadi

9.   Edit File Mikrotik.php Dan Data.json Mengunkan notepade ++ atau sejenisnya

10.  Jika sudah diedit
11.  Upload Seluruh Folder bot ke hosting

12.  Saatnya menjalankan Bot

13.  Menjalankannya Tidak seperti long polling

14.  Cukup dengan set webhook ke api.telegram.org

15.  Dengan cara. https://api.telegram.org/botTOKENBOTANDAINIWAJIBDIGANTI/setWebhook?url=URLDIMANA BOTMIKROTIK.PHPBERADA

Contoh :

https://api.telegram.org/botsHgGbgHhTRdCcDFfFDcFfdEwWsXcvVBhJujYt/setWebhook?url=https://mywebpagetorespondtobot/mikrotik.php .

16.  Jika muncul webhook was set

17.  Bot sudah memiliki engine
 */






require_once 'src/FrameBot.php';
$bot = new FrameBot('TOKEN_BOT', 'BOT_USERNAME');   //Ganti sesuai dengan token dan username bot

require_once ('formatbytesbites.php');
require_once ('routeros_api.class.php');




// /DHCP lease command
$bot->cmd('/dhcp|/Dhcp|!Dhcp', function ($dhcp){
               $json = file_get_contents("data.json");
    $json_a = json_decode($json, TRUE);
     //============ Tidak boleh diubah!!!
    $mikrotik_ip = $json_a['ipaddress'];
    $mikrotik_username = $json_a['user'];
    $mikrotik_password = $json_a['password'];
    //====================================!!!
    $API = new routeros_api();
    if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password)) {

    if($dhcp=='lease'){
        $getlease = $API->comm("/ip/dhcp-server/lease/print");
        $TotalReg = count($getlease);
        $countlease = $API->comm("/ip/dhcp-server/lease/print", array(
            "count-only" => "",
        ));
        if ($countlease < 2) {
            echo "$countlease item";
        }
        else if ($countlease > 1) {
            $data1.= "$countlease items";
        };
        $data.= "<b> 🏷 Daftar DHCP Total : $TotalReg </b>\n\n";
        for ($i = 0; $i < $TotalReg; $i++) {
            $lease = $getlease[$i];
            $id = $lease['.id'];
            $addr = $lease['address'];
            $maca = $lease['mac-address'];
            $server = $lease['server'];
            $aaddr = $lease['active-address'];
            $amaca = $lease['active-mac-address'];
            $ahostname = $lease['host-name'];
            $host = str_replace("android", "AD", $ahostname);
            $status = $lease['status'];
            if ($lease['dynamic'] == "true") {
                $dy = "🎯Dynamic";
            }
            else {
                $dy = "📝Static";
            }
            $data.= "🔎 Dhcp to $addr \n  ";
            $data.= "┠  <code>$dy</code>  \n";
            $data.= "  ┠ <b>IP</b>       : <code>$addr</code>\n";
            $data.= "  ┠ <b>Mac</b>     :  <code>$maca</code>\n";
            $data.= "  ┠ <b>DHCP</b>   :  <code>$server</code>\n";
            $data.= "  ┗ <b>HOST</b>   :  <code>$host</code>\n";
            $data.= "\n ";
        }
         }else if($dhcp=='server'){
     $ARRAY = $API->comm("/ip/dhcp-server/print");
    $datatext.= "DHCP SERVER LIST\n\n";
    //kumpulkan data
    $num = count($ARRAY);
    for ($i = 0; $i < $num; $i++) {
        $name = $ARRAY[$i]['name'];
        $interface = $ARRAY[$i]['interface'];
        $lease = $ARRAY[$i]['lease-time'];
        $bootp = $ARRAY[$i]['bootp-support'];
        $authoritative = $ARRAY[$i]['authoritative'];
        $use_radius = $ARRAY[$i]['use-radius'];
        $dynamic = $ARRAY[$i]['dynamic'];
        $disable = $ARRAY[$i]['disabled'];


        $data.= "\n";
        $data.= "📋 Dhcp Server\n";
        $data.= "┠Nama :$name\n";
        $data.= "┠Interface :$interface \n";
        $data.= "┠lease-time :$lease \n";
        $data.= "┠bootp-support :$bootp \n";
        $data.= "┠authoritative :$authoritative \n";
        $data.= "┠use-radius :$use_radius \n";
        if ($dynamic == "true") {
            $data .= "┠Dynamic : Iya \n";
        } else {
            $data .= "┠Dynamic : Tidak \n";
        }
        if ($disable == "true") {
            $data .= "┗Status: ⚠ Disable\n";
        } else {
            $data .= "┗Status : ✔ Enable \n";
        }
      }

      } else {
                $texta = "Server or lease";
                $keyboard = [['!Dhcp server', '!Dhcp lease'], ['Help', 'Sembunyikan'], ];
                $replyMarkup = ['keyboard' => $keyboard, 'resize_keyboard' => true, 'setOneTimeKeyboard' => true, 'selective' => true];
                $anu['reply_markup'] = json_encode($replyMarkup);
                Bot::sendMessage($texta, $anu);
            }
        } else {
            $data = "Tidak dapat Terhubung dengan Mikrotik Coba Kembali";
        }
        $replyMarkup = ['keyboard' => [], 'remove_keyboard' => true, 'selective' => false, ];
        $anu['reply_markup'] = json_encode($replyMarkup);
        return Bot::sendMessage($data, $anu);

});
//dns command
$bot->cmd('/dns|/Dns', function () {


    $json = file_get_contents("data.json");
    $json_a = json_decode($json, TRUE);

    //============ Tidak boleh diubah!!!
    $mikrotik_ip = $json_a['ipaddress'];
    $mikrotik_username = $json_a['user'];
    $mikrotik_password = $json_a['password'];
    //====================================!!!

    $API = new routeros_api();
    if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password)) {
        $ARRAY = $API->comm("/ip/dns/print");
    $Ipserver = $ARRAY[0]['servers'];
    $dyserver = $ARRAY[0]['dynamic-servers'];
    $Allow = $ARRAY[0]['allow-remote-requests'];
    $cache = $ARRAY[0]['cache-used'];

    $text.= "🌏 DNS\n";
    $text.= "┠ Server :$Ipserver\n";
    $text.= "┠ Dynamic Server :$dyserver\n";
    if ($Allow == "true") {
        $text .= "┠ Allow Remote : Iya \n";
    } else {
        $text .= "┠ Allow Remote : Tidak \n";
    }
    $text.= "┗ Cache Used  :$cache \n";
}else{
    $text="Tidak Terkoneksi Dengan Mikrotik Coba Lagi";

}
    return Bot::sendMessage($text);
});
// /traffic command
$bot->cmd('/traffic|traffic|/Traffic', function () {

     $json = file_get_contents("data.json");
    $json_a = json_decode($json, TRUE);

    //============ Tidak boleh diubah!!!
    $mikrotik_ip = $json_a['ipaddress'];
    $mikrotik_username = $json_a['user'];
    $mikrotik_password = $json_a['password'];
    //====================================!!!

    $API = new routeros_api();

    //traffic ether1
    if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password)) {
        $getinterface = $API->comm("/interface/print");
    $interface = $getinterface[0]['name'];
    $getinterfacetraffic = $API->comm("/interface/monitor-traffic", array(
        "interface" => "ether1",  //traffic ether yang akan kita tampilkan
        "once" => "",

    ));;
    $tx = formatBites($getinterfacetraffic[0]['tx-bits-per-second'],1);
    $rx = formatBites($getinterfacetraffic[0]['rx-bits-per-second'],1);
    if ($maxtx == "" || $maxtx == "0") {
        $mxtx = formatBites(100000000,0);
        $maxtx = "100000000";
    } else {
        $mxtx = formatBites($maxtx,0);
        $maxtx = $maxtx;
    }
    if ($maxrx == "" || $maxrx == "0") {
        $mxrx = formatBites(100000000,0);
        $maxrx = "100000000";
    } else {
        $mxrx = formatBites($maxrx,0);
        $maxrx = $maxrx;
    }
    $Traffic .="Traffic\n";
    $Traffic .="====================\n\n";
    $Traffic .="Traffic ether1\n";
    $Traffic .="TX: <code>$tx / $mxtx </code>\n";
    $Traffic .="RX: <code>$rx / $mxrx </code>\n";
    $Traffic .="====================\n\n";
     //ulanggi lagi ether2
    if ($API->connect($mikrotik_ip, $mikrotik_username,$mikrotik_password))
        $getinterface = $API->comm("/interface/print");
    $interface = $getinterface[0]['name'];
    $getinterfacetraffic = $API->comm("/interface/monitor-traffic", array(
        "interface" => "ether2",  //traffic ether yang akan kita tampilkan
        "once" => "",

    ));;
    $tx = formatBites($getinterfacetraffic[0]['tx-bits-per-second'],1);
    $rx = formatBites($getinterfacetraffic[0]['rx-bits-per-second'],1);
    if ($maxtx == "" || $maxtx == "0") {
        $mxtx = formatBites(100000000,0);
        $maxtx = "100000000";
    } else {
        $mxtx = formatBites($maxtx,0);
        $maxtx = $maxtx;
    }
    if ($maxrx == "" || $maxrx == "0") {
        $mxrx = formatBites(100000000,0);
        $maxrx = "100000000";
    } else {
        $mxrx = formatBites($maxrx,0);
        $maxrx = $maxrx;
    }

    $Traffic .="Traffic ether2\n";
    $Traffic .="TX: <code>$tx / $mxtx </code>\n";
    $Traffic .="RX: <code>$rx / $mxrx </code>\n";
    $Traffic .="====================\n\n";

    //ulanggi lagi ether3
    if ($API->connect($mikrotik_ip, $mikrotik_username,$mikrotik_password))
        $getinterface = $API->comm("/interface/print");
    $interface = $getinterface[0]['name'];
    $getinterfacetraffic = $API->comm("/interface/monitor-traffic", array(
        "interface" => "ether3",   //traffic ether yang akan kita tampilkan
        "once" => "",

    ));;
    $tx = formatBites($getinterfacetraffic[0]['tx-bits-per-second'],1);
    $rx = formatBites($getinterfacetraffic[0]['rx-bits-per-second'],1);
    if ($maxtx == "" || $maxtx == "0") {
        $mxtx = formatBites(100000000,0);
        $maxtx = "100000000";
    } else {
        $mxtx = formatBites($maxtx,0);
        $maxtx = $maxtx;
    }
    if ($maxrx == "" || $maxrx == "0") {
        $mxrx = formatBites(100000000,0);
        $maxrx = "100000000";
    } else {
        $mxrx = formatBites($maxrx,0);
        $maxrx = $maxrx;
    }

    $Traffic .="Traffic ether3\n";
    $Traffic .="TX: <code>$tx / $mxtx </code>\n";
    $Traffic .="RX: <code>$rx / $mxrx </code>\n";
    $Traffic .="====================\n\n";

     //ulangi lagi ether 4
    if ($API->connect($mikrotik_ip, $mikrotik_username,$mikrotik_password))
        $getinterface = $API->comm("/interface/print");
    $interface = $getinterface[0]['name'];
    $getinterfacetraffic = $API->comm("/interface/monitor-traffic", array(
        "interface" => "ether4",   //traffic ether yang akan kita tampilkan
        "once" => "",

    ));;
    $tx = formatBites($getinterfacetraffic[0]['tx-bits-per-second'],1);
    $rx = formatBites($getinterfacetraffic[0]['rx-bits-per-second'],1);
    if ($maxtx == "" || $maxtx == "0") {
        $mxtx = formatBites(100000000,0);
        $maxtx = "100000000";
    } else {
        $mxtx = formatBites($maxtx,0);
        $maxtx = $maxtx;
    }
    if ($maxrx == "" || $maxrx == "0") {
        $mxrx = formatBites(100000000,0);
        $maxrx = "100000000";
    } else {
        $mxrx = formatBites($maxrx,0);
        $maxrx = $maxrx;
    }

    $Traffic .="Traffic ether4\n";
    $Traffic .="TX: <code>$tx / $mxtx </code>\n";
    $Traffic .="RX: <code>$rx / $mxrx </code>\n";
    $Traffic .="====================\n\n";

    //ulangi lagi ether5
    //ulangi lagi ether6 Dst.

}else{
    $Traffic="Tidak dapat terhubung ke mikrotik coba lagi";
}
        $options = [
                   'parse_mode' => 'html',
                   'reply' => true,
                   ];
    return Bot::sendMessage($Traffic, $options);
});
        //ping situs dari router test latency ke luar dan local
        //anda bisa gunakan multi command contoh /ping ping PING Ping
        //*Perbaikan Untuk bugs ping
$bot->cmd('/ping|ping|PING|Ping', function ($address){

        if ($address == NULL) {     ///Your Costum text
            $datas = "\nPing latency\n=======================\nContoh Penggunaan :\n=======================\nping google.com\nping detik.com\nping kompas.com\nping youtube.com\nMasukan Alamat Tidak Boleh pakai http://\n";

            Bot::sendMessage($datas);

        } else if (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/', $address)) {       //*detect ip address jalankan ini
            $texta = "Mohon Ditunggu Permintaan Sedang Diproses";
            Bot::sendMessage($texta);
            $json = file_get_contents("data.json");
            $json_a = json_decode($json, TRUE);

            //============ Tidak boleh diubah!!!
            $mikrotik_ip = $json_a['ipaddress'];
            $mikrotik_username = $json_a['user'];
            $mikrotik_password = $json_a['password'];
            //====================================!!!

            $API = new routeros_api();
            if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password)) {
               $PING = $API->comm("/ping", array(
                "address" => "$address",
                 "count" => "5",)); //*Jumlah ping bisa di tambah atau dikurangi
                $num = count($PING);
                $text = "<b>Ping  $address</b>\n\n";
                for ($i = 0;$i < $num;$i++) {
                    $hot = $PING[$i]['host'];
                    $status = $PING[$i]['status'];
                    $size = $PING[$i]['size'];
                    $ttl = $PING[$i]['ttl'];
                    $time = $PING[$i]['time'];
                    $packet_loss = $PING[$i]['packet-loss'];
                    $avg = $PING[$i]['avg-rtt'];
                    $packet_loss = $PING[$i]['packet-loss'];
                    if ($status == 'timeout') {
                        $text.= "<code>PING $hot \nStatus $status Loss $packet_loss% </code>\n\n";
                    } else {
                        $text.= "<code>PING $hot \nSize $size TTL $ttl \nTime $time AVG $avg</code>\n\n";
                    }
                }
            } else {
                $data = "Tidak Terkoneksi Dengan Mikrotik Coba Lagi";
            }

            return Bot::sendMessage($text);
        } elseif (preg_match('/^[-a-z0-9]+\.[a-z]{2,6}$/', strtolower($address))) {     //*detect domain jalankan ini
            $texta = "Mohon Ditunggu Permintaan Sedang Diproses";
            Bot::sendMessage($texta);
            $json = file_get_contents("data.json");
            $json_a = json_decode($json, TRUE);

            //============ Tidak boleh diubah!!!
            $mikrotik_ip = $json_a['ipaddress'];
            $mikrotik_username = $json_a['user'];
            $mikrotik_password = $json_a['password'];
            //====================================!!!

            $API = new routeros_api();
            if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password)) {
                $PING = $API->comm("/ping", array(
                "address" => "$address",
                 "count" => "5",));//*Jumlah ping bisa di tambah atau dikurangi
                $num = count($PING);
                $text = "<b>Ping  $address</b>\n\n";
                for ($i = 0;$i < $num;$i++) {
                    $hot = $PING[$i]['host'];
                    $status = $PING[$i]['status'];
                    $size = $PING[$i]['size'];
                    $ttl = $PING[$i]['ttl'];
                    $time = $PING[$i]['time'];
                    $packet_loss = $PING[$i]['packet-loss'];
                    $avg = $PING[$i]['avg-rtt'];
                    $packet_loss = $PING[$i]['packet-loss'];
                    if ($status == 'timeout') {
                        $text.= "<code>PING $hot \nStatus $status Loss $packet_loss% </code>\n\n";
                    } else {
                        $text.= "<code>PING $hot \nSize $size TTL $ttl \nTime $time AVG $avg</code>\n\n";
                    }
                }
            } else {
                $text = "Tidak Terkoneksi Dengan Mikrotik Coba Lagi";
            }
                $options = ['parse_mode' => 'html', ];
                return Bot::sendMessage($text, $options);
        }
});
//Paling sulit disini Perulangan from ping ke local untuk memonitoring Acesspoint
$bot->cmd('/Monitor|/monitor|monitor|Monitor', function (){

    $texta = "Mohon ditunggu Permintaan sedang diprosses";
    Bot::sendMessage($texta);

    $json = file_get_contents("data.json");
    $json_a = json_decode($json, TRUE);

    //============ Tidak boleh diubah!!!
    $mikrotik_ip = $json_a['ipaddress'];
    $mikrotik_username = $json_a['user'];
    $mikrotik_password = $json_a['password'];
    //====================================!!!

    $API = new routeros_api();
    if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password)) {
        $PING = $API->comm("/ping", array(
            "address" => "10.150.1.7",  //ip local target ping silahkan dirubah
            "count" => "1",
        ));
        $hot = $PING[0]['host'];
        $status = $PING[0]['status'];
        $size = $PING[0]['size'];
        $ttl = $PING[0]['ttl'];
        $time = $PING[0]['time'];
        $packet_loss = $PING[0]['packet-loss'];
        $avg = $PING[0]['avg-rtt'];
        if ($status == 'timeout') {
            $data = "PING WIFI 1 ⚠ Down \nHost :$hot Status : <b>$status</b> Loss :$packet_loss%";
        }
        else {
            $data = "PING WIFI 1 ✔ Reply  \nHost :$hot Time : <b>$time</b> Loss :$packet_loss%";
        }

        $options = ['parse_mode' => 'html', ];
        Bot::sendMessage($data, $options);   //hasil 1 akan dikirim


        $PING = $API->comm("/ping", array(
            "address" => "10.150.1.6",   //ip local target ping silahkan dirubah
            "count" => "1",
        ));
        $hot = $PING[0]['host'];
        $status = $PING[0]['status'];
        $size = $PING[0]['size'];
        $ttl = $PING[0]['ttl'];
        $time = $PING[0]['time'];
        $packet_loss = $PING[0]['packet-loss'];
        $avg = $PING[0]['avg-rtt'];
        if ($status == 'timeout') {
            $data = "PING WIFI 2 ⚠ Down \nHost :$hot Status : <b>$status</b> Loss :$packet_loss%";
        }
        else {
            $data = "PING WIFI 2  ✔ Reply \nHost :$hot Time : <b>$time</b> Loss :$packet_loss%";
        }

        $options = ['parse_mode' => 'html', ];
        Bot::sendMessage($data, $options);     //hasil 2 akan dikirim

        $PING = $API->comm("/ping", array(
            "address" => "10.150.1.1",   //ip local target ping silahkan dirubah
            "count" => "1",
        ));
        $hot = $PING[0]['host'];
        $status = $PING[0]['status'];
        $size = $PING[0]['size'];
        $ttl = $PING[0]['ttl'];
        $time = $PING[0]['time'];
        $packet_loss = $PING[0]['packet-loss'];
        $avg = $PING[0]['avg-rtt'];
        if ($status == 'timeout') {
            $data = "PING WIFI 3 ⚠ Down  \nHost :$hot Status : <b>$status</b> Loss :$packet_loss%";
        }
        else {
            $data = "PING WIFI 3  ✔ Reply  \nHost :$hot Time : <b>$time</b> Loss :$packet_loss%";
        }

        $options = ['parse_mode' => 'html', ];
        Bot::sendMessage($data, $options);    //hasil3 akan dikirim

        $PING = $API->comm("/ping", array(
            "address" => "10.150.1.2",     //ip local target ping silahkan dirubah
            "count" => "1",
        ));
        $hot = $PING[0]['host'];
        $status = $PING[0]['status'];
        $size = $PING[0]['size'];
        $ttl = $PING[0]['ttl'];
        $time = $PING[0]['time'];
        $packet_loss = $PING[0]['packet-loss'];
        $avg = $PING[0]['avg-rtt'];
        if ($status == 'timeout') {
            $data = "PING WIFI 4 ⚠ Down  \nHost :$hot Status : <b>$status</b> Loss :$packet_loss%";
        }
        else {
            $data = "PING WIFI 4 ✔ Reply  \nHost :$hot Time : <b>$time</b> Loss :$packet_loss%";
        }

        $options = ['parse_mode' => 'html', ];
        Bot::sendMessage($data, $options);      //hasil4 akan dikirim

        $PING = $API->comm("/ping", array(
            "address" => "10.150.1.3",   //ip local target ping silahkan dirubah
            "count" => "1",
        ));
        $hot = $PING[0]['host'];
        $status = $PING[0]['status'];
        $size = $PING[0]['size'];
        $ttl = $PING[0]['ttl'];
        $time = $PING[0]['time'];
        $packet_loss = $PING[0]['packet-loss'];
        $avg = $PING[0]['avg-rtt'];
        if ($status == 'timeout') {
            $data = "PING WIFI 5 ⚠ Down  \nHost :$hot Status : <b>$status</b> Loss :$packet_loss%";
        }
        else {
            $data = "PING WIFI 5 ✔ Reply  \nHost :$hot Time : <b>$time</b> Loss :$packet_loss%";
        }

        $options = ['parse_mode' => 'html', ];
        Bot::sendMessage($data, $options);      //hasil5 akan dikirim

        $PING = $API->comm("/ping", array(
            "address" => "10.150.1.9",      //ip local target ping silahkan dirubah
            "count" => "1",
        ));
        $hot = $PING[0]['host'];
        $status = $PING[0]['status'];
        $size = $PING[0]['size'];
        $ttl = $PING[0]['ttl'];
        $time = $PING[0]['time'];
        $packet_loss = $PING[0]['packet-loss'];
        $avg = $PING[0]['avg-rtt'];
        if ($status == 'timeout') {
            $data = "PING WIFI 6 ⚠ Down  \nHost :$hot Status : <b>$status</b> Loss :$packet_loss%";
        }
        else {
            $data = "PING WIFI 6 ✔ Reply   \nHost :$hot Time : <b>$time</b> Loss :$packet_loss%";
        }

        $options = ['parse_mode' => 'html', ];
        Bot::sendMessage($data, $options);      //hasil6 akan dikirim

        ///====================//*loopinglagi//====================///
        $PING = $API->comm("/ping", array(
            "address" => "10.150.1.8",     //ip local target ping silahkan dirubah
            "count" => "1",
        ));
        $hot = $PING[0]['host'];
        $status = $PING[0]['status'];
        $size = $PING[0]['size'];
        $ttl = $PING[0]['ttl'];
        $time = $PING[0]['time'];
        $packet_loss = $PING[0]['packet-loss'];
        $avg = $PING[0]['avg-rtt'];
        if ($status == 'timeout') {
            $data = "PING WIFI 7 ⚠ Down  \nHost :$hot Status : <b>$status</b> Loss :$packet_loss%";
        }
        else {
            $data = "PING WIFI 7 ✔ Reply  \nHost :$hot Time : <b>$time</b> Loss :$packet_loss%";
        }
        $options = ['parse_mode' => 'html', ];
        Bot::sendMessage($data, $options);  //hasil 7 akan dikirim
        ///====================//*SelesaIlooping//====================///


        //*Jika inggin menambahkan silahkan tambahkan sendiri
       //* Untuk menambahkan copy dari tulisan //*loopinlagi Sampai dengan //*Selesailooping dan pastekan dibawah ini (Sesuaikan kebutuhan )



    }

    else {
        $datas="Tidak dapat Terhubung dengan Mikrotik Coba Kembali";
    }
    $options = ['parse_mode' => 'html', ];
    return Bot::sendMessage($datas, $options);
});

// /interface command    pengunaan ada dua /interface dan /interface bridge
$bot->cmd('/interface|/Interface|!interface', function ($bridge) {
      $json = file_get_contents("data.json");
    $json_a = json_decode($json, TRUE);

    //============ Tidak boleh diubah!!!
    $mikrotik_ip = $json_a['ipaddress'];
    $mikrotik_username = $json_a['user'];
    $mikrotik_password = $json_a['password'];
    //====================================!!!

    $API = new routeros_api();
    if ($bridge == 'Bridge') {
        if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password)) {
            $ARRAY = $API->comm('/interface/bridge/print');
            // kumpulkan data
            $num = count($ARRAY);
            for ($i = 0;$i < $num;$i++) {
                $nama = $ARRAY[$i]['name'];
                $mtu = $ARRAY[$i]['mtu'];
                $Mac_status = $ARRAY[$i]['mac-address'];
                $pro = $ARRAY[$i]['protocol-mode'];
                $run = $ARRAY[$i]['running'];
                $Disable = $ARRAY[$i]['disabled'];
                $text.= "\n";
                $text.= "🚗 Bridge\n";
                $text.= "┠Nama : $nama\n";
                $text.= "┠Mtu : $mtu \n";
                $text.= "┠Mac : $Mac_status \n";
                $text.= "┠Protocol : $pro \n";
                if ($run == "true") {
                    $text.= "┠Active : Iya \n";
                } else {
                    $text.= "┠Active : Tidak \n";
                }
                if ($Disable == "false") {
                    $text.= "┠Disable : Tidak \n";
                } else {
                    $text.= "┠Disable : Iya \n";
                }
                $text.= "┠Disablenow  : hidden  \n";
            }
            $text.= "┗Enablenow : hidden \n";
        } else {
            $text = "Tidak dapat Terhubung dengan Mikrotik Coba Kembali ";
        }
    } else if ($bridge == 'List') {
        if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password)) {
            $ARRAY = $API->comm("/interface/print");
            $num = count($ARRAY);
            for ($i = 0;$i < $num;$i++) {
                $no = $i + 1;
                $ids = $ARRAY[$i]['.id'];
                $dataid = str_replace('*', 'id', $ids);
                $namaport = $ARRAY[$i]['name'];
                $comentport = $ARRAY[$i]['comment'];
                $typeport = $ARRAY[$i]['type'];
                $tx = formatBytes($ARRAY[$i]['rx-byte']);
                $rx = formatBytes($ARRAY[$i]['rx-byte']);
                $true = $ARRAY[$i]['running'];
                $text.= " \n ";
                $text.= "💻 Interface$no \n ";
                //Deteksi
                if ($true == "true") {
                    $text.= " ┠🆙 CONNECT \n";
                } else {
                    $text.= " ┠⚠ DISCONNECT\n";
                }
                $text.= "  ┠Nama : $namaport \n";
                $text.= "  ┠Comment : $comentport  \n";
                $text.= "  ┠Type : $typeport \n";
                $text.= "  ┠Download : $tx\n";
                $text.= "  ┠Upload : $rx\n";
                $text.= "  ┠Disablenow  : /Comingsoon  \n";
                $text.= "  ┗Enablenow : /omingsoon \n";
            }
        } else {
            $text = "Tidak dapat Terhubung dengan Mikrotik Coba Kembali";
        }
    } else {
        $texta = "Interface List or Bridge?";
        $keyboard = [['!interface List', '!interface Bridge'], ['Help', 'Sembunyikan'], ];
        $replyMarkup = ['keyboard' => $keyboard, 'resize_keyboard' => true, 'setOneTimeKeyboard' => true, 'selective' => true];
        $anu['reply_markup'] = json_encode($replyMarkup);
        Bot::sendMessage($texta, $anu);
    }
    $replyMarkup = ['keyboard' => [], 'remove_keyboard' => true, 'selective' => true, ];
    $anu['reply_markup'] = json_encode($replyMarkup);
    return Bot::sendMessage($text, $anu);
});
// /address command
$bot->cmd('/Address|/address', function () {


      $json = file_get_contents("data.json");
    $json_a = json_decode($json, TRUE);

    //============ Tidak boleh diubah!!!
    $mikrotik_ip = $json_a['ipaddress'];
    $mikrotik_username = $json_a['user'];
    $mikrotik_password = $json_a['password'];
    //====================================!!!

    $API = new routeros_api();
     if ($API->connect($mikrotik_ip, $mikrotik_username,$mikrotik_password)) {
        $ARRAY = $API->comm("/ip/address/print");
         $num = count($ARRAY);
         $text .= "Daftar IP Address $num\n";
    for ($i = 0; $i < $num; $i++) {
        $address = $ARRAY[$i]['address'];
        $network = $ARRAY[$i]['network'];
        $interface = $ARRAY[$i]['interface'];
        $dynamic = $ARRAY[$i]['dynamic'];
        $disabled = $ARRAY[$i]['disabled'];

        //ambil data
        $text .= "\n♨  $interface\n";
        $text .= "┠IP address :  $address\n";
        $text .= "┠Network    : $network \n";
        $text .= "┠interface  : $interface \n";
        //pecah kata true

        if ($dynamic == "true") {
            $text .= "┠Dynamic : Iya \n";
        } else {
            $text .= "┠Dynamic : Tidak \n";
        }

        //pecah kata false
        if ($disabled == "false") {
            $text .= "┗Disable : Tidak  \n";
        } else {
            $text .= "┗Disable : Yes  \n";
        }

    }
     }

    return Bot::sendMessage($text);
});

// /hotspot command ada dua command /hotspot aktif  dan/hotspot user
$bot->cmd('Hotspot|hotspot|/hotspot|/Hotspot|!Hotspot', function ($user) {


   $json = file_get_contents("data.json");
    $json_a = json_decode($json, TRUE);

    //============ Tidak boleh diubah!!!
    $mikrotik_ip = $json_a['ipaddress'];
    $mikrotik_username = $json_a['user'];
    $mikrotik_password = $json_a['password'];
    //====================================!!!

$API = new routeros_api();
        if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password)) {
            if ($user == 'aktif') {
                if ($serveractive != "") {
                    $gethotspotactive = $API->comm("/ip/hotspot/active/print", array("?server" => "" . $serveractive . ""));
                    $TotalReg = count($gethotspotactive);
                    $counthotspotactive = $API->comm("/ip/hotspot/active/print", array("count-only" => "", "?server" => "" . $serveractive . ""));
                } else {
                    $gethotspotactive = $API->comm("/ip/hotspot/active/print");
                    $TotalReg = count($gethotspotactive);
                    $counthotspotactive = $API->comm("/ip/hotspot/active/print", array("count-only" => "",));
                }
                $text.= "User Aktif $counthotspotactive item\n\n";
                for ($i = 0;$i < $TotalReg;$i++) {
                    $hotspotactive = $gethotspotactive[$i];
                    $id = $hotspotactive['.id'];
                    $server = $hotspotactive['server'];
                    $user = $hotspotactive['user'];
                    $address = $hotspotactive['address'];
                    $mac = $hotspotactive['mac-address'];
                    $uptime = $hotspotactive['uptime'];
                    $usesstime = $hotspotactive['session-time-left'];
                    $bytesi = formatBytes($hotspotactive['bytes-in'], 2);
                    $byteso = formatBytes($hotspotactive['bytes-out'], 2);
                    $loginby = $hotspotactive['login-by'];
                    $comment = $hotspotactive['comment'];
                    $text.= "👤 User aktif\n";
                    $text.= "┠ID :$id\n";
                    $text.= "┠SERVER :$server\n";
                    $text.= "┠USER :$user\n";
                    $text.= "┠IP :$address\n";
                    $text.= "┠UPTIME:$uptime\n";
                    $text.= "┠B IN :$bytesi\n";
                    $text.= "┠B OUT :$byteso\n";
                    $text.= "┠SESION :$usesstime\n";
                    $text.= "┗LOGIN :$loginby\n \n";
                }
            } elseif ($user == 'user') {
                $ARRAY = $API->comm("/ip/hotspot/user/print");
                $num = count($ARRAY);
                $text = "Total $num User\n\n";
                for ($i = 0;$i < $num;$i++) {
                    $no = $i;
                    $data = $ARRAY[$i]['.id'];
                    $dataid = str_replace('*', 'id', $data);
                    $data1 = $ARRAY[$i]['server'];
                    $data2 = $ARRAY[$i]['name'];
                    $data3 = $ARRAY[$i]['password'];
                    $data4 = $ARRAY[$i]['mac-address'];
                    $data5 = $ARRAY[$i]['profile'];
                    $data6 = $ARRAY[$i]['limit-uptime'];
                    $text.= "👥  ($dataid)\n";
                    $text.= "┣Server :$data1 \n";
                    $text.= "┣Nama : $data2\n";
                    $text.= "┣password : $data3 \n";
                    $text.= "┣mac : $data4\n";
                    $text.= "┣Profil : $data5\n";
                    $text.= "┣limit : $data6\n┗RemoveNow User /rEm0v$dataid\n\n";
                }
            } else {
                $texta = "User list Or aktif";
                $keyboard = [['!Hotspot user', '!Hotspot aktif'], ['Help', 'Sembunyikan'], ];

                $replyMarkup = [

                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'setOneTimeKeyboard' => true,
                'selective' => true,

                ];

                $anu['reply_markup'] = json_encode($replyMarkup);

                Bot::sendMessage($texta, $anu);
            }
        } else {
            $text = "Tidak dapat Terhubung dengan Mikrotik Coba Kembali";
        }
        $replyMarkup = [

         'keyboard' => [],
         'remove_keyboard' => true,
         'selective' => true,

         ];

        $anu['reply_markup'] = json_encode($replyMarkup);
        return Bot::sendMessage($text, $anu);
});
// /resoure command
$bot->cmd('/resource|/Resource', function () {


    $json = file_get_contents("data.json");
    $json_a = json_decode($json, TRUE);

    //============ Tidak boleh diubah!!!
    $mikrotik_ip = $json_a['ipaddress'];
    $mikrotik_username = $json_a['user'];
    $mikrotik_password = $json_a['password'];
    //====================================!!!

    $API = new routeros_api();
     if ($API->connect($mikrotik_ip, $mikrotik_username,$mikrotik_password)) {
         $health = $API->comm("/system/health/print");
         $dhealth = $health['0'];
         $ARRAY = $API->comm("/system/resource/print");
         $first = $ARRAY['0'];
         $memperc = ($first['free-memory']/$first['total-memory']);
         $hddperc = ($first['free-hdd-space']/$first['total-hdd-space']);
         $mem = ($memperc*100);
         $hdd = ($hddperc*100);

         $sehat=$dhealth['temperature'];
         $platform=$first['platform'];
         $board=$first['board-name'];
         $version=$first['version'];
         $architecture=$first['architecture-name'];
         $cpu=$first['cpu'];
         $cpuload=$first['cpu-load'];
         $uptime=$first['uptime'];
         $cpufreq=$first['cpu-frequency'];
         $cpucount=$first['cpu-count'];
         $memory=formatBytes($first['total-memory']);
         $fremem=formatBytes($first['free-memory']);
         $mempersen=number_format($mem,3);
         $hdd=formatBytes($first['total-hdd-space']);
         $frehdd=formatBytes($first['free-hdd-space']);
         $hddpersen=number_format($hdd,3);
         $sector=$first['write-sect-total'];
         $setelahreboot=$first['write-sect-since-reboot'];
         $kerusakan=$first['bad-blocks'];

        $text.="<b>📡 Resource</b>\n";
        $text.="<code>Boardname: $board</code>\n";
        $text.="<code>Platform : $platform</code>\n";
        $text.="<code>Uptime is: $uptime</code>\n";
        $text.="<code>Cpu Load : $cpuload%</code>\n";
        $text.="<code>Cpu type : $cpu</code>\n";
        $text.="<code>Cpu Hz   : $cpufreq Mhz/$cpucount core</code>\n==========================\n";
        $text.="<code>Free memory and memory \n$memory-$fremem/$mempersen %</code>\n==========================\n";
        $text.="<code>Free disk and disk      \n$hdd-$frehdd/$hddpersen %</code>\n==========================\n";
        $text.="<code>Since reboot, bad blocks \n$sector-$setelahreboot/$kerusakan%</code>\n==========================\n";
     }

    $options = ['parse_mode' => 'html', ];
    return Bot::sendMessage($text, $options);
});
 //*New Command /neighbor
$bot->cmd('/neighbor|/Neighbor', function () {
    $info = bot::message();
    $id = $info['chat']['id'];
    $iduser = $info['from']['id'];
    $msgid = $info['message_id'];

        $json = file_get_contents("data.json");
        $json_a = json_decode($json, TRUE);

    //============ Tidak boleh diubah!!!
    $mikrotik_ip = $json_a['ipaddress'];
    $mikrotik_username = $json_a['user'];
    $mikrotik_password = $json_a['password'];
    //====================================!!!
        $API = new routeros_api();
        if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password)) {
            $ARRAY3 = $API->comm("/ip/hotspot/user/print");
            $ARRAY2 = $API->comm("/system/scheduler/print");
            $ARRAY = $API->comm("/ip/neighbor/print");
            $num = count($ARRAY);
            $num2 = count($ARRAY2);
            $num3 = count($ARRAY3);
            for ($i = 0;$i < $num;$i++) {
                $no = $i + 1;
                $interfaces = "<code>" . $ARRAY[$i]['interface'] . "</code>";
                $identity = "<code>" . $ARRAY[$i]['identity'] . "</code>";
                $address = "<code>" . $ARRAY[$i]['address'] . "</code>";
                $mac = "<code>" . $ARRAY[$i]['mac-address'] . "</code>";
                $version = "<code>" . $ARRAY[$i]['version'] . "</code>";
                $uptime = "<code>" . $ARRAY[$i]['uptime'] . "</code>";
                $text.= "👥  $no\n";
                $text.= "┣Interface :  $interfaces \n";
                $text.= "┣Nama : $identity\n";
                $text.= "┣IP address : $address \n";
                $text.= "┣Mac : $mac\n";
                $text.= "┣version :    $version\n";
                $text.= "┗Uptime :     $uptime\n\n";
            }
        } else {
            $text = "Tidak dapat Terhubung dengan Mikrotik Coba Kembali";
        }
        $keyboard[] = [['text' => '💝 Mikrotik Diskusi', 'url' => 'https://t.me/mikrotikuser'], ['text' => '🌏 Tes Bot', 'url' => 'https://t.me/testingbotmikrotik'], ];
        $options = ['parse_mode' => 'html', 'reply_markup' => ['inline_keyboard' => $keyboard], ];
        Bot::sendMessage($text, $options);

});
$bot->cmd('/Pool|/pool', function () {
    $json = file_get_contents("data.json");
    $json_a = json_decode($json, TRUE);

   //============ Tidak boleh diubah!!!
    $mikrotik_ip = $json_a['ipaddress'];
    $mikrotik_username = $json_a['user'];
    $mikrotik_password = $json_a['password'];
    //====================================!!!

    $API = new routeros_api();
    if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password)) {
        $ARRAY = $API->comm("/ip/pool/print");
        // kumpulkan data
        $num = count($ARRAY);
        for ($i = 0;$i < $num;$i++) {
            $namapool = $ARRAY[$i]['name'];
            $rannge = $ARRAY[$i]['ranges'];
            $id = $ARRAY[$i]['.id'];
            $text.= "🎯 \n";
            $text.= "┠Nama :$namapool\n";
            $text.= "┠range:$rannge\n";
            $text.= "┗ID   :$id \n";
        }
    } else {
        $text = "Tidak dapat Terhubung dengan Mikrotik Coba Kembali";
    }
    $keyboard[] = [['text' => '💝 Mikrotik Diskusi', 'url' => 'https://t.me/mikrotikuser'], ['text' => '🌏 Tes Bot', 'url' => 'https://t.me/testingbotmikrotik'], ];
    $options = ['parse_mode' => 'html', 'reply_markup' => ['inline_keyboard' => $keyboard], ];
    return Bot::sendMessage($text, $options);
});

//*New Command ipbinding
$bot->cmd('/ipbinding||/Ipbinding', function () {
    $info = bot::message();
    $id = $info['chat']['id'];
    $iduser = $info['from']['id'];
    $msgid = $info['message_id'];

        $json = file_get_contents("data.json");
        $json_a = json_decode($json, TRUE);

      //============ Tidak boleh diubah!!!
    $mikrotik_ip = $json_a['ipaddress'];
    $mikrotik_username = $json_a['user'];
    $mikrotik_password = $json_a['password'];
    //====================================!!!

        $API = new routeros_api();
        if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password)) {
            $ARRAY = $API->comm('/ip/hotspot/ip-binding/getall');
            $num = count($ARRAY);
            $baris = $ARRAY;
            for ($i = 0;$i < $num;$i++) {
                $no = $i + 1;
                $id = "<code>" . $baris[$i]['.id'] . "</code>";
                $address = "<code>" . $baris[$i]['address'] . "</code>";
                $mac = "<code>" . $baris[$i]['mac-address'] . "</code>";
                $toaddress = "<code>" . $baris[$i]['to-address'] . "</code>";
                $server = "<code>" . $baris[$i]['server'] . "</code>";
                $type = "<code>" . $baris[$i]['type'] . "</code>";
                $comment = "<code>" . $baris[$i]['comment'] . "</code>";
                $disabled = "<code>" . $baris[$i]['disabled'] . "</code>";
                $text.= "👥  $no\n";
                $text.= "┣Address :  $address \n";
                $text.= "┣Mac address :  $mac \n";
                $text.= "┣To address  : $toaddress\n";
                $text.= "┣Server      : $server \n";
                $text.= "┣Type    : $type\n";
                $text.= "┗Disable : $disabled\n\n";
            }
        } else {
            $text = "Tidak dapat Terhubung dengan Mikrotik Coba Kembali";
        }
        $options = ['parse_mode' => 'html', ];
        return Bot::sendMessage($text, $options);

});
 //*Command Nambah user
 /*

  Cara Mengunakannya :

  usagenya
  Command spasi namaserver spasi userprofil spasi usernameuser spasi passworduser

  +user server profil username password

  contoh :

  +user all admin testing testing
  +user hotspot1 admin testing2 testing2

  */
$bot->cmd('+user', function ($server, $username, $password) {
    $info = bot::message();
    $id = $info['chat']['id'];
    $iduser = $info['from']['id'];
    $msgid = $info['message_id'];
    $json = file_get_contents("data.json");
    $json_a = json_decode($json, TRUE);

 //============ Tidak boleh diubah!!!
    $mikrotik_ip = $json_a['ipaddress'];
    $mikrotik_username = $json_a['user'];
    $mikrotik_password = $json_a['password'];
    //====================================!!!

    $API = new routeros_api();
    if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password)) {
        $add_user_api = $API->comm("/ip/hotspot/user/add", array(
        "server"    => $server,
        "name"     => $username,
        "password" => $password,

          ));
        $texta = json_encode($add_user_api);
        if (strpos(strtolower($texta), 'failure: already have user with this name for this server') !== false) {
            $gagal = $add_user_api['!trap'][0]['message'];
            $text.= "⛔ Gagal Menginput user baru pastikan mengisikannya dengan benar \n\n<b>KETERANGAN   :</b>\n$gagal";
        } elseif (strpos(strtolower($texta), 'ambiguous value of server, more than one possible value matches input') !== false) {
            $gagal = $add_user_api['!trap'][0]['message'];
            $text.= "⛔ Gagal Menginput user baru pastikan mengisikannya dengan benar \n\n<b>KETERANGAN   :</b>\n$gagal";
        } elseif (strpos(strtolower($texta), 'input does not match any value of server') !== false) {
            $gagal = $add_user_api['!trap'][0]['message'];
            $text.= "⛔ Gagal Menginput user baru pastikan mengisikannya dengan benar \n\n<b>KETERANGAN   :</b>\n$gagal";
        } else {
            $text.= "Berhasil Diinput\n\n";
            $text.= "<code>ID         : $add_user_api</code>\n";
            $text.= "<code>Server     : $server</code>\n";
            $text.= "<code>Name       : $username</code>\n";
            $text.= "<code>Password   : $password</code>\n";
            $dataid = str_replace('*', 'id', $add_user_api);
            $text.= "RemoveNow   : /rEm0v$dataid\n";
        }
    } else {
        $text = "Tidak dapat Terhubung dengan Mikrotik Coba Kembali";
    }
    $options = ['parse_mode' => 'html', ];
    return Bot::sendMessage($text, $options);
});

//$bot->cmd('!userbyprofil', function ($id) {
 //     //Comingsoon
//    return Bot::sendMessage($text, $options);
//});

//$bot->cmd('!Generate', function () {
    //  Comingsiin
    //   return Bot::sendMessage($text, $options);
    //   $API->disconnect();
//});

//Command Untuk Remove User
 $bot->regex('/^\/rEm0vid/', function ($matches) {
    $mess = Bot::Message();
    $id = $mess['chat']['id'];
    $isi = $mess['text'];
    if ($isi == '/rEm0vid') {
        $text.= "⛔ Gagal dihapus \n\n<b>KETERANGAN   :</b>\nTidak Ditemukan Id User";
    } else {
        $id = str_replace('/rEm0vid', '*', $isi);
        $ids = str_replace('@Mikrotikinbot', '', $id);/////////////////////////PERHATIKAN INI ISIKAN SESUAI DENGAN  USERNAMEBOT DIAWALI DENGAN @  (manualdulu sambil buat belajar dan kegiatan keik mengetik :-D )
        $json = file_get_contents("data.json");
        $json_a = json_decode($json, TRUE);

    //============ Tidak boleh diubah!!!
    $mikrotik_ip = $json_a['ipaddress'];
    $mikrotik_username = $json_a['user'];
    $mikrotik_password = $json_a['password'];
    //====================================!!!

        $API = new routeros_api();
        if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password)) {
            $ARRAY = $API->comm("/ip/hotspot/user/print", array("?.id" => $ids,));
            $data1 = $ARRAY[0]['.id'];
            $data1 = $ARRAY[0]['profile'];
            $data2 = $ARRAY[0]['name'];
            $data3 = $ARRAY[0]['password'];
            $ARRAY2 = $API->comm("/ip/hotspot/user/remove", array("numbers" => $ids,));
            $texta = json_encode($ARRAY2);
            if (strpos(strtolower($texta), 'no such item') !== false) {
                $gagal = $ARRAY2['!trap'][0]['message'];
                $text.= "⛔ Gagal dihapus \n\n<b>KETERANGAN   :</b>\n$gagal";
            } elseif (strpos(strtolower($texta), 'invalid internal item number') !== false) {
                $gagal = $ARRAY2['!trap'][0]['message'];
                $text.= "⛔ Gagal dihapus \n\n<b>KETERANGAN   :</b>\n$gagal";
            } elseif (strpos(strtolower($texta), 'default trial user can not be removed') !== false) {
                $gagal = $ARRAY2['!trap'][0]['message'];
                $text.= "⛔ Gagal dihapus \n\n<b>KETERANGAN   :</b>\n$gagal";
            } else {
                $text.= "Berhasil Dihapus\n\n";
                $text.= "<code>ID         : $ids</code>\n";
                $text.= "<code>Server     : $data1</code>\n";
                $text.= "<code>Name       : $data2</code>\n";
                $text.= "<code>Password   : $data3</code>\n";
                sleep(2);
                $ARRAY3 = $API->comm("/ip/hotspot/user/print");
                $jumlah = count($ARRAY3);
                $text.= "Jumlah user saat ini : $jumlah user";
            }
        } else {
            $text = "Gagal Periksa sambungan Kerouter";
        }
    }
    $options = ['parse_mode' => 'html', ];
    $texta = json_encode($ARRAY2);
    return Bot::sendMessage($text, $options);
});
// Simple whoami command
//contoh command
$bot->cmd('/start', function () {

       $text="Mikrotik bot telegram see command in /help";
    return Bot::sendMessage($text);
});


 //TAMBAHAKAN DISINI UNTUK CASTOM PERINTAH///



// slice text by space
$bot->cmd('!help|/help|Help', function ()
{

    $text.= "Apa yang bisa saya bantu?\n";
    $text.= "Daftar Perintah\n";
    $text.= "/Monitor - Monitoring Wifi\n";
    $text.= "/ping - Ping local / Internet\n";
    $text.= "/Dhcp - Dhcp view (lease)\n";
    $text.= "/Address - Address list\n";
    $text.= "/Pool - Pool list view\n";
    $text.= "/Traffic - Traffic view\n";
    $text.= "/Interface - Interface view(bride)\n";
    $text.= "/Dns - Dns view\n";
    $text.= "/Hotspot - Hotspot view(aktif)(user)\n";
    $text.= "/Resource - Dns view\n";
    $text.= "/Neighbor\n";
    $text.= "/Ipbinding\n";
    $text.= "Other Comingsoon\n";

 $keyboard = [
        ['Monitor','Ping google.com'], ['Hotspot'],['Help','Sembunyikan'],
    ];

    $replyMarkup = [
        'keyboard'        => $keyboard,
        'resize_keyboard' => true,
        'setOneTimeKeyboard' => true,
        'selective' => true,
    ];
      $anu['reply_markup'] = json_encode($replyMarkup);
    return Bot::sendMessage($text, $anu);
});


$bot->cmd('Sembunyikan|!Sembunyikan', function ()
{
    $text="disembunyikan";

    $replyMarkup = [
            'keyboard' => [],
            'remove_keyboard' => true,
            'selective' => false,
          ];
    $anu['reply_markup'] = json_encode($replyMarkup);
    return Bot::sendMessage($text, $anu);
});


$bot->on('new_chat_member', function() {

    $info=bot::message();
    $nama=$info['new_chat_member']['first_name'];
    $grup=$info['chat']['title'];

    $text.="Selamat datang  💘 " . $nama."\n\nSaat ini Anda  Berada di Grup \n<b>$grup</b>";

   $options = ['parse_mode' => 'html', ];

    return Bot::sendMessage($text, $options);
});

$bot->run();
