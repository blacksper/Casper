<?php
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 07.03.2015
 * Time: 20:04
 */

class Mysql {
    private $link;
    private $host='localhost';
    private $login='root';
    private $password='';

    function __construct(){

       $this->link=mysql_connect($this->host,$this->login,$this->password);

        mysql_select_db('casper_db',$this->link);
        mysql_query("SET NAMES 'utf8'");
       // print('Я СОЗДАЛЬ');
    }

    function GetNumericArray($query){
        //echo $query."<br>";
        $resultArr=array();
        $result= mysql_query($query,$this->link);

        $i=0;
        while($result1=mysql_fetch_array($result,MYSQL_ASSOC)) {//было SQL_NUM
            $resultArr[$i] =  $result1;
            //print_r($result1);
            $i++;
        }
       return $resultArr;
    }

    public function checkAuth($username,$password){
        $username = addslashes($username);
       // print_r($username);
        $query="SELECT username,password FROM users WHERE username='$username' && password='$password'";
        // echo $query;
        $combtrue = $this->GetNumericArray($query);

        if(isset($combtrue[0]['username'])&&isset($combtrue[0]['password']))
            return $combtrue;           //если логин и пароль вернулись, вернуть 1

}


    function Query($query){
        //echo $query;
        return $resource=mysql_query($query);

    }



    function QueryFirst($query)
    {
        $resource = mysql_query($query, $this->link);
        if (($resource == 0) || ($resource == 1)) {
            //echo 123;
            return $resource;
         }

       // echo $resource;
        ##это место может вызвать ошибку
        $result=@mysql_result($resource,0);
        if(!isset($result))
            $result=0;

        return $result;
    }








}