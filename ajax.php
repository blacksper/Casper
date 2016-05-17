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
                        echo json_encode($Controller->addCampaign($name));
                    } elseif (isset($_POST['serverUrl'])) {
                        $url = $_POST['serverUrl'];
                        echo json_encode($Controller->addServer($url));
                    }

                    break;
                case "refresh":
                    if (isset($_POST['serverId'])) {
                        $sid = $_POST['serverId'];
                        echo json_encode($Controller->refreshStatus($sid));
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
                    echo intval($result);
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
                        echo $CampaignsController->CampaignViewer->Tabs->getDirScanDetails($scid);
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
                            echo($CampaignsController->addTargets($targetUrls, $cid));
                        }

                        break;
                    case "refresh":

                    case "delete":

                        break;

                    case "getNote":
                        if (isset($_POST['tid'])) {
                            $tid = (int)$_POST['tid'];
                            echo $CampaignsController->CampaignViewer->Tabs->getNote($tid);
                        }

                        break;
                    case "getScanDetails":
                        if (isset($_POST['scid'])) {
                            $scid = $_POST['scid'];

                            echo $CampaignsController->CampaignViewer->Tabs->getScanDetails($scid);
                        }
                        break;
                    case "addHash":
                        if (isset($_POST['cid'], $_POST['strForHash'], $_POST['type'])) {
                            //echo 123;
                            $cid = $_POST['cid'];

                            echo $CampaignsController->addHash($_POST['strForHash'], $_POST['type'], $cid);
                        }
                        break;

                    case "saveNote":
                        if (isset($_POST['tid'], $_POST['note'])) {
                            //echo 123;
                            $tid = $_POST['tid'];
                            $note = htmlspecialchars($_POST['note']);

                            echo $CampaignsController->saveNote($tid, $note);
                        }
                        break;

                }

            }


        break;

}
