<?php
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 07.10.2015
 * Time: 21:03
 */
set_time_limit(0);

$time_start = microtime(true);

$dirs=explode(";",$_POST['dirs']);
$t=$_POST['t'];
$ch=curl_init();
//curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch,CURLOPT_TIMEOUT,0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_HEADER, true);
$vara="";
foreach($dirs as $dir){
    curl_setopt($ch,CURLOPT_URL,$t.$dir);
    curl_exec($ch);
    $code=curl_getinfo($ch,CURLINFO_HTTP_CODE);
    $length=curl_getinfo($ch,CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    $hd=curl_getinfo($ch);
    //print_r($hd);
    //if($code==200)
        $vara.=$dir." ".$code." ".$length."\n";
    //echo $hd;
}
curl_close($ch);
echo $vara;
$time_end = microtime(true);
    $time = $time_end - $time_start;
    echo "Process Time: {$time}";
/*
include 'Mysqli.class.php';
$mysqli=new MysqliClass();
if(isset($_POST['target'])&&isset($_POST['filename'])&&isset($_POST['type'])&&isset($_POST['server'])) {
    //print_r($_POST);
    //die();
    $filedata=file_get_contents('./txt/paths/'.$_POST['filename']);
    $filename=$_POST['filename'];
    $filedata=urlencode($filedata);
    $type=$_POST['type'];
    $target=$_POST['target'];
    $server=$_POST['server'];
    //echo $server."\n";
    //$serverid=
    $id=rand(100000,999999);
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,$server);
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,CURLOPT_TIMEOUT,5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $pd="type=$type&target=$target&filedata=$filedata&id=$id";
    curl_setopt($ch,CURLOPT_POSTFIELDS,$pd);
    $data=curl_exec($ch);
    curl_close($ch);
    echo $pd;
    echo $data;


    $query="INSERT INTO fscans VALUES($id,(select cid FROM servers where url='$server'),'$filename','$type',DEFAULT, (select tid FROM targets where url='$target'))";
    //echo $query;
    //die();
    $mysqli->Query($query);


    //print_r($_POST);


}elseif(isset($_POST['brute'])){
    print_r($_POST);
    $loginfile=$_POST['loginfile'];
    $passwordfile=$_POST['passwordfile'];

    //$query="INSERT INTO fscans VALUES($id,(select cid FROM servers where url='$server'),'$filename','$type',DEFAULT, (select tid FROM targets where url='$target'))";


    $logins=urlencode(file_get_contents("./txt/paths/".$loginfile));
    $passwords=urlencode(file_get_contents("./txt/paths/".$passwordfile));


    $target=$_POST['target'];
    $server=$_POST['server'];

    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,$server);
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,CURLOPT_TIMEOUT_MS,1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $pd="brute=1&target=$target&logins=$logins&passwords=$passwords";
    curl_setopt($ch,CURLOPT_POSTFIELDS,$pd);
    $data=curl_exec($ch);
    curl_close($ch);
    echo $data;
    echo $pd;

    //http://pavvvka.tw1.su/wp-login.php

}
*/
?>