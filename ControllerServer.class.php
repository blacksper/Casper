<?php
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 10.10.2015
 * Time: 2:10
 */
include("Model.class.php");


class ControllerServer {


    var $Model;

    function __construct(){
        $this->Model= new Model();
    }

    function CheckClient($clientId){    //���������� ���� ���� ��������� 200, ����� ����
        $clientId=(int)$clientId;
        $path=$this->Model->GetClientPath($clientId);

        if(!$path)
            die("path or client not found");
        preg_match("#^(http[s]?:\/\/)?([A-z0-9.-]*)(\/.*)?$#",$path,$url);
       // print_r($url);

        if(empty($url)) {
            print_r($url);
            die('qwe');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://".$url[2]);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //echo $httpcode;
        curl_close($ch);

        $ip=gethostbyname($url[2]);
       // echo $ip." ".$httpcode;
        if($httpcode==200&&!empty($ip)) {
            $this->Model->Query("UPDATE servers SET status=1,ip='$ip' WHERE cid=$clientId");
            return $ip;
        }else {
            $this->Model->Query("UPDATE servers SET status=0 WHERE cid=$clientId");
            return 0;
        }
    }

    function DeleteClient($clientId){   //удаление сервера
        $clientId=(int)$clientId;
        $this->Model->Query("DELETE FROM servers WHERE cid=$clientId");
        return 1;
    }

    public function AddServer($url,$type){    //добавление сервера
        preg_match("#^(http[s]?:\/\/)?([A-z0-9.-]*)(/[A-z0-9.-]+)+$#",$url,$clurl);
         //print_r($clurl);
        //echo 123;

        switch($type){
            case 'server':
                break;
            case 'target':
                break;

        }


        if(empty($clurl[2])) {  //clurl пустой, то url не прошёл регулярку
            return -1;
        }
        #проверка есть ли уже в бд этот сервер
        $query="SELECT cid from servers where url = '$clurl[2]'";//тут инъекция
        $cid=$this->Model->QueryFirst($query);
        if($cid!=0)
            return -1;


        $query="INSERT INTO servers(url,date_add) values('$clurl[2]',now())";
        //echo $query;

        $this->Model->Query($query);
        $query="select cid from servers where url ='$clurl[2]' order by date_add desc";
        $result=$this->Model->QueryFirst($query);
        //echo $query;
        if(!isset($result))
            $result=-1;

        return $result;
    }


} 