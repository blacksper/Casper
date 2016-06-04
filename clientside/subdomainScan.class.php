<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 27.04.2016
 * Time: 16:45
 */
class SubdomainScanClass extends Main
{

    var $target;
//    var $ch;
//    var $scid;



    function startScan($subdomains)
    {

        preg_match("#(http[s]?:\/\/)?(www\.)?([\w.]*)\/?#", $this->target, $m);
        if (!isset($m[3]))
            return 0;

        echo $this->target = $m[3];

        //$res['url']=array();
        foreach($subdomains as $subdomain) {
            $resolve = false;
            $str=trim($subdomain).".".$this->target;
            //echo $str."\n";
            $result = dns_get_record($str, DNS_A);
            if (!empty($result)) {
                $resolve = true;
            }
            array_push($this->result[$this->scid], array("subdomain" => $subdomain, "resolve" => $resolve));
            //array_push($this->result[$this->scid], array("url" => $url, "httpcode" => $code, "length" => $length));
        }
        //$this->result = json_encode($this->result);
        //file_put_contents("BRED.TXT", $this->result);
        $this->sendResults();


    }


}