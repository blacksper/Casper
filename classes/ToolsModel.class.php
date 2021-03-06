<?php

/**
 * Created by PhpStorm.
 * User: Lalka
 * Date: 31.05.2016
 * Time: 15:54
 */

include "Model.class.php";

class ToolsModel extends Model
{

    public $cms;
    public $uid;
    private $url;

    function startScanPath($action, $filename, $tid, $sidArr)
    {
        if (($action != "dirScan") && ($action != "subdomainScan"))
            exit;

        //print_r($sidArr);
        //die();
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

        return $scid;
    }

    function addScan($tid, array $sids, $action, $filename = "")
    {

        $scid = rand(1000000, 90000000);
        foreach ($sids as $sid) {
            $query = "insert into scans(scid,type,uid,sid,tid,status,filename,dateScan) VALUES($scid,'$action',$this->uid,$sid,$tid,0,'$filename',now()) ";
            //echo $query."\n";
            $this->MysqliClass->query($query);
        }
        return $scid;

    }

    function  getUrls($tid, $sid)
    {

        $query = "select * from servers where sid=$sid";
        $serverUrl = $this->MysqliClass->firstResult($query)['path'];
        $query = "select * from targets where tid=$tid";
        $targetUrl = $this->MysqliClass->firstResult($query)['url'];

        if (!isset($serverUrl, $targetUrl))
            return 0;
        else
            return array("serverUrl" => $serverUrl, "targetUrl" => $targetUrl);

    }

    function startScan($scid, $data, $sid)
    {

        $query = "select url,path,type from scans LEFT JOIN targets on scans.tid=targets.tid LEFT JOIN servers on scans.sid=servers.sid where scid=$scid and scans.sid=$sid";
        //echo $query . "\n";
        $arrResult = $this->MysqliClass->firstResult($query);
        $ch = curl_init();
        //foreach($arrResults as $arrResult) {
        if (!isset($arrResult))
            exit;
        $action = $arrResult['type'];
        $serverUrl = $arrResult['path'];
        $targetUrl = $arrResult['url'];
        $arrTask[$scid] = array("url" => $targetUrl, "action" => $action, "data" => $data);
        $command = "execute=" . json_encode($arrTask);

        curl_setopt($ch, CURLOPT_URL, $serverUrl);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        $serverUrl . " " . $command . "\n";
        curl_setopt($ch, CURLOPT_POSTFIELDS, $command);
        curl_exec($ch);
        //}
        curl_close($ch);

    }

    function startNmap($tid, $option = null, $sid = 0)
    {

        switch ($option) {
            case "quick":
                $param = "-T4 -F";
                break;
            case "quickplus":
                $param = "-sV -T4 -O -sS -v";
                break;

            default:
                return 0;
                break;
        }

        $query = "select * from targets where tid=$tid";
        $targeturl = $this->MysqliClass->firstResult($query)['url'];
        preg_match("/http[s]?:\/\/([\w\d.-]+)\//", $targeturl, $m);
        if (!isset($m[1]))
            return 0;

        $targeturl = $m[1];
        $scid = $this->addScan($tid, array($sid), "nmap $option");

        $result = array();
        if (!isset($param))
            return 0;

        $cmd = '"' . PATH_NMAP . '" ' . escapeshellcmd($param . ' ' . $targeturl);
        echo $cmd;
        //$cmd =  '"'.PATH_NMAP.'" '.escapeshellarg($param . ' ' . $targeturl);
        $content = shell_exec($cmd);
        //$content = ob_get_clean();

        echo $content;
        preg_replace("/\s+/", "", $content);
        preg_match_all("/(\d+)\/tcp\s+(\w{4,})\s+([^\s]+)([\r\n]+|[\s]+([^\r\n]+))?[\r\n]?/", $content, $m);
        //print_r($m);
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
                //echo $query . "\n";
                $this->MysqliClass->query($query);
                $query = $queryStart;
            }
        }
        //die();
        $query = substr($query, 0, -1);
        $query .= " ON DUPLICATE KEY UPDATE status=values(status)";
        echo $query . "\n";
        $this->MysqliClass->query($query);
        //echo $query;
        $query = "update scans set status=1 where scid=$scid";
        $this->MysqliClass->query($query);
    }

    function startBruteforce($type, $loginsfile, $passwordsfile, $tid, $sidArr)
    {
        //$sidArr=explode(',',$sid);

        $logins = explode("\r\n", file_get_contents(PATH_TXTP . "/" . $loginsfile));
        $passwords = explode("\r\n", file_get_contents(PATH_TXTP . "/" . $passwordsfile));
        $source = $this->generateSource($logins, $passwords);

        $filename = time() . "-$loginsfile-$passwordsfile";
        $scid = $this->addScan($tid, $sidArr, $type, $filename);
        if (!$scid) exit;

        file_put_contents(PATH_BSRC . "/" . $filename, $source);
        $countLogins = ceil(count($logins) / count($sidArr));
        $loginsParts = (array_chunk($logins, $countLogins));
        //echo $countLogins . "\n";

        foreach ($sidArr as $i => $sid) {
            if (!$this->getUrls($tid, $sid))
                return 0;
            $this->startScan($scid, array("logins" => $loginsParts[$i], "passwords" => $passwords), $sid);
        }
        //die();
        return $scid;
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

    function downloadFile($url, $filename)
    {
        $result = 0;
        @$filecont = file_get_contents($url);
        $urlarr = parse_url($url);
        //print_r($urlarr);
        $path_parts = pathinfo($filename);
        if (!isset($path_parts['extension']))
            return 0;

        //print_r($path_parts);
        $filename = trim($filename);
        $filenameFull = PATH_GIT . "/" . $urlarr['host'] . "/" . $filename . "";
        if ($path_parts['extension'] == "php")
            $filenameFull .= ".txt";
        //echo $filecont."66666666666";
        $decode = @zlib_decode($filecont);
        //echo 123123;
        //print_r($filenameFull);

        if ($decode) {
            $pos = strpos($decode, 0x00) + 1;
            //echo $pos." 1234fd";
            $decode = substr($decode, $pos);
            file_put_contents($filenameFull, $decode);
            $query = "update gitdump set exist=1 where filename='$filename' and filepath='$url'";
            $result = 1;

        } else {
            $query = "update gitdump set exist=-1 where filename='$filename' and filepath='$url'";

        }
        //echo $query;


        $this->MysqliClass->query($query);
        return $result;
    }

    function gitDump($tid, $sid = array(0), $option = "gitdump")
    {

        $query = "select * from targets where tid=$tid";
        $targeturl = $this->MysqliClass->firstResult($query)['url'];


        $urlArr = parse_url($targeturl);
        $directory = PATH_GIT . "/" . $urlArr['host'];
        $results = array();

        $query = "select scid from scans where tid=$tid and type='gitdump'";
        $scid = $this->MysqliClass->firstResult($query)['scid'];
        $query = "update gitdump set older=1 where scid=$scid";
        $this->MysqliClass->query($query);
        if (!$scid)
            $scid = $this->addScan($tid, $sid, $option);
        //if (!file_exists("$directory/index"))//РАСКОМЕНТИРОВАТЬ ПОТОМ
        $this->downloadIndex($targeturl);

        $file = fopen("$directory/index", "rb");
        fread($file, 8);
        $entrycount = fread($file, 4);
        $entrycount = unpack("N", $entrycount)[1] . "\n";


        $b = 0;
        for ($i = 0; $i < $entrycount; $i++) {
            $b++;
            //echo $i . ", ";
            $entrylen = 62;

            $nulldata = fread($file, 40);
            $sha1part1 = bin2hex(fread($file, 1));
            $sha1part2 = bin2hex(fread($file, 19));
            $flag = hexdec(unpack("H4flag", fread($file, 2))['flag']);
            $filename = "";
            if ($flag < 0xFFF) {
                $entrylen += $flag;
                $filename = fread($file, $flag);
                //echo "$flag first " . $filename . "\n";
            } else {
                while (1) {
                    $sym = fread($file, 1);
                    if ($sym == "\x00")
                        break;
                    $filename .= $sym;
                }

            }
            $padlen = (8 - ($entrylen % 8)) or 8;
            fread($file, $padlen);
            $dname = dirname($filename);
            if ($dname && !file_exists($directory . "/" . $dname)) {
                //echo "ya sozdal "."";
                $tomake = $directory . "/" . $dname;
                if (strlen($tomake) > 247) //ограничение на длину пути, только в винде
                    continue;
                //echo "\n";
                mkdir($tomake, 0777, 1);
            }
            $filepath = $targeturl . ".git/objects/$sha1part1/$sha1part2";
            array_push($results, array("filename" => $filename, "filepath" => $filepath));
        }
        fclose($file);

        $queryStart = "INSERT INTO gitdump(scid,filename,filepath) VALUES";
        $query = $queryStart;
        $i = 0;


        foreach ($results as $result) {
            $query .= "($scid,'{$result['filename']}','{$result['filepath']}'),";
            $i++;
            if ($i == 300) {
                $i = 0;
                $query = substr($query, 0, -1);
                $query .= " ON DUPLICATE KEY UPDATE filepath=values(filepath),filename=values(filename)";
                //echo $query . "\n";
                $this->MysqliClass->query($query);
                $query = $queryStart;
            }
        }

        $query = substr($query, 0, -1);
        $query .= " ON DUPLICATE KEY UPDATE filepath=values(filepath),filename=values(filename)";
        $this->MysqliClass->query($query);
        //echo $query;
        $query = "update scans set status=1 where scid=$scid";
        $this->MysqliClass->query($query);

        return $scid;
    }

    function downloadIndex($url)
    {
        //echo $url . "\n";
        $index = @file_get_contents($url . "/.git/index");
        $urlArr = parse_url($url);
        $directory = PATH_GIT . "/" . $urlArr['host'];
        //print_r($qwe);
        if ($index == "")
            return 0;
        //die("empty\n");

        if (!file_exists($directory))
            mkdir($directory, null, 1);
        file_put_contents("$directory/index", $index);
    }

    function detectCms($tid)
    {

        $scid = $this->addScan($tid, array(0), "detectCms");

        $query = "select * from targets where tid=$tid";
        $this->url = $this->MysqliClass->firstResult($query)['url'];
        //$this->url="http://yegor1111-joomla-4.tw1.ru/";
        //$this->url="http://casper.localhost/dle/";
        echo $this->url . "\n";
        $ch = curl_init();
        //curl_setopt($ch,CURLOPT_URL,$url."/administrator/");

        //$responseData=$this->getContent($ch,"wp-login.php");
        //$responseData=$this->getContent($ch,"/administrator/");
        $responseData = $this->getContent($ch, "wp-login.php");
        $this->checkCms($responseData, "wp-login.php", "WordPress");
        $this->checkCms($responseData, "wordpress", "WordPress");
        $this->checkCms($responseData, "wp-content", "WordPress");

        $responseData = $this->getContent($ch, "/");
        $this->checkCms($responseData, "wp-login.php", "WordPress");
        $this->checkCms($responseData, "wordpress", "WordPress");
        $this->checkCms($responseData, "wp-content", "WordPress");

        $responseData = $this->getContent($ch, "/administrator/");
        $this->checkCms($responseData, "Joomla!", "Joomla");
        $this->checkCms($responseData, "loginform", "Joomla");

        $responseData = $this->getContent($ch, "admin.php");
        $this->checkCms($responseData, "DataLife Engine", "Datalife Engine");
        $this->checkCms($responseData, "var dle_root", "Datalife Engine");
        //$this->checkCms($responseData,"wp-content","joomla");

        curl_close($ch);

        arsort($this->cms);
        print_r($this->cms);
        if (current($this->cms) <= 1) {
            $cms = "?";
            $version = "";
        } else {

        $cms = key($this->cms);
        echo $cms;
        if (isset($cms)) {
            if ($cms == "WordPress") {
                if (!$version = $this->getVersionWp())
                    $version = "?";
            } elseif ($cms == "Joomla") {
                if (!$version = $this->getVersionJoomla())
                    $version = "?";
            } elseif ($cms == "Datalife Engine") {
                if (!$version = $this->getVersionDle())
                    $version = "?";

            }
        }
        }
            $query = "update targets set cms='$cms $version' WHERE tid=$tid";
        echo $query;
            $this->MysqliClass->query($query);

            $query = "update scans set status=1 where scid=$scid";
            $this->MysqliClass->query($query);


        //print_r($this->cms);
        //return $this->cms;

    }

    function getContent($ch, $path)
    {

        curl_setopt($ch, CURLOPT_URL, $this->url . $path);
        $this->setCurlOpts($ch);
        $responseData = curl_exec($ch);
        curl_close($ch);
        return $responseData;
    }

    /**
     * @param $ch
     */
    public function setCurlOpts($ch)
    {
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    }

    function checkCms($responseData, $content, $cmsName)
    {
        if (!isset($this->cms[$cmsName]))
            $this->cms[$cmsName] = 0;

        if (strpos($responseData, $content)) {
            $this->cms[$cmsName]++;
        }
    }

    function getVersionWp()
    {
        $result = 0;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url . "/wp-content/languages/ru_RU.po");
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $this->setCurlOpts($ch);
        $content = curl_exec($ch);
        if ($content) {
            //preg_match("#Project-Id-Version: ([\w\d.]+)#",$content,$m);
            //echo $content;
            preg_match("#Project-Id-Version:([\s\w()]*)(\d\.\d\.[\d\w]?)#", $content, $m);
            if (isset($m[1]))
                $result = $m[2];
            print_r($m);
        }
        curl_close($ch);
        return $result;
    }

    function getVersionJoomla()
    {
        $result = 0;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url . "/language/en-GB/en-GB.xml");
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $this->setCurlOpts($ch);
        $content = curl_exec($ch);
        if ($content) {
            preg_match('#metafile version="([\w.]+)"#', $content, $m);
            if (isset($m[1]))
                $result = $m[1];
            print_r($m);
        }

        return $result;
    }

    function getVersionDle()
    {
        $result = 0;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url . "/engine/ajax/updates.php");
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $this->setCurlOpts($ch);
        $content = curl_exec($ch);
        if ($content) {
            preg_match('#color="red">([]\d.]+)<\/font\>#', $content, $m);
            if (isset($m[1]))
                $result = $m[1];
            print_r($m);
        }

        return $result;
    }

}