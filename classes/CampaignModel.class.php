<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 01.05.2016
 * Time: 1:08
 */
include("Mysqli.class.php");

class CampaignModel
{

    // var $Model;
    public $MysqliClass;

    function __construct()
    {
        $this->MysqliClass = new MysqliClass();
    }

    function getUserId($username)
    {

        $query = "SELECT uid from users where username='$username'";
        $result = $this->MysqliClass->firstResult($query)['uid'];
        return $result;
    }

    /**
     * @return mixed
     */
    public function getCampaigns()
    {
        $query = "SELECT * FROM campaigns WHERE deleted=0";
        $urlsArr = $this->MysqliClass->getAssocArray($query);
        return $urlsArr;
    }

    /**
     * @param $cid
     * @return mixed
     */
    public function getTargetsByCid($cid)
    {
        $query = "select * from targets where cid=$cid and deleted=0 order by dateAdd desc";
        $targetsArr = $this->MysqliClass->getAssocArray($query);

        $newarr=array();
        //$link=&$targetsArr;

        foreach($targetsArr as $i=>$value ){
         //$newarr[$i['tid']]
            //$newarr
            $newarr[$value['tid']]=$value;
            $newarr[$value['tid']]['childs']=array();

        }
        //print_r($newarr)
        foreach($newarr as $tid=>$val){
            //$newarr[$val['pid']]['childs']=array();
            if($val['pid']){
                array_push($newarr[$val['pid']]['childs'],$val);
                //array_push($newarr[],array('child'=>$val));
                unset($newarr[$tid]);
            }
        }
        //print_r($newarr);
        return $newarr;
    }

    /**
     * @param $scid
     * @return mixed
     */
    public function getTestedUrl($scid)
    {
        $query = "select url from scans left JOIN targets on scans.tid=targets.tid where scid=$scid";
        //echo $query;
        $testedUrl = $this->MysqliClass->firstResult($query)['url'];
        return $testedUrl;
    }

    function getScanType($scid)
    {
        $query = "select type from scans where scid=$scid";
        $type = $this->MysqliClass->firstResult($query)['type'];
        return $type;
    }

    function getFoundSubs($scid)
    {
        $foundPaths = $this->getScansResult($scid, "subdomainScan", "resolve desc");
        return $foundPaths;
    }

    /**
     * @param $scid
     * @return mixed
     */
    public function getScansResult($scid, $scanType, $orderby, $limit = PHP_INT_MAX, $offset = 0)
    {
        //$tableName="";
        switch ($scanType) {
            case "bruteforce":
                $tableName = "bruteforce";
                break;
            case "nmap":
                $tableName = "nmap";
                break;
            case "subdomainScan":
                $tableName = "subdomain";
                break;
            case "dirScan":
                $tableName = "pathfound";
                break;
            case "gitDump":
                $tableName = "gitdump";
                break;
            default:
                return 0;

        }
        $query = "select * from $tableName where scid=$scid ORDER BY $orderby limit $offset,$limit";
        //echo $query;
        $foundPaths = $this->MysqliClass->getAssocArray($query);
        return $foundPaths;
    }

    function getFoundDirs($scid)
    {
        $foundPaths = $this->getScansResult($scid, "dirScan", "httpcode asc");
        return $foundPaths;
    }

    function getGitdumpFiles($scid, $like = "", $offset = 0, $limit = 10)
    {
        $query = "select * from gitdump where scid=$scid and filename like '%$like%' and older=0 ORDER BY exist desc,dateAdd desc limit $offset,$limit";
        //echo $query;
        $filesArr = $this->MysqliClass->getAssocArray($query);
        return $filesArr;
    }

    function getCampName($cid)
    {
        $name = $this->MysqliClass->firstResult("select name from campaigns where cid=$cid")['name'];
        return $name;
    }

    function getScans($cid)
    {
        $query = "select * from targets RIGHT JOIN scans on targets.tid=scans.tid where cid=$cid and scans.deleted=0 and targets.deleted=0 and scans.type<>'gitdump' group by scid order by dateScan desc";
        $scanSArr = $this->MysqliClass->getAssocArray($query);
        return $scanSArr;
    }

    function getTargets($cid)
    {
        $query = "select tid,url from targets where cid=$cid and deleted=0";
        $targetsArr = $this->MysqliClass->getAssocArray($query);
        return $targetsArr;
    }

    function getServers($cid)
    {
        $query = "SELECT sid,path FROM servers WHERE deleted=0 AND sid>0 and status=1";
        $serversArr = $this->MysqliClass->getAssocArray($query);
        return $serversArr;
    }

    function getNote($tid)
    {
        $query = "select note,tid from targets where tid=$tid and deleted=0";
        $note = $this->MysqliClass->firstResult($query);
        return $note;
    }

}