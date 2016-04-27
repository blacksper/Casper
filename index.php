<?php
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 27.09.2015
 * Time: 17:56
 */
include "header.php";
session_start();

if(isset($_SESSION['auth'])) {
    echo "<script>window.location.href='panel.php'</script>";
    //  $html->Success();
    //$controller->ShowMain();
}else{
    echo "<body >
                    <div class='container' id='main'>
                        <form class='form-signin'>
                            <h2>Sign in</h2>
                            <input class='sign-inp' type='text' id='username' placeholder='Username'>
                            <input class='sign-inp' type='password' id='password' placeholder='Password'>
                            <button class='btn btn-lg btn-primary btn-block' id='login' type='submit'>Login</button>
                        </form>
                    </div>
                </body>";
}








/*
include "Controller.class.php";
//include "ControllerServer.class.php";
$controller=new Controller();
//$ee=$controller->model->GetNumericArray("select * from servers");
//print_r($ee);
//$controller->ShowMain();


if(isset($_POST['username'])&&isset($_POST['password'])){
    $password = md5($_POST['password']);

    if($combtrue=$controller->model->checkAuth($_POST['username'],$password)) {  //если там чёт есть, то лог и пасс верны
        $_SESSION['auth'] = true;
        $ip=$_SERVER['REMOTE_ADDR'];
        $controller->model->Query("update users set last_login=now(),last_ip='$ip'");
    }


}
//если сессия есть то вход
if(@$_SESSION['auth']) {
  //  $html->Success();
    //$controller->ShowMain();
}else{
    $controller->view->ShowLoginForm();
}

if(isset($_GET['logout'])){
    unset($_SESSION['auth']);
    $controller->view->Head="<script>window.location.replace('./')</script>";
}

if(isset($_POST['tname'])&&isset($_POST['add'])){
    //$serverC=new ControllerServer();
    $url=$_POST['tname'];
    //$type=$_POST['sname'];
    $result=$controller->AddTarget($url);
    //echo $result;
    $controller->view->Head="<script>window.location.replace('./')</script>";
}

if(isset($_POST['tid'])&&isset($_POST['del'])){
    //$serverC=new ControllerServer();
    $tid=$_POST['tid'];
    //$type=$_POST['sname'];
    $result=$controller->deleteTarget($tid);
    //echo $result;
    $controller->view->Head="<script>window.location.replace('./')</script>";
}

if(isset($_POST['sname'])&&isset($_POST['add'])){
    //$serverC=new ControllerServer();
    $url=$_POST['sname'];
    //$type=$_POST['sname'];
    $result=$controller->AddServer($url);
    //echo $result;
    $controller->view->Head="<script>window.location.replace('./')</script>";
}

if(isset($_POST['sid'])&&isset($_POST['del'])){
    //$serverC=new ControllerServer();
    $sid=$_POST['sid'];
    //$type=$_POST['sname'];
    $result=$controller->deleteServer($sid);
    //echo $result;
    $controller->view->Head="<script>window.location.replace('./')</script>";
}

if(isset($_POST['sid'])&&isset($_POST['ref'])){
    //$serverC=new ControllerServer();
    $cid=$_POST['sid'];
    $result=$controller->CheckClient($cid);
    echo $result;
    //echo $result;
    $controller->view->Head="<script>window.location.replace('./')</script>";
}
if(isset($_POST['cid'])&&$_POST['action']=='ref'){

    die();
}




$controller->view->ShowPage();
*/
