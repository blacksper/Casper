<?php
set_time_limit(100);
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 16.10.2015
 * Time: 15:14
 */


$ch = curl_init();
//var_dump($serverUrl);
curl_setopt($ch, CURLOPT_URL, "http://casper.localhost/clientside/execute.php");
//echo $serverUrl;
curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1);
//curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_NOBODY, 1);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100);
//echo $enc;
curl_setopt($ch, CURLOPT_POSTFIELDS, "123");
curl_exec($ch);
//curl_close($ch);


die();


$socket = socket_create(AF_INET, SOCK_STREAM, 0);
$addres = "casper.localhost";
$port = 80;

$result = socket_connect($socket, $addres, $port);
if ($result === false) {
    echo "Не получилось выполнить функцию socket_connect().\nПричина: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    echo "OK.\n";
}

$in = "POST /clientside/execute.php HTTP/1.1\r\n";
$in .= "Host: casper.localhost\r\n";
$in .= "Connection: Close\r\n\r\n";
$out = '';

echo "Отправка запроса HTTP HEAD...";
$arrOpt = array('l_onoff' => 1, 'l_linger' => 1);
socket_set_option($socket, SOL_SOCKET, SO_LINGER, $arrOpt);
socket_write($socket, $in, strlen($in));
//socket_send($socket, $in, strlen($in),MSG_OOB);
//usleep(10000);
//socket_shutdown($socket,2);
socket_close($socket);
echo "OK.\n";

echo "Получение ответа:\n\n";
$buf = 'Это мой буфер.';
//socket_recv($socket,$buf,2000,MSG_DONTWAIT);
//    echo "Не получилось выполнить socket_recv(); причина: " . (socket_last_error($socket)) . "\n";



echo $buf . "\n";
