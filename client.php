<?php
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 10.10.2015
 * Time: 3:00
 */


 if(isset($_POST['check'])&&isset($_POST['paths'])&&isset($_POST['url'])){
     $url=urldecode($_POST['url']);
     $paths=explode("\n",$_POST['paths']);
    // $paths=file_get_contents($_POST['paths']);
     //$paths=$_POST['paths'];

     //echo $paths;
     $f='';
    // print_r($paths);
     //die();
     $count=count($paths);
     $ch = curl_init();
     for($i=0;$i<$count;$i++) {
         curl_setopt($ch, CURLOPT_URL,$url.'/'.$paths[$i]);
         curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
         curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
         curl_setopt($ch, CURLOPT_TIMEOUT, 5);
         $data = curl_exec($ch);
         $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
         //if($httpcode==200)
             $f.=$httpcode.";".$url."/".$paths[$i];
         //echo $httpcode;

     }

     curl_close($ch);

     file_put_contents('qweqwe.txt',$f);


 }
 
 ?>