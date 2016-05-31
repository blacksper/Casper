<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 29.04.2016
 * Time: 17:26
 */
//session_start();

include("CampaignViewer.class.php");
include("CampaignModel.class.php");



class CampaignsController
{

    public $Viewer;
    public $Model;
    public $cid;

    //public $cid;

    function __construct($cid = null)
    {

        $this->Model = new CampaignModel();
        if ($cid != null) {
            $query = "select * from campaigns where cid=$cid and deleted=0";
            $result = $this->Model->MysqliClass->firstResult($query);
            if (empty($result)) {
                //$this->doRedirect();
                echo "tut dolzen bit redirect";
                exit();
            }

        }
        // $this->cid=$cid;
        $this->Viewer = new CampaignViewer($this->Model);


    }

    function doRedirect()
    {
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

    function addTarget($targeturl, $cid)
    {    //добавление сервера


        preg_match("#^((http[s]?:\/\/)?([A-z0-9.-_]*)\/.+)#", $targeturl, $clurl);

        if (!isset($clurl[1])) {
            preg_match("#^((\d{1,3}\.){3}\d{1,3})#", $targeturl, $ip);
            if (isset($ip[1]))
                $targeturl = $ip[1];
            else
                $targeturl = null;
        } else {
            $targeturl = $clurl[1];
        }

        //var_dump($clurl);
        //die();
        $result = "";

        if (isset($targeturl)) {
            //$targeturl = $clurl[1];

            ####проверка есть ли уже в бд этот сервер
            $query = "SELECT tid from targets where url = '$targeturl' and deleted=0 and cid=$cid";//тут инъекция
            $tid = $this->Model->MysqliClass->firstResult($query)['tid'];

            if (isset($tid))
                exit;

            $uid = $this->Model->getUserId($_SESSION['username']);

            if ($uid) {
                $query = "INSERT INTO targets(uid,url,cid,dateAdd) values($uid,'$targeturl',$cid,now())";
                $result = $this->Model->MysqliClass->query($query);
                //echo $query;
                $query = "SELECT * from targets where url='$targeturl' and cid=$cid";
                $resultArr = $this->Model->MysqliClass->firstResult($query);
                //echo $query;
                //var_dump($resultArr);
                $result = $this->Viewer->Tabs->getMainTableRow($resultArr);

            } else {
                $result = "";
            }

        }

        return $result;
    }


    function addHash($str, $type, $cid)
    {
        if (!isset($str, $type, $cid))
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
            default:
                exit;
        }

        $uid = $this->Model->getUserId($_SESSION['username']);
        $query = "INSERT INTO hashes(source,hash,type,uid,cid,deleted) VALUES('$str','$hash','$type',$uid,$cid,0) ";
        //echo $query."\n";
        $this->Model->MysqliClass->query($query);
        $result = $this->Viewer->Tabs->getHashContentTableRow(array("hash" => $hash, "source" => $str, "type" => $type));

        return $result;
    }


    function saveNote($tid, $note)
    {
        //echo 123123;
        $query = "select * from targets where tid=$tid";
        $tid = $this->Model->MysqliClass->firstResult($query)['tid'];
        if (!isset($tid))
            return 0;
        $query = "update targets set note='$note' where tid=$tid";
        $result = $this->Model->MysqliClass->query($query);
        if ($result)
            return 1;

    }

    function getTargetScans($tid, $type)
    {
        $query = "select * from scans where tid=$tid and type='$type' and deleted=0 GROUP BY scid";
        $scansArr = $this->Model->MysqliClass->getAssocArray($query);
        return $scansArr;
    }

    function getScanDetails($scid)
    {
        $type = $this->Model->getScanType($scid);

        $result = "";
        if (!isset($type))
            return 0;
        if (strstr($type, "nmap")) {

            $type = "nmap";
        }

        switch ($type) {
            case "subdomainScan":
                $testedUrl = $this->Model->getTestedUrl($scid);
                $foundSubs = $this->Model->getFoundSubs($scid);
                $result = $this->Viewer->Tabs->getSubdomainScanDetails($foundSubs, $testedUrl);
                break;
            case "dirScan":
                $testedUrl = $this->Model->getTestedUrl($scid);
                $foundDirs = $this->Model->getFoundDirs($scid);
                $result = $this->Viewer->Tabs->getDirScanDetails($foundDirs, $testedUrl);
                break;
            case "nmap":
                $testedUrl = $this->Model->getTestedUrl($scid);
                $hostsArr = $this->Model->getScansResult($scid, "nmap", "dateAdd desc");
                $result = $this->Viewer->Tabs->getNmapDetails($hostsArr, $testedUrl);
                break;
            case "wpBrute":
            case "dleBrute":
                $testedUrl = $this->Model->getTestedUrl($scid);
                $combinationsArr = $this->Model->getScansResult($scid, "bruteforce", "dateAdd");
                $result = $this->Viewer->Tabs->getBruteDetails($combinationsArr, $testedUrl);
                break;
            case "gitdump":
                $testedUrl = $this->Model->getTestedUrl($scid);
                $like = "";
                $offset = 0;
                $limit = 10;
                $filesArr = $this->Model->getGitdumpFiles($scid, $like, $offset, $limit);

                $result = $this->Viewer->Tabs->getGitdumpDetails($filesArr, $scid);
                break;
        }
        return $result;

    }


    function getPage($cid)
    {
        $targetsArr = $this->Model->getTargetsByCid($cid);
        //var_dump($targetsArr);
        $this->Viewer->Tabs->getMainTab($targetsArr);

        $scansArr = $this->Model->getScans($cid);
        $this->Viewer->Tabs->getScansTab($scansArr);

        $targetsArr = $this->Model->getTargets($cid);
        $serversArr = $this->Model->getServers($cid);


        $handle = opendir(PATH_TXTP);
        $dirs = '';//список директорий
        $i = 0;

        while ($dir = readdir($handle)) {
            if ($i < 2) {
                $i++;
                continue;
            }
            $dirs .= '<option>' . $dir . "</option>";
        }

        $targetList = $this->Viewer->getTargetList($targetsArr);
        $servers = $this->Viewer->getServersList($serversArr);
        $hashesArr = $this->Model->MysqliClass->getAssocArray("SELECT * FROM hashes WHERE deleted=0 ORDER BY dateAdd DESC");

        $this->Viewer->Tabs->getDirscanTab($targetList, $servers, $dirs);
        $this->Viewer->Tabs->getNmapTab($targetList);
        $this->Viewer->Tabs->getHashmakerTab($hashesArr);
        $this->Viewer->Tabs->getGitdumperTab($targetList);
        $this->Viewer->Tabs->getCmsDetecterTab($targetList);
        $this->Viewer->Tabs->getWpBruteTab($targetList, $servers, $dirs);
        $this->Viewer->Tabs->getDleBruteTab($targetList, $servers, $dirs);


        $this->Viewer->Tabs->getToolsTab($targetsArr, $serversArr);

        //$this->Model->MysqliClass->firstResult("select ");
        $name = $this->Model->getCampName($cid);
        //$this->cid = $cid;


        $this->Viewer->buildPage($name);


    }




}