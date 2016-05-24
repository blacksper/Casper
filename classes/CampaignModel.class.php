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
        return $targetsArr;
    }

    /**
     * @param $scid
     * @return mixed
     */
    public function getTestedUrl($scid)
    {
        $testedUrl = $this->MysqliClass->firstResult("select url from scans left JOIN targets on scans.tid=targets.tid where scid=$scid")['url'];
        return $testedUrl;
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


}