<?php
namespace Ryantxr\TorCurl;


class Curl
{
    protected $ch;
    protected $baseurl;

    protected static $instance;
    
    public static function run()
    {
        self::instance()->exec();
    }

    /**
     * Create the instance if necessary
     */
    public static function instance() : Curl
    {
        if ( ! self::$instance ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * execute the curl code
     */
    public function exec()
    {
        echo __METHOD__ . "\n";
        $this->ch = curl_init("http://piratebayztemzmv.onion/");
        
        // Get back the header
        curl_setopt($this->ch, CURLOPT_HEADER, true);
        
        // curl_setopt($this->ch, CURLOPT_INTERFACE, $ips[rand(0, count($ips)-1)]);
        // curl_setopt($this->ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        // curl_setopt( $this->ch, CURLOPT_PROXYTYPE, 7 );
        // curl_setopt( $this->ch, CURLOPT_PROXY, '127.0.0.1:9050' );
        
        // uncomment below 2 lines for tor site
        // Fixed $ch -> $this->ch Looks OK
        curl_setopt( $this->ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5_HOSTNAME );
        $torSocks5Proxy = "socks5h://127.0.0.1:9050"; // Do we need to run something on local?
        curl_setopt( $this->ch, CURLOPT_PROXY, $torSocks5Proxy );

        // sending manually set cookie
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array("Cookie: test=cookie"));

        // sending cookies from file
        $ckfile = 'COOKIES';
        curl_setopt($this->ch, CURLOPT_COOKIEFILE, $ckfile);    // To read from file
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, $ckfile);     // Need this to write to the file

        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 20);     // --connect-timeout
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 20);            // -m

		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);   // -L and --location
        curl_setopt($this->ch, CURLOPT_MAXREDIRS, 10000);       // --max-redirs
        curl_setopt($this->ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 5.2; rv:2.0.1) Gecko/20100101 Firefox/4.0.1"); // -A

        // TPB is returning a gzipped body, force uncompressed
        curl_setopt($this->ch, CURLOPT_ENCODING, 'identity');

        $s = curl_exec($this->ch);
        if ( curl_errno($this->ch) ) {
            echo 'Curl error: ' . curl_error($this->ch);
        } else {
            
        }
        echo "\n";
        // URL without proxy.php
        $this->baseUrl = substr($_SERVER['PHP_SELF'], 0, -9);
    }
}
/*
torsocks curl -c x -L -s "http://piratebayztemzmv.onion/" --max-redirs 10000 --location --connect-timeout 20 -m 20 -A "Mozilla/5.0 (Windows NT 5.2; rv:2.0.1) Gecko/20100101 Firefox/4.0.1" 2>&1
-c COOKIEFILE

-L                      Follow
--location

-s                      Silent

--max-redirs 10000      Max redirects

--connect-timeout 20    Connect timeout
-m 20                   
-A AGENT                User agent                
*/