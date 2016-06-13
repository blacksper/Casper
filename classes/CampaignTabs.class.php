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
    var $tabsHtml;

    function __construct()
    {
        //$this->Model = $Model;
        $this->tabsHtml = "";
        //$this->GetMainTab();
        //$this->getCampaignTab();
        //$this->getServerTab();

    }


    function getMainTab($targetsArr)//закладка главное
    {


        if (!isset($targetsArr))
            exit;

        $f = "<div class='row'><div class='col-md-8'>";
        $f .= "";
        $f .= $this->getMainTable($targetsArr);

        $f .= "</div>";

        $f .= "<div class='col-md-4'> <textarea placeholder='Список целей(url/ip-адреса)' id='targetsArea' class='form-control custom-control'></textarea><button id='addTargets' style='float: right;margin-top: 10px;' class='btn btn-success'>Добавить цели</button></div></div>";

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
            <th>CMS</th>
            <th></th>
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
        $btns = '<div class="btn-group btns">
                            <button type="button" class="btn btn-warning btn-sm editNote">
                            <span  class="glyphicon glyphicon-pencil" aria-hidden="true">
                            </span></button>
                            <button type="button" class="btn btn-danger btn-sm deleteTgt">
                            <span  class="glyphicon glyphicon-remove" aria-hidden="true">
                            </span></button>
                 </div>';

        $result = "<tr class='targetRow' data-tid='{$row['tid']}'><td class=' col-md-6'>{$row['url']}</td><td>{$row['cms']}</td><td class='text-right'>$btns</td></tr>";
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

        $urlsArr = $this->Model->getCampaigns();


        $i = 0;
        foreach ($urlsArr as $row) {
            $tbody .= $this->getCampaignTableRow($row);
            $i++;
        }
        $tbody .= "</tbody>";


        $table = '<table id="campaignContent" class="table table-hover">
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


        return $result;
    }


    function getToolsTab($targetsArr, $serversArr)//закладка инструменты
    {

//        $dirScanTab=$this->getDirscanTab($targetList,$servers,$dirs);
//        $nmapTab=$this->getNmapTab($targetList);
//        $hashMakerTab=$this->getHashmakerTab($hashesArr);
//        $gitDumperTab=$this->getGitdumperTab($targetList);
//        $cmsDetecterTab=$this->getCmsDetecterTab($targetList);

        $this->allHtml .= '
                        <div class="tab-pane fade" id="tools-tab">
                            <ul class="nav nav-pills ">
                                    <li class="active"><a href="#gl" data-toggle="tab">Scanner</a></li>
                                    <li><a href="#btfc" data-toggle="tab">Bruteforce</a></li>
                                    <li><a href="#nmap" data-toggle="tab">Nmap</a></li>
                                    <li><a href="#hashMaker" data-toggle="tab">hashmaker</a></li>
                                    <li><a href="#gitDumper" data-toggle="tab">gitDumper</a></li>
                                    <li><a href="#cmsDetecter" data-toggle="tab">cmsDetecter</a></li>

                            </ul>
                            <br>

                            <div class="tab-content" >



                                ' ./*$dirScanTab.$nmapTab.$hashMakerTab.$gitDumperTab.$cmsDetecterTab*/
            $this->tabsHtml . '


                            </div>
                        </div>
                       ';
    }


    function getDirscanTab($targetList, $servers, $files)
    {

        $tab = '        <div class="tab-pane fade in active" id="gl">
                            <div id="fileselect" class="navbar-form navbar-left">
                                <div class="form-group">
                                    ' . $targetList . '

                                    <select class="form-control" name="filename">
                                    <option selected="selected" value="0">Choose file</option>
                                    ' . $files . '
                                    </select>
                                    <select class="form-control options" name="action" >
                                        <option selected="selected">Option</option>
                                        <option value="dirScan" >path</option>
                                        <option value="subdomainScan">subdomain</option>
                                    </select>

                                    <input type="hidden" class="action" name="action" value="mscan">


                                <p><h4>Servers:</h4></p>
                                    ' . $servers . '

                                <p style="margin-top:10px"><button class="btn btn-default doScan">Начать сканирование</button></p>

                                </div>


                            </div>
                        </div>';
        $this->tabsHtml .= $tab;
    }

    function getNmapTab($targetList)
    {

        $tab = '       <div class="tab-pane fade" id="nmap">
                        <div id="fileselect" class="navbar-form navbar-left">
                            <div class="form-group">
                               ' . $targetList . '

                                <select class="form-control" name="option" >
                                    <option value="quick" selected="quick">quick scan</option>
                                    <option value="quickplus" >quick scan plus</option>

                                </select>

                                <input type="hidden" name="action" value="nmapScan" class="action">
                                <input type="submit" class="btn btn-default doScan">
                            </div>
                        </div>
                    </div>';
        $this->tabsHtml .= $tab;
    }

    function getHashmakerTab($hashesArr)
    {

        if (!empty($hashesArr))
            $hashContent = $this->getHashContentTable($hashesArr);
        else
            $hashContent = "pusto";//!!!!!!!!!!!!!!!!!!!!

        $tab = '      <div class="tab-pane fade" id="hashMaker">
                                        <ul class="nav nav-pills ">
                                            <li><button style="margin: 0px 0px 10px 0px;" class="btn btn-success" data-toggle="collapse" data-target="#hashesAdd">Add hash</button></li>
                                        </ul>

                        <div class="tab-content">
                            <div class="collapse" id="hashesAdd">
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

                            <div class="tab-pane fade in active" id="hashesTab">
                                ' . $hashContent . '
                            </div>
                        </div>
                    </div>';
        $this->tabsHtml .= $tab;
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
        $result .= "<tr class='hashRow' data-hid='{$row['hid']}'>
            <td>{$row['hash']}</td>
            <td>{$row['source']}</td>
            <td>{$row['type']}</td>
            <td><button type='button' class='btn btn-danger btn-sm deleteHash'>
                <span  class='glyphicon glyphicon-remove' aria-hidden='true'></span>
            </button></td></tr>";
        return $result;
    }

    function getGitdumperTab($targetList)
    {
        $tab = '  <div class="tab-pane fade" id="gitDumper">
                    <div class="row">
                        <div class="form-group col-md-8">
                         <div class="form-inline">

                                ' . $targetList . '

                            <input id="searchText" type="text" class="form-control" placeholder="Пример: .php">
                            <button id="searchGit" class="btn btn-default">Найти в именах файлах</button>

                            <input type="hidden" class="action" name="action" value="gitDump">
                            </div>
                         </div>
                    </div>
                </div>';
        $this->tabsHtml .= $tab;
    }

    function getCmsDetecterTab($targetList)
    {

        $tab = '  <div class="tab-pane fade" id="cmsDetecter">
                    <div class="row">
                        <div class="form-group col-md-6">
                         <div class="form-inline">
                              ' . $targetList . '

                            <button id="detectCMS" class="btn btn-default doScan">detectCms</button>
                            <input type="hidden" class="action" name="action" value="detectCms">
                            </div>
                         </div>
                    </div>
                </div>';
        $this->tabsHtml .= $tab;
    }

    function getWpBruteTab($targetList, $servers, $files)
    {

        $tab = '
                <div class="tab-pane fade" id="btfc">
                    <div class="tabbable tabs-left">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#wpBrute" data-toggle="tab">WordPress</a></li>
                          <li><a href="#dleBrute" data-toggle="tab">Datalife Engine</a></li>
                          <li><a href="#joomlaBrute" data-toggle="tab">Joomla</a></li>
                        </ul>
                        <div class="tab-content">
                                    <div class="tab-pane fade in active" id="wpBrute">
                                        <h1>Wordpress</h1>
                                        <div  id="fileselect" class="navbar-form navbar-left">
                                            <div class="form-group">
                                               ' . $targetList . '
                                                <select class="form-control" name="loginfile">
                                                <option selected="selected">Choose logins file</option>
                                                ' . $files . '
                                                </select>
                                                <select class="form-control" name="passwordfile">
                                                <option selected="selected">Choose passwords file</option>
                                                ' . $files . '
                                                </select>
                                                <p><h4>Servers:</h4></p>
                                                ' . $servers . '
                                                <p style="margin-top:10px"><button class="btn btn-default doScan">Начать сканирование</button></p>
                                                <input type="hidden" name="action" value="wpBrute" class="action">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="joomlaBrute">
                                        <h1>Datalife Engine</h1>
                                        <div  id="fileselect" class="navbar-form navbar-left">
                                            <div class="form-group">
                                               ' . $targetList . '
                                                <select class="form-control" name="loginfile">
                                                <option selected="selected">Choose logins file</option>
                                                ' . $files . '
                                                </select>
                                                <select class="form-control" name="passwordfile">
                                                <option selected="selected">Choose passwords file</option>
                                                ' . $files . '
                                                </select>
                                                <p><h4>Servers:</h4></p>
                                                ' . $servers . '
                                                <p style="margin-top:10px"><button class="btn btn-default doScan">Начать сканирование</button></p>
                                                <input type="hidden" name="action" value="joomlaBrute" class="action">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="dleBrute">
                                        <h1>Joomla</h1>
                                        <div  id="fileselect" class="navbar-form navbar-left">
                                            <div class="form-group">
                                               ' . $targetList . '
                                                <select class="form-control" name="loginfile">
                                                <option selected="selected">Choose logins file</option>
                                                ' . $files . '
                                                </select>
                                                <select class="form-control" name="passwordfile">
                                                <option selected="selected">Choose passwords file</option>
                                                ' . $files . '
                                                </select>
                                                <p><h4>Servers:</h4></p>
                                                ' . $servers . '
                                                <p style="margin-top:10px"><button class="btn btn-default doScan">Начать сканирование</button></p>
                                                <input type="hidden" name="action" value="dleBrute" class="action">
                                            </div>
                                        </div>
                                    </div>

                        </div>
                    </div>
                </div>';
        $this->tabsHtml .= $tab;
    }

    function getScansTab($scanArr)
    {
        //var_dump( $cid);

        $tbody = "<tbody>";
        foreach ($scanArr as $row)
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
                    <td class='dateScan col-md-2'>
                    " . (($row['type'] != "detectCms") ? "<a class='btn btn-primary'>{$row['dateScan']}</a>" : "{$row['dateScan']}") . "
                    </td>
                     <td class='col-md-2'>{$row['type']}</td>
                    <td class='scanUrl col-md-3'>
                    <div class='cc1'><div class='cc2'>{$row['url']}</div></div>
                    </td>

                    <td class='filename col-md-2'><div class='filenameS'><div class='cc2'>{$row['filename']}</div></div></td>
                    <td class='col-md-1'>" . (($row['status'] == 1) ? $finished : $proccessed) . "</td>
                    <td class='col-md-1'><button type='button' class='btn btn-danger btn-sm deleteScn'>
                            <span  class='glyphicon glyphicon-remove' aria-hidden='true'>
                            </span>
                        </button></td>
                  </tr>";

        return $result;

    }


    function getSubdomainScanDetails($foundSubs, $testedUrl)
    {

        $goodPaths = "";
        if (empty($foundSubs)) {
            $goodPaths = '<div class="alert alert-warning">
                            <strong>Пусто</strong>
                       </div>';
        } else {
            $goodPaths = $this->getSubdomainScanTable($foundSubs, $testedUrl);
        }

        $result = '<div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="gridSystemModalLabel">' . $testedUrl . '</h4>
                          </div>
                          <div class="modal-body">
                            <ul style="width: 300px" class="qwe nav nav-pills" >
                                <li class="active"><a  href="#found" data-toggle="tab">Found</a></li>
                                <!--<li><a  href = "#notFound" data-toggle = "tab" > Not Found </a></li >
                                <li><a  href = "#forbidden" data-toggle = "tab" > Forbidden </a></li >-->
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

        return $result;
    }


    function getSubdomainScanTable($foundPaths, $testedUrl)
    {
        preg_match("#(http[s]?:\/\/)?(www\.)?([\w.]*)\/?#", $testedUrl, $m);
        $url = $m[3];
        //if(!isset($m[2]))


        $table = '<table class="table table-hover">';
        $thead = '<thead><tr><th>subdomain</th><th>resolve</th></tr></thead>';
        //var_dump( $foundPaths);
        $tbody = '';
        //$httpcode=$path['httpcode'];
        foreach ($foundPaths as $path) {
            $tbody .= '<tr class="' . (($path['resolve'] == 1) ? 'success' : 'danger') . '">
            <td>' . $path['subdomain'] . "." . $url . '</td>
            <td class="httpcode"><span >' . $path['resolve'] . '</span></td>

            </tr>';

        }
        $table .= $thead . $tbody . "</table>";

        return $table;
    }

    function getDirScanDetails($foundPaths, $testedUrl)
    {
        //$foundPaths = $this->Model->getScansResult($scid, "dirScan", "httpcode asc");//->getAssocArray("select * from pathfound where scid=$scid order by httpcode asc");
        //$testedUrl = $this->Model->getTestedUrl($scid);

        //echo "select * from pathfound where scid=$scid order by httpcode asc\n";

        //var_dump($foundPaths);
        $goodPaths = "";
        if (empty($foundPaths)) {
            $goodPaths = '<div class="alert alert-warning">
                            <strong>Пусто</strong>
                       </div>';
        } else {
            $goodPaths = $this->getDirScanTable($foundPaths, $testedUrl);
        }


        $childs = "";//$this->getSubInfoChilds($tid);


        $result = '<div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="gridSystemModalLabel">' . $testedUrl . '</h4>
                          </div>
                          <div class="modal-body">
                            <ul style="width: 300px" class="qwe nav nav-pills" >
                                <li class="active"><a  href="#found" data-toggle="tab">Found</a></li>

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

        return $result;
    }

    function getDirScanTable($foundPaths, $testedUrl)
    {

        $table = '<table class="table table-hover">';
        $thead = '<thead><tr><th>path</th><th>httpcode</th></tr></thead>';
        //var_dump( $foundPaths);
        $tbody = '';
        //$httpcode=$path['httpcode'];
        foreach ($foundPaths as $path) {
            $tbody .= '<tr class="' . (($path['httpcode'] == 200) ? 'success' : (($path['httpcode'] == 404) ? 'danger' : 'warning')) . '">
            <td><a target="_blank" href="' . $testedUrl . $path['url'] . '">' . $testedUrl . $path['url'] . '</a></td>
            <td class="httpcode"><span >' . $path['httpcode'] . '</span></td>

            </tr>';

        }
        $table .= $thead . $tbody . "</table>";

        return $table;
    }

    function getNmapDetails($hostsArr, $testedUrl)
    {
        //$query = "select * from nmap where scid=$scid";
        //$hostsArr = $this->Model->getScansResult($scid, "nmap", "dateAdd desc");
        //$testedUrl = $this->Model->getTestedUrl($scid);


        if (empty($hostsArr)) {
            //var_dump("watafuck");
            $goodPaths = '<div class="alert alert-warning">
                            <strong>Пусто</strong>
                       </div>';
        } else {
            $goodPaths = $this->getNmapScanTable($hostsArr);
        }


        $result = '<div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="gridSystemModalLabel">' . $testedUrl . '</h4>
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

    function getBruteDetails($combinationsArr, $testedUrl)
    {
        if (empty($combinationsArr)) {
            $combsCont = '<div class="alert alert-warning">
                            <strong>Верных комбинаций не найдено</strong>
                       </div>';
        } else {
            $combsCont = $this->getBruteforceTable($combinationsArr);
        }

        $result = '<div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="gridSystemModalLabel">' . $testedUrl . '</h4>
                          </div>
                          <div class="modal-body">
                            <ul style="width: 300px" class="qwe nav nav-pills" >
                                <li class="active"><a  href="#found" data-toggle="tab">Valid</a></li>
                            </ul >
                            <p>
                                <div class="tab-content" >

                                        <div class="tab-pane fade in active" id="found"  >
                                        ' . $combsCont . ' </div>


                                </div >
                            </p>
                            </div>
                    </div>
                ';
        return $result;


    }

    function getBruteforceTable($combs)
    {

        $table = '<table class="table table-hover">';
        $thead = '<thead><tr><th>login</th><th>password</th></tr></thead>';
        //var_dump( $foundPaths);
        $tbody = '';
        //$httpcode=$path['httpcode'];
        foreach ($combs as $comb) {
            $tbody .= '<tr class="success">
            <td>' . $comb['login'] . '</td>
            <td class="httpcode"><span >' . $comb['password'] . '</span></td>

            </tr>';

        }
        $table .= $thead . $tbody . "</table>";

        return $table;
    }

    function getGitdumpDetails($filesArr, $testedUrl, $scid = 0)
    {
        //print_r($filesArr);
        //die();

        $table = '<table id="gitTable" class="table table-hover">';
        $thead = '<thead><tr><th>Имя файла</th><th>Путь до файла</th></tr></thead>';

        $tbody = $this->getGitdumpTable($filesArr, $testedUrl);

        if (!strstr($tbody, "<strong>Пусто</strong>"))
            $combsCont = $table . $thead . $tbody . "</table>";
        else $combsCont = $tbody;

        $result = '

             <div id="gitDumpTable" data-scid="' . $scid . '">
                <div class="row"><div >
                     <div class="form-group">
                        <input style="margin: 0 5px;" class="btn btn-primary doScan" type="submit" value="Обновить список файлов">
                        <input type="hidden" class="action" name="action" value="gitDump">
                    </div>

                        <div class="tab-content" >
                                <div class="tab-pane fade in active" id="found"  >
                                ' . $combsCont . ' </div>

                                    <div class="row" style="text-align:center;"><div class="col-md-12">
                                        <button id="moreGitRows" class="btn btn-info btn-sm">MORE <span class="glyphicon glyphicon-download"></span></button>
                                    </div>
                                 </div>
                        </div >
                    </div></div></div>
                ';
        return $result;
    }

    function getGitdumpTable($filesArr, $testedUrl)
    {

        //$filesArr = $this->Model->getScansResult($scid, "gitDump", "exist desc", $limit, $offset);
        //$query = "select * from gitdump where scid=$scid and filename like '%$like%' ORDER BY exist desc,dateAdd desc limit $offset,$limit";
        //$filesArr = $this->Model->MysqliClass->getAssocArray($query);
        //$testedUrl = $this->Model->getTestedUrl($scid);

        preg_match("@http[s]?:\/\/([\w\d.-]+)\/@", $testedUrl, $m);

        if (empty($filesArr)) {//!!!!!!!!вернуться сюда
            //if ($offset == 0)
            $tbody = '<div class="alert alert-warning">
                            <strong>Пусто</strong>
                       </div>';
            //else
            //    $tbody = "";
        } else {

            $tbody = '';
            foreach ($filesArr as $file) {
                $ext = pathinfo($file['filename'])["extension"];

                $tbody .= '<tr class="gitRow ' . (($file['exist'] == 1) ? 'success' : (($file['exist'] == 0) ? 'warning' : "danger")) . '">
            <td class="filename"><div class="cc1"><div class="cc2"><a target="_blank" href="' . PATH_GITD . "/" . $m[1] . "/" . $file['filename'] . (($ext == "php") ? ".txt" : '') . '"><span class="glyphicon glyphicon-link"></span></a> ' . $file['filename'] . '</div></div></td>
            <td class="filepath"><div class="cc1"><div class="cc2">' . $file['filepath'] . '</div></div></td>
            <td class="buttonload"><button class="btn btn-info btn-sm downSrc"><span class="glyphicon glyphicon-download-alt"></span></button></td>
            </tr>';
            }
        }


        return $tbody;
    }

//    function getSubInfoScans(int $cid)
//    {
//
//        $thead = '<div class="row scan-row">
//                         <div class="col-sm-3">Тип</div>
//                         <div class="col-sm-3">Заголовок2</div>
//                         <div class="col-sm-3">Заголовок3</div>
//                     </div>';
//        $hashes = "";
//
//        $scansArr = $this->Model->MysqliClass->getAssocArray("select * from scans where tid=$cid");//sqli
//
//        if (empty($scansArr)) {
//            $result = "<div class=\"alert alert-warning\">
//                            <strong>Пусто</strong>
//                       </div>";
//        } else {
//            $result = $thead;
//            foreach ($scansArr as $row) {
//                $result .= '
//                        <div class="row scan-row">
//                             <div class="col-sm-3">' . $row['type'] . '</div>
//                             <div class="col-sm-3">' . $row['sid'] . '</div>
//                             <div class="col-sm-3">' . $row['tid'] . '</div>
//                         </div>
//                    ';
//
//            }
//        }
//
//        return $result;
//    }

//    function getSubInfoHashes(int $tid)
//    {
//
//        $thead = '<div class="row scan-row">
//                         <div class="col-sm-3">Тип</div>
//                         <div class="col-sm-3">Заголовок2</div>
//                         <div class="col-sm-3">Заголовок3</div>
//                     </div>';
//        $hashes = "";
//
//        $scansArr = $this->Model->MysqliClass->getAssocArray("select * from hashes where tid=$tid");//sqli
//
//        if (empty($scansArr)) {
//            $result = "<div class=\"alert alert-warning\">
//                            <strong>Пусто</strong>
//                       </div>";
//        } else {
//            $result = $thead;
//            foreach ($scansArr as $row) {
//                $result .= '
//                        <div class="row scan-row">
//                             <div class="col-sm-3">' . $row['source'] . '</div>
//                             <div class="col-sm-3">' . $row['type'] . '</div>
//                             <div class="col-sm-3">' . $row['hash'] . '</div>
//                         </div>
//                    ';
//
//            }
//        }
//
//        return $result;
//    }

//    function getSubInfoChilds(int $tid)
//    {
//
//        $thead = '          <ul style="width: 300px" class="qwe nav nav-pills" >
//                                <li class="active"><a  href="#existChilds" data-toggle="tab">Существующие</a></li>
//                                <li><a  href = "#" data-toggle = "tab" > Добавить </a></li >
//                            </ul >
//                     ';
//
//
//        $scansArr = $this->Model->MysqliClass->getAssocArray("select * from targets where pid=$tid");//sqli
//
//        if (empty($scansArr)) {
//            $result = "<div class=\"alert alert-warning\">
//                            <strong>Пусто</strong>
//                       </div>";
//        } else {
//            $result = $thead;
//            $cont = "";
//            foreach ($scansArr as $row) {
//                $cont .= '
//                        <div class="row child-row childUrl">
//                             <div class="col-sm-2">' . $row['tid'] . '</div>
//                             <div class="col-sm-6 ">' . $row['url'] . '</div>
//                         </div>
//                    ';
//            }
//
//            $result .= '   <div class="tab-content" >
//                                <div class="tab-pane fade in active scanTable" id = "existChilds" >
//                                    <div class="row child-row">
//                                         <div class="col-sm-2">ид</div>
//                                         <div class="col-sm-4">url</div>
//
//                                    </div>
//                                    ' . $cont . '
//                                </div>
//                            </div>';
//
//
//        }
//
//
//        return $result;
//    }

    function getNote($note)
    {

        if (!$note)
            $result = 0;
        else
        $result = "<div class='modal-content'>
                    <div class='modal-header'>
                        <div class='form-group'>
                          <label for='Comment'>Заметка:</label>
                        </div>
                        <div class='modal-body'>
                            <textarea class='form-control' rows='5' id='noteText'>{$note['note']}</textarea>
                        </div>
                        <div class='modal-footer'>
                           <button id='saveNote' style='float: right;' class='btn btn-success' data-tid='{$note['tid']}'>Сохранить</button>
                        </div>
                     </div>
                </div>";


        //$result = json_encode($result);
        return $result;

    }


}