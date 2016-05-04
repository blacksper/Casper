<?php
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 21.04.2016
 * Time: 15:34
 */
session_start();
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

                //$sid = (int)$_POST['sid'];
                $filename = $_POST['filename'];
                $Tools->startScan($tid, $sid, $filename, $action);
            }

            break;
        case "brute":
            $str = array();
            $str[12]['url'] = "http://pavvvka.tw1.su/wp-login.php";
            $str[12]['logins'] = file("logins.txt");
            $str[12]['passwords'] = file("passwords.txt");
            $str[12]['action'] = "brute";

            echo json_encode($str);


            break;

        case "nmap":
            if (isset($_POST['option']))
                $Tools->startNmap($tid, $sid, $_POST['option']);
            break;


        default:
            break;
    }

}

