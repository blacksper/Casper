<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 29.04.2016
 * Time: 17:26
 */
//session_start();

include("CampaignViewer.class.php");
include("Model.class.php");


class CampaignsController
{

    public $Viewer;
    public $servers;
    public $Model;

    //public $uid;

    function __construct($cid)
    {


        $this->Model = new Model();

        $query = "select * from campaigns where cid=$cid and deleted=0";
        $result = $this->Model->MysqliClass->firstResult($query);
        if (empty($result)) {
            $this->doRedirect();
            exit();
        }


        $this->CampaignViewer = new CampaignViewer($this->Model, $cid);


    }

    function doRedirect()
    {
        echo 123123;
        header("Location: ./index.php");
    }


}