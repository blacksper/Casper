<?php
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 29.04.2016
 * Time: 17:11
 */

include "./classes/CampaignsController.class.php";

if (isset($_GET['cid'])) {
    $cid = $_GET['cid'];
    $v = new CampaignsController();
    include "header.php";
    $v->CampaignViewer->buildPage($cid);
    $v->CampaignViewer->ShowPage();
}