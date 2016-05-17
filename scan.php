<?php
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 21.04.2016
 * Time: 15:34
 */
session_start();
require($_SERVER['DOCUMENT_ROOT'] . "/config.php");
include "./classes/Tools.class.php";
isset($_POST['action'])?$action=$_POST['action']:$action="";


$Tools=new Tools();

if (isset($_POST['tid'], $_POST['action'])) {
    $tid = (int)$_POST['tid'];
    (isset($_POST['sid'])) ? $sid = $_POST['sid'] : $sid = 0;

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
        case "brute":
            if (($sid == 0) || ($tid == 0))
                return 0;
            $loginfile = $_POST['loginfile'];
            $passwordfile = $_POST['passwordfile'];

            if (!isset($loginfile, $passwordfile))
                return 0;

            $Tools->startBruteforce($loginfile, $passwordfile, $tid, $sid);

            break;

        case "nmap":
            if (isset($_POST['option']))
                $Tools->startNmap($tid, $sid, $_POST['option']);
            break;

        case "gitdump":
            $Tools->gitDump($tid, $sid, $action);
            break;


        default:
            break;
    }

}

