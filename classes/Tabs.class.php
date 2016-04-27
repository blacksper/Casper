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
        $this->GetTargetTab();
        $this->GetServerTab();
        $this->GetToolsTab();
        //$this->Tools=new Tools($this->Model->MysqliClass);
    }


    public function GetMainTab()//закладка главное
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

    function GetTargetTab(){
        //$qweqwe=$this->getSubInfo();
        $thead = '<thead>
                        <tr>
                            <th>url</th>
                            <th>ip</th>
                            <th></th>
                        </tr>
                      </thead>';
        $tbody = "<tbody>";

        $query="select * from targets where deleted=0";
        $urlsArr = $this->Model->MysqliClass->getAssocArray($query);

        #################### Формирование таблицы
        $i = 0;
        foreach ($urlsArr as $row) {
            if (!isset($row['pid'])) {
                $tbody .= $this->getTargetTableRow($row);
                foreach ($urlsArr as $row2) {
                    if ($row['tid'] == $row2['pid']) {
                        $tbody .= "<tr class=\"serverRow\" value='" . $row2['tid'] . "'>
                        <td class=url> $i" . $row2['url'] . "</td>
                        <td class='ip'> " . $row2['ip'] . "</td>
                        </tr>";
                    }
                }
            }
            $i++;
        }
        $tbody .= "</tbody>";
        ####################

        $table = '<table id="targetContent" class="table table-hover">
                        ' . $thead . '
                        ' . $tbody . '
                        </table>';

        $this->allHtml .= '<div class="tab-pane fade in active" id="add-targets">
                                      <div class="nav pol" id="targets">
                                      <div  class="navbar-form navbar-left">
                                            <div class="form-group">
                                                <button id="addTarget" class="btn btn-success">Добавить цель</button>
                                            </div>
                                            <input class="form-control" id="targetUrl" type="text">

                                      </div>
                                      </div>
                                      ' . $table . '

                            </div>';
    }

    function getTargetTableRow($row)
    {

        $result = "<tr class=targetRow data-tid='" . $row['tid'] . "'>
                <td class=\"url\">
                 <a href=\"#spoiler-" . $row['tid'] . "\" data-toggle=\"collapse\" class=\"btn btn-primary\">" . $row['url'] . "</a>
                 <div id='spoiler-" . $row['tid'] . "' class=\"fade collapse  wellm\" data-toggle=\"toggle\"></div>

                 <div class='collapse wellm' data-toggle='toggle'>
                 </div>
                 </td>
                    <td class='ip'> " . $row['ip'] . "</td>
                    <td class='btns'>" .
            '<form method=post>

                        <button type="button" class="btn btn-danger deleteTgt">
                            <span class="glyphicon glyphicon-remove" aria-hidden="true">
                            </span>
                        </button>
                        <input type=hidden name=tid value=' . $row['tid'] . '>
                    </form>
                    ' . "</td>

                    </tr>";

        // echo $result;
        return $result;
    }

    //функция генерирует строку, которая содержит сканирования и хэши

    function GetServerTab()//закладка серверы
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
        $tab = '<div class="tab-pane fade" id="add-servers">
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

    function GetToolsTab()//закладка инструменты
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
                        <div class="tab-pane fade" id="tools">
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

    function getSubInfoTable($tid)
    {

        $scans = $this->getSubInfoScans($tid);
        $hashes = $this->getSubInfoHashes($tid);


        $result = '
                            <ul style="width: 300px" class="qwe nav nav-pills" >
                                <li class="active"><a  href="#scan-' . $tid . '" data-toggle="tab">Сканирования</a></li>
                                <li><a  href = "#logins-' . $tid . '" data-toggle = "tab" > Хеши</a ></li >
                            </ul >
                            <p>
                                <div class="tab-content" >
                                        <div class="tab-pane fade in active scanTable" id = "scan-' . $tid . '" >
                                            ' .
            //((isset($wwsd[$row['tid']]))? $wwsd[$row['tid']]:"pusto")
            $scans
            . ' </div>
                                        <div class="tab-pane fade" id = "logins-' . $tid . '" >
                                             ' . $hashes . '
                                        </div >
                                </div >

                            </p>
                ';
        //echo $result[$row['tid']];
        //}
        //echo (($wwsd[$row['tid']])? "beach":"treach");
        //print_r($result);
        return $result;
    }

    function getSubInfoScans(int $tid)
    {

        $thead = '<div class="row scan-row">
                         <div class="col-sm-3">Тип</div>
                         <div class="col-sm-3">Заголовок2</div>
                         <div class="col-sm-3">Заголовок3</div>
                     </div>';
        $hashes = "";

        $scansArr = $this->Model->MysqliClass->getAssocArray("select * from scans where tid=$tid");//sqli

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




}