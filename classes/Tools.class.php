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
        $query = "insert into scans(scid,type,uid,sid,tid,status,filename) VALUES($scid,'$action',$this->uid,$sid,$tid,1,'$filename') ";
        echo $query;
        $this->Model->MysqliClass->query($query);
        $arrTask = array();

        $urls = explode("\r\n", file_get_contents("./txt/paths/" . $filename));

        $arrTask[$scid] = array("url" => $targetUrl, "action" => $action, "data" => $urls);


        $enc = json_encode($arrTask);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $serverUrl);
        echo $serverUrl;
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        echo $enc;
        curl_setopt($ch, CURLOPT_POSTFIELDS, "execute=$enc");
        echo curl_exec($ch);
        curl_close($ch);

    }


}