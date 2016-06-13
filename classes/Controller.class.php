<?php
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 07.10.2015
 * Time: 16:47
 */
session_start();
require($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
include("Viewer.class.php");
include("Model.class.php");

class Controller {

    public $Viewer;
    public $servers;
    public $Model;
    public $uid;

    function __construct(){
        $this->Model=new Model();
        $this->Viewer=new Viewer($this->Model);
    }



    public function addCampaign($name)
    {    //добавление сервера
        preg_match("#^([A-z0-9.-_]+)#", $name, $clname);

        $result = "";
        if (isset($clname[1])) {
            $name = $clname[1];

            ####проверка есть ли уже в бд эта кампания
            $query = "SELECT cid from campaigns where name = '$name' and deleted=0";//тут инъекция

            $cid = $this->Model->MysqliClass->firstResult($query)['cid'];

            if ($cid != "")
                return 0;

            $uid = $this->Model->getUserId($_SESSION['username']);

            if ($uid) {
                $query = "INSERT INTO campaigns(uid,name,dateAdd) values($uid,'$name',now())";
                $result = $this->Model->MysqliClass->query($query);

                $query = "SELECT *,0 as cnt from campaigns where name='$name'";
                $resultArr = $this->Model->MysqliClass->firstResult($query);

                if (!empty($resultArr))
                    $result = $this->Viewer->Tabs->getCampaignTableRow($resultArr);

            } else {
                $result = "";
            }

        }
        return $result;
    }


    public function addServer($url){    //добавление сервера
        $result="";
        preg_match("#^((http[s]?:\/\/)[A-z0-9.-_\/]*)#", $url, $clurl);
        if(isset($clurl[1])) {
             $url=$clurl[1];

            ####проверка есть ли уже в бд этот сервер
            $query = "SELECT sid from servers where path = '$url' and deleted=0";//тут инъекция
            $sid=$this->Model->MysqliClass->firstResult($query)['sid'];

            if($sid!="")
                exit("uze est");
            $uid=$this->Model->getUserId($_SESSION['username']);
            //var_dump($uid);
            if($uid) {
                $query = "INSERT INTO servers(uid,path,dateAdd) values($uid,'$url',now())";
                $this->Model->MysqliClass->query($query);
                $query = "SELECT * from servers where path='$url'";
                $resultArr = $this->Model->MysqliClass->firstResult($query);
                $result = $this->Viewer->Tabs->getServerTableRow($resultArr);
            }else{
                $result="";
            }
        }
        return $result;
    }

    /**
     * @param $sid server id
     * @return int|string
     */
    public function refreshStatus($sid){
        $sid=(int)$sid;
        $ip="";
        $path=$this->GetClientPath($sid);

        if(!$path)
            die("path or client not found");
        preg_match("#^(http[s]?:\/\/)?([A-z0-9.-]*)(\/.*)?$#",$path,$url);

        if(empty($path)) {
            print_r($path);
            die('qwe');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $path);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if($httpcode==200) {
            $ip = gethostbyname($url[2]);   //$url[2]= url w.o. http:// etc
            if (!empty($ip)) {
                $status = 1;
                $this->Model->MysqliClass->query("UPDATE servers SET status=$status,ip='$ip' WHERE sid=$sid");
                $statusArr = $this->Viewer->Tabs->getStatusByCode($status);
            }

        }else {
            $status=0;
            $this->Model->MysqliClass->query("UPDATE servers SET status=$status WHERE sid=$sid");
            $statusArr=$this->Viewer->Tabs->getStatusByCode($status);
        }
        $result=array("ip"=>$ip,"statusArr"=>$statusArr);


        return $result;
    }


    function GetClientPath($sid){
        $query="SELECT * FROM servers WHERE sid=$sid";
        $result=$this->Model->MysqliClass->firstResult($query);

        if(empty($result['path']))
            return 0;
        else
            return $result['path'];
    }


    function setDelete($id,$type){   //удаление сервера
        $result="";
        $id=(int)$id;

            if ($type == "server") {
                $query = "UPDATE servers set deleted=1 WHERE sid=$id";
                $result = $this->Model->MysqliClass->query($query);
            } elseif ($type == "campaign") {
                $query = "UPDATE campaigns set deleted=1 WHERE cid=$id";
                $result = $this->Model->MysqliClass->query($query);
            } elseif ($type == "target") {
                $query = "UPDATE targets set deleted=1 WHERE tid=$id";
                $result = $this->Model->MysqliClass->query($query);
            } elseif ($type == "scan") {
                $query = "UPDATE scans set deleted=1 WHERE scid=$id";
                $result = $this->Model->MysqliClass->query($query);
            } elseif ($type == "hash") {
                // echo 123123;
                $query = "UPDATE hashes set deleted=1 WHERE hid=$id";
                $result = $this->Model->MysqliClass->query($query);
            }

        return $result;
    }



} 