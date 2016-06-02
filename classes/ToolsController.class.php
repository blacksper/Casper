<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 31.05.2016
 * Time: 15:51
 */

//include "Model.class.php";
session_start();
include "ToolsModel.class.php";
include "ToolsViewer.class.php";

//require($_SERVER['DOCUMENT_ROOT'] . "/config.php");
class ToolsController
{

    public $Model;
    public $Viewer;
    public $uid;


    function __construct()
    {
        $this->Model = new ToolsModel();
        $this->Viewer = new ToolsViewer();
        $this->Model->uid = $this->Model->getUserId($_SESSION['username']);
    }


    function doDetectCms(int $tid)
    {
        $scid = $this->Model->detectCms($tid);
        return $scid;
    }

    function doScanPath($action, $filename, $tid, $sid)
    {
        if (isset($action, $filename, $tid, $sid)) {
            $scid = $this->Model->startScanPath($action, $filename, $tid, $sid);
            $rowArr = $this->Model->MysqliClass->firstResult("select * from targets RIGHT JOIN scans on targets.tid=scans.tid where  scans.scid=$scid group by scid order by dateScan desc");
            $row = $this->Viewer->Tabs->getScansTableRow($rowArr);
            $result = $row;
        } else {
            $result = 0;
        }
        return $result;

    }

    function doBruteforce($type, $loginfile, $passwordfile, $tid, $sid)
    {
        if (isset($type, $loginfile, $passwordfile, $tid, $sid)) {
            $scid = $this->Model->startBruteforce($type, $loginfile, $passwordfile, $tid, $sid);
            $rowArr = $this->Model->MysqliClass->firstResult("select * from targets RIGHT JOIN scans on targets.tid=scans.tid where  scans.scid=$scid group by scid order by dateScan desc");
            $row = $this->Viewer->Tabs->getScansTableRow($rowArr);
            $result = $row;
        } else
            $result = 0;

        return $result;
    }

    function doNmapScan($tid, $option)
    {
        $this->Model->startNmap($tid, $option);

    }

    function doGitDump($tid)
    {
        $scid = $this->Model->gitDump($tid);
        //$result=$this->Model->
        return $scid;
    }


}