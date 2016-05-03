<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 27.04.2016
 * Time: 16:45
 */
class SubdomainScanClass{

    var $target;
    var $ch;
    var $scid;

    function __construct($target,$scid){
        preg_match("#(http[s]?:\/\/)?([\w.]*)\/#",$target,$m);
        if(!isset($m[2]))
            return 0;
        //print_r($m);
        //die();
        $this->target=$m[2];
        //$this->ch=curl_init();
        $this->scid=$scid;
        //echo 123;
    }

    function scan($subdomains){
        //echo 123;
        $res = array();
        //var_dump($subdomains);
        $res[$this->scid] = array();
        $res[$this->scid]['scanType'] = "subdomainScan";
        $res[$this->scid]['data'] = array();

        //$res['url']=array();
        foreach($subdomains as $subdomain) {
            $resolve = false;
            $str=trim($subdomain).".".$this->target;
            //echo $str."\n";
            $result = dns_get_record($str, DNS_A);
            if (!empty($result)) {
                $resolve = true;
            }
            array_push($res[$this->scid]['data'], array("subdomain" => $subdomain, "resolve" => $resolve));
        }
        $res = json_encode($res);
        file_put_contents("BRED.TXT", $res);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://casper.localhost/listener.php");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "result=$res");
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100);
        curl_exec($ch);
        curl_close($ch);
        echo "result=$res";






    }

    function __destruct()
    {
        // TODO: Implement __destruct() method.
        //curl_close($ch);
    }

}