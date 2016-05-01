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
            $CampaignsController = new CampaignsController();


            if (isset($_POST['action'])) {
                $action = $_POST['action'];
                switch ($action) {
                    case "add":
                        if (isset($_POST['campaignName'])) {
                            $name = $_POST['campaignName'];
                            echo json_encode($Controller->addCampaign($name));
                        } elseif (isset($_POST['serverUrl'])) {
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
        }


        break;

}





/*
if(isset($_POST['targetUrl'])&&isset($_POST['add'])){
    $url=$_POST['targetUrl'];
    //$type=$_POST['sname'];
    echo json_encode($Controller->addTarget($url));
}

if(isset($_POST['serverUrl'])&&isset($_POST['add'])){
    $url=$_POST['serverUrl'];
    echo json_encode($Controller->addServer($url));
}

if(isset($_POST['serverId'])&&isset($_POST['refresh'])){
    $sid=$_POST['serverId'];
    echo json_encode($Controller->refreshStatus($sid));
}
*/






/*

if(isset($_POST['cid'])&&$_POST['action']=='ref'){
    $cid=$_POST['cid'];

    $result=$serverC->CheckClient($cid);
    echo $result;
    die();
}

if(isset($_POST['cid'])&&$_POST['action']=='del'){
    $cid=$_POST['cid'];
    $result=$serverC->DeleteClient($cid);
    echo $result;
}



if(isset($_POST['sname'])&&$_POST['action']=='add'){
    $url=$_POST['sname'];
    $type=$_POST['sname'];
    $result=$serverC->AddServer($url,$type);
    echo $result;
}
*/
