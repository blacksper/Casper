<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 20.04.2016
 * Time: 19:20
 */
//file_put_contents(time().".txt","qwertyy");
set_time_limit(100);
include("dirScan.class.php");
include("subdomainScan.class.php");

(isset($_POST['execute']))?$ar=json_decode($_POST['execute'],1):$ar="";
print_r($ar);
if(!empty($ar)) {
    foreach ($ar as $scid => $b) {

        $action = $b['action'];
        switch ($action) {
            case "dirScan":
                if (isset($b['url'])) {
                    //echo "here";
                    $target = $b['url'];
                    //$scid = $b['scid'];
                    $urls = $b['data'];

                    $dirscanC = new DirScan($target, $scid);
                    $dirscanC->scan($urls);
                }
                break;
            case "subdomainScan":
                $target = $b['url'];
                $subdomains = $b['data'];
                $subdomainC=new SubdomainScanClass($target,$scid);
                $subdomainC->scan($subdomains);

                break;


        }
    }
}