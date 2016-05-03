<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 20.04.2016
 * Time: 18:15
 */
//ignore_user_abort(1);
//set_time_limit(0);
class DirScan
{
    //public $MysqliClass;

    //function __construct($MysqliClass){
    //    $this->MysqliClass=$MysqliClass;
    //}
    var $target;
    var $size404;
    var $httpCode404;
    var $ch;
    var $scid;

    function __construct($target,$scid){
        $this->target=$target;
        $this->ch=curl_init();
        $this->scid=$scid;
        print_r($this);
        //echo 123;
    }

    function scan($urls)
    {
        $this->find404();
        file_put_contents(rand(1, 199999), "w");
        //exit();
        $result = array();
        $result[$this->scid] = array();
        $result["scanType"] = "scanType";

        if ($this->httpCode404 == 0)
            return $result;
        $ch = $this->ch;
        curl_setopt($ch, CURLOPT_URL, $this->target);
        //curl_setopt($ch,CURLOPT_POST,true);
        //curl_setopt($ch,CURLOPT_TIMEOUT,5);
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_NOBODY, true);
        $vara = "";

        $i = 0;
        $fp = fopen(rand(1000, 200000) . '.txt', 'a+');
        foreach ($urls as $url) {
            $i++;
            fwrite($fp, "$i\n");


            $url = trim($url);
            $u = $this->target . $url;
            echo $u . "\n";
            if (substr($u, -1) !== "/")
                $u .= "/";
            curl_setopt($ch, CURLOPT_URL, $u);
            curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $length = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
            //print_r(array("url"=>$url,"httpcode"=>$code,"length"=>$length));
            //if(($code!=$this->httpCode404)&&($this->size404!==$length))
            array_push($result[$this->scid], array("url" => $url, "httpcode" => $code, "length" => $length));
            //echo $hd;
        }

        echo $result = json_encode($result);

        fclose($fp);

        curl_setopt($ch, CURLOPT_URL, "http://casper.localhost/listener.php");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "result=$result");
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100);
        //echo $result;
        //curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_exec($ch);
        //echo curl_getinfo($this->ch,CURLINFO_HTTP_CODE);

    }

    function find404(){
        curl_setopt($this->ch,CURLOPT_URL,$this->target."/qwedsfwrefve5h45r143rb5t3");
        curl_setopt($this->ch,CURLOPT_TIMEOUT,5);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_NOBODY, true);
        curl_exec($this->ch);

        $code=curl_getinfo($this->ch,CURLINFO_HTTP_CODE);
        $length=curl_getinfo($this->ch,CURLINFO_CONTENT_LENGTH_DOWNLOAD);

         $this->size404=$length;
         $this->httpCode404=$code;
    }

    function __destruct(){
        // TODO: Implement __destruct() method.

        curl_close($this->ch);
    }

}