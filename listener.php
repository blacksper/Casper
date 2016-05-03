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
include './classes/Mysqli.class.php';
$mysqliClass=new MysqliClass();

$results=json_decode($_POST['result'],1);
//$scanType=$results['scanType'];
//echo $_POST['result'];
//print_r($results);


$date=date('Y-m-d H:i:s');


//if($scanType=="dirScan")
/*foreach($results as $scid => $result){

}*/


//if($scanType=="subdomainScan")
foreach ($results as $scid => $result) {
    $scanType = $result['scanType'];

    switch ($scanType) {
        case "dirScan":
            $queryStart = "INSERT INTO pathfound(scid,url,httpcode,dateResult) VALUES";
            $query = $queryStart;
            $i = 0;
            foreach ($result as $infoArr) {
                $query .= "($scid,'{$infoArr['url']}','{$infoArr['httpcode']}','$date'),";
                $i++;
                if ($i == 100) {
                    $i = 0;
                    $query = substr($query, 0, -1);
                    $query .= " ON DUPLICATE KEY UPDATE httpcode=values(httpcode)";
                    echo $query . "\n";
                    $mysqliClass->query($query);
                    $query = $queryStart;
                }
            }
            $query = substr($query, 0, -1);
            $query .= " ON DUPLICATE KEY UPDATE httpcode=values(httpcode)";
            $mysqliClass->query($query);
            $query = "update scans set status=1 where scid=$scid";
            echo $query . "\n";
            $mysqliClass->query($query);
            break;

        case "subdomainScan":
            $queryStart = "INSERT INTO subdomain(scid,subdomain,resolve,dateResult) VALUES";
            $query = $queryStart;
            $i = 0;

            foreach ($result['data'] as $infoArr) {
                //var_dump($result['data']);
                //die();
                $resolve = intval($infoArr['resolve']);
                $query .= "($scid,'{$infoArr['subdomain']}',{$resolve},'$date'),";
                $i++;
                if ($i == 100) {
                    $i = 0;
                    $query = substr($query, 0, -1);
                    //$query.=" ON DUPLICATE KEY UPDATE httpcode=values(httpcode)";
                    echo $query . "\n";
                    $mysqliClass->query($query);
                    $query = $queryStart;
                }
            }

            $query = substr($query, 0, -1);
            $mysqliClass->query($query);
            $query = "update scans set status=1 where scid=$scid";
            echo $query . "\n";
            $mysqliClass->query($query);

            break;

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