<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 15.05.2016
 * Time: 19:12
 */
include 'Mysqli.class.php';

class ProcessingController extends MysqliClass
{

    function dirScanProc($result, $scid)
    {
        $date = date('Y-m-d H:i:s');

        $queryStart = "INSERT INTO pathfound(scid,url,httpcode,dateResult) VALUES";
        $query = $queryStart;
        $i = 0;
        foreach ($result as $infoArr) {
            $query .= "($scid,'{$infoArr['url']}','{$infoArr['httpcode']}','$date'),";
            $i++;
            if ($i == 100) {
                $i = 0;
                $query = substr($query, 0, -1);
                $query .= " ON DUPLICATE KEY UPDATE httpcode=values(httpcode)";
                echo $query . "\n";
                $this->query($query);
                $query = $queryStart;
            }
        }
        $query = substr($query, 0, -1);
        $query .= " ON DUPLICATE KEY UPDATE httpcode=values(httpcode)";
        $this->query($query);
        $query = "update scans set status=1 where scid=$scid";
        echo $query . "\n";
        $this->query($query);
    }

    function subDomainScanProc($result, $scid)
    {
        $date = date('Y-m-d H:i:s');

        $queryStart = "INSERT INTO subdomain(scid,subdomain,resolve,dateResult) VALUES";
        $query = $queryStart;
        $i = 0;

        foreach ($result['data'] as $infoArr) {

            $resolve = intval($infoArr['resolve']);
            $query .= "($scid,'{$infoArr['subdomain']}',{$resolve},'$date'),";
            $i++;
            if ($i == 100) {
                $i = 0;
                $query = substr($query, 0, -1);
                echo $query . "\n";
                $this->query($query);
                $query = $queryStart;
            }
        }

        $query = substr($query, 0, -1);
        $this->query($query);
        $query = "update scans set status=1 where scid=$scid";
        echo $query . "\n";
        $this->query($query);


    }

    function bruteForceProc($results, $scid)
    {
        //$usernames=array();
        //$password=array();
        foreach ($results as $result) {
            $query = "insert into bruteforce(scid,login,password) VALUES($scid,'{$result['login']}','{$result['password']}') ON DUPLICATE KEY UPDATE password='{$result['password']}'";
            echo $query;
            $this->query($query);
        }

        $query = "update scans set status=1 where scid=$scid";
        echo $query . "\n";
        $this->query($query);


    }


}