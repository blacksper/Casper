<?php
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 06.04.2016
 * Time: 21:30
 */
//session_start();
include("Controller.class.php");
if(!isset($_SESSION['auth']))
    die(" <script>window.location.replace('./')</script>");

include("header.php");


if($_SESSION['auth']==true) {
    $Controller = new Controller();
    $Controller->Viewer->ShowMain();
    //$Viewer->ShowMain();
    $Controller->Viewer->ShowPage();

}


if(isset($_GET['logout'])){
    unset($_SESSION['auth']);
    echo "<script>window.location.replace('./')</script>";

    //var_dump($_SESSION);
   // die();
}
