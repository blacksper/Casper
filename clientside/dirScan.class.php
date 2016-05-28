<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 20.04.2016
 * Time: 18:15
 */
//ignore_user_abort(1);
//set_time_limit(0);
class DirScan extends Main
{
    //public $MysqliClass;

    //function __construct($MysqliClass){
    //    $this->MysqliClass=$MysqliClass;
    //}

    var $size404;
    var $httpCode404;
    var $ch;

    var $https;



    function startScan($urls)
    {

        //$this->ch=curl_init();
        $this->find404();
        //file_put_contents(rand(1, 199999), "w");

        if ($this->httpCode404 == 0)
            return $this->result;

        $ch = $this->ch;
        curl_reset($ch);
        //curl_setopt($ch, CURLOPT_URL, $this->target);
        //curl_setopt($ch,CURLOPT_POST,true);
        //curl_setopt($ch,CURLOPT_TIMEOUT,5);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_NOBODY, true);
        if ($this->https) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }

        $i = 0;
        //$fp = fopen(rand(1000, 200000) . '.txt', 'a+');
        //echo 123123;
        foreach ($urls as $url) {
            $i++;
            //fwrite($fp, "$i\n");
            $url = trim($url);
            $u = $this->target . $url;

            if (substr($u, -1) !== "/")
                $u .= "/";
            curl_setopt($ch, CURLOPT_URL, $u);
            curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $length = curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
            //$length = strlen($responseData);
            //print_r(curl_getinfo($ch));

            //if($length!=$this->size404)//есть ли смысл хранить 404
            array_push($this->result[$this->scid], array("url" => $url, "httpcode" => $code, "length" => $length));
            echo $u . " " . $code . " " . $length . " " . "\n";
            //echo $code." ".$length." ".$u."\n";
            //die();
        }

        //echo $result = json_encode($this->result);

        //fclose($fp);
        $this->sendResults();

        //print_r($this->result);

//        curl_setopt($ch, CURLOPT_URL, "http://casper.localhost/listener.php");
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, "result=$result");
//        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100);
//        //echo $result;
//        //curl_setopt($ch, CURLOPT_NOBODY, false);
//        curl_exec($ch);
        //echo curl_getinfo($this->ch,CURLINFO_HTTP_CODE);

    }

    function find404(){
        //$this->ch=curl_init();
        $absurdUrl = "qwewqecdsgvrefb325143rfqew";
        $url = $this->target . $absurdUrl;
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch,CURLOPT_TIMEOUT,5);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($this->ch, CURLOPT_NOBODY, true);
        if ($this->https) {
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
        }
        curl_exec($this->ch);

        $code=curl_getinfo($this->ch,CURLINFO_HTTP_CODE);
        $length = curl_getinfo($this->ch, CURLINFO_SIZE_DOWNLOAD);
        //$length=strlen($data);
        //echo $data;
         $this->size404=$length;
         $this->httpCode404=$code;
        echo $url . " " . $this->size404 . " " . $this->httpCode404 . "\n";
    }



}