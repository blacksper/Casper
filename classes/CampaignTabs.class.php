<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 29.04.2016
 * Time: 17:56
 */
class CampaignTabs
{
    //var $MysqliClass;
    var $allHtml;

    //var $Tools;
    function __construct($Model)
    {
        $this->Model = $Model;
        //$this->GetMainTab();
        //$this->getCampaignTab();
        //$this->getServerTab();
        //$this->getToolsTab();
        //$this->getScansTab();
        //$this->Tools=new Tools($this->Model->MysqliClass);
    }


    function getMainTab($cid)//закладка главное
    {
        $query = "select * from targets where cid=$cid and deleted=0";
        $targetsArr = $this->Model->MysqliClass->getAssocArray($query);
        $f = "<div class='row'><div class='col-md-6'><table id='targetsContent' class='table table-hover'>
            <thead>
            <tr>
            <th>Url</th>
            <th>info</th>
            </tr>
            </thead>";
        $f .= "<tbody>";
        foreach ($targetsArr as $target) {
            $f .= "<tr><td>{$target['url']}</td><td>{$target['ip']}</td></tr>";
        }
        $f .= "</tbody>";
        $f .= "</table></div>";

        $f .= "<div class='col-md-6'> <textarea class='form-control custom-control'></textarea><button style='float: right;margin-top: 10px;' class='btn btn-success'>Добавить цели</button></div></div>";

        $tmpHtml = '<div class="tab-pane fade in active" id="mainCampaign-tab">

                                    ' . $f . '


                                </div>';


        $this->allHtml .= $tmpHtml;
    }

    function getCampaignTab($cid = null)
    {
        //echo $cid;
        if (!isset($cid)) {
            $this->getMainCampaign();
        } else {
            $this->allHtml .= '<div class="tab-pane fade in active" id="campaigns-tab">
                                      <div class="nav pol" id="campaigns">
                                     12345678
                                      </div>

                               </div>';

        }

    }

    public function getMainCampaign()
    {
        $thead = '<thead>
                        <tr>
                            <th>name</th>
                            <th>ip</th>
                            <th></th>
                        </tr>
                      </thead>';
        $tbody = "<tbody>";

        $query = "select * from campaigns where deleted=0";
        $urlsArr = $this->Model->MysqliClass->getAssocArray($query);

        #################### Формирование таблицы
        $i = 0;
        foreach ($urlsArr as $row) {
            $tbody .= $this->getCampaignTableRow($row);
            $i++;
        }
        $tbody .= "</tbody>";
        ####################

        $table = '<table id="campaignContent" class="table table-hover">123123123
                        ' . $thead . '
                        ' . $tbody . '
                        </table>';

        $this->allHtml .= '<div class="tab-pane fade in active" id="campaigns-tab">
                                      <div class="nav pol" id="campaigns">
                                      <div  class="navbar-form navbar-left">
                                            <div class="form-group">
                                                <button id="addCampaign" class="btn btn-success">Добавить цель</button>
                                            </div>
                                            <input class="form-control" id="campaignName" type="text">

                                      </div>
                                      </div>
                                      ' . $table . '
                               </div>';
    }

    //функция генерирует строку, которая содержит сканирования и хэши

    function getCampaignTableRow($row)
    {
        //echo 123;
        $result = '<tr class="campaignRow" data-cid="' . $row['cid'] . '">';
        $result .= '
                    <td class="url">
                        <a href="?cid=' . $row['cid'] . '" class="btn btn-primary">' . $row['name'] . '</a>

                    </td>
                    <td class="ip"> ----</td>
                    <td class="btns">
                    <form method=post>
                        <button type="button" class="btn btn-danger deleteCmp">
                            <span class="glyphicon glyphicon-remove" aria-hidden="true">
                            </span>
                        </button>

                    </form>
                    </td>
                    ';
        $result .= '</tr>';

        //echo $result;
        return $result;
    }

// " . $row['ip'] . "

    function getToolsTab($cid)//закладка инструменты
    {
        $handle = opendir(PATH_TXTP);
        $dirs = '';//список директорий
        $urls = '';//список целей
        $servers = '';//
        $i = 0;
        while ($dir = readdir($handle)) {
            if ($i < 2) {
                $i++;
                continue;
            }
            $dirs .= '<option>' . $dir . "</option>";
        }

        $urlsArr = $this->Model->MysqliClass->getAssocArray("select tid,url from targets where cid=$cid and deleted=0");
        if ($urlsArr) {
            foreach ($urlsArr as $url) {
                $urls .= '<option value="' . $url['tid'] . '">' . $url['url'] . '</option>';
            }
        }

        $urlsArr = $this->Model->MysqliClass->getAssocArray("select sid,path from servers where deleted=0");
        if ($urlsArr) {
            foreach ($urlsArr as $url) {
                $servers .= '<option value="' . $url['sid'] . '">' . $url['path'] . '</option>';
            }
        }


        $this->allHtml .= '
                        <div class="tab-pane fade" id="tools-tab">
                            <ul class="nav nav-pills ">
                                    <li class="active"><a href="#gl" data-toggle="tab">Scanner</a></li>
                                    <li><a href="#gg" data-toggle="tab">BRUTEFORCE</a></li>

                            </ul>
                            <br>

                            <div class="tab-content" >
                                <div class="tab-pane fade" id="gg">
                                    <ul style="width: 300px" class="nav nav-pils nav-stacked">
                                            <li><a href="#gg1" data-toggle="tab">Wordpress</a></li>

                                    </ul>
                                </div>


                                <div class="tab-pane fade" id="gg1">
                                    <h1>Wordpress</h1>

                                    <form method="post" action="../cp.php" id="fileselect" class="navbar-form navbar-left">
                                        <div class="form-group">


                                           <select class="form-control" name="tid">
                                            <option selected="selected">Choose target</option>
                                            ' . $urls . '
                                            </select>

                                            <select class="form-control" name="loginfile">
                                            <option selected="selected">loginfile</option>
                                            ' . $dirs . '
                                            </select>

                                            <select class="form-control" name="passwordfile">
                                            <option selected="selected">passwordfile</option>
                                            ' . $dirs . '
                                            </select>

                                            <select class="form-control" name="sid">
                                            <option selected="selected">Choose server</option>
                                            ' . $servers . '
                                            </select>

                                            <input type="submit" name="brute" class="btn btn-default">
                                        </div>
                                    </form>

                                </div>


                                <div class="tab-pane fade in active" id="gl">
                                    <form method="post" action="../scan.php" id="fileselect" class="navbar-form navbar-left">
                                        <div class="form-group">


                                            <select class="form-control" name="tid">
                                            <option selected="selected">Choose target</option>
                                            ' . $urls . '
                                            </select>

                                            <select class="form-control" name="filename">
                                            <option selected="selected">Choose your file</option>
                                            ' . $dirs . '
                                            </select>
                                            <select class="form-control" name="action" >
                                                <option selected="selected">Option</option>
                                                <option value="dirScan" >path</option>
                                                <option>url param</option>
                                                <option value="subdomainScan">subdomain</option>
                                            </select>

                                            <select class="form-control" name="sid">
                                            <option selected="selected">Choose server</option>
                                            ' . $servers . '
                                            </select>

                                            <input type="submit" class="btn btn-default">
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                       ';
    }

    function getScansTab($cid)
    {
        //var_dump( $cid);
        $query = "select * from targets RIGHT JOIN scans on targets.tid=scans.tid where cid=$cid order by dateScan desc";
        $result = $this->Model->MysqliClass->getAssocArray($query);
        //if (empty($result))
        //    exit;

        $tbody = "<tbody>";


        foreach ($result as $row) {
            $tbody .= $this->getScansTableRow($row);

        }


        $tbody .= "</tbody>";

        $this->allHtml .= '<div class="tab-pane fade" id="scansCampaign-tab">
                                      <div class="nav pol" id="scansCampaign">
                                          <table id="scansCampaignContent" class="table table-hover">
                                              <thead>
                                              <tr>
                                                  <th>date</th>
                                                  <th>url</th>
                                                  <th>filename</th>
                                                  <th>Status</th>
                                                  <th>type</th>
                                              </tr>
                                              </thead>

                                          ' . $tbody . '

                                          </table>
                                        <button class="btn btn-default" data-toggle="collapse" data-target="#demo">Collapsible</button>
                                        <div id="demo" class="collapse">
                                        Some text..
                                        </div>


                                         </div>
                            </div>
                            ';
        //var_dump($this->allHtml);

    }

    function getScansTableRow(array $row)
    {
        $result = "";
        $finished = "<span style='color: #5cb85c;font-size: 16px;' class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></span>";
        $proccessed = "<span style='color: goldenrod;font-size: 16px;' class='glyphicon glyphicon-hourglass' aria-hidden='true'></span>";

        $result .= "<tr class='scanRow' data-scid='{$row['scid']}'>
                    <td class='dateScan'>
                    <a href='#' class='btn btn-primary'>{$row['dateScan']}</a>
                    </td>
                    <td class='scanUrl'>
                    {$row['url']}
                    </td>

                    <td>{$row['filename']}</td>
                    <td>" . (($row['status'] == 1) ? $finished : $proccessed) . "</td>
                    <td> </td>
                  </tr>";

        return $result;

    }

    function getDirScanDetails($scid)
    {
        $foundPaths = $this->Model->MysqliClass->getAssocArray("select * from pathfound where scid=$scid");

        //var_dump($foundPaths);
        $goodPaths = "";
        if (empty($foundPaths)) {
            $goodPaths = '<div class="alert alert-warning">
                            <strong>Пусто</strong>
                       </div>';
        } else {
            $goodPaths = $this->getTableScanResults($foundPaths);
        }

        //$res = $this->Model->MysqliClass->firstResult("select * from pathfound where scid=$scid where httpcode=404");
        //$res = $this->Model->MysqliClass->firstResult("select * from pathfound where scid=$scid where httpcode=200");

        $childs = "";//$this->getSubInfoChilds($tid);


        $result = '<div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="gridSystemModalLabel">123456</h4>
                          </div>
                          <div class="modal-body">
                            <ul style="width: 300px" class="qwe nav nav-pills" >
                                <li class="active"><a  href="#found" data-toggle="tab">Found</a></li>
                                <li><a  href = "#notFound" data-toggle = "tab" > Not Found </a></li >
                                <li><a  href = "#forbidden" data-toggle = "tab" > Forbidden </a></li >
                            </ul >
                            <p>
                                <div class="tab-content" >



                                        <div class="tab-pane fade in active" id="found"  >
                                        ' . $goodPaths . ' </div>
                                        <div class="tab-pane fade" id="notFound"  >
                                             321
                                        </div >
                                        <div class="tab-pane fade" id="forbidden"  >
                                             ' . $childs . '
                                        </div >
                                </div >
                            </p>
                            </div>
                    </div>
                ';
        //echo $result[$row['tid']];
        //}
        //echo (($wwsd[$row['tid']])? "beach":"treach");
        //print_r($result);
        return $result;
    }

    function getTableScanResults($foundPaths)
    {

        $table = '<table class="table table-hover">';
        $thead = '<thead><tr><th>path</th><th>httpcode</th></tr></thead>';
        //var_dump( $foundPaths);
        $tbody = '';
        //$httpcode=$path['httpcode'];
        foreach ($foundPaths as $path) {
            $tbody .= '<tr style="background-color:' . (($path['httpcode'] == 200) ? 'yellowgreen' : (($path['httpcode'] == 302) ? 'yellow' : "#BE7D77")) . ';">
            <td>' . $path['url'] . '</td>
            <td class="httpcode"><span >' . $path['httpcode'] . '</span></td>

            </tr>';

        }
        $table .= $thead . $tbody . "</table>";

        return $table;
    }


    function getSubInfoScans(int $cid)
    {

        $thead = '<div class="row scan-row">
                         <div class="col-sm-3">Тип</div>
                         <div class="col-sm-3">Заголовок2</div>
                         <div class="col-sm-3">Заголовок3</div>
                     </div>';
        $hashes = "";

        $scansArr = $this->Model->MysqliClass->getAssocArray("select * from scans where tid=$cid");//sqli

        if (empty($scansArr)) {
            $result = "<div class=\"alert alert-warning\">
                            <strong>Пусто</strong>
                       </div>";
        } else {
            $result = $thead;
            foreach ($scansArr as $row) {
                $result .= '
                        <div class="row scan-row">
                             <div class="col-sm-3">' . $row['type'] . '</div>
                             <div class="col-sm-3">' . $row['sid'] . '</div>
                             <div class="col-sm-3">' . $row['tid'] . '</div>
                         </div>
                    ';

            }
        }

        return $result;
    }

    function getSubInfoHashes(int $tid)
    {

        $thead = '<div class="row scan-row">
                         <div class="col-sm-3">Тип</div>
                         <div class="col-sm-3">Заголовок2</div>
                         <div class="col-sm-3">Заголовок3</div>
                     </div>';
        $hashes = "";

        $scansArr = $this->Model->MysqliClass->getAssocArray("select * from hashes where tid=$tid");//sqli

        if (empty($scansArr)) {
            $result = "<div class=\"alert alert-warning\">
                            <strong>Пусто</strong>
                       </div>";
        } else {
            $result = $thead;
            foreach ($scansArr as $row) {
                $result .= '
                        <div class="row scan-row">
                             <div class="col-sm-3">' . $row['source'] . '</div>
                             <div class="col-sm-3">' . $row['type'] . '</div>
                             <div class="col-sm-3">' . $row['hash'] . '</div>
                         </div>
                    ';

            }
        }

        return $result;
    }

    function getSubInfoChilds(int $tid)
    {

        $thead = '          <ul style="width: 300px" class="qwe nav nav-pills" >
                                <li class="active"><a  href="#existChilds" data-toggle="tab">Существующие</a></li>
                                <li><a  href = "#" data-toggle = "tab" > Добавить </a></li >
                            </ul >
                     ';


        $scansArr = $this->Model->MysqliClass->getAssocArray("select * from targets where pid=$tid");//sqli

        if (empty($scansArr)) {
            $result = "<div class=\"alert alert-warning\">
                            <strong>Пусто</strong>
                       </div>";
        } else {
            $result = $thead;
            $cont = "";
            foreach ($scansArr as $row) {
                $cont .= '
                        <div class="row child-row childUrl">
                             <div class="col-sm-2">' . $row['tid'] . '</div>
                             <div class="col-sm-6 ">' . $row['url'] . '</div>
                         </div>
                    ';
            }

            $result .= '   <div class="tab-content" >
                                <div class="tab-pane fade in active scanTable" id = "existChilds" >
                                    <div class="row child-row">
                                         <div class="col-sm-2">ид</div>
                                         <div class="col-sm-4">url</div>

                                    </div>
                                    ' . $cont . '
                                </div>
                            </div>';


        }


        return $result;
    }


}