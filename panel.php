<?php
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 06.04.2016
 * Time: 21:30
 */
//session_start();
include("./classes/Controller.class.php");
if(!isset($_SESSION['auth']))
    die(" <script>window.location.replace('./')</script>");

include("header.php");


if($_SESSION['auth']==true) {
    $Controller = new Controller();
    //var_dump($_GET);
    //if(empty($_GET))
    $Controller->Viewer->ShowMain();
    //$Viewer->ShowMain();


    //if(isset($_GET['cid'])){
    //    $Controller->Viewer->ShowMain($_GET);
    //}



    $Controller->Viewer->ShowPage();

}


if(isset($_GET['logout'])){
    unset($_SESSION['auth']);
    echo "<script>window.location.replace('./')</script>";

    //var_dump($_SESSION);
   // die();
}
