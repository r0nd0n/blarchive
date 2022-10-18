<?php
require ("/home/webadmin/vendor/autoload.php");
$server="https://blarchive.net";
require("confc.php");
require("encode.php");
$database="archive";
//$start2=350;
//please see https://stackoverflow.com/questions/23921117/disable-only-full-group-by to remedy only_sql_full_group_by error
$start2=$argv[1];
//$user=$_SESSION["user"];


//$accesstoken=$_SESSION["access_token"];
$mod1=$argv[1]-1;
$start2=$argv[2];
$mod2=$argv[3];
$appname=$argv[4];
$user=$argv[5];
//$accesstoken=$argv[6];
$server=$argv[6];

echo "<br>\nmod1";
echo $mod1;
echo "<br>\nstart2";
echo $start2;
echo "<br>\nmod2";
echo $mod2;
echo "<br>\nappname";
echo $appname;
echo "<br>\nuser";
echo $user;
sleep(15);

$tsql="select * from (SELECT lookup.fileid,payment,sum(paid) as m2 FROM `lookup` join accounting on lookup.fileid=accounting.fileid WHERE `blockchain`='0' AND lookup.fileid>".$start2." AND MOD(lookup.fileid,?)=? AND warn=0  AND `coin`=? group by lookup.fileid) as myalias where m2>=`payment` AND `payment`>=0 limit 1";
$tsql2="UPDATE `lookup` SET `blockchain`=? WHERE `fileid`=?";
$tsql3="select * from (select *,sum(strcmp(tickerid,0)) as m2 FROM (select stores.tickerid, lookup.fileparts, lookup.fileid, stores.fileid as t2, lookup.filepath, lookup.added from `stores` join `lookup` on lookup.fileid=stores.fileid where strcmp(blockchain,\"0\")!=0) as myalias where added=0  group by `fileid`) as myalias2 where m2=fileparts AND MOD(fileid,?)=?";

$perma="-compressed-encoded-binary-data";
$title="compress data encoded";
$data="If you are here, this is the container for future compressed encoded binary data files.";
$count=1;


function startsWith($a,$b) {return (substr($a,0,strlen($b))===$b);}

function endsWith($a,$b) {
if (strlen($b)==0) {return true;}
return (substr($a,-strlen($b))===$b);}


function doquery($a,$conn) {
$kk="";
$result="";
global $mod1,$mod2,$chain;
echo "\n".$a;
global $conn;
if ($sql=$conn->prepare($a))
{$sql->bind_param("iis",$mod2,$mod1,$chain);
if ($sql->execute())
{echo "success select";
$result=$sql->get_result();
$kk = $result->fetch_array();
$sql->close();}
else {trigger_error("there was an error....".$conn->error, E_USER_WARNING);} 
}
else {echo "Error: " . $a . "" . mysqli_error($conn);}
unset($sql);
return $kk;
}


$chain="STEEM";
$filename="/home/webadmin/steemsafe/admin/conf.conf";
$fp=fopen($filename,'r');
$myfile=fread($fp,filesize($filename));
fclose($fp);

$myfile=str_replace("\n","\r",$myfile);

$linpath=substr($myfile,strpos($myfile,"\r#path\r")+8);
$linpath=substr($linpath,0,strpos($linpath,"\r"));

if (startsWith($linpath,"\"")) {$linpath=substr($linpath,1);}
if (endsWith($linpath,"\"")) {$linpath=substr($linpath,0,-1);}


$filename2=$linpath."archive.conf";
$fp=fopen($filename2,'r');
$myfile=fread($fp,filesize($filename2));
fclose($fp);
$myfile=str_replace("\n","\r",$myfile);

$chain=substr($myfile,strpos($myfile,"\r#chain\r")+9);
$chain=substr($chain,0,strpos($chain,"\r"));
$chain=strToUpper($chain);
$sp="";
$ss1="https://api.steemconnect.com";
$ss2="https://beta.steemconnect.com";
$ss3="https://steemdb.com";
$ss4="https://api.steemit.com";
if ($chain==="HIVE")
{$sp="2";
$ss1="https://hivesigner.com";
$ss2="https://hivesigner.com";
$ss3="https://hive-db.com";
$ss4="https://api.hive.blog";
}


//$conn=new mysqli($servername,$username,$password,"archive");
//if ($conn->connect_error) {die("connection to the database failed:". $conn->connect_error);} else {echo "database connected";}

while(true)
{
$conn=new mysqli($servername,$username,$password,"archive");
if ($conn->connect_error) {die("connection to the database failed:". $conn->connect_error);} else {echo "database connected";}

$filename2="/home/webadmin/steemsafe/admin/".$user.".acc".$sp;
$fp=fopen($filename2,'r');
$myfile2=fread($fp,filesize($filename2));
fclose($fp);

$accesstoken=$myfile2;
echo $accesstoken;
$ch=curl_init($ss1."/api/me");
curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_HTTPHEADER, array('accept: application/json, text/plain, */*','Content-type: application/json','authorization: '.$accesstoken,'DNT:1','Origin:https://blarchive.net','Referer:https://blarchive.net','sec-fetch-mode: cors','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));
//*/
echo "\ncurl2:".$ss1."/api/me\n";
if(($result=curl_exec($ch)) === false) {}
echo $result;
$result="";


$count=($count+1)%2;
$time=time();
echo "\ncount:".$count;
if ($count==0) {
echo "initial post stage";

$kk=doquery($tsql, $conn);
$fileid=$kk[0];
$lab=$kk[0];

echo "\nfileid:";
echo $fileid;
echo "\n$lab:";
echo $lab;
echo "\nsrtlenfileid";
echo strlen($fileid);
echo "\nsrtlenlab";
echo strlen($lab);



if (strlen($lab)>0 && strlen($fileid)>0) {
$json =  "{\"operations\": [[\"comment\",{\"parent_author\":\"\",\"parent_permlink\":";
$json.="\"datastores\", \"author\" : \"".$user."\", \"permlink\" : \"".$fileid.$perma;
$json.="\",\"title\" : \"".$title."\", \"body\":\"";
$json.=$data."\", \"json_metadata\":\"{\\\"tags\\\":[\\\"datastores\\\"]}\"}]";
//$json.=",[\"comment_options\", {\"author\": \"".$user."\", \"permlink\":\"".$lab.$perma."\", \"title\":\"\",\"max_accepted_payout\": \"0.000 SBD\", \"percent_steem_dollars\": 0, \"allow_votes\": true,\"allow_curation_rewards\": true, \"extensions\": []}]]}";
$json.=",[\"comment_options\", {\"author\": \"".$user."\", \"permlink\":\"".$lab.$perma."\", \"title\":\"\",\"max_accepted_payout\": \"0.000 HBD\", \"percent_hbd\": 0, \"allow_votes\": true,\"allow_curation_rewards\": true, \"extensions\": []}]]}";
echo "\njson:\n".$json."\n";
//echo "strlenjson".strlen($json);
$ok=true;
$ch = curl_init($ss1."/api/broadcast");
curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json );
curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json, text/plain, */*','authorization: '.$accesstoken,'content-type: application/json','DNT:1','Origin: https://blarchive.net/php.steem.php','','Sec-Fetch-Mode: cors','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));
//*/
$fragment="";
$result="";
$resu="";
if (($result=curl_exec($ch)) === false)
{echo '\nCurl error: ' . curl_error($ch);}
else
{echo '\nOperation completed without any errors';
if (strpos($result, "Bad Request")<105 && strpos($result, "Bad Request")>0 ) {echo "\nBad Request";$ok=false;}
else if (strpos($result, "or\":\"unauthorized_cl")<105 && strpos($result, "or\":\"unauthorized_cl")>0 ) {echo "\nunauthorized token\n";$ok=false;echo $result;}
else {
echo "<br>\n";
echo $result;
echo "<br>\n";
$resu=substr($result,strpos($result, "id")+5);
$resu=substr($resu,0,strpos($resu, ",")-1);
echo "<br>\n";
echo $resu;
$fragment.="tk;".$resu." ";
echo "<br>\n";
echo $fragment;
echo "<br>\n";}}
//
if ($resu==="ant") {$ok=false;}
if ($resu==='or":"server_error') {$ok=false;echo "or server error";}
if ($ok) {
if (strpos(substr(strtolower($fragment),0,12),"server")===false) {
$sql = $conn->prepare( $tsql2);
$sql->bind_param("si",$resu,$fileid);
if(!$sql->execute()){trigger_error("there was an error....".$conn->error, E_USER_WARNING);} else {echo "sucess update";}
} else {echo "needed to wait 5 minutes";}
}
sleep(301);
}
else {sleep(5);}
}  //end engine1


else {
echo "\nfinal post stage";
$fileid=0;
$path="";
$index=0;
echo "\n".$tsql3;
if ($result=$conn->prepare($tsql3)){
$result->bind_param("ii",$mod2,$mod1);
if ($result->execute()) {
$result2=$result->get_result();
    while ($row = $result2->fetch_array()) {      $fileid=$row[2];$path=$row[4];}
echo "\nfileid:".$fileid;
               echo "\nsql selection successful";
} else {trigger_error("there was an error....".$conn->error, E_USER_WARNING);}
            } else {
               echo "Error: " . $tsql . "" . mysqli_error($conn);
            }
$data2="";
$ok=true;
$temp="";
if (strlen($fileid)>0 && strlen($path)>0) {
$tsql4="select tickerid from stores where fileid='".$fileid."' AND tickerid!=\"0\"";
if ($result=mysqli_query($conn, $tsql4)) {
    while ($row = $result->fetch_row()) {
if (strlen($row[0])==0) {$ok=false;}
$temp.="fp:".$row[0].";";
echo "\n";
echo $row[0];
echo "\n";
}}//end for/while/if
else {echo "Error: " . $tsql . "" . mysqli_error($conn);}
if (strlen($temp)==0) {$ok=false;}
$data2.=$temp;
$efid=encode($fileid);
echo "shortlink".$efid;
$data2="This is an archive of ".$path." stores of the block chain.  To decompile the data (*testing*), please visit ".$server."/php/loadfile.php?fileid=".$fileid."&index=0&user=".$user."<br>shortlink:".$server."/".$efid."<br><br>*****<br>".$data2;
if ($ok) {
//$json =  "{\"operations\": [[\"custom_json\",{\"required_auths\":[],\"required_posting_auths\": [\"".$user."\"], \"id\" : \"".$appname."\", \"json\":\"{\\\"data\\\":\\\"".$data2."\\\"}\"}]]}";
$json =  "{\"operations\": [[\"comment\",{\"parent_author\":\"\",\"parent_permlink\":";
$json.="\"datastores\", \"author\" : \"".$user."\", \"permlink\" : \"".$fileid.$perma;
$json.="\",\"title\" : \"".$title."\", \"body\":\"";
$json.=$data2."\", \"json_metadata\":\"{\\\"tags\\\":[\\\"datastores\\\"]}\"}]";
$json.=",[\"comment_options\", {\"author\": \"".$user."\", \"permlink\":\"".$fileid.$perma."\", \"title\":\"\",\"max_accepted_payout\": \"0.000 HBD\", \"percent_hbd\": 0, \"allow_votes\": true,\"allow_curation_rewards\": true, \"extensions\": []}]]}";
echo "\n<br>json:".$json."<br>\n";
$ch = curl_init($ss1."/api/broadcast");
curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json );
curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json, text/plain, */*','authorization: '.$accesstoken,'content-type: application/json','DNT:1','Origin: https://blarchive.net/php.steem.php','','Sec-Fetch-Mode: cors','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));

if(($result=curl_exec($ch)) === false) {echo 'Curl error: ' . curl_error($ch);;$success=false;$ok=false;}
else
{echo 'Operation completed without any errors';
if (strpos($result, "Bad Request")<105 && strpos($result, "Bad Request")>0 ) {echo $result; echo "\n\n";echo "Bad Request1 ";$success=false;$ok=false;}
else if (strpos($result, "Account: \${account} has \${rc_current} RC, needs \${rc_needed} RC")>0) {echo $result; echo "\n\n";echo "Bad Request2 ";$success=false;$ok=false;}
else {
$resu=substr($result,strpos($result, "id")+5);
$resu=substr($resu,0,strpos($resu, ",")-1);
}}//end else/else/if

echo "\nresu:".$resu."\n";

$sql2 = $conn->prepare( "UPDATE `lookup` SET `added`=?, `bot`=? WHERE `fileid`=?");
$sql2->bind_param("ssi",$resu,$user,$fileid);
if(!$sql2->execute()){trigger_error("there was an error....".$conn->error, E_USER_WARNING);} else {echo "sucess update";}
sleep(1);
$sql2->close();

sleep(301);
} //end $ok
else {sleep(5);}
} //end not null
else {sleep(5);}

}//end engine2

$conn->close();
}//end while
?>
