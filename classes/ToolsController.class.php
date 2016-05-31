<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 31.05.2016
 * Time: 15:51
 */

//include "Model.class.php";

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
        $this->Model->uid = $this->Model->getUserId("admin");
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
            $rowArr = $this->Model->MysqliClass->firstResult("select * from targets RIGHT JOIN scans on targets.tid=scans.tid where scans.deleted=0 and targets.deleted=0 and scans.scid=$scid group by scid order by dateScan desc");
            $row = $this->Viewer->Tabs->getScansTableRow($rowArr);
            $result = $row;
        } else {
            $result = 0;
        }
        return $result;

    }


}