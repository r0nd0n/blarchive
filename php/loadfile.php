<?php

/*
* Blarchive - Loadfile.php.
*/

session_start();
unset($_SESSION['user']);
unset($_SESSION['access__token']);
session_destroy();

set_time_limit(60);

$anyx = false;

require("confc.php");

$filename = "archive.conf";
$fp = fopen($filename, 'r');
$myfile = fread($fp, filesize($filename));
fclose($fp);

$myfile = str_replace("\n","\r", $myfile);
$bot = substr($myfile, strpos($myfile, "\r#posting_bot\r") + 15);
$bot = substr($bot, 0, strpos($bot, "\r"));
$myhttp = substr($myfile, strpos($myfile, "\r#http\r") + 8);
$myhttp = substr($myhttp, 0, strpos($myhttp, "\r"));
$myport = substr($myfile, strpos($myfile, "\r#port\r") + 8);
$myport = substr($myport, 0, strpos($myport, "\r"));
$replac = false;
$chain = substr($myfile, strpos($myfile, "\r#chain\r") + 9);
$chain = strToUpper(substr($chain, 0, strpos($chain, "\r")));

$sss1="https://hiveblocks.com";
$sss2="https://anyx.io";
$sss3="https://hive.blog";

try 
{
    $conn = new mysqli($servername, $username, $password, "archive");
}
catch (Exception $e) 
{
    echo "dbconnectivity issue. try again later";
    die;
}

if ($conn->connect_error) 
{
    die("connection to the database failed:". $conn->connect_error);
}

if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) 
    {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
    }
}

$perma = "-compressed-encoded-binary-data";

function startsWith($a,$b)
{
    return (substr($a, 0, strlen($b)) === $b);
}

function endsWith($a,$b) 
{
    if (strlen($b) == 0) 
    {
        return true;
    }
    return (substr($a, -strlen($b)) === $b);
}

$isandroid = false;

if (strpos(strToLower($_SERVER['HTTP_USER_AGENT']),"android") > 0) 
{
    $isandroid = true;
}

if((isset($_GET['fileid'])) 
&& (isset($_GET['index'])))  
{
    $fileid = $_GET['fileid'];
    $index = $_GET['index'];
    $type = "";

    if (isset($_GET['type'])) 
    {
        $type=  $_GET['type'];
    }

    if ((ctype_digit(strval($fileid))) 
    && (ctype_digit(strval($index))))
    {
        if (isset($_GET['user'])) 
        {
            $user = $_GET['user'];
        }
        else 
        {
            $sql = $conn->prepare( "select bot from lookup WHERE `fileid`=?");
            $sql->bind_param("i", $fileid);

            if(!$sql->execute())
            {
                trigger_error("there was an error...." . $conn->error, E_USER_WARNING);
            }

            $result = $sql->get_result();
            while($row = $result->fetch_assoc()) 
            {
                $user = $row["bot"];
            }
            $sql->close();
        }

        //echo $user;

        $result = "";
        $tx = -1;
        $zz = dochop4($fileid, $index);

        if ($zz[1]) 
        {
            $result = $zz[0];
        } 
        else 
        {
            sleep(1);
            $zz = dochop4($fileid, $index);
            if ($zz[1]) 
            {
                $result = $zz[0];
            } 
            else 
            {
                sleep(1);
                $zz = dochop4($fileid, $index);
                if ($zz[1]) 
                {
                    $result = $zz[0];
                } 
                else 
                {
                    sleep(1);
                }
            }
        }

        //echo $result;
        $st = explode(";", $result);
        $tx = $st[$index];
        if (!startsWith($tx, "fp:local")) 
        {
            $tx = substr($tx, 3);
            $aa = loadstuff($tx);
        }
        else 
        {
            $filename = $fileid . "/" . $index . ".mp4";
            $fp=fopen($filename, 'r');
            $aa = fread($fp, filesize($filename));
            fclose($fp);
        }

        $result = $aa;
        if ($user !== $bot) 
        {
            //echo "testing mode:";
            $ss1 = substr($zz[2], strpos($zz[2], " please visit <") + 23);
            $ss2 = substr($ss1, 0, strpos($ss1, "/php"));
            $ss3 = substr($ss1, strpos($ss1, "user=") + 5);
            //echo substr($ss3,0,40);

            //$ss3=substr($ss3,0,strpos($ss3,"<"));
            //$ss3=substr($ss3,0,strpos($ss3,"&"));
            $ss3 = substr($ss3, 0, strpos($ss3, "\""));

            //echo "<br>ss1:".$ss1;
            //echo "<br>ss2:".$ss2;
            //echo "<br>ss3:".$ss3." ".strlen($ss3);

            if (!($ss2 === $myhttp . ":" . $myport || $ss2 === $myhttp )) 
            {
                $result = str_replace($ss2, ($myport === "443" ? "https://" : "http://") . $myhttp . ":" . $myport, $result);
                //legacy
                $result = str_replace("fileid=" . $fileid . "&index=", "fileid=" . $fileid . "&user=" . $user . "&index=", $result);
            }
        }

        if ($fileid < 700)  
        {
            $result = str_replace("steemsafe.net", "blarchive.net", $result);
        }

        if ($type === "src")
        {
            header("Content-Type: application/javascript");
            ob_clean();
            flush();
            echo $result;
        }
        else if ($type === "css")
        {
            header("Content-Type: text/css");
            ob_clean();
            flush();
            echo $result;
        }
        else if ($type === "js") 
        {
            if ((strpos($result, "</") === false)
            && (strpos($result, "for(") === false)
            && strpos($result, "if(") === false)
            {
                header("Content-Type: text/css");
                ob_clean();
                flush();
                echo $result;
            }
            else 
            {
                header("Content-Type: application/javascript");
                ob_clean();
                flush();
                echo $result;
            }
        }
        else if ($type === "img") 
        {
            $samp = substr($result, 0, 4);
            $samp2 = unpack('C*', substr($result, 0, 5));
            
            if (strpos(strtolower($samp), "png") !== false)
            {
                header("Content-Type: image/png");
                ob_clean();
                flush();
                echo $result;
                exit;
            }
            else if (startsWith(strtolower($samp), "riff"))
            {
                header("Content-Type: image/webp");
            }
            else if (startsWith(strtolower($samp), "bm"))
            {
                header("Content-Type: image/bmp");
            }
            else if (startsWith(strtolower($samp), "gif"))
            {
                header("Content-Type: image/gif");
            }
            else if (startsWith(strtolower($samp), "jpg")) 
            {
                header("Content-Type: image/jpeg");
            }
            else if (startsWith(strtolower($samp), "<svg")) 
            {
                header("Content-Type: image/svg+xml");
            }
            else if ((startsWith(strtolower($result), "<?xml")) 
            && (strpos($result, "<svg") < 150) 
            && (strpos($result, "<svg") > 0)) 
            {
                header("Content-Type: image/svg+xml");
            }
            else if (endsWith(strtolower($result), "svg>")) 
            {
                header("Content-Type: image/svg+xml");
            }
            else if (startsWith(strtolower($samp), "wof2")) 
            {
                header("Content-Type: font/woff2");
            }
            else if (($samp2[1] == 0) 
            && ($samp2[2] == 0) 
            && ($samp2[3] == 1) 
            && ($samp2[4] == 0)) 
            {
                header("Content-Type: image/x-icon");
            }
            else if (($samp2[1] == 0) 
            && ($samp2[2] == 0) 
            && ($samp2[3] == 2) 
            && ($samp2[4] == 0)) 
            {
                header("Content-Type: image/x-win-bitmap");
            }
            else if (($samp2[1] == 0) 
            && ($samp2[2] == 1) 
            && ($samp2[3] == 0) 
            && ($samp2[4] == 0) 
            && ($samp2[5] == 0)) 
            {
                header("Content-Type: application/x-font-ttf");
            }
            //else if (startsWith(strtolower($samp),"%pdf")) {header("Content-Type: application/pdf");}
            else if (strpos(substr(strtolower($result), 0, 100), "jfif") > 0) 
            {
                header("Content-Type: image/jpeg");
            }
            else if (strpos(substr(strtolower($result), 0, 100), "<svg ") !== FALSE) 
            {
                header("Content-Type: image/svg+xml");
            }
            else if (strpos(substr($result, 0, 100),
            "GSUB") > 0) 
            {
                header("Content-Type: application/octet-stream");
            }

            ob_clean();
            flush();
            echo $result;
        }
        else if ($type === "vid") 
        {
            $samp = substr($result, 0, 4);
            $samp2 = unpack('C*', substr($result, 0, 5));
            if (startsWith(strtolower($samp), "riff")) 
            {
            //if ($isandroid) {header("Location: ".$fileid."/".$index.".mp4");}
            //else 
                header("Content-Type: video/avi");
                ob_clean();
                flush();
                echo $result;
            }
            else if (startsWith(strtolower($samp), strToLower("Eߣ"))) 
            {
            //if ($isandroid) {header("Location: ".$fileid."/".$index.".mp4");}
            //else
                header("Content-Type: video/mkv");
                ob_clean();
                flush();
                echo $result;
            }
            else if (startsWith(strtolower($samp), "flv")) 
            {
            //if ($isandroid) {header("Location: ".$fileid."/".$index.".mp4");}
            //else
                header("Content-Type: video/flv");
                ob_clean();
                flush();
                echo $result;
            }
            else  
            {
                header("Content-Type: video/mp4");
                header("Content-Length: " . filesize($filename));
                ob_clean();
                flush();
                echo $result;
            }
        }
        else if ($type === "emb") 
        {
            $samp = substr($result, 0, 4);
            if (startsWith(strtolower($samp), "fws"))
            {
                header("Content-Type: x-shockwave-flash");
            }

            ob_clean();
            flush();
            echo $result;
        }
        else if ($type === "txt") 
        {
            $samp = substr($result, 0, 4);
            $samp10 = substr($result, 0, 4);
            if (startsWith(strtolower($samp), "webvtt")) 
            {
                header("Content-Type: type/vtt");
            }
            ob_clean();
            flush();
            echo $result;
        }
        else if ($type === "java") 
        {
            header("Content-Type: application/x-java-applet");
            ob_clean();
            flush();
            echo $result;
        }
        else if ($type === "snd") 
        {
            $samp = substr($result, 0, 4);
            $samp2 = substr($result, 0, 12);
            if (startsWith(strtolower($samp), "riff"))
            {
                header("Content-Type: audio/wave");
            }
            else if (startsWith(strtolower($samp), "ogg")) 
            {
                header("Content-Type: audio/ogg");
            }
            else if (startsWith(strtolower($samp), "mthd") || startsWith(strtolower($samp),"mtrk")) 
            {
                header("Content-Type: audio/ogg");
            }
            else if (strpos(strtolower($samp2), "aifc") !== false) 
            {
                header("Content-Type: audio/x-aiff");
            }
            else 
            {
                header("Content-Type: audio/mp3");
            }
            ob_clean();
            flush();
            echo $result;
        }
        else if (!isset($_GET['type'])) 
        {
            $samp = strtolower(substr($result, 0, 4));
            $samp2 = unpack('C*', substr($result, 0, 5));

            //if ($index==0) {
            //$samp=strtolower(substr($result,0,4));
            //$ishtml=true;
            //if (startsWith(strtolower($samp),"%pdf")) {$ishtml=false;}
            //if ($ishtml) {header("Content-Type: text/html");}
            //else {header("Content-Type: application/pdf");}} 

            if ($samp === "<!do" || $samp === "<htm") 
            {
                header("Content-Type: text/html");
            }
            else if (strpos(strtolower($samp), "png") !== false) 
            {
                header("Content-Type: image/png");
            }
            else if (startsWith(strtolower($samp), "riff"))
            {
                header("Content-Type: image/webp");
            }
            else if (startsWith(strtolower($samp), "bm")) 
            {
                header("Content-Type: image/bmp");
            }
            else if (startsWith(strtolower($samp), "gif")) 
            {
                header("Content-Type: image/gif");
            }
            else if (startsWith(strtolower($samp), "jpg")) 
            {
                header("Content-Type: image/jpeg");
            }
            else if (startsWith(strtolower($samp), "<svg")) 
            {
                header("Content-Type: image/svg+xml");
            }
            else if (endsWith(strtolower($result), "svg>")) 
            {
                header("Content-Type: image/svg+xml");
            }
            else if (startsWith(strtolower($samp), "wof2")) 
            {
                header("Content-Type: font/woff2");
            }
            else if (startsWith(strtolower($samp), "%pdf")) 
            {
                header("Content-Type: application/pdf");
            }
            else if (isset($samp2[4])) 
            {
                if ($samp2[1] == 0 && $samp2[2] == 0 && $samp2[3] == 1 && $samp2[4] == 0) 
                {
                    header("Content-Type: image/x-icon");
                }
                else if ($samp2[1] == 0 && $samp2[2] == 0 && $samp2[3] == 2 && $samp2[4] == 0) 
                {
                    header("Content-Type: image/x-win-bitmap");
                }
                else if ($samp2[1] == 0 && $samp2[2] == 1 && $samp2[3] == 0 && $samp2[4] == 0 && $samp2[5] == 0)
                {
                    header("Content-Type: application/x-font-ttf");
                }
            }
            else if (strpos(substr(strtolower($result), 0, 10), "jfif") > 0) 
            {
                header("Content-Type: image/jpeg");
            }
            else if (startsWith(strtolower($samp), "gif"))
            {
                header("Content-Type: image/gif");
            }
            else if (startsWith(strtolower($samp), "%pdf")) 
            {
                header("Content-Type: application/pdf");
            }
            else 
            {
                header("Content-Type: text/html");
            }
            ob_clean();
            flush();
            echo $result;
        }
        else 
        {
            ob_clean();
            flush();
            echo $result;
        }
        exit;
    }
    else 
    {
        echo "not a int" . ctype_digit(strval($fileid)) . " " . ctype_digit(strval($index)) . "end";
        exit;
    }
}
else 
{
    echo "not found";
    exit;
}

function loadstuff($result) 
{
    //echo "<br>hello world:".$result;
    global $index;
    //echo "path0";
    $pagetext = chopper($result, 1);
    //echo $pagetext;
    //exit;
    if (strpos(substr($pagetext, 0, 10), ";") !== false) 
    {
        //echo "path1";
        if (strpos(substr($pagetext, 0, 10), "tk;") !== FALSE) 
        {
            //echo "path1.3";
            $fd = dochop2($pagetext);
        } 
        else 
        {
            //echo "path1.6";
            $fd = dochop5($pagetext);
        }
        return $fd;
    } 
    else 
    {//echo "path2";if ($index>0){exit;}
        $rr = explode(" ", $pagetext);
        $t = count($rr);
        $data = "";
        for($x = 0; $x < $t; $x++)
        {
            $data .= chopper($rr[$x], 1);
        }
    }

    if (strpos(substr($data, 0, 10), ";") !== false) 
    {
        $fd = dochop2($data);
        return $fd;
    }
    else 
    {
        $rr = explode(" ", $data);
        $t = count($rr);
        $pagetext = "";
        for ($x = 0; $x < $t; $x++)
        {
            $pagetext .= chopper($rr[$x], 1);
        }
    }

    if (strpos(substr($pagetext, 0, 10), ";") !== false) 
    {
        $fd = dochop2($pagetext);
        return $fd;
    }
    else 
    {
        $rr = explode(" ", $pagetext);
        $t = count($rr);
        $data = "";
        for($x = 0; $x < $t; $x++)
        {
            $data .= chopper($rr[$x], 1);
        }
    }

    if (strpos(substr($data, 0, 10), ";") !== false) 
    {
        $fd = dochop2($data);
        return $fd;
    }
}

function ancurl($resu, $l) 
{
    global $anyx, $sss2;
    $anyx = true;
    $pagetext = "";

    //echo "ancurl<br>";
    //echo $resu;

    $ch = curl_init($sss2 . "/v1/account_history_api/get_transaction?id=" . $resu);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json, text/plain, */*','content-type: application/json','DNT:1','Origin: https://blarchive.net/php.steem.php','','Sec-Fetch-Mode: cors','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));
    
    if(($pagetext = curl_exec($ch)) === false) 
    {
        echo 'ancurl:Curl error: ' . curl_error($ch);
        if (curl_error($ch) === "Illegal characters found in URL") 
        {
            echo "<br>This site may not have been stored to the block chain yet, or steemd is not delivering this transaction number, nor can it be obtained from anyx. Please be patient, it takes a while.\n<br>";
            exit;
        }
    }
    //echo "<br>datalocation".strpos($pagetext,"data\\\" :");
    //echo "<br>datalength".strlen($pagetext);
    if (strpos($pagetext, "data\\\" :") > 0) 
    {
        $pagetext = substr($pagetext, strpos($pagetext, "data\\\" :") + 11);
        $pagetext = substr($pagetext, 0, strpos($pagetext, "\\\"") -1);
        //echo "<br>made it 4:".$pagetext;
    }
    else if (strpos($pagetext, "d\\\" :") > 0) 
    {
        $pagetext = substr($pagetext, strpos($pagetext, "d\\\" :") + 8);
        $pagetext = substr($pagetext, 0, strpos($pagetext, "\\\"") - 1);
    }
    //echo "<br>endchopper:".$pagetext;
    //echo "<br>endendchopper:".strpos($pagetext,"<html>");
    if  (strpos($pagetext, "<html>") > 0)
    {
        if ($l == 3) 
        {
            header("Content-Type: text/html");
            echo "anyx is down";
            exit;
        } 
        else 
        {
            sleep(3);
            $l++;
            return ancurl($resu, $l);
        }
    }
    return $pagetext;
}

function chopper($resu, $l) 
{
    global $anyx, $chain;
    global $sss1;

    $pagetext = "";
    $anresu = $resu;

    //echo $resu;
    //echo $anresu;
    //echo "<br>chopper:".$resu." ".$l;
    //echo "<br>";
    //echo $anyx?"true":"false";
    //exit;

    if ($anyx) 
    {
        return ancurl($resu, 1);
    }

    //echo "<br>".$sss1."/tx/".$resu;

    $ch = curl_init($sss1 . "/tx/" . $resu);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json, text/plain, */*','content-type: application/json','DNT:1','Origin: https://blarchive.net/php.steem.php','','Sec-Fetch-Mode: cors','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));
    //*/
    //echo "testing";
    if(($pagetext = curl_exec($ch)) === false) 
    {
        echo 'chopper:Curl error: ' . curl_error($ch);
        if (curl_error($ch) === "Illegal characters found in URL") 
        {}
    }
    if (strpos($pagetext, "data&quot; :") > 0) 
    {
        $pagetext = substr($pagetext, strpos($pagetext, "data&quot; :") + 18);
        $pagetext = substr($pagetext, 0, strpos($pagetext, "&quot;"));
    }
    else if (strpos($pagetext, "d&quot; :") > 0) 
    {
        $pagetext = substr($pagetext, strpos($pagetext, "d&quot; :") + 15);
        $pagetext = substr($pagetext, 0, strpos($pagetext, "&quot;"));
    }
    //echo "<br>endchopper:".$pagetext;
    //echo "<br>endendchopper:".strpos($pagetext,"<html>");

    if  (strpos($pagetext, "<html>") > 0)
    {
        if ($l == 3) 
        {
            $pagetext = ancurl($anresu, 1);
        } 
        else 
        {
            sleep(3);
            $l++;
            return chopper($resu, $l);
        }
    }
    //echo "made it";

    $debug = substr($pagetext, strpos($pagetext, "<title>"));
    $debug = substr($debug, 0, strpos($debug, "</title>"));
    //echo "debug".$debug;
    //echo "strlen:".strlen($pagetext);

    //echo $pagetext;
    return $pagetext;
}

function dochop5($resu) 
{
    //echo "<br>dochop5:".$resu;
    //echo "<br>strlen:".strlen($data);
    $aa = "";
    $aa = gzuncompress(base64_decode($resu));
    //echo "dochop5end:".$aa;

    return $aa;
}

function anchop2($resu) 
{
    //echo "\n<br>anchop2:".$resu;
    $aa = "";
    $r = explode(" ", $resu);
    $t = count($r);
    $data = "";
    //echo "<br>t:".$t;
    for($x = 0; $x < $t; $x++) 
    {
        $zz = dochop3($r[$x], $x);
        if ($zz[1])
        {
            $data .= $zz[0];
        } 
        else 
        {
            sleep(1);
            $zz = dochop3($r[$x], $x);
            if ($zz[1]) 
            {
                $data .= $zz[0];
            } 
            else 
            {
                sleep(1);
                $zz = dochop3($r[$x], $x);
                if ($zz[1]) 
                {
                    $data .= $zz[0];
                }
                else 
                {
                    sleep(1);
                }
            }
        }
    }
    //echo $data;
    $zz = "";
    //echo "<br>strlen:".strlen($data);
    $aa = "";
    $aa = gzuncompress(base64_decode($data));
    
    if (strlen($aa) == 0) 
    {
        $aa = $data;
    }
    return $aa;
}

function dochop2($resu) 
{
    //echo "\n<br>dochop2:".$resu;
    global $anyx;
    //echo "<br>";
    //echo $anyx?"true":"false";
    //this was working without the $
    if ($anyx) 
    {
        return anchop2($resu);
    }

    $aa = "";
    $r = explode(" ", $resu);
    $t = count($r);
    $data = "";
    //echo "<br>t:".$t;
    for($x = 0; $x < $t - 1; $x++) 
    {
        $zz = dochop3($r[$x], $x);
        if ($zz[1]) 
        {
            $data .= $zz[0];
        } 
        else 
        {
            sleep(1);
            $zz = dochop3($r[$x], $x);
            if ($zz[1]) 
            {
                $data .= $zz[0];
            }
            else 
            {
                sleep(1);
                $zz = dochop3($r[$x], $x);
                if ($zz[1]) 
                { 
                    $data .= $zz[0];
                }
                else 
                {
                    sleep(1);
                }
            }
        }
    }
    //echo $data;
    $zz = "";
    //echo "<br>strlen:".strlen($data);
    $aa = "";
    $aa = gzuncompress(base64_decode($data));
   
    if (strlen($aa) == 0) 
    {
        $aa = $data;
    }
    return $aa;
}

function ancurl3($resu, $xx) 
{
    global $sss2;
    //echo "\n<br>ancurl3:".$resu." ".$xx;
    exit;
    $ok = true;
    $pagetext = "";
    $resu = substr($resu, strpos($resu, "tk;") + 3);
    //echo "<br>".$sss2."/v1/account_history_api/get_transaction?id=".$resu;
    $ch = curl_init($sss2."/v1/account_history_api/get_transaction?id=" . $resu);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json, text/plain, */*','content-type: application/json','DNT:1','Origin: https://blarchive.net/php.steem.php','','Sec-Fetch-Mode: cors','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));

    //*/
    if(($pagetext = curl_exec($ch)) === false) 
    {
        echo 'ancurl3:Curl error: ' . curl_error($ch);
        $ok = false;
    }

    if (strlen($pagetext) < 1450) 
    {
        if (strpos($pagetext, "We're sorry, but something went wrong (500)") > 0)
        {
            $ok = false;
        }
        else 
        {
            echo $pagetext;
            exit();
        }
    }
    //echo $pagetext;
    $resu = substr($pagetext, strpos($pagetext, "d\\\" :") + 8);
    $resu = substr($resu, 0, strpos($resu, "\\\""));
    //echo "\n\n<br>".$resu;
    $zz[0] = $resu;
    $zz[1] = $ok;
    return $zz;
}

function dochop3($resu, $xx)
{
    //echo "\n<br>dochop3:".$resu." ".$xx;
    //global $index;
    global $anyx, $sss1;
    if ($anyx) 
    {
        return ancurl3($resu, $xx);
    }

    $ok = true;
    $pagetext = "";
    $resu = substr($resu, strpos($resu, "tk;") + 3);
    //echo "<br>".$sss1."/tx/".$resu;
    $ch = curl_init($sss1 . "/tx/" . $resu);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json, text/plain, */*','content-type: application/json','DNT:1','Origin: https://blarchive.net/php.steem.php','','Sec-Fetch-Mode: cors','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));
    //*/
    if(($pagetext = curl_exec($ch)) === false)
    {
        echo 'dochop3:Curl error: ' . curl_error($ch);
        $ok = false;
    }

    if (strlen($pagetext) < 1450) 
    {
        if (strpos($pagetext, "We're sorry, but something went wrong (500)") > 0)
        {
            $ok = false;
        }
        else 
        {
            echo $pagetext;
            exit();
        }
    }

    $resu = substr($pagetext, strpos($pagetext, "d&quot;") + 15);
    $resu = substr($resu, 0, strpos($resu, "&quot;"));
    $zz[0] = $resu;
    $zz[1] = $ok;
    return $zz;
}

function dochop4($fileid, $index) 
{
    //echo "hello world2".$fileid;

    $ok = true;
    global $perma, $user, $sss3;
    $ch = curl_init($sss3 . "/datastores/@" . $user . "/" . $fileid . $perma);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json, text/plain, */*','DNT:1','','Sec-Fetch-Mode: cors','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));
    
    $result = "";
    $resu = "";

    if (($result = curl_exec($ch)) === false)  
    {
        echo 'dochop4:Curl error: ' . curl_error($ch);
        $ok = false;
    }
    else
    {
        //echo $sss3."/datastores/@".$user."/".$fileid.$perma;
        //echo $result;
        //exit;
        if ((strpos($result, "Bad Request") < 105) 
        && (strpos($result, "Bad Request") > 0)) 
        {
            echo "Bad Request";
            $ok = false;
        }
        else 
        {
            if (strlen($result) < 1450) 
            {
                if (strpos($result, "We're sorry, but something went wrong (500)") > 0) 
                {
                    $ok=false;
                }
                if (strpos($result, "Server error!") > 0) 
                {
                    $ok = false;
                }
                else 
                {
                    echo $result;
                    exit();
                }
            }

            $zz[2] = $result;
            $result = substr($result, strpos($result, "<p>"));
            $result = substr($result, strpos($result, "*****") + 11);
            $result = substr($result, 0, strpos($result, "</p>"));
        }
    }

    $zz[0] = $result;
    $zz[1] = $ok;
    return $zz;
}

?>