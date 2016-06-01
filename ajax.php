<?php
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 10.10.2015
 * Time: 2:15
 */
ini_set("zlib.output_compression", 0);
ob_implicit_flush(1);
header('Content-type: text/html; charset=utf-8');
session_start();

//$action=$_POST['action'];
if (!isset($_POST['page']))
    return 0;

switch ($_POST['page']) {
    case "main":
        include("./classes/Controller.class.php");
        $Controller = new Controller();

        if (isset($_POST['action'])) {

            $action = $_POST['action'];
            switch ($action) {
                case "add":
                    if (isset($_POST['campaignName'])) {
                        $name = $_POST['campaignName'];
                        $result = ($Controller->addCampaign($name));
                    } elseif (isset($_POST['serverUrl'])) {
                        $url = $_POST['serverUrl'];
                        $result = ($Controller->addServer($url));
                    }

                    break;
                case "refresh":
                    if (isset($_POST['serverId'])) {
                        $sid = $_POST['serverId'];
                        $result = $Controller->refreshStatus($sid);
                    }
                    break;
                case "delete":
                    $result = 0;
                    if (isset($_POST['campaignId'])) {
                        $cid = $_POST['campaignId'];
                        $result = $Controller->setDelete($cid, "campaign");
                    } elseif (isset($_POST['serverId'])) {
                        $sid = $_POST['serverId'];
                        $result = $Controller->setDelete($sid, "server");
                    } elseif (isset($_POST['targetId'])) {
                        $tid = $_POST['targetId'];
                        $result = $Controller->setDelete($tid, "target");
                    } elseif (isset($_POST['scanId'])) {
                        $scid = $_POST['scanId'];
                        $result = $Controller->setDelete($scid, "scan");
                    }
                    $result = intval($result);
                    //var_dump($result);
                    break;
                case "getsubinfo":
                    if (isset($_POST['cid'])) {
                        $cid = $_POST['cid'];
                        //echo $Controller->Viewer->Tabs->getSubInfoTable($tid);
                    }
                    break;
                case "getScanDetails":
                    if (isset($_POST['scid'])) {
                        $scid = $_POST['scid'];
                        //echo $Controller->Viewer->Tabs->getSubInfoTable($scid);
                        $result = $CampaignsController->Viewer->Tabs->getDirScanDetails($scid);
                    }
                    break;
            }
        }

        break;


    case "campaigns":
        include("./classes/CampaignsController.class.php");
        if (isset($_POST['scid'])) {
            $scid = $_POST['scid'];
        }
            $CampaignsController = new CampaignsController();


            if (isset($_POST['action'])) {
                $action = $_POST['action'];
                switch ($action) {
                    case "add":
                        if (isset($_POST['targetUrls'])) {
                            $targetUrls = $_POST['targetUrls'];
                            $cid = $_POST['cid'];
                            $result = $CampaignsController->addTargets($targetUrls, $cid);
                        }

                        break;

                    case "getNote":
                        if (isset($_POST['tid'])) {
                            $tid = (int)$_POST['tid'];
                            $note = $CampaignsController->Model->getNote($tid);
                            $result = $CampaignsController->Viewer->Tabs->getNote($note);
                        }

                        break;
                    case "getScanDetails":
                        if (isset($_POST['scid'])) {
                            $scid = $_POST['scid'];
                            $result = $CampaignsController->getScanDetails($scid);
                        }
                        break;
                    case "addHash":
                        if (isset($_POST['cid'], $_POST['strForHash'], $_POST['type'])) {
                            //echo 123;
                            $cid = $_POST['cid'];
                            $result = $CampaignsController->addHash($_POST['strForHash'], $_POST['type'], $cid);
                        }
                        break;

                    case "saveNote":
                        if (isset($_POST['tid'], $_POST['note'])) {
                            //echo 123;
                            $tid = $_POST['tid'];
                            $note = htmlspecialchars($_POST['note']);

                            $result = $CampaignsController->saveNote($tid, $note);
                        }
                        break;
                    case "getGitRows":
                        if (isset($_POST['scid'], $_POST['offset'], $_POST['limit'])) {
                            //echo 123;
                            $scid = $_POST['scid'];
                            $offset = (int)$_POST['offset'];
                            $limit = (int)$_POST['limit'];
                            $searchText = $_POST['searchText'];
                            //$note = htmlspecialchars($_POST['note']);
                            //echo json_encode($CampaignsController->CampaignViewer->Tabs->getGitdumpRows($scid, $offset, $limit));
                            $fileArr = $CampaignsController->Model->getGitdumpFiles($scid, $searchText, $offset, $limit);
                            $testedUrl = $CampaignsController->Model->getTestedUrl($scid);
                            $result = $CampaignsController->Viewer->Tabs->getGitdumpTable($fileArr, $testedUrl);
                        }
                        break;
                    case "getGitDetails":
                        if (isset($_POST['tid'], $_POST['type'])) {
                            $tid = (int)$_POST['tid'];
                            $type = $_POST['type'];
                            $searchtext = $_POST['searchText'];
                            $query = "select * from scans where scans.tid=$tid and scans.type='$type' and scans.deleted=0";
                            //echo $query;
                            $scid = $CampaignsController->Model->MysqliClass->firstResult($query)['scid'];

                            if (isset($scid)) {
                                $fileArr = $CampaignsController->Model->getGitdumpFiles($scid, $searchtext);
                                $testedUrl = $CampaignsController->Model->getTestedUrl($scid);
                                //print_r($fileArr);//die();
                                $result = $CampaignsController->Viewer->Tabs->getGitdumpDetails($fileArr, $testedUrl, $scid);
                            }
                            else
                                $result = '<div id="gitDumpTable">
                                        <div>
                                            <input class="btn btn-success doScan" type="submit" value="Получить список файлов">
                                            <input type="hidden" class="action" name="action" value="gitDump">
                                        </div>
                                    </div>';
                            // $result = $CampaignsController->getTargetScans($tid,$type);
                        }
                        break;

                }

            }


        break;
    case "scan":
        (isset($_POST['sid'])) ? $sid = (array)$_POST['sid'] : $sid = 0;
        include "./classes/ToolsController.class.php";
        $Tools = new ToolsController();
        $tid = (int)$_POST['tid'];
        if (!$tid)
            return 0;

        if (!isset($_POST['action']))
            return 0;
        $action = $_POST['action'];
        switch ($action) {
            case "gitDump":
                $scid = $Tools->doGitDump($tid);
                $result = $scid;
                break;
            case "detectCms":
                $result = $Tools->doDetectCms($tid);
                break;
            case "mscan":
                $type = $_POST['type'];
                if (isset($_POST['filename'])) {
                    $filename = $_POST['filename'];
                    $result = $Tools->doScanPath($type, $filename, $tid, $sid);
                    //$CampaignsController->Model->MysqliClass->getAssocArray("select * from scans where scid=$scid");
                    //$CampaignsController->Viewer->Tabs->getScansTableRow();
                }
                break;
            case "wpBrute":
            case "dleBrute":
                if (($sid == 0) || ($tid == 0))
                    return 0;
                $type = $action;
                $loginfile = $_POST['loginfile'];
                $passwordfile = $_POST['passwordfile'];

                if (!isset($loginfile, $passwordfile))
                    return 0;

                $result = $Tools->doBruteforce($type, $loginfile, $passwordfile, $tid, $sid);

                break;

            case "nmapScan":
                $option = $_POST['option'];
                $Tools->doNmapScan($tid, $option);
                break;

        }


        break;

}

if (isset($result))
    echo json_encode($result);