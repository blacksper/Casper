<html>
<?php
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 29.04.2016
 * Time: 17:11
 */

include "./classes/CampaignsController.class.php";

if (isset($_GET['cid'])) {
    $cid = (int)$_GET['cid'];
    $v = new CampaignsController($cid);
    include "header.php";
    $v->getPage($cid);
    $v->Viewer->ShowPage();
}
?>
</html>
