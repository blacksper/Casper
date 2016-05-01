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
    public $Model;

    //public $uid;

    function __construct($cid = null)
    {


        $this->Model = new Model();
        if ($cid != null) {
            $query = "select * from campaigns where cid=$cid and deleted=0";
            $result = $this->Model->MysqliClass->firstResult($query);
            if (empty($result)) {
                //$this->doRedirect();
                echo "tut dolzen bit redirect";
                exit();
            }

        }
        $this->CampaignViewer = new CampaignViewer($this->Model);


    }

    function doRedirect()
    {
        echo 123123;
        header("Location: ./index.php");
    }


}