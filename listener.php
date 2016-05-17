<?php
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 30.03.2016
 * Time: 2:27
 */
//echo 123123123;
ignore_user_abort(1);
set_time_limit(0);

include './classes/ProcessingController.class.php';
$ProcessingClass = new ProcessingController();
if (!isset($_POST['result']))
    exit;
$results=json_decode($_POST['result'],1);
//$scanType=$results['scanType'];
//echo $_POST['result'];
//print_r($results);


$date=date('Y-m-d H:i:s');


//if($scanType=="dirScan")
/*foreach($results as $scid => $result){

}*/


//if($scanType=="subdomainScan")
foreach ($results as $scid => $results) {
    //echo 123;
    $query = "select * from scans where scid=$scid";
    echo $query;
    $scanType = $ProcessingClass->firstResult($query)['type'];


    switch ($scanType) {
        case "dirScan":
            $ProcessingClass->dirScanProc($results, $scid);
            break;

        case "subdomainScan":
            $ProcessingClass->subDomainScanProc($results, $scid);
            break;

        case "brute":
            $ProcessingClass->bruteForceProc($results, $scid);

            break;
        default:
            exit;
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