<?php
set_time_limit(100);
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 16.10.2015
 * Time: 15:14
 */


/*
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://casper.localhost/clientside/execute.php");
    //echo $serverUrl;
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    //echo $enc;
    curl_setopt($ch, CURLOPT_POSTFIELDS, "execute=qwe");
    echo curl_exec($ch);
    $qwe=curl_getinfo($ch,CURLINFO_HEADER_OUT);
    echo $qwe;

*/

$subs=file("txt/paths/subdomain.txt");

//$result1 = dns_get_record("qwe.wildo.ru");

//print_r($result);
//print_r($result1);
/*
$socket = socket_create(AF_INET, SOCK_STREAM, 0);
echo $addres;

$result = socket_connect($socket, $addres, $port);
if ($result === false) {
    echo "Не получилось выполнить функцию socket_connect().\nПричина: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    echo "OK.\n";
}

$in = "HEAD / HTTP/1.1\r\n";
$in .= "Host: casper.localhost\r\n";
$in .= "Connection: Close\r\n\r\n";
$out = '';

echo "Отправка запроса HTTP HEAD...";
socket_write($socket, $in, strlen($in));
echo "OK.\n";

echo "Получение ответа:\n\n";
$buf = 'Это мой буфер.';

    echo "Не получилось выполнить socket_recv(); причина: " . socket_strerror(socket_last_error($socket)) . "\n";

socket_close($socket);

echo $buf . "\n";
*/