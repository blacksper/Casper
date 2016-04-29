<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 29.04.2016
 * Time: 17:28
 */
include("CampaignTabs.class.php");

class CampaignViewer
{

    var $Head;
    var $Body;
    var $Footer;
    //var $menu;
    var $Tabs;
    var $Model;

    //var $MysqliClass;
    //var $tabsClass;


    function __construct($Model)
    {

        $this->Model = $Model;
        $this->Head = '';
        $this->Tabs = new CampaignTabs($this->Model);
        //<script src="my.js"></script>
        $this->Head .= "</head>";
        $this->Body = "<body>";
        $this->Footer = "";

    }

    public function ShowPage()
    {

        $outstr = $this->Head . $this->Body . "</body>" . $this->Footer;
        echo $outstr; //iconv("CP1252","UTF-8", $outstr);
    }


    public function ShowMain()
    {

        $this->Tabs->getMainTab();
        $this->Tabs->getScansTab();
        //$this->Tabs->getToolsTab();


        $menu = '<div class="tabs menu col-xs-3 col-md-2">
                                <ul class="nav nav-pills nav-stacked ">
                                    <li><a href="panel.php">В панель</a></li>
                                    <li><a href="#mainCampaign-tab" data-toggle="tab">Главная</a></li>
                                    <li><a href="#scansCampaign-tab" data-toggle="tab">Сканирования</a></li>
                                    <li><a href="#servers-tab" data-toggle="tab">Дочерные цели</a></li>


                                </ul>
                            </div>';
        $this->Body .= $menu . '
                            <div class="tab-content col-xs-10 col-md-6" >
                            ' . $this->Tabs->allHtml . '
                            </div>
                        ';
    }


}