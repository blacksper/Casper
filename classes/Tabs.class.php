<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 08.04.2016
 * Time: 12:30
 */
//include("Tools.class.php");
class Tabs
{
    //var $MysqliClass;
    var $allHtml;
    //var $Tools;
    function __construct($Model){
        $this->Model=$Model;
        $this->GetMainTab();
        $this->getCampaignTab();
        $this->getServerTab();
        $this->getToolsTab();
        $this->getScansTab();
        //$this->Tools=new Tools($this->Model->MysqliClass);
    }


    function GetMainTab()//закладка главное
    {
        $tmpHtml="";
        if(isset($_GET['fsid'])) {

            $fsid=$_GET['fsid'];
            $query="select * from found where fsid=$fsid";
            $arr=$this->Model->MysqliClass->getAssocArray($query);
            $stack="<table class=table>";
            foreach ($arr as $row) {
                $stack.="<tr><td>".$row['data']."</td><td>".$row['httpcode']."</td></tr>";
            }
            $stack.="</table>";
            $tmpHtml .= '<div style="max-height: 300px;max-width: 500px;overflow-y: auto;" class="tab-pane fade in active" id="tab-1">';
            $tmpHtml .= "$stack";
            $tmpHtml .= "</div>";

        }else {
            $tmpHtml .= '<div class="tab-pane fade " id="tab-1">
                             <p>HELLO</p>
                        ';
            $tmpHtml .="</div>";
        }
        $this->allHtml.=$tmpHtml;
    }

    function getCampaignTab()
    {
        //$qweqwe=$this->getSubInfo();
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
            //if (!isset($row['pid'])) {
            $tbody .= $this->getCampaignTableRow($row);
//                foreach ($urlsArr as $row2) {
//
//                        $tbody .= "<tr class=\"campaignRow\" value='" . $row2['cid'] . "'>
//                        <td class=url> $i" . $row2['name'] . "</td>
//                        <td class='ip'> </td>
//                        </tr>";
//
//                }
//           // }
            $i++;
        }
        $tbody .= "</tbody>";
        ####################

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

    function getCampaignTableRow($row)
    {
        //echo 123;
        $result = '<tr class="campaignRow" data-cid="' . $row['cid'] . '">';
        $result .= '
                    <td class="url">
                        <a href="#spoiler-' . $row['cid'] . '" data-toggle="collapse" class="btn btn-primary">' . $row['name'] . '</a>

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

    //функция генерирует строку, которая содержит сканирования и хэши

    function getServerTab()//закладка серверы
    {
        $query = "SELECT * FROM servers where deleted=0";
        $arr = $this->Model->MysqliClass->getAssocArray($query);
        $thead = '<thead>
                    <tr>
                        <th>host</th>
                        <th>ip</th>
                        <th>status</th>
                        <th id="content"></th>
                    </tr>
                  </thead>';
        $tbody = "<tbody>";

        ########## Формирование таблицы
        foreach ($arr as $row) {
            $tbody.=$this->getServerTableRow($row);
        }

        $tbody .= "</tbody>";

        $table = '<table id="serverContent" class="table table-hover">
                    ' . $thead . '
                    ' . $tbody . '
                </table>';
        //echo htmlspecialchars($table);
        $tab = '<div class="tab-pane fade" id="servers-tab">
                                     <div class="nav pol" id="servers">
                                     <div class="navbar-form navbar-left">
                                        <div class="form-group">
                                            <button id="addServer" class="btn btn-success"  >Добавить сервер</button>
                                        </div>
                                        <input class="form-control" id="serverUrl" type="text">

                                    </div>
                                     </div>
        ' . $table . '
                                </div>';
        $this->allHtml .= $tab;

        return $tab;
    }

    function getServerTableRow($row)
    {
        $result = "";

        $codeArr = $this->getStatusByCode((int)$row['status']);

        $btns = '<div class="btn-group btns" value="' . $row['sid'] . '">


                        <button type="button" class="btn btn-info refresh">
                            <span  class="glyphicon glyphicon-refresh" aria-hidden="true">
                            </span>
                        </button>



                        <button type="button" class="btn btn-danger deleteSrv">
                            <span  class="glyphicon glyphicon-remove" aria-hidden="true">
                            </span>
                        </button>


                        <input class="btn btn-danger" type="hidden" name="sid" value=' . $row['sid'] . '>

                    </div>';

        $result .= "<tr class='serverRow' value='{$row['sid']}' data-sid='{$row['sid']}'>
                            <td class=url> " . $row['path'] . "</td>
                            <td class='ip'>" . $row['ip'] . "</td>
                            <td class='status {$codeArr['status']}'>{$codeArr['stmsg']}</td>
                            <td class='btns'>$btns</td>
                        </tr>";
        return $result;
    }

    function getStatusByCode(int $code)
    {
        $result = array();

        switch ($code) {
            case 0:
                $result = array("status" => "danger", "stmsg" => "BAD");
                break;
            case 1:
                //$status = "success";
                //$stmsg = "GOOD";
                $result = array("status" => "success", "stmsg" => "GOOD");
                break;
            case -1:
                //$status = "info";
                //$stmsg = "UNKNOWN";
                $result = array("status" => "info", "stmsg" => "UNKNOWN");
                break;
        }

        return $result;

    }

    function getToolsTab()//закладка инструменты
    {
        $handle=opendir(PATH_TXTP);
        $dirs='';//список директорий
        $urls='';//список целей
        $servers='';//
        $i=0;
        while($dir=readdir($handle)){
            if($i<2){
                $i++;
                continue;
            }
            $dirs.='<option>'.$dir."</option>";
        }

        $urlsArr=$this->Model->MysqliClass->getAssocArray("select tid,url from targets where deleted=0");
        if($urlsArr) {
            foreach ($urlsArr as $url) {
                $urls .= '<option value="' . $url['tid'] . '">' . $url['url'] . '</option>';
            }
        }

        $urlsArr=$this->Model->MysqliClass->getAssocArray("select sid,path from servers where deleted=0");
        if($urlsArr){
            foreach($urlsArr as $url){
                $servers.='<option value="'.$url['sid'].'">'.$url['path'].'</option>';
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
                                            '.$dirs.'
                                            </select>

                                            <select class="form-control" name="passwordfile">
                                            <option selected="selected">passwordfile</option>
                                            '.$dirs.'
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
                                            '.$dirs.'
                                            </select>
                                            <select class="form-control" name="action" >
                                                <option selected="selected">Option</option>
                                                <option value="dirScan" >path</option>
                                                <option>url param</option>
                                                <option>subdomain</option>
                                            </select>

                                            <select class="form-control" name="sid">
                                            <option selected="selected">Choose server</option>
                                            '.$servers.'
                                            </select>

                                            <input type="submit" class="btn btn-default">
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                       ';
    }

// " . $row['ip'] . "

    function getScansTab()
    {

        $this->allHtml .= '<div class="tab-pane fade in" id="scans-tab">
                                      <div class="nav pol" id="campaigns">
                                        <table>
                                        <tr>
                                            <td>sd</td>
                                        </tr>
                                        </table>


                                      </div>


                            </div>';


    }

    function getSubInfoTable($tid)
    {
        $res = $this->Model->MysqliClass->firstResult("select * from targets where tid=$tid");

        $scans = $this->getSubInfoScans($tid);
        $hashes = $this->getSubInfoHashes($tid);
        $childs = "";//$this->getSubInfoChilds($tid);


        $result = '<div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="gridSystemModalLabel">' . $res['url'] . '</h4>
                          </div>
                          <div class="modal-body">
                            <ul style="width: 300px" class="qwe nav nav-pills" >
                                <li class="active"><a  href="#scan-' . $tid . '" data-toggle="tab">Сканирования</a></li>
                                <li><a  href = "#logins-' . $tid . '" data-toggle = "tab" > Хеши </a></li >
                                <li><a  href = "#childs-' . $tid . '" data-toggle = "tab" > Дочерние цели </a></li >
                            </ul >
                            <p>
                                <div class="tab-content" >



                                        <div class="tab-pane fade in active scanTable" id = "scan-' . $tid . '" >
                                        ' .
            $scans
            . ' </div>
                                        <div class="tab-pane fade" id = "logins-' . $tid . '" >
                                             ' . $hashes . '
                                        </div >
                                        <div class="tab-pane fade" id = "childs-' . $tid . '" >
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