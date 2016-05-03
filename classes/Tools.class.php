<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 21.04.2016
 * Time: 15:02
 */
include "Model.class.php";

class Tools
{

    public $Model;
    public $uid;

    function __construct()
    {
        $this->Model = new Model();
    }

    function startScan(int $tid,int $sid, $filename, $type = null)
    {
        $action="";
        switch ($type) {
            case "dirScan":
            case "subdomainScan":
                 $action=$type;
                break;
            default:
                break;
        }

        if(!$action)
            return 0;

        $this->uid = $this->Model->getUserId($_SESSION['username']);
        $query = "select * from targets where tid=$tid";
        $targetUrl = $this->Model->MysqliClass->firstResult($query)['url'];

        $query = "select * from servers where sid=$sid";
        $serverUrl = $this->Model->MysqliClass->firstResult($query)['path'];

        $scid = rand(1000000, 90000000);
        $query = "insert into scans(scid,type,uid,sid,tid,status,filename) VALUES($scid,'$action',$this->uid,$sid,$tid,0,'$filename') ";
        //echo $query;
        $this->Model->MysqliClass->query($query);
        $arrTask = array();

        //echo "./txt/paths/" . $filename;

        $urls = explode("\r\n", file_get_contents("./txt/paths/" . $filename));

        $arrTask[$scid] = array("url" => $targetUrl, "action" => $action, "data" => $urls);
        $enc = "scanType=dirScan&execute=" . json_encode($arrTask);
        /*
                $enc ="execute=". json_encode($arrTask);
                $socket = socket_create(AF_INET, SOCK_STREAM, 0);
                $parseUrl=parse_url($serverUrl);
                $addres=$parseUrl['host'];
                $port=80;
                //print_r($parseUrl);
                $result = socket_connect($socket, $addres, $port);
                if ($result === false) {
                    echo "Не получилось выполнить функцию socket_connect().\nПричина: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
                } else {
                    echo "OK.\n";
                }
                $arrOpt = array('l_onoff' => 1, 'l_linger' => 1);
                socket_set_option($socket, SOL_SOCKET, SO_LINGER,$arrOpt);
                $in= "POST /clientside/execute.php HTTP/1.1\r\n";
                $in.= "Host: example.com\r\n";
                $in.= "Content-Type: application/x-www-form-urlencoded\r\n";
                $in.= "Content-Length: ".strlen($enc)."\r\n";
                $in.= "Connection: close\r\n";
                $in.= "\r\n";
                $in.= $enc;
                echo $in;
                socket_write($socket, $in, strlen($in));
                socket_close($socket);
        */



        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $serverUrl);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        echo $enc;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $enc);
        echo curl_exec($ch);
        curl_close($ch);

    }


}