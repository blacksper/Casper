<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 20.04.2016
 * Time: 19:20
 */
//file_put_contents(time().".txt","qwertyy");
ignore_user_abort(1);
set_time_limit(0);
error_reporting(E_ALL);

//file_put_contents("dd.txt",print_r($_SERVER,1));

//sleep(5);
$fp = fopen("gg" . rand(1000, 200000) . '.txt', 'w+');
fwrite($fp, print_r($_POST, 1));
fclose($fp);

//die();


include("Main.class.php");
include("dirScan.class.php");
include("subdomainScan.class.php");

(isset($_POST['execute'])) ? $ar = json_decode($_POST['execute'], 1) : $ar = "";


//var_dump($ar);
if(!empty($ar)) {
    foreach ($ar as $scid => $b) {

        $action = $b['action'];
        switch ($action) {
            case "dirScan":
                //echo 123123;
                if (isset($b['url'])) {
                    //echo "here";
                    $target = $b['url'];
                    $urls = $b['data'];
                    // echo "start scan";
                    $dirscanC = new DirScan($target, $scid);
                    $dirscanC->startScan($urls);
                }
                break;
            case "subdomainScan":
                // echo 12;
                $target = $b['url'];
                $subdomains = $b['data'];
                $subdomainC=new SubdomainScanClass($target,$scid);
                $subdomainC->startScan($subdomains);

                break;

            case "brute":
                include("Bruteforce.class.php");
                // echo 12;
                $target = $b['url'];
                $source = $b['data'];
                //$logins = $b['logins'];
                //var_dump($passwords);
                //var_dump($logins);
                //die();

                $subdomainC = new Bruteforce($target, $scid);
                $subdomainC->startScan($source);

                break;


        }
    }
}