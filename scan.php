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


switch($action){
    case "dirScan":
    case "subdomainScan":
        if(isset($_POST['tid'],$_POST['sid'],$_POST['filename'])){
            $tid=(int)$_POST['tid'];
            $sid=(int)$_POST['sid'];
            $filename=$_POST['filename'];
            $Tools->startScan($tid,$sid,"./txt/paths/$filename",$action);
        }
        break;


    default:
        break;
}



