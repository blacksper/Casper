<?php
/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 27.09.2015
 * Time: 17:56
 */
include "header.php";
session_start();

if(isset($_SESSION['auth'])) {
    echo "<script>window.location.href='panel.php'</script>";
    //  $html->Success();
    //$controller->ShowMain();
}else{
    echo "<body >
                    <div class='container' id='main'>
                        <div class='form-signin'>
                            <h2>Вход</h2>
                            <input class='sign-inp' type='text' id='username' placeholder='Username'>
                            <input class='sign-inp' type='password' id='password' placeholder='Password'>
                            <button class='btn btn-lg btn-primary btn-block' id='login'>Войти</button>
                        </div>
                    </div>
                </body>";
}


