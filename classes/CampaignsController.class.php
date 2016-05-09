<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 29.04.2016
 * Time: 17:26
 */
session_start();

include("CampaignViewer.class.php");
include("Model.class.php");



class CampaignsController
{

    public $CampaignViewer;
    public $Model;

    //public $uid;

    function __construct($cid = null)
    {


        $this->Model = new Model();
        if ($cid != null) {
            $query = "select * from campaigns where cid=$cid and deleted=0";
            $result = $this->Model->MysqliClass->firstResult($query);
            if (empty($result)) {
                //$this->doRedirect();
                echo "tut dolzen bit redirect";
                exit();
            }

        }
        $this->CampaignViewer = new CampaignViewer($this->Model);


    }

    function doRedirect()
    {
        echo 123123;
        header("Location: ./index.php");
    }

    function addTargets($urls, int $cid)
    {
        $query = "select cid from campaigns where cid=$cid";
        $cid = $this->Model->MysqliClass->firstResult($query)['cid']; // проверка на существование кампании с таким cid
        if (!isset($cid))
            exit;
        $urlsArr = explode("\n", $urls);
        if (empty($urlsArr))
            exit;
        $res = "";
        //$res=$this->addTarget($urlsArr[0],$cid);
        //$res.=$this->addTarget($urlsArr[1],$cid);
        foreach ($urlsArr as $url)
            $res .= $this->addTarget($url, $cid);

        return $res;


    }

    public function addTarget($targeturl, $cid)
    {    //добавление сервера
        preg_match("#^(http[s]?:\/\/)?([A-z0-9.-_]*)\/+#", $targeturl, $clurl);
        //echo 123;
        $result = "";

        if (isset($clurl[2])) {
            $targeturl = $clurl[2];

            ####проверка есть ли уже в бд этот сервер
            $query = "SELECT tid from targets where url = '$targeturl' and deleted=0";//тут инъекция
            $tid = $this->Model->MysqliClass->firstResult($query)['tid'];

            if (isset($tid))
                exit;

            $uid = $this->Model->getUserId($_SESSION['username']);

            if ($uid) {
                $query = "INSERT INTO targets(uid,url,cid,dateAdd) values($uid,'$targeturl',$cid,now())";
                $result = $this->Model->MysqliClass->query($query);
                //echo $query;
                $query = "SELECT * from targets where url='$targeturl'";
                $resultArr = $this->Model->MysqliClass->firstResult($query);
                //echo $query;
                //var_dump($resultArr);
                $result = $this->CampaignViewer->Tabs->getMainTableRow($resultArr);

            } else {
                $result = "";
            }

        }

        return $result;
    }


    function getHash($str, $type)
    {
        if (!isset($str, $type))
            return 0;
        include "Hasher.class.php";
        $hasher = new Hasher();
        $hash = "";

        switch ($type) {
            case "md5":
            case "sha1":
                $hash = $hasher->getDefHash($str, $type);
                break;
            case "wordpress3":
                $hash = $hasher->wordpress3Hash($str);
                break;
            case "mysql":
                $hash = $hasher->mysqlHash($str);
                break;
            case "mysqlOld":
                $hash = $hasher->mysqlOldHash($str);
                break;
        }

        return $hash;

    }


}