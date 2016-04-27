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
        print_r($m);
        //die();
        $this->target=$m[2];
        //$this->ch=curl_init();
        $this->scid=$scid;
        //echo 123;
    }

    function scan($subdomains){

        foreach($subdomains as $subdomain) {
            $str=trim($subdomain).".".$this->target;
            //echo $str;
            $result = dns_get_record($str, DNS_A);
            if(!empty($result))
                echo $str."\n";
        }
    }

    function __destruct()
    {
        // TODO: Implement __destruct() method.
        //curl_close($ch);
    }

}