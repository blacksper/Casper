<?php
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 30.03.2016
 * Time: 2:27
 */
//echo 123123123;
include './classes/Mysqli.class.php';
$mysqliClass=new MysqliClass();

$results=json_decode($_POST['result'],1);
//echo $_POST['result'];
//print_r($results);

$date=date('Y-m-d H:i:s');
foreach($results as $sid => $result){
    foreach($result as $infoArr) {
        $query="insert into pathfound(scid,url,httpcode,dateResult) values($sid,'{$infoArr['url']}','{$infoArr['httpcode']}','$date') ON DUPLICATE KEY UPDATE httpcode={$infoArr['httpcode']}";
        echo $query."\n";
        $mysqliClass->query($query);
    }



}

/*
if(isset($_POST['result'])&&isset($_POST['id'])){
    $result=$_POST['result'];
    $result=urldecode($result);
    $arr=explode("\n",$result);
    $count=count($arr);
    $id=$_POST['id'];
    for($i=0;$i<$count;$i++){
        $ar=explode(";",$arr[$i]);
        $query="INSERT INTO found VALUES(DEFAULT,'$ar[0]',$id,$ar[1]) ";

        echo $query."\n";
        $mysql->Query($query);
        //die();
    }


    echo(123123);
}elseif(isset($_POST['result'])&&isset($_POST['brute'])){
    $result=$_POST['result'];
    $result=urldecode($result);
   $target=$_POST['target'];
    $arr=explode("\n",$result);
    var_dump($arr);

    foreach($arr as $res){
//
        $lp=explode(";",$res);
        if(!isset($lp[0])||!isset($lp[1]))
            continue;
        $lp[0]=trim($lp[0]);
        $lp[1]=trim($lp[1]);

        $query="INSERT INTO logins VALUES(DEFAULT,(select tid from targets where url='$target'),'$lp[0]','$lp[1]' ) ";
        $mysql->Query($query);
        echo $query."\n";
    }


    $count=count($arr);




//    for($i=0;$i<$count;$i++){
//        $ar=explode(";",$arr[$i]);
//        $query="INSERT INTO logins VALUES(DEFAULT,'$ar[0]',$id,$ar[1]) ";
//
//        echo $query."\n";
//        $mysql->Query($query);
//        //die();
//    }


    echo(123123);
}
*/