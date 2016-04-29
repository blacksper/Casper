<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 29.09.2015
 * Time: 22:45
 */

require($_SERVER['DOCUMENT_ROOT'] . "/config.php");
//include("Mysqli.class.php");
include("Tabs.class.php");


class Viewer
{
    var $Head;
    var $Body;
    var $Footer;
    //var $menu;
    var $Tabs;
    var $Model;

    //var $MysqliClass;
    //var $tabsClass;


    function __construct($Model){

        $this->Model=$Model;
        $this->Head = '';
        $this->Tabs=new Tabs($this->Model);
        //<script src="my.js"></script>
        $this->Head .= "</head>";
        $this->Body = "<body>";
        $this->Footer = "";

    }

    public function ShowPage(){

        $outstr = $this->Head . $this->Body . "</body>" . $this->Footer;
        echo $outstr; //iconv("CP1252","UTF-8", $outstr);
    }


    public function ShowMain(){

        if (isset($getArr['cid']))
            $this->Tabs->getCampaignTab($getArr['cid']);
        else
            $this->Tabs->getCampaignTab();

        //$this->Tabs->getCampaignTab();
        $this->Tabs->getServerTab();

        $this->Tabs->getToolsTab();

        $menu = '<div class="tabs menu col-xs-3 col-md-2">
                                <ul class="nav nav-pills nav-stacked ">
                                    <li><a href="#tab-1" data-toggle="tab">Главная</a></li>
                                    <li><a href="#campaigns-tab" data-toggle="tab">Campaigns</a></li>
                                    <li><a href="#servers-tab" data-toggle="tab">Сервера</a></li>
                                    <li><a href="#tools-tab" data-toggle="tab">Инструменты</a></li>
                                    <li><a href="#scans-tab" data-toggle="tab">Сканирования</a></li>

                                    <li><a href="'.$_SERVER['PHP_SELF'].'?logout=1">Выход</a></li>
                                </ul>
                            </div>';
        $this->Body .= $menu . '
                            <div class="tab-content col-xs-10 col-md-6" >
                            ' . $this->Tabs->allHtml . '
                            </div>
                        ';
    }

    public function GetTabs()//подгрузка всех закладок
    {
        //$this->GetMainTab();
       // $this->tabs.=$this->tabsClass->allHtml;
        //$this->GetServerTab();
        //$this->GetToolsTab();
    }




    public function ShowLoginForm($message = "", $alert = 0){ //alert=1- red;

        $this->Body .= "
                ";
    }

}

?>