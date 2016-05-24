<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 29.04.2016
 * Time: 17:28
 */
include("CampaignTabs.class.php");
require($_SERVER['DOCUMENT_ROOT'] . "/config.php");
class CampaignViewer
{

    var $Head;
    var $Body;
    var $Footer;
    var $cid;
    var $Tabs;
    var $Model;

    //var $MysqliClass;
    //var $tabsClass;


    function __construct($Model)
    {
        //$this->cid = $cid;
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


    public function buildPage($cid)
    {
        //$this->Model->MysqliClass->firstResult("select ");
        $name = $this->Model->MysqliClass->firstResult("select name from campaigns where cid=$cid")['name'];
        $this->cid = $cid;
        //var_dump(123);

        $this->Tabs->getMainTab($this->cid);

        $this->Tabs->getScansTab($this->cid);
        $this->Tabs->getToolsTab($this->cid);
        //$this->Tabs->getInfoTab($this->cid);
        //echo 123;
        //$this->Tabs->getToolsTab();


        $menu = '<div class="tabs menu col-xs-3 col-md-2">
                    <div class="panel panel-default">
                        <ul class="nav nav-pills nav-stacked list-group">
                              <div class="panel-heading">
                                <h3 class="panel-title ">Кампания <strong>' . $name . '</strong></h3>
                              </div>
                            <!--<li  class="list-group-item campTitle"> \' . $this->cid . \'</li>-->

                            <li class="active"><a href="#mainCampaign-tab" data-toggle="tab">Главная</a></li>
                            <li ><a href="#scansCampaign-tab" data-toggle="tab">Сканирования</a></li>
                            <li><a href="#tools-tab" data-toggle="tab">Инструменты</a></li>

                            <!--<li><a href="#servers-tab" data-toggle="tab">Дочерные цели</a></li>-->

                            <li><a href="panel.php"><span class="glyphicon glyphicon-menu-left"></span> В панель</a></li>

                        </ul>
                    </div>
                </div>';
        $this->Body .= $menu . '
                            <div class="tab-content col-md-10" >
                            ' . $this->Tabs->allHtml . '
                            </div>
                        ';
    }


}