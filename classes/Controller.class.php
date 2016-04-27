<?php
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 07.10.2015
 * Time: 16:47
 */
session_start();

include("Viewer.class.php");
include("Model.class.php");

class Controller {

    public $Viewer;
    public $servers;
    public $Model;
    public $uid;

    function __construct(){

        //$this->MysqliClass=new MysqliClass();
        $this->Model=new Model();
        //$qwe="";
        //echo $qwe;
        //$this->uid=;

        $this->Viewer=new Viewer($this->Model);
        //var_dump($this->uid);

        //$this->view=new Html($this->model);

    }


    public function ShowMain(){
       // $servers=$this->model->GetNumericArray("select * from servers");

        $this->view->ShowMain();

    }



    public function updt(){
        $tbl=$this->view->GetServerTab();
        return $tbl;
    }

    public function addTarget($url){    //добавление сервера
        preg_match("#^(http[s]?:\/\/)?([A-z0-9.-_]*)\/+#",$url,$clurl);

        $result="";

        if(isset($clurl[2])) {
             $url=$clurl[2];

            ####проверка есть ли уже в бд этот сервер
            $query="SELECT tid from targets where url = '$url'";//тут инъекция
            $sid=$this->Model->MysqliClass->firstResult($query)['tid'];

            if($sid!="")
                exit;

            $uid=$this->Model->getUserId($_SESSION['username']);
            //var_dump($uid);
            if($uid) {
                $query = "INSERT INTO targets(uid,url,dateAdd) values($uid,'$url',now())";
                $result = $this->Model->MysqliClass->query($query);
                //echo $query;
                $query = "SELECT * from targets where url='$url'";
                $resultArr = $this->Model->MysqliClass->firstResult($query);
                //echo $query;
                //var_dump($resultArr);

                $result = $this->Viewer->Tabs->getTargetTableRow($resultArr);
            }else{
                $result="";
            }

        }

        return $result;
    }


    public function addServer($url){    //добавление сервера
        preg_match("#^((http[s]?:\/\/)[A-z0-9.-_\/]*)#",$url,$clurl);

        $result="";
       // print_r($clurl);
        //die();
        if(isset($clurl[1])) {
             $url=$clurl[1];

            ####проверка есть ли уже в бд этот сервер
            $query="SELECT sid from servers where path = '$url'";//тут инъекция
            $sid=$this->Model->MysqliClass->firstResult($query)['sid'];

            if($sid!="")
                exit("uze est");
            $uid=$this->Model->getUserId($_SESSION['username']);
            //var_dump($uid);
            if($uid) {
                $query = "INSERT INTO servers(uid,path,dateAdd) values($uid,'$url',now())";
                $result = $this->Model->MysqliClass->query($query);
                //echo $query;
                $query = "SELECT * from servers where path='$url'";
                $resultArr = $this->Model->MysqliClass->firstResult($query);
                //echo $query;
                //var_dump($resultArr);

                $result = $this->Viewer->Tabs->getServerTableRow($resultArr);
            }else{
                $result="";
            }

        }
        //echo $result;
        return $result;
    }

    /**
     * @param $sid server id
     * @return int|string
     */
    public function refreshStatus($sid){
        //$result=array();
        $sid=(int)$sid;
        $ip="";
        $path=$this->GetClientPath($sid);
        //echo $path;
        if(!$path)
            die("path or client not found");
        preg_match("#^(http[s]?:\/\/)?([A-z0-9.-]*)(\/.*)?$#",$path,$url);
       //print_r($url[2]);
        //die();
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
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if($httpcode==200) {
            $ip = gethostbyname($url[2]);   //$url[2]= url w.o. http:// etc
            if (!empty($ip)) {
                $status = 1;
                $this->Model->MysqliClass->query("UPDATE servers SET status=$status,ip='$ip' WHERE sid=$sid");
                $statusArr = $this->Viewer->Tabs->getStatusByCode($status);
            }
            //$result=
            //return $ip;
        }else {
            $status=0;
            $this->Model->MysqliClass->query("UPDATE servers SET status=$status WHERE sid=$sid");
            $statusArr=$this->Viewer->Tabs->getStatusByCode($status);
            //return 0;
        }
        $result=array("ip"=>$ip,"statusArr"=>$statusArr);
        //print_r($result);

        return $result;


    }


    function GetClientPath($sid){
        $query="SELECT * FROM servers WHERE sid=$sid";

        $result=$this->Model->MysqliClass->firstResult($query);
        //print_r($result);
        if(empty($result['path']))
            return 0;
        else
            return $result['path'];


    }


    function setDelete($id,$type){   //удаление сервера
        $result="";
        $id=(int)$id;
        if($id) {
            if ($type == "server") {
                //$sid=(int)$id;
                $query = "UPDATE servers set deleted=1 WHERE sid=$id";
                $result = $this->Model->MysqliClass->query($query);
            } elseif ($type == "target") {
                //$tid=(int)$id;
                $query = "UPDATE targets set deleted=1 WHERE tid=$id";
                $result = $this->Model->MysqliClass->query($query);
            }
            //echo $query;
        }
        return $result;
    }



} 