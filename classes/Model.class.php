<?php
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 10.10.2015
 * Time: 2:04
 */

//include("ViewerServer.class.php");
//include("Mysql.class.php");
include("Mysqli.class.php");

//include("config.php");
class Model{

   // var $Model;
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