<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 01.05.2016
 * Time: 1:08
 */
include("Mysqli.class.php");

class CampaignModel
{

    // var $Model;
    public $MysqliClass;

    function __construct()
    {
        $this->MysqliClass = new MysqliClass();
    }

    function getUserId($username)
    {

        $query = "SELECT uid from users where username='$username'";
        $result = $this->MysqliClass->firstResult($query)['uid'];
        return $result;
    }


}