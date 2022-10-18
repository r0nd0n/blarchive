<?php
function startsWith($a,$b) {return (substr($a,0,strlen($b))===$b);}

function endsWith($a,$b) {
if (strlen($b)==0) {return true;}
return (substr($a,-strlen($b))===$b);}

function gettx($a)
{
if (strpos($a,"trx_num")==0) {return "failed";}
else {
$b=substr($a,strpos($a,"id"));
$b=substr($b,strpos($b," ")+3);
$b=substr($b,0,strpos($b,",")-1);
echo "\n<br>gettx:".$b;
return $b;}
}

$lasttime=time();
require("/home/webadmin/steemsafe/admin/confc.php");
$database="archive";
$mod1=$argv[1]-1;
$start2=$argv[2];
$mod2=$argv[3];
$appname=$argv[4];
$user=$argv[5];
//$accesstoken=$argv[6];
sleep(1);
$filename="/home/webadmin/steemsafe/admin/"."conf.conf";
$fp=fopen($filename,'r');
$myfile=fread($fp,filesize($filename));
fclose($fp);
echo $myfile;
echo "\n";


$myfile=str_replace("\n","\r",$myfile);
$linpath=substr($myfile,strpos($myfile,"\r#path\r")+8);
$linpath=substr($linpath,0,strpos($linpath,"\r"));
echo $linpath;
echo "\n";

if (startsWith($linpath,"\"")) {$linpath=substr($linpath,1);}
if (endsWith($linpath,"\"")) {$linpath=substr($linpath,0,-1);}

$result="";
$fragment2="";
$max=64950;
$perma="-compressed-encoded-binary-data";

echo $linpath;


$filename2=$linpath."archive.conf";
$fp=fopen($filename2,'r');
$myfile=fread($fp,filesize($filename2));
fclose($fp);
$myfile=str_replace("\n","\r",$myfile);
$chain="STEEM";
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


function doquery($a,$conn,$b) {
$result="";
$kk[0]="";
$kk[1]="";
global $mod1,$mod2,$start2,$conn;
if ($sql=$conn->prepare($a))
{$sql->bind_param("sii",$start2,$mod2,$mod1);
if ($sql->execute())
{echo "\nsuccess select";
$result=$sql->get_result();
$kk = $result->fetch_array();
$sql->close();}
else {trigger_error("there was an error....".$conn->error, E_USER_WARNING);} 
}
else {echo "Error: " . $a . "" . mysqli_error($conn);}

unset($sql);
return $kk;
}




while (true) 
{
$conn=new mysqli($servername,$username,$password,"archive");
$conn2=new mysqli($servername,$username,$password,"archive");
if ($conn->connect_error) {die("connection to the database failed:". $conn->connect_error);}
else {echo "\ndatabase connected";}
if ($conn2->connect_error) {die("connection to the database failed:". $conn2->connect_error);}
else {echo "\ndatabase connected";}
$ok=true;
$success=true;
$ran=true;
$time=time();
$lastime=time();
$text="";
echo "\n".$time."\n";
$index=0;
$sindex=0;
$tsql="SELECT stores.fileid, stores.fileindex FROM `stores` join lookup on lookup.fileid=stores.fileid WHERE `tickerid`='0' and strcmp(blockchain,\"0\")!=0 AND ".$time."-`filedate`>30 AND stores.fileid>? AND MOD(stores.fileid,?)=? LIMIT 1";
$kk=doquery($tsql, $conn, $sindex);
$sindex++;
if (!isset($kk[0])) {sleep(3);continue;}
echo "\nfileid:".$kk[0];
echo "\nindex:".$kk[1];
if (strlen($kk[0])==0 || strlen($kk[1])==0) {$ran=false;}
else {
$filename=$linpath.$kk[0]."/".$kk[1].".mp4";
$fk=fopen($filename,'r') or $ran=false;
if (filesize($filename)==0) {$text="";}
else {$text=fread($fk,filesize($filename)) or $ran=false;}
$text=base64_encode(gzcompress($text));
if (filesize($filename)==0) {$text="";}
fclose($fk);
$filename=$linpath.$kk[0]."/index.html";
$fk=fopen($filename,'r') or $ran=false;
$ext=fread($fk,filesize($filename));
fclose($fk);
$ar=explode("\n",$ext);
echo "\nindex:".$kk[1]."ext".$ar[$kk[1]]."\n";
echo $linpath.$kk[0]."/".$kk[1].".mp4"."\n";
echo filesize($linpath.$kk[0]."/".$kk[1].".mp4")."\n";
echo (filesize($linpath.$kk[0]."/".$kk[1].".mp4")>$max)?"local save":"chain save";
if (($ar[$kk[1]]==="img" || $ar[$kk[1]]==="&type=vid" || $ar[$kk[1]]==="vid" || $ar[$kk[1]]==="snd") && filesize($linpath.$kk[0]."/".$kk[1].".mp4")>($max))  {
echo "made it";
$save="local:".$kk[0]."/".$kk[1];
echo ($save!=="ant" && $save!=="failed" )?"true":"false";
if ($save!=="ant" && $save!=="failed") {
if ($save!=="or\":server_error") {

if ($sql2 = $conn2->prepare( "UPDATE `stores` SET `tickerid`=? WHERE `fileid`=? AND `fileindex`=?")){
$sql2->bind_param("sii",$save,$kk[0],$kk[1]);
if(!$sql2->execute()){trigger_error("there was an sql error....".$conn->error, E_USER_WARNING);} else {echo "sucess update";}
$ran=false;
}


}}}

}
echo "\nran ".($ran?"true":"false");
if ($ran) {
$url ="ssl://".$ss1;
$path ="/api/broadcast";
$pagetext="";

echo strlen($text);
$lab = $kk[0];
$index=0;
$data="";
$start=0;
$data2="";
$end=strlen($text);
$fragment="";

for ($start=0;$start<$end;$start+=$max) {
if (($start+$max)>$end) {$data=substr($text,$start);}
else {$data=substr($text,$start,$max);}
$result="";$resu="";
echo "curl execute1";
//echo ("python helloworld.py socketpuppet $lab$perma $lab$perma-$kk[1]-$index none '{\"d\" : \"$data\"}' datastores");
$result=shell_exec("python helloworld.py socketpuppet $lab$perma $lab$perma-$kk[1]-$index none '{\"d\" : \"$data\"}' datastores 2>&1");
if (strpos($result,"trx_num")==0) {echo 'Curl error: ' . $result;$ok=false;$success=false;
echo "\nec0:".$result;
echo "\nec1:".strpos($result, "Please wait to transact, or power up STEEM.");
echo "\nec2:".strpos($result, "eemapi.exceptions.UnhandledRPCError: plugin exception:Account:");
echo "\n";
if(strpos($result, "Please wait to transact, or power up STEEM.")>0 && strpos($result, "eemapi.exceptions.UnhandledRPCError: plugin exception:Account:")>0)
 {echo "Bad Request2 (out of RCS)";$success=false;$ok=false;sleep(36000);}
}
else
{echo '\nOperation completed.';
if (strpos($result, "Bad Request")<105 && strpos($result, "Bad Request")>0 ) {echo "Bad Request";echo "\n".$result."\n";sleep(3);}
else if (strpos($result, "Account: \${account} has \${rc_current} RC, needs \${rc_needed} RC")>0 || 
(strpos($result, "Please wait to transact, or power up STEEM.")>0 && strpos($result, "eemapi.exceptions.UnhandledRPCError: plugin exception:Account:")>0))
 {echo "Bad Request2 (out of RCS)";$success=false;$ok=false;sleep(36000);}
else {
$resu=gettx($result);
$fragment.="tk;".$resu." ";
}}//endelse if
if (time()-$lasttime<3) {sleep(3);}
$lasttime=time();
$index++;
}//endfor

$ok=true;
if (strpos($fragment,"undefined")>0  || strpos($fragment,"server_error")>0) {$success=false;$ok=false;echo "\n<br>result:".$result;echo "\n<br>json:".$json;}

$zx=explode("tk:",$fragment);

if (count($zx)==2) {$ok=false;}
 else {
if (strlen($fragment)<$max) {
$ok=false;


echo "under 64950";
echo "\ncurl execute2";
$result=shell_exec("python helloworld.py socketpuppet $lab$perma $lab$perma-table1-$kk[1] none '{\"d\" : \"$fragment\"}' datastores 2>&1");
if (strpos($result,"trx_num")==0) {echo 'Curl error: ' . $result;$ok=false;$success=false;
echo "\n2ec0:".$result;
echo "\n2ec1:".strpos($result, "Please wait to transact, or power up STEEM.");
echo "\n2ec2:".strpos($result, "eemapi.exceptions.UnhandledRPCError: plugin exception:Account:");
echo "\n";


if (strpos($result, "Please wait to transact, or power up STEEM.")>0 && strpos($result, "eemapi.exceptions.UnhandledRPCError: plugin exception:Account:")>0)
 {echo "Bad Request2 (out of RCS)";$success=false;$ok=false;sleep(36000);}

}
else
{echo '\nOperation completed.';
if (strpos($result, "Bad Request")<105 && strpos($result, "Bad Request")>0 ) {echo "Bad Request";$success=false;$ok=false;

}
else if (strpos($result, "Account: \${account} has \${rc_current} RC, needs \${rc_needed} RC")>0
||
(strpos($result, "Please wait to transact, or power up STEEM.")>0 && strpos($result, "eemapi.exceptions.UnhandledRPCError: plugin exception:Account:")>0) ) 
{echo "Bad Request2 ";$success=false;$ok=false;sleep(36000);}
else {$resu=gettx($result);}
}}//end else/if
else {
echo "over 64950";
$finaldata=str_split($fragment,$max);
$uu=count($finaldata);
for($pp=0;$pp<$uu;$pp++) {
if (time()-$lasttime<3) {sleep(3);}
$lasttime=time();
echo "\ncurl execute3";
$result=shell_exec("python helloworld.py socketpuppet $lab$perma $lab$perma-table1-$kk[1] none '{\"d\" : \"$finaldata[$pp]\"}' datastores 2>&1");
if (strpos($result,"trx_num")==0) {echo 'Curl error: ' . $result;$ok=false;$sucess=false;
if (strpos($result, "Please wait to transact, or power up STEEM.")>0 && strpos($result, "eemapi.exceptions.UnhandledRPCError: plugin exception:Account:")>0)
 {echo "Bad Request2 (out of RCS)";$success=false;$ok=false;sleep(36000);}
}
else
{echo '\nOperation completed.';
if (strpos($result, "Bad Request")<105 && strpos($result, "Bad Request")>0 ) {echo "Bad Request";$success=false;$ok=false;}
else if (strpos($result, "Account: \${account} has \${rc_current} RC, needs \${rc_needed} RC")>0 ||
(strpos($result, "Please wait to transact, or power up STEEM.")>0 && strpos($result, "eemapi.exceptions.UnhandledRPCError: plugin exception:Account:")>0) ) 
{echo "Bad Request2 ";$success=false;$ok=false;sleep(36000);}
else {
$resu=gettx($result);
$fragment2.=$resu." ";
}}}
//end else/else/for/else
}//end count("tk");



if (strpos($fragment2,"undefined")>0  || strpos($fragment2,"server_error")>0) {$success=false;$ok=false;}
$finaldata=$fragment2;
$fragment3="";


if ($ok) {
if (time()-$lasttime<3) {sleep(3);}
$lasttime=time();
if (strlen($fragment2)<$max) {
$ok=false;
echo "second under 64950".$fragment2."fragmentsize:".strlen($fragment2)."\ncurl execute4";
$result=shell_exec("python helloworld.py socketpuppet $lab$perma $lab$perma-table2 none '{\"d\" : \"$fragment2\"}' datastores 2>&1");
if (strpos($result,"trx_num")==0) {echo 'Curl error: ' . $result;$ok=false;$success=false;
if (strpos($result, "Please wait to transact, or power up STEEM.")>0 && strpos($result, "eemapi.exceptions.UnhandledRPCError: plugin exception:Account:")>0)
 {echo "Bad Request2 (out of RCS)";$success=false;$ok=false;sleep(36000);}
}
else {
echo '\nOperation completed without any errors';
if (strpos($result, "Bad Request")<105 && strpos($result, "Bad Request")>0 ) {echo "Bad Request";$success=false;$ok=false;}
else if (strpos($result, "Account: \${account} has \${rc_current} RC, needs \${rc_needed} RC")>0 ||
(strpos($result, "Please wait to transact, or power up STEEM.")>0 && strpos($result, "eemapi.exceptions.UnhandledRPCError: plugin exception:Account:")>0) )
 {echo "Bad Request2 ";$success=false;$ok=false;sleep(36000);}
else {$resu=gettx($result);}
}}//end else/if
else {
echo "second over 64950";
$finaldata=str_split($fragment2,$max);
$uu=count($finaldata);
for($pp=0;$pp<$uu;$pp++) {
if (time()-$lasttime<3) {sleep(3);}
$lasttime=time();
echo "\ncurl execute5";
$result=shell_exec("python helloworld.py socketpuppet $lab$perma $lab$perma-table2-$pp none '{\"d\" : \"$finaldata[$pp]\"}' datastores 2>&1");
if (strpos($result,"trx_num")==0) {echo 'Curl error: ' . $result;$ok=false;$success=false;
if (strpos($result, "Please wait to transact, or power up STEEM.")>0 && strpos($result, "eemapi.exceptions.UnhandledRPCError: plugin exception:Account:")>0)
 {echo "Bad Request2 (out of RCS)";$success=false;$ok=false;sleep(36000);}
}
else{
echo '\nOperation completed.';
if (strpos($result, "Bad Request")<105 && strpos($result, "Bad Request")>0 ) {echo "Bad Request";$success=false;$ok=false;}
else if (strpos($result, "Account: \${account} has \${rc_current} RC, needs \${rc_needed} RC")>0||
(strpos($result, "Please wait to transact, or power up STEEM.")>0 && strpos($result, "eemapi.exceptions.UnhandledRPCError: plugin exception:Account:")>0) 
) {echo "Bad Request2 ";$success=false;$ok=false;sleep(36000);}
else {
$resu=gettx($result);
$fragment3.=$resu." ";}
}}}//end else/for/else
}//end ok


$finaldata=$fragment3;
$fragment2="";
if (strpos($fragment3,"undefined")>0 || strpos($fragment3,"server_error")>0) {$success=false;$ok=false;}

if ($ok) {if (time()-$lasttime<3) {sleep(3);} $lasttime=time();
if (strlen($fragment3)<$max) {
$ok=false;
echo "fragment 3 under 64950<br>";
echo "\ncurl execute6";
$result=shell_exec("python helloworld.py socketpuppet $lab$perma $lab$perma-table3 none '{\"d\" : \"$fragment3\"}' datastores 2>&1");
if (strpos($result,"trx_num")==0) {echo 'Curl error: ' . $result;$ok=false;$success=false;
if (strpos($result, "Please wait to transact, or power up STEEM.")>0 && strpos($result, "eemapi.exceptions.UnhandledRPCError: plugin exception:Account:")>0)
 {echo "Bad Request2 (out of RCS)";$success=false;$ok=false;sleep(36000);}
}
else
{echo '\nOperation completed.';
if (strpos($result, "Bad Request")<105 && strpos($result, "Bad Request")>0 ) {echo "Bad Request";$success=false;$ok=false;}
else if (strpos($result, "Account: \${account} has \${rc_current} RC, needs \${rc_needed} RC")>0||
(strpos($result, "Please wait to transact, or power up STEEM.")>0 && strpos($result, "eemapi.exceptions.UnhandledRPCError: plugin exception:Account:")>0) )
{echo "Bad Request2 ";$success=false;$ok=false;sleep(36000);}
else {$resu=gettx($result);}
}}  //end else/if
else {
echo "second over 64950";
$finaldata=str_split($fragment3,$max);
$uu=count($finaldata);
for($pp=0;$pp<$uu;$pp++) {if (time()-$lasttime<3) {sleep(3);} $lasttime=time();
echo "\ncurl execute7";
$result=shell_exec("python helloworld.py socketpuppet $lab$perma $lab$perma-table3-$pp none '{\"d\" : \"$finaldata[$pp]\"}' datastores 2>&1");
if (strpos($result,"trx_num")==0) {echo 'Curl error: ' . $result;$ok=false;$success=false;
if (strpos($result, "Please wait to transact, or power up STEEM.")>0 && strpos($result, "eemapi.exceptions.UnhandledRPCError: plugin exception:Account:")>0)
 {echo "Bad Request2 (out of RCS)";$success=false;$ok=false;sleep(36000);}
}
else
{echo '\nOperation completed.';
if (strpos($result, "Bad Request")<105 && strpos($result, "Bad Request")>0 ) {echo "Bad Request";$success=false;$ok=false;}
else if (strpos($result, "Account: \${account} has \${rc_current} RC, needs \${rc_needed} RC")>0||
(strpos($result, "Please wait to transact, or power up STEEM.")>0 && strpos($result, "eemapi.exceptions.UnhandledRPCError: plugin exception:Account:")>0) )
 {echo "Bad Request2 ";$success=false;$ok=false;sleep(36000);}
else {
$resu=gettx($result);
$fragment2=$fragment2.$resu." ";}
}}}//end else/for/else
}//end ok



if (strpos($fragment2,"undefined")>0 || strpos($fragment2,"server_error")>0) {$success=false;$ok=false;}
if ($ok) {
if (time()-$lasttime<3) {sleep(3);}
$lasttime=time();
if (strlen($fragment2)<$max) {
$ok=false;
echo "fourth over 64950".$fragment2."fragmentsize:".strlen($fragment2)."\ncurl execute8";
$result=shell_exec("python helloworld.py socketpuppet $lab$perma $lab$perma-table4 none '{\"d\" : \"$fragment2\"}' datastores 2>&1");
if (strpos($result,"trx_num")==0) {echo 'Curl error: ' . $result;$ok=false;$success=false;
if(strpos($result, "Please wait to transact, or power up STEEM.")>0 && strpos($result, "eemapi.exceptions.UnhandledRPCError: plugin exception:Account:")>0)
 {echo "Bad Request2 (out of RCS)";$success=false;$ok=false;sleep(36000);}
}
else {
echo '\nOperation completed.';
if (strpos($result, "Bad Request")<105 && strpos($result, "Bad Request")>0 ) {echo "Bad Request";$success=false;$ok=false;}
else if (strpos($result, "Account: \${account} has \${rc_current} RC, needs \${rc_needed} RC")>0||
(strpos($result, "Please wait to transact, or power up STEEM.")>0 && strpos($result, "eemapi.exceptions.UnhandledRPCError: plugin exception:Account:")>0) )
{echo "Bad Request2 ";$success=false;$ok=false;sleep(36000);}
else {$resu=gettx($result);}
}}}//end else/else/if/ok

if ($success) {
if ($resu!=="ant" && $resu!=="failed") {
if ($resu!=="or\":server_error") {
if ($sql2 = $conn2->prepare( "UPDATE `stores` SET `tickerid`=? WHERE `fileid`=? AND `fileindex`=?")) {
$sql2->bind_param("sii",$resu,$kk[0],$kk[1]);
if(!$sql2->execute()){trigger_error("there was an error....".$conn->error, E_USER_WARNING);} else {echo "sucess update";}
sleep(1);
$sql2->close();}}}
}//end success

//new
}//end more than one frag

}//end ran

if (time()-$lasttime<3) {sleep(3);} $lasttime=time();
$conn->close();
$conn2->close();
}//end while
?>
