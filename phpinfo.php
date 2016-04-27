<?php
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 26.03.2016
 * Time: 20:20
 */


//$mysqli = new mysqli('localhost', 'root', '', 'casper_db');
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,"http://pidor.localhost/index.php");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: pidor.localhost'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_VERBOSE,1);
curl_setopt($ch,CURLINFO_HEADER_OUT,1);




$data= curl_exec($ch);

$ff= curl_getinfo($ch,CURLINFO_HEADER_OUT);
$code=curl_getinfo($ch,CURLINFO_HTTP_CODE);

//echo $ff;
//echo $code;
echo $data;
curl_close($ch);




