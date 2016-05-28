<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 15.05.2016
 * Time: 18:23
 */
abstract class Main
{
    var $target;
    var $scid;
    var $ch;
    var $result;

    function __construct($target, $scid)
    {
        //echo 123;
        $this->target = $target;
        if (substr($this->target, -1) !== "/")
            $this->target .= "/";
        $this->ch = curl_init();
        $this->scid = $scid;
        $this->result = array();
        $this->result[$this->scid] = array();
        if (strstr($target, "https://"))
            $this->https = true;

    }

    abstract function startScan($data);

    function sendResults()
    {
        $ch = $this->ch;
        curl_reset($ch);
        $results = json_encode($this->result);
        echo "\n" . $results . "\n";
        curl_setopt($ch, CURLOPT_URL, "http://casper.localhost/listener.php");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "result=$results");
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100);
        curl_exec($ch);
    }


    function __destruct()
    {
        curl_close($this->ch);
    }
}