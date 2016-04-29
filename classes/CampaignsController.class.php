<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 29.04.2016
 * Time: 17:26
 */
session_start();

include("CampaignViewer.class.php");
include("Model.class.php");


class CampaignsController
{

    public $Viewer;
    public $servers;
    public $Model;
    public $uid;

    function __construct()
    {

        $this->Model = new Model();
        $this->CampaignViewer = new CampaignViewer($this->Model);


    }


}