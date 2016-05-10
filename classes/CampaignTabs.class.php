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
        //var_dump($targetsArr);
        if (!isset($targetsArr))
            exit;

        $f = "<div class='row'><div class='col-md-8'>";
        $f .= "";
        $f .= $this->getMainTable($targetsArr);

        $f .= "</div>";

        $f .= "<div class='col-md-4'> <textarea id='targetsArea' class='form-control custom-control'></textarea><button id='addTargets' style='float: right;margin-top: 10px;' class='btn btn-success'>Добавить цели</button></div></div>";

        $tmpHtml = '<div class="tab-pane fade in active" id="mainCampaign-tab">
                                    ' . $f . '
                                </div>';

        //var_dump($tmpHtml);
        $this->allHtml .= $tmpHtml;
    }

    function getMainTable($targetsArr)
    {
        $result = "<table id='targetsContent' class='table table-hover'>
            <thead>
            <tr>
            <th>Url</th>
            <th>info</th>
            <th>btns</th>
            </tr>
            </thead>
            <tbody>";
        foreach ($targetsArr as $target) {
            $result .= $this->getMainTableRow($target);
        }
        $result .= "</tbody></table>";
        //var_dump($result);
        return $result;
    }

    function getMainTableRow($row)
    {
        $btns = '<button type="button" class="btn btn-danger btn-sm deleteTgt">
                            <span  class="glyphicon glyphicon-remove" aria-hidden="true">
                            </span>
                        </button>';

        $result = "<tr class='targetRow' data-tid='{$row['tid']}'><td>{$row['url']}</td><td>{$row['ip']}</td><td>$btns</td></tr>";
        return $result;
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

        $hashesArr = $this->Model->MysqliClass->getAssocArray("SELECT * FROM hashes WHERE deleted=0 ORDER BY dateAdd DESC");
        if (!empty($hashesArr))
            $hashContent = $this->getHashContentTable($hashesArr);
        else
            $hashContent = "pusto";

        $this->allHtml .= '
                        <div class="tab-pane fade" id="tools-tab">
                            <ul class="nav nav-pills ">
                                    <li class="active"><a href="#gl" data-toggle="tab">Scanner</a></li>
                                    <li><a href="#gg" data-toggle="tab">BRUTEFORCE</a></li>
                                    <li><a href="#nmap" data-toggle="tab">Nmap</a></li>
                                    <li><a href="#hashMaker" data-toggle="tab">hashmaker</a></li>

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

                                <div class="tab-pane fade" id="nmap">
                                    <form method="post" action="../scan.php" id="fileselect" class="navbar-form navbar-left">
                                        <div class="form-group">
                                            <select class="form-control" name="tid">
                                            <option selected="selected">Choose target</option>
                                            ' . $urls . '
                                            </select>


                                            <select class="form-control" name="option" >
                                                <option value="quick" selected="quick">quick scan</option>
                                                <option value="quickplus" >quick scan plus versions</option>

                                            </select>


                                            <input type="hidden" name="action" value="nmap">
                                            <input type="submit" class="btn btn-default">
                                        </div>
                                    </form>
                                </div>


                                <div class="tab-pane fade" id="hashMaker">


                                        <ul class="nav nav-pills ">
                                            <li class="active"><a href="#hashesTab" data-toggle="tab">Hashes</a></li>
                                            <li><a href="#hashesAdd" data-toggle="tab">add</a></li>
                                        </ul>
                                        <br>

                                    <div class="tab-content">
                                        <div class="tab-pane fade in active" id="hashesTab">
                                            ' . $hashContent . '
                                        </div>
                                        <div class="tab-pane fade" id="hashesAdd">
                                            <div class="form-inline">
                                                <select class="form-control" id="hashType" >
                                                    <option selected="quick">select hash type</option>
                                                    <option value="md5" >MD5</option>
                                                    <option value="sha1" >SHA-1</option>
                                                    <option value="wordpress3" >WordPress v3+</option>
                                                    <option value="mysqlOld" >Mysql old</option>
                                                    <option value="mysql" >Mysql</option>
                                                </select>
                                                <input id="strForHash" type="text" class="form-control" >
                                                <button id="addHash" class="btn btn-default">getHash</button>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                       ';
    }

    function getHashContentTable($hashesArr)
    {
        $table = '<table class="table table-hover" id="hashesContent">';
        $thead = '<thead>
                    <th>hash</th>
                    <th>source</th>
                    <th>type</th>
                    <th></th>
                </thead>';
        $tbody = '<tbody>';

        foreach ($hashesArr as $hash) {
            $tbody .= $this->getHashContentTableRow($hash);
        }
        $tbody .= "</tbody>";
        $table .= $thead . $tbody;
        $table .= "</table>";
        return $table;
    }

    function getHashContentTableRow($row)
    {
        $result = "";
        $result .= "<tr class='hashRow'>
            <td>{$row['hash']}</td>
            <td>{$row['source']}</td>
            <td>{$row['type']}</td>
            <td><button type='button' class='btn btn-danger btn-sm deleteScn'>
                <span  class='glyphicon glyphicon-remove' aria-hidden='true'></span>
            </button></td></tr>";
        return $result;
    }

    function getScansTab($cid)
    {
        //var_dump( $cid);
        $query = "select * from targets RIGHT JOIN scans on targets.tid=scans.tid where cid=$cid and scans.deleted=0 and targets.deleted=0 order by dateScan desc";
        $result = $this->Model->MysqliClass->getAssocArray($query);
        $tbody = "<tbody>";
        foreach ($result as $row)
            $tbody .= $this->getScansTableRow($row);


        $tbody .= "</tbody>";
        $this->allHtml .= '<div class="tab-pane fade" id="scansCampaign-tab">
                                      <div class="nav pol" id="scansCampaign">
                                          <table id="scansCampaignContent" class="table table-hover">
                                              <thead>
                                              <tr>
                                                  <th>date</th>
                                                  <th>type</th>
                                                  <th>url</th>
                                                  <th>filename</th>
                                                  <th>Status</th>
                                                  <th></th>
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

    }


    function getScansTableRow(array $row)
    {
        $result = "";
        $finished = "<span value='1' style='color: #5cb85c;font-size: 16px;' class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></span>";
        $proccessed = "<span value='0' style='color: goldenrod;font-size: 16px;' class='glyphicon glyphicon-hourglass' aria-hidden='true'></span>";

        $result .= "<tr class='scanRow' data-scid='{$row['scid']}'>
                    <td class='dateScan'>
                    <a class='btn btn-primary'>{$row['dateScan']}</a>
                    </td>
                     <td>{$row['type']}</td>
                    <td class='scanUrl'>
                    {$row['url']}
                    </td>

                    <td>{$row['filename']}</td>
                    <td>" . (($row['status'] == 1) ? $finished : $proccessed) . "</td>
                    <td><button type='button' class='btn btn-danger btn-sm deleteScn'>
                            <span  class='glyphicon glyphicon-remove' aria-hidden='true'>
                            </span>
                        </button></td>
                  </tr>";

        return $result;

    }

    function getScanDetails($scid)
    {
        $query = "select type from scans where scid=$scid";
        $type = $this->Model->MysqliClass->firstResult($query)['type'];

        $result = "";
        if (!isset($type))
            return 0;
        if (strstr($type, "nmap")) {

            $type = "nmap";
        }

        switch ($type) {
            case "subdomainScan":
                $result = $this->getSubdomainScanDetails($scid);
                break;
            case "dirScan":
                $result = $this->getDirScanDetails($scid);
                break;
            case "nmap":

                $result = $this->getNmapDetails($scid);
                break;
        }

        return $result;

    }

    function getSubdomainScanDetails($scid)
    {
        $foundPaths = $this->Model->MysqliClass->getAssocArray("select * from subdomain where scid=$scid ORDER BY resolve DESC");

        //var_dump($foundPaths);
        $goodPaths = "";
        if (empty($foundPaths)) {
            $goodPaths = '<div class="alert alert-warning">
                            <strong>Пусто</strong>
                       </div>';
        } else {
            $goodPaths = $this->getSubdomainScanTable($foundPaths);
        }

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

    function getSubdomainScanTable($foundPaths)
    {

        $table = '<table class="table table-hover">';
        $thead = '<thead><tr><th>path</th><th>resolve</th></tr></thead>';
        //var_dump( $foundPaths);
        $tbody = '';
        //$httpcode=$path['httpcode'];
        foreach ($foundPaths as $path) {
            $tbody .= '<tr class="' . (($path['resolve'] == 1) ? 'success' : 'danger') . '">
            <td>' . $path['subdomain'] . '</td>
            <td class="httpcode"><span >' . $path['resolve'] . '</span></td>

            </tr>';

        }
        $table .= $thead . $tbody . "</table>";

        return $table;
    }

    function getDirScanDetails($scid)
    {
        $foundPaths = $this->Model->MysqliClass->getAssocArray("select * from pathfound where scid=$scid order by httpcode asc");

        //var_dump($foundPaths);
        $goodPaths = "";
        if (empty($foundPaths)) {
            $goodPaths = '<div class="alert alert-warning">
                            <strong>Пусто</strong>
                       </div>';
        } else {
            $goodPaths = $this->getDirScanTable($foundPaths);
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

    function getDirScanTable($foundPaths)
    {

        $table = '<table class="table table-hover">';
        $thead = '<thead><tr><th>path</th><th>httpcode</th></tr></thead>';
        //var_dump( $foundPaths);
        $tbody = '';
        //$httpcode=$path['httpcode'];
        foreach ($foundPaths as $path) {
            $tbody .= '<tr class="' . (($path['httpcode'] == 200) ? 'success' : (($path['httpcode'] == 404) ? 'danger' : 'warning')) . '">
            <td>' . $path['url'] . '</td>
            <td class="httpcode"><span >' . $path['httpcode'] . '</span></td>

            </tr>';

        }
        $table .= $thead . $tbody . "</table>";

        return $table;
    }

    function getNmapDetails($scid)
    {
        $query = "select * from nmap where scid=$scid";
        $foundHosts = $this->Model->MysqliClass->getAssocArray($query);
        //var_dump($foundHosts);

        if (empty($foundHosts)) {
            //var_dump("watafuck");
            $goodPaths = '<div class="alert alert-warning">
                            <strong>Пусто</strong>
                       </div>';
        } else {
            $goodPaths = $this->getNmapScanTable($foundHosts);
        }


        $result = '<div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="gridSystemModalLabel">123456</h4>
                          </div>
                          <div class="modal-body">
                            <ul style="width: 300px" class="qwe nav nav-pills" >
                                <li class="active"><a  href="#found" data-toggle="tab">Found</a></li>

                            </ul >
                            <p>
                                <div class="tab-content" >

                                        <div class="tab-pane fade in active" id="found"  >
                                        ' . $goodPaths . ' </div>

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

    function getNmapScanTable($foundPaths)
    {

        $table = '<table class="table nmaptable table-hover">';
        $thead = '<thead><tr><th>port</th><th>status</th><th>service</th><th>version</th></tr></thead>';
        //var_dump( $foundPaths);
        $tbody = '';
        //$httpcode=$path['httpcode'];
        foreach ($foundPaths as $path) {
            $tbody .= '<tr class="' . (($path['status'] == "open") ? 'success' : (($path['status'] == "filtered") ? 'warning' : "danger")) . '">
            <td class="nmapport">' . $path['port'] . '</td>
            <td class="nmapstatus">' . $path['status'] . '</td>
            <td class="nmapservice"><span >' . $path['service'] . '</span></td>
            <td class="nmapversion"><span >' . $path['version'] . '</span></td>


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