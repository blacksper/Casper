<?php
declare(strict_types=1);
session_start();
include("./classes/Mysqli.class.php");

if(isset($_POST['username'])&&isset($_POST['password'])){
    echo 123123123;
    $mysqliClass=new MysqliClass();

    $username=preg_replace("/[^\w]+/","",$_POST['username']);
    $password=sha1($_POST['password']);

    $query="SELECT * FROM users WHERE username='$username' AND password='$password'";
    echo $query;
    $result=$mysqliClass->query($query);
    //var_dump($result) ;

    if($result->num_rows!==1) {
        mysqli_free_result($result);
        exit(0);
    }
    mysqli_free_result($result);

    $_SESSION['auth'] = true;
    $_SESSION['username'] = $username;
    $ip=$_SERVER['REMOTE_ADDR'];
    $query="UPDATE users SET dateLogin=NOW(),ip='$ip'";

    $result=$mysqliClass->query($query);
    if(!$result)
        exit(0);


   // echo "window.location.replace('panel.php')";
    echo "window.location.href='panel.php'";
}

?>