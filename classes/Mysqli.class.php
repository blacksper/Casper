<?php
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 06.04.2016
 * Time: 21:52
 */
declare(strict_types=1);
include "./config/db.php";
class MysqliClass{

    var $link;

    function __construct(){

        $this->link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }

        mysqli_select_db($this->link, DB_NAME);

    }


    function query($query){
        $result=mysqli_query($this->link,$query);

        return $result;
    }

    function firstResult($query){
        $result=mysqli_query($this->link,$query);
        //mysqli_use_result()
        $row="";
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            mysqli_free_result($result);
        }
        //var_dump($row);
        return $row;
    }



    function getAssocArray(string $query){
        //echo $query."<br>";
        $resultArr=array();
        $result= mysqli_query($this->link,$query);
        $i=0;
        if($result) {
            while ($result1 = mysqli_fetch_array($result, MYSQLI_ASSOC)) {//было SQL_NUM
                $resultArr[$i] = $result1;
                //print_r($result1);
                $i++;
            }
            mysqli_free_result($result);
        }else{

            $resultArr=0;
        }


        return $resultArr;
    }









    function __destruct()
    {
        mysqli_close($this->link);
    }

}