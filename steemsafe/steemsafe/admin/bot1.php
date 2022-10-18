<?php

/*
* Blarchive - Bot 1
*/

require ("/home/webadmin/vendor/autoload.php");
require("/home/webadmin/steemsafe/admin/confc.php");

function startsWith($a,$b) 
{
    return (substr($a,0,strlen($b)) === $b);
}

function endsWith($a,$b) 
{
    if (strlen($b) == 0)
    {
        return true;
    }
    return (substr($a, -strlen($b)) === $b);
}

$lasttime = time();

$database = "archive";

// get input arguments
$mod1 = $argv[1]-1;
$start2 = $argv[2];
$mod2 = $argv[3];
$appname = $argv[4];

// Hive user being used to make comments is being passed in to the bot
$user = $argv[5];

//$accesstoken=$argv[6];

// why is this sleep needed?
sleep(1);

// open config file
$filename = "/home/webadmin/steemsafe/admin/conf.conf";
$fp = fopen($filename, 'r');
$myfile = fread($fp, filesize($filename));
fclose($fp);

echo $myfile . "\n";

// Get Blarchive PHP path
$myfile = str_replace("\n", "\r", $myfile);
$blarchivePhpPath = substr($myfile, strpos($myfile, "\r#path\r") + 8);
$blarchivePhpPath = substr($blarchivePhpPath, 0, strpos($blarchivePhpPath, "\r"));
echo $blarchivePhpPath;
echo "\n";

if (startsWith($blarchivePhpPath, "\"")) 
{
    $blarchivePhpPath = substr($blarchivePhpPath, 1);
}

if (endsWith($blarchivePhpPath, "\"")) 
{
    $blarchivePhpPath = substr($blarchivePhpPath, 0, -1);
}

$result = "";
$fragment2 = "";

// max size of a post on hive?
$max = 64950;

$perma = "-compressed-encoded-binary-data";

echo $blarchivePhpPath;

// reading the archive.conf file - but this appears to not be used anywhere, so commenting out
/*
$filename2 = $blarchivePhpPath . "archive.conf";
$fp = fopen($filename2, 'r');
$myfile = fread($fp, filesize($filename2));
fclose($fp);
$myfile = str_replace("\n", "\r", $myfile);
*/

//$chain="HIVE";

$sp="2";
$ss1="https://hivesigner.com";
$ss2="https://hivesigner.com";
$ss3="https://hiveblocks.com";
$ss4="https://api.hive.blog";

// Function to run an SQL query - $b does not seem to be used (remove it?)
// This function uses Global variables defined at the start of the script
function doquery($a, $conn, $b) 
{
    $result = "";
    $return[0] = "";
    $return[1] = "";
    global $mod1, $mod2, $start2, $conn;

    if ($sql = $conn->prepare($a))
    {
        $sql->bind_param("sii", $start2, $mod2, $mod1);
        if ($sql->execute())
        {
            echo "\nsuccess select";
            $result = $sql->get_result();
            $return = $result->fetch_array();
            $sql->close();
        }
        else 
        {
            trigger_error("there was an error...." . $conn->error, E_USER_WARNING);
        } 
    }
    else 
    {
        echo "Error: " . $a . "" . mysqli_error($conn);
    }

    unset($sql);
    return $return;
}

// Loop infinitely
while (true) 
{
    // Open 2 connections to MySql
    $conn = new mysqli($servername,$username,$password,"archive");
    $conn2 = new mysqli($servername,$username,$password,"archive");

    if ($conn->connect_error) 
    {
        die("connection to the database failed:". $conn->connect_error);
    }
    else
    {
        echo "\ndatabase connected";
    }

    if ($conn2->connect_error) 
    {
        die("connection to the database failed:". $conn2->connect_error);
    }
    else 
    {
        echo "\ndatabase connected";
    }

    // read user's Hive posting authorisation?
    $filename2 = "/home/webadmin/steemsafe/admin/" . $user . ".acc" . $sp;
    $fp = fopen($filename2, 'r');
    $myfile2 = fread($fp, filesize($filename2));
    fclose($fp);

    $accesstoken = $myfile2;
    //echo $accesstoken;
    //sleep(1000000);

    $ok = true;
    $success = true;

    $ran = true;
    $time = time();
    $lastime = time();
    $text = "";
    echo "\n";
    echo $time;
    echo "\n";
    $index = 0;
    $sindex = 0;

    // what does this do?
    $tsql = "SELECT stores.fileid, stores.fileindex FROM `stores` join lookup on lookup.fileid=stores.fileid WHERE `tickerid`='0' and strcmp(blockchain,\"0\")!=0 AND " . $time . "-`filedate`>30 AND stores.fileid>? AND MOD(stores.fileid,?)=? LIMIT 1";
    
    // $sindex appears to be superfluous since doquery doesn't use the third input variable. global variables appear to be used instead of $sindex
    $storesData = doquery($tsql, $conn, $sindex);
    $sindex++;

    echo "\nfileid:" . $storesData[0];
    echo "\nindex:" . $storesData[1];

    if (strlen($storesData[0]) == 0 
    || strlen($storesData[1]) == 0) 
    {
        $ran = false;
    }
    else 
    {
        // create local path string for file
        $filename = $blarchivePhpPath.$storesData[0] . "/" . $storesData[1] . ".mp4";
        // open file
        $fk = fopen($filename, 'r') or $ran = false;

        if (filesize($filename) == 0) 
        {
            $text = "";
        }
        else 
        {
            // read the text stored locally in the file
            $text = fread($fk, filesize($filename)) or $ran = false;
        }

        $text = base64_encode(gzcompress($text));
        if (filesize($filename) == 0) 
        {
            $text = "";
        }

        fclose($fk);
        
        $filename = $blarchivePhpPath.$storesData[0] . "/index.html";
        $fk = fopen($filename, 'r') or $ran = false;
        $ext = fread($fk, filesize($filename));
        fclose($fk);

        $ar = explode("\n", $ext);
        echo "\nindex:" . $storesData[1] . "ext" . $ar[$storesData[1]] . "\n";
        echo $blarchivePhpPath . $storesData[0] . "/" . $storesData[1] . ".mp4" . "\n";
        echo filesize($blarchivePhpPath . $storesData[0] . "/" . $storesData[1] . ".mp4") . "\n";
        echo (filesize($blarchivePhpPath . $storesData[0] . "/" . $storesData[1] . ".mp4") > $max) ? "local save" : "chain save";

        if (($ar[$storesData[1]] === "img" 
        || $ar[$storesData[1]] === "&type=vid" 
        || $ar[$storesData[1]] === "vid" 
        || $ar[$storesData[1]] === "snd") 
        && filesize($blarchivePhpPath. $storesData[0] . "/" . $storesData[1] . ".mp4") > ($max))  
        {
            //echo "made it";
            $save = "local:" . $storesData[0] . "/" . $storesData[1];
            echo ($save !== "ant") ? "true" : "false";

            if ($save !== "ant") 
            {
                if ($save !== "or\":server_error") 
                {
                    if ($sql2 = $conn2->prepare( "UPDATE `stores` SET `tickerid`=? WHERE `fileid`=? AND `fileindex`=?"))
                    {
                        $sql2->bind_param("sii", $save, $storesData[0], $storesData[1]);
                        if(!$sql2->execute())
                        {
                            trigger_error("there was an sql error...." . $conn->error, E_USER_WARNING);
                        } 
                        else 
                        {
                            echo "sucess update";
                        }
                        $ran = false;
                    }
                }
            }
        }
    }

    echo "\nran " . ($ran ? "true" : "false");

    if ($ran) 
    {
        $url = "ssl://" . $ss1;
        $path = "/api/broadcast";
        $pagetext = "";

        echo strlen($text);
        $lab = $storesData[0];
        $index = 0;
        $data = "";
        $start = 0;
        $data2 = "";
        $end = strlen($text);
        $fragment = "";

        $data3 = "{\"operations\": [[\"custom_json\",{\"required_auths\":[], \"required_posting_auths\": [\"" . $user . "\"], \"id\" : \"" . $appname . "\", \"json\":\"{\\\"data\\\" : \\\"testing\\\" }\"}]]}";

        for ($start = 0; $start < $end; $start += $max) 
        {
            if (($start + $max) > $end) 
            {
                $data = substr($text, $start);
            }
            else 
            {
                $data = substr($text, $start, $max);
            }
            //echo "strlen".strlen($data);

            $data3 = "{\"operations\": [[\"custom_json\",{\"required_auths\":[],\"required_posting_auths\": [\"" . $user . "\"], \"id\" : \"" . $appname . "\", \"json\":\"{\\\"data\\\" : \\\"testing\\\" }\"}]]}";
            //$json ="{\"operations\": [[\"custom_json\",{\"required_auths\":[],\"required_posting_auths\": [\"".$user."\"], \"id\" : \"".$appname."\", \"json\":\"{\\\"a\\\" : \\\"" . $lab. "\\\", \\\"c\\\" :\\\"" . $index . "\\\",\\\"d\" :\\\"c\\\"}\"}]]}";
            //$json ="{\"operations\": [[\"custom_json\",{\"required_auths\":[],\"required_posting_auths\": [\"".$user."\"], \"id\" : \"".$appname."\", \"json\":\"{\\\"a\\\" : \\\"" . $lab. "\\\", \\\"b\\\" :\\\"" . $storesData[1] . "\\\", \\\"c\\\" :\\\"" . $index . "\\\",\\\"d\\\" :\\\"".$data."\\\"}\"}]]}";
            //$json ="{\"operations\": [[\"custom_json\",{\"required_auths\":[],\"required_posting_auths\": [\"".$user."\"], \"id\" : \"".$appname."\", \"json\":\"{\\\"d\\\" :\\\"".$data."\\\"}\"}]]}";


            // Define hive API broadcast to post a comment containing the data generated to represent the archived page

            $json = "{\"operations\": [[\"comment\",{\"parent_author\":\"" . $user . "\",\"parent_permlink\":";
            $json .= "\"" . $lab . $perma . "\", \"author\" : \"" . $user . "\", \"permlink\" : \"" . $lab . $perma . "-" . $storesData[1] . "-" . $index;
            $json .= "\",\"title\" : \"\", \"body\":";
            $json .= "\"{\\\"d\\\" : \\\"" . $data . "\\\"}\", \"json_metadata\":\"{\\\"tags\\\":[\\\"datastores\\\"]}\"}]";
            $json .= ",[\"comment_options\", {\"author\": \"" . $user . "\", \"permlink\":\"" . $lab . $perma . "-" . $storesData[1] . "-" . $index . "\", \"title\":\"\",\"max_accepted_payout\": \"0.000 HBD\", \"percent_hbd\": 0, \"allow_votes\": true,\"allow_curation_rewards\": true, \"extensions\": []}]]}";
            //echo "<br>".$json."<br>";

            //echo $json;
            //echo "strlenjson".strlen($json);

            $ch = curl_init($ss1 . "/api/broadcast");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json );
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json, text/plain, */*','authorization: ' . $accesstoken, 'content-type: application/json','DNT:1','Origin: http://127.0.0.1:8080/php.steem.php','','Sec-Fetch-Mode: cors','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));
            //*/

            $result = "";
            $resu = "";

            echo "curl execute1";

            if (($result = curl_exec($ch)) === false) 
            {
                echo 'Curl error: ' . curl_error($ch);
            }
            else
            {
                echo '\nOperation completed.';
                if (strpos($result, "Bad Request") < 105 && strpos($result, "Bad Request") > 0 ) 
                {
                    echo "Bad Request";
                    echo "\n".$result."\n";
                    sleep(3);
                }
                else if (strpos($result, "Account: \${account} has \${rc_current} RC, needs \${rc_needed} RC") > 0) 
                {
                    echo "Bad Request2 (out of RCS)";
                    $success = false;
                    $ok = false;
                    sleep(36000);
                }
                else 
                {
                    echo "<br>";
                    //echo $result;
                    //echo "<br>";
                    $resu=substr($result, strpos($result, "id") + 5);
                    $resu=substr($resu,0, strpos($resu, ",") - 1);
                    //echo "<br>";
                    //echo $resu;
                    $fragment .= "tk;" . $resu." ";
                    //echo "<br>";
                    //echo $fragment;
                    //echo "<br>";
                }
            }
            if (time() - $lasttime < 3) 
            {
                sleep(3);
            }
            $lasttime = time();
            $index++;
        }

        $ok = true;

        if (strpos($fragment, "undefined") > 0  || strpos($fragment, "server_error") > 0) 
        {
            $success = false;
            $ok = false;
            echo "\n<br>result:" . $result;
            echo "\n<br>json:" . $json;
        }

        $zx = explode("tk:", $fragment);
        if (count($zx) == 2) 
        {
            $ok = false;
        }
        else 
        {
            if (strlen($fragment) < $max) 
            {
                $ok = false;
                //$json =  "{\"operations\": [[\"custom_json\",{\"required_auths\":[],\"required_posting_auths\": [\"".$user."\"], \"id\" : \"".$appname."\", \"json\":\"{\\\"data\\\":\\\"".$fragment."\\\"}\"}]]}";

                $json =  "{\"operations\": [[\"comment\",{\"parent_author\":\"" . $user . "\",\"parent_permlink\":";
                $json .= "\"" . $lab . $perma . "\", \"author\" : \"" . $user . "\", \"permlink\" : \"" . $lab . $perma . "-table1-" . $storesData[1];
                $json .= "\",\"title\" : \"\", \"body\":";
                $json .= "\"{\\\"data\\\" : \\\"" . $fragment . "\\\"}\", \"json_metadata\":\"{\\\"tags\\\":[\\\"datastores\\\"]}\"}]";
                $json .= ",[\"comment_options\", {\"author\": \"" . $user . "\", \"permlink\":\"" . $lab . $perma . "-table1-" . $storesData[1] . "\", \"title\":\"\",\"max_accepted_payout\": \"0.000 HBD\", \"percent_hbd\": 0, \"allow_votes\": true,\"allow_curation_rewards\": true, \"extensions\": []}]]}";
                //echo "<br>".$json."<br>";

                echo "under 64950";
                $ch = curl_init($ss1 . "/api/broadcast");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json );
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json, text/plain, */*','authorization: ' . $accesstoken, 'content-type: application/json','DNT:1','Origin: http://127.0.0.1:8080/php.steem.php','','Sec-Fetch-Mode: cors','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));
                //*/
                echo "\ncurl execute2";

                if(($result = curl_exec($ch)) === false) 
                {
                    echo 'Curl error: ' . curl_error($ch);
                    $success = false;
                    $ok = false;
                }
                else
                {
                    echo '\nOperation completed.';
                    if (strpos($result, "Bad Request") < 105 && strpos($result, "Bad Request") > 0 ) 
                    {
                        echo "Bad Request";
                        $success = false;
                        $ok = false;
                    }
                    else if (strpos($result, "Account: \${account} has \${rc_current} RC, needs \${rc_needed} RC") > 0) 
                    {
                        echo "Bad Request2 ";
                        $success = false;
                        $ok = false;
                        sleep(36000);
                    }
                    else 
                    {
                        //echo "<br>";
                        //echo $result;
                        //echo "<br>";
                        $resu = substr($result, strpos($result, "id") + 5);
                        $resu = substr($resu, 0, strpos($resu, ",") - 1);
                    }
                    //echo "<br>";
                    //echo $resu;
                    //echo "<br>";
                }
            }//end else/if
            else 
            {
                echo "over 64950";
                $finaldata = str_split($fragment, $max);
                $uu = count($finaldata);

                for($pp = 0; $pp < $uu; $pp++) 
                {
                    if (time() - $lasttime < 3) 
                    {
                        sleep(3);
                    } 
                    $lasttime = time();
                    //$json =  "{\"operations\": [[\"custom_json\",{\"required_auths\":[],\"required_posting_auths\": [\"".$user."\"], \"id\" : \"".$appname."\", \"json\":\"{\\\"data\\\":\\\"".$finaldata[$pp]."\\\"}\"}]]}";
                    $json = "{\"operations\": [[\"comment\",{\"parent_author\":\"" . $user . "\",\"parent_permlink\":";
                    $json .= "\"" . $lab . $perma . "\", \"author\" : \"" . $user . "\", \"permlink\" : \"" . $lab . $perma . "-table1-" . $storesData[1] . "-" . $pp;
                    $json .= "\",\"title\" : \"\", \"body\":";
                    $json .= "\"{\\\"data\\\" : \\\"" . $finaldata[$pp] . "\\\"}\", \"json_metadata\":\"{\\\"tags\\\":[\\\"datastores\\\"]}\"}]";
                    $json .= ",[\"comment_options\", {\"author\": \"" . $user . "\", \"permlink\":\"" . $lab . $perma . "-table1-" . $storesData[1] . "-" . $pp . "\", \"title\":\"\",\"max_accepted_payout\": \"0.000 HBD\", \"percent_hbd\": 0, \"allow_votes\": true,\"allow_curation_rewards\": true, \"extensions\": []}]]}";
                    //echo "<br>".$json."<br>";

                    //echo $json;
                    $ch = curl_init($ss1 . "/api/broadcast");
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json );
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json, text/plain, */*','authorization: ' . $accesstoken, 'content-type: application/json', 'DNT:1', 'Origin: http://127.0.0.1:8080/php.steem.php', '', 'Sec-Fetch-Mode: cors', 'User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));
                    //*/
                    echo "\ncurl execute3";

                    if(($result = curl_exec($ch)) === false) 
                    {
                        echo 'Curl error: ' . curl_error($ch);
                        $fragment2 .= $resu . " ";
                        $success = false;
                        $ok = false;
                    }
                    else
                    {
                        echo '\nOperation completed.';
                        if (strpos($result, "Bad Request")<105 && strpos($result, "Bad Request")>0 ) 
                        {
                            echo "Bad Request";
                            $success = false;
                            $ok = false;
                        }
                        else if (strpos($result, "Account: \${account} has \${rc_current} RC, needs \${rc_needed} RC")>0) 
                        {
                            echo "Bad Request2 ";
                            $success = false;
                            $ok = false;
                            sleep(36000);
                        }
                        else 
                        {
                            //echo "<br>";
                            //echo $result;
                            //echo "<br>";
                            $resu = substr($result, strpos($result, "id") + 5);
                            $resu = substr($resu, 0, strpos($resu, ",") - 1);
                        }
                        //echo "<br>";
                        //echo $resu;
                        //echo "<br>";

                        $fragment2 .= $resu . " ";
                    }
                }
            }
            //end else/else/for/else
        }//end count("tk");

        if (strpos($fragment2, "undefined") > 0 
        || strpos($fragment2, "server_error") > 0) 
        {
            $success = false;
            $ok = false;
        }
        
        $finaldata = $fragment2;
        $fragment3 = "";

        if ($ok) 
        {
            if (time() - $lasttime < 3) 
            {
                sleep(3);
            } 

            $lasttime = time();

            if (strlen($fragment2) < $max) 
            {
                $ok = false;
                echo "second under 64950" . $fragment2 . "fragmentsize:" . strlen($fragment2);
                //$json =  "{\"operations\": [[\"custom_json\",{\"required_auths\":[],\"required_posting_auths\": [\"".$user."\"], \"id\" : \"".$appname."\", \"json\":\"{\\\"data\\\":\\\"".$fragment2."\\\"}\"}]]}";
                $json =  "{\"operations\": [[\"comment\",{\"parent_author\":\"".$user."\",\"parent_permlink\":";
                $json.="\"".$lab.$perma."\", \"author\" : \"".$user."\", \"permlink\" : \"".$lab.$perma."-table2";
                $json.="\",\"title\" : \"\", \"body\":";
                $json.="\"{\\\"data\\\" : \\\"".$fragment2."\\\"}\", \"json_metadata\":\"{\\\"tags\\\":[\\\"datastores\\\"]}\"}]";
                $json.=",[\"comment_options\", {\"author\": \"".$user."\", \"permlink\":\"".$lab.$perma."-table2\", \"title\":\"\",\"max_accepted_payout\": \"0.000 HBD\", \"percent_hbd\": 0, \"allow_votes\": true,\"allow_curation_rewards\": true, \"extensions\": []}]]}";
                //echo "<br>".$json."<br>";

                //echo $json;
                $ch = curl_init($ss1."/api/broadcast");
                curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json );
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json, text/plain, */*','authorization: '.$accesstoken,'content-type: application/json','DNT:1','Origin: http://127.0.0.1:8080/php.steem.php','','Sec-Fetch-Mode: cors','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));
        
                echo "\ncurl execute4";

                if(($result = curl_exec($ch)) === false) 
                {
                    echo 'Curl error: ' . curl_error($ch);
                    $success = false;
                    $ok = false;
                }
                else
                {
                    echo '\nOperation completed without any errors';
                    if (strpos($result, "Bad Request") < 105 && strpos($result, "Bad Request") > 0 ) 
                    {
                        echo "Bad Request";
                        $success = false;
                        $ok = false;
                    }
                    else if (strpos($result, "Account: \${account} has \${rc_current} RC, needs \${rc_needed} RC") > 0) 
                    {
                        echo "Bad Request2 ";
                        $success = false;
                        $ok = false;
                        sleep(36000);
                    }
                    else 
                    {
                        //echo "<br>";
                        //echo $result;
                        //echo "<br>";
                        $resu = substr($result, strpos($result, "id") + 5);
                        $resu = substr($resu, 0, strpos($resu, ",") - 1);
                    }
                    //echo "<br>";
                    //echo $resu;
                    //echo "<br>";
                }
            }//end else/if
            else 
            {
                echo "second over 64950";
                $finaldata = str_split($fragment2, $max);
                $uu = count($finaldata);
                for($pp = 0; $pp < $uu; $pp++) 
                {
                    if (time() - $lasttime < 3) 
                    {
                        sleep(3);
                    } 

                    $lasttime=time();

                    //$json =  "{\"operations\": [[\"custom_json\",{\"required_auths\":[],\"required_posting_auths\": [\"".$user."\"], \"id\" : \"".$appname."\", \"json\":\"{\\\"data\\\":\\\"".$finaldata[$pp]."\\\"}\"}]]}";
                    $json =  "{\"operations\": [[\"comment\",{\"parent_author\":\"".$user."\",\"parent_permlink\":";
                    $json.="\"".$lab.$perma."\", \"author\" : \"".$user."\", \"permlink\" : \"".$lab.$perma."-table2-".$pp;
                    $json.="\",\"title\" : \"\", \"body\":";
                    $json.="\"{\\\"data\\\" : \\\"".$finaldata[$pp]."\\\"}\", \"json_metadata\":\"{\\\"tags\\\":[\\\"datastores\\\"]}\"}]";
                    $json.=",[\"comment_options\", {\"author\": \"".$user."\", \"permlink\":\"".$lab.$perma."-table12-".$pp."\", \"title\":\"\",\"max_accepted_payout\": \"0.000 HBD\", \"percent_hbd\": 0, \"allow_votes\": true,\"allow_curation_rewards\": true, \"extensions\": []}]]}";
                    //echo "<br>".$json."<br>";

                    //echo $json;
                    $ch = curl_init($ss1."/api/broadcast");
                    curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json );
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json, text/plain, */*','authorization: '.$accesstoken,'content-type: application/json','DNT:1','Origin: http://127.0.0.1:8080/php.steem.php','','Sec-Fetch-Mode: cors','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));
    
                    echo "\ncurl execute5";
                    if(($result = curl_exec($ch)) === false) 
                    {
                        echo 'Curl error: ' . curl_error($ch);
                        $success = false;
                        $ok = false;
                    }
                    else
                    {
                        echo '\nOperation completed.';
                        if (strpos($result, "Bad Request") < 105 && strpos($result, "Bad Request")>0 ) 
                        {
                            echo "Bad Request";
                            $success = false;
                            $ok = false;
                        }
                        else if (strpos($result, "Account: \${account} has \${rc_current} RC, needs \${rc_needed} RC") > 0) 
                        {
                            echo "Bad Request2 ";
                            $success = false;
                            $ok = false;
                            sleep(36000);
                        }
                        else 
                        {
                            //echo "<br>";
                            //echo $result;
                            //echo "<br>";
                            $resu = substr($result, strpos($result, "id") + 5);
                            $resu = substr($resu, 0, strpos($resu, ",") - 1);
                        }
                        //echo "<br>";
                        //echo $resu;
                        //echo "<br>";

                        $fragment3 .= $resu . " ";
                    }
                }
            }//end else/for/else
        }//end ok

        $finaldata = $fragment3;
        $fragment2 = "";

        if (strpos($fragment3, "undefined") > 0 
        || strpos($fragment3, "server_error") > 0) 
        {
            $success = false;
            $ok = false;
        }

        if ($ok) 
        {
            if (time()-$lasttime<3) {sleep(3);} $lasttime=time();
            if (strlen($fragment3)<$max) 
            {
                $ok=false;
                echo "fragment 3 under 64950<br>";
                //$json =  "{\"operations\": [[\"custom_json\",{\"required_auths\":[],\"required_posting_auths\": [\"".$user."\"], \"id\" : \"".$appname."\", \"json\":\"{\\\"data\\\":\\\"".$fragment3."\\\"}\"}]]}";
                $json =  "{\"operations\": [[\"comment\",{\"parent_author\":\"".$user."\",\"parent_permlink\":";
                $json.="\"".$lab.$perma."\", \"author\" : \"".$user."\", \"permlink\" : \"".$lab.$perma."-table3";
                $json.="\",\"title\" : \"\", \"body\":";
                $json.="\"{\\\"data\\\" : \\\"".$fragment3."\\\"}\", \"json_metadata\":\"{\\\"tags\\\":[\\\"datastores\\\"]}\"}]";
                $json.=",[\"comment_options\", {\"author\": \"".$user."\", \"permlink\":\"".$lab.$perma."-table3\", \"title\":\"\",\"max_accepted_payout\": \"0.000 HBD\", \"percent_hbd\": 0, \"allow_votes\": true,\"allow_curation_rewards\": true, \"extensions\": []}]]}";
                //echo "<br>".$json."<br>";

                //echo $json;
                $ch = curl_init($ss1."/api/broadcast");
                curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json );
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json, text/plain, */*','authorization: '.$accesstoken,'content-type: application/json','DNT:1','Origin: http://127.0.0.1:8080/php.steem.php','','Sec-Fetch-Mode: cors','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));
                //*/
                echo "\ncurl execute6";
                if(($result=curl_exec($ch)) === false) 
                {
                    echo 'Curl error: ' . curl_error($ch);
                    $success=false;
                    $ok=false;
                }
                else
                {
                    echo '\nOperation completed.';
                    if (strpos($result, "Bad Request")<105 && strpos($result, "Bad Request")>0 ) 
                    {
                        echo "Bad Request";
                        $success=false;
                        $ok=false;
                    }
                    else if (strpos($result, "Account: \${account} has \${rc_current} RC, needs \${rc_needed} RC")>0) 
                    {
                        echo "Bad Request2 ";
                        $success=false;
                        $ok=false;
                        sleep(36000);
                    }
                    else 
                    {
                        //echo "<br>";
                        //echo $result;
                        //echo "<br>";
                        $resu=substr($result,strpos($result, "id")+5);
                        $resu=substr($resu,0,strpos($resu, ",")-1);
                    }
                    //echo "<br>";
                    //echo $resu;
                    //echo "<br>";
                }
            }  //end else/if
            else 
            {
                echo "second over 64950";
                $finaldata = str_split($fragment3, $max);
                $uu = count($finaldata);

                for($pp = 0; $pp < $uu; $pp++) 
                {
                    if (time()-$lasttime<3) {sleep(3);} $lasttime=time();
                    //$json =  "{\"operations\": [[\"custom_json\",{\"required_auths\":[],\"required_posting_auths\": [\"".$user."\"], \"id\" : \"".$appname."\", \"json\":\"{\\\"data\\\":\\\"".$finaldata[$pp]."\\\"}\"}]]}";
                    $json =  "{\"operations\": [[\"comment\",{\"parent_author\":\"".$user."\",\"parent_permlink\":";
                    $json.="\"".$lab.$perma."\", \"author\" : \"".$user."\", \"permlink\" : \"".$lab.$perma."-table3-".$pp;
                    $json.="\",\"title\" : \"\", \"body\":";
                    $json.="\"{\\\"data\\\" : \\\"".$finaldata[$pp]."\\\"}\", \"json_metadata\":\"{\\\"tags\\\":[\\\"datastores\\\"]}\"}]";
                    $json.=",[\"comment_options\", {\"author\": \"".$user."\", \"permlink\":\"".$lab.$perma."-table3-".$pp."\", \"title\":\"\",\"max_accepted_payout\": \"0.000 HBD\", \"percent_hbd\": 0, \"allow_votes\": true,\"allow_curation_rewards\": true, \"extensions\": []}]]}";
                    //echo "<br>".$json."<br>";

                    //echo $json;
                    $ch = curl_init($ss1."/api/broadcast");
                    curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json );
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json, text/plain, */*','authorization: '.$accesstoken,'content-type: application/json','DNT:1','Origin: http://127.0.0.1:8080/php.steem.php','','Sec-Fetch-Mode: cors','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));
                    //*/
                    echo "\ncurl execute7";
                    if(($result=curl_exec($ch)) === false) 
                    {
                        echo 'Curl error: ' . curl_error($ch);
                        $success=false;
                        $ok=false;
                    }
                    else
                    {
                        echo '\nOperation completed.';
                        if (strpos($result, "Bad Request")<105 && strpos($result, "Bad Request")>0 ) 
                        {
                            echo "Bad Request";
                            $success=false;
                            $ok=false;
                        }
                        else if (strpos($result, "Account: \${account} has \${rc_current} RC, needs \${rc_needed} RC")>0) 
                        {
                            echo "Bad Request2 ";
                            $success=false;
                            $ok=false;
                            sleep(36000);
                        }
                        else 
                        {
                            //echo "<br>";
                            //echo $result;
                            //echo "<br>";
                            $resu=substr($result,strpos($result, "id")+5);
                            $resu=substr($resu,0,strpos($resu, ",")-1);
                        }
                        //echo "<br>";
                        //echo $resu;
                        //echo "<br>";

                        $fragment2=$fragment2.$resu." ";
                    }
                }
            }//end else/for/else
        }//end ok

        if (strpos($fragment2,"undefined")>0 || strpos($fragment2,"server_error")>0) 
        {
            $success=false;
            $ok=false;
        }

        if ($ok) 
        {
            if (time()-$lasttime<3) 
            {
                sleep(3);
            } 
            $lasttime=time();
            
            if (strlen($fragment2)<$max) 
            {
                $ok=false;
                echo "fourth over 64000".$fragment2."fragmentsize:".strlen($fragment2);
                //$json =  "{\"operations\": [[\"custom_json\",{\"required_auths\":[],\"required_posting_auths\": [\"".$user."\"], \"id\" : \"".$appname."\", \"json\":\"{\\\"data\\\":\\\"".$fragment2."\\\"}\"}]]}";
                $json =  "{\"operations\": [[\"comment\",{\"parent_author\":\"".$user."\",\"parent_permlink\":";
                $json.="\"".$lab.$perma."\", \"author\" : \"".$user."\", \"permlink\" : \"".$lab.$perma."-table4";
                $json.="\",\"title\" : \"\", \"body\":";
                $json.="\"{\\\"data\\\" : \\\"".$fragment2."\\\"}\", \"json_metadata\":\"{\\\"tags\\\":[\\\"datastores\\\"]}\"}]";
                $json.=",[\"comment_options\", {\"author\": \"".$user."\", \"permlink\":\"".$lab.$perma."-table4\", \"title\":\"\",\"max_accepted_payout\": \"0.000 HBD\", \"percent_hbd\": 0, \"allow_votes\": true,\"allow_curation_rewards\": true, \"extensions\": []}]]}";
                //echo "<br>".$json."<br>";

                //echo $json;
                $ch = curl_init($ss1."/api/broadcast");
                curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json );
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json, text/plain, */*','authorization: '.$accesstoken,'content-type: application/json','DNT:1','Origin: http://127.0.0.1:8080/php.steem.php','','Sec-Fetch-Mode: cors','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));
                echo "\ncurl execute8";
                if(($result=curl_exec($ch)) === false) 
                {
                    echo 'Curl error: ' . curl_error($ch);
                    $success=false;
                    $ok=false;
                }
                else
                {
                    echo '\nOperation completed.';
                    if (strpos($result, "Bad Request")<105 && strpos($result, "Bad Request")>0 ) 
                    {
                        echo "Bad Request";
                        $success=false;
                        $ok=false;
                    }
                    else if (strpos($result, "Account: \${account} has \${rc_current} RC, needs \${rc_needed} RC")>0) 
                    {
                        echo "Bad Request2 ";
                        $success=false;
                        $ok=false;
                        sleep(36000);
                    }
                    else 
                    {
                        //echo "<br>";
                        //echo $result;
                        //echo "<br>";
                        $resu=substr($result,strpos($result, "id")+5);
                        $resu=substr($resu,0,strpos($resu, ",")-1);
                    }
                    //echo "<br>";
                    //echo $resu;
                    //echo "<br>";
                }
            }
        }//end else/else/if/ok

        if ($success) 
        {
            if ($resu!=="ant") 
            {
                if ($resu!=="or\":server_error") 
                {
                    if ($resu!=="or\":\"unauthorized_client\"") 
                    {
                        if ($sql2 = $conn2->prepare( "UPDATE `stores` SET `tickerid`=? WHERE `fileid`=? AND `fileindex`=?")) 
                        {
                            $sql2->bind_param("sii",$resu,$storesData[0],$storesData[1]);
                            if(!$sql2->execute())
                            {
                                trigger_error("there was an error....".$conn->error, E_USER_WARNING);
                            }
                            else 
                            {
                                echo "sucess update";
                            }
                            sleep(1);
                            $sql2->close();
                        }
                    }
                }
            }
            //;unset($sql2);
        }//end success
    }//end ran

    if (time()-$lasttime<3) 
    {
        sleep(3);
    }
    $lasttime=time();
    $conn->close();
    $conn2->close();

}//end while

?>