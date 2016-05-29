<?php
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 10.10.2015
 * Time: 2:15
 */


//$action=$_POST['action'];
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
                            $result = $CampaignsController->Viewer->Tabs->getNote($tid);
                        }

                        break;
                    case "getScanDetails":
                        if (isset($_POST['scid'])) {
                            $scid = $_POST['scid'];
                            $result = $CampaignsController->Viewer->Tabs->getScanDetails($scid);
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
                            $result = $CampaignsController->Viewer->Tabs->getGitdumpRows($scid, $searchText, $offset, $limit);
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
                            if (isset($scid))
                                $result = $CampaignsController->Viewer->Tabs->getGitdumpDetails($scid, $searchtext);
                            else
                                $result = '<div id="gitDumpTable">
                                        <div >
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

        if ($_POST['type'] == "gitDump") {
            $tid = $_POST['tid'];
            include "./classes/Tools.class.php";
            $Tools = new Tools();
            $scid = $Tools->gitDump($tid);
            $result = $scid;
        }

        break;

}

if (isset($result))
    echo json_encode($result);