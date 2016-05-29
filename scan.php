<?php
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 21.04.2016
 * Time: 15:34
 */
//session_start();
//require($_SERVER['DOCUMENT_ROOT'] . "/config.php");
include "./classes/Tools.class.php";
isset($_POST['action'])?$action=$_POST['action']:$action="";
set_time_limit(0);

$Tools=new Tools();

if (isset($_POST['action'])) {
    // $tid = (int)$_POST['tid'];
    (isset($_POST['tid'])) ? $tid = (int)$_POST['tid'] : $tid = 0;
    (isset($_POST['sid'])) ? $sid = (array)$_POST['sid'] : $sid = 0;

    //var_dump($sid);
    //die();
    switch ($action) {
        case "dirScan":
        case "subdomainScan":
            if (isset($_POST['filename'])) {
                //echo 123;
                //$sid = (int)$_POST['sid'];
                $filename = $_POST['filename'];
                $Tools->startScanPath($action, $filename, $tid, $sid);
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

            $Tools->startBruteforce($type, $loginfile, $passwordfile, $tid, $sid);

            break;

        case "nmap":
            if (isset($_POST['option']))
                $Tools->startNmap($tid, $sid, $_POST['option']);
            break;

        case "gitdump":
            $Tools->gitDump($tid);
            break;

        case "downloadSrc":
            //echo 123123;
            if (isset($_POST['filename'], $_POST['filepath'])) {

                $f1 = pathinfo($_POST['filename']);
                $f2 = pathinfo($_POST['filepath']);
                if (!isset($f1['filename'], $f2['filename']))
                    return 0;
                $filename = $_POST['filename'];
                $filepath = $_POST['filepath'];

                if (($filename != "") && ($filepath != ""))
                    echo $Tools->downloadFile($filepath, $filename);
            }

            break;


        default:
            break;
    }

}

