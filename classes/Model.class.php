<?php
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 10.10.2015
 * Time: 2:04
 */


include("Mysqli.class.php");

class Model{


    public $MysqliClass;

    function __construct(){
        $this->MysqliClass=new MysqliClass();
    }

    function getUserId($username){

        $query="SELECT uid from users where username='$username'";
        //echo $query;
        $result=$this->MysqliClass->firstResult($query)['uid'];
        return $result;
    }







} 