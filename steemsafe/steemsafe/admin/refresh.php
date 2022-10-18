<?php

function startsWith($a,$b) {return (substr($a,0,strlen($b))===$b);}

function endsWith($a,$b) {
if (strlen($b)==0) {return true;}
return (substr($a,-strlen($b))===$b);}


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
$chain="STEEM";
$chain=substr($myfile,strpos($myfile,"\r#chain\r")+9);
$chain=substr($chain,0,strpos($chain,"\r"));
$chain=strToUpper($chain);
$sp="";
$ss1="https://api.steemconnect.com";
$ss2="https://beta.steemconnect.com";
if ($chain==="HIVE")
{
$sp="2";
$ss1="https://hivesigner.com";
$ss2="https://hivesigner.com";

}
echo "<br>chain:".$chain;


while(true) {

if (isset($argv)) {
if (count($argv)==5)  {
$code=$argv[1];
$secret=$argv[2];
echo "<br>\nargv[2]".$argv[2];
$appname=$argv[3];
$server=$argv[4];

$server=str_replace(":","%3A",$server);
$server=str_replace("/","%2F",$server);

//echo "<br>\nserver:".$server;
echo "<br>\ncode:".$code;
echo "<br>\nsecret:".$secret;
echo "\n<br>server:".$ss1."/api/oauth2/token";
$ch = curl_init($ss1."/api/oauth2/token");
curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS,"code=".$code."&client_secret=$secret");
$result="";
if (($result=curl_exec($ch)) === false) {}
//echo "\n<br>result:".$result;
if (strpos($result,">Application Error")>0) {echo"the server ".$ss1." is down.";
echo $result;
}
else {
echo "<br>\n accesscode:\n";
$access_code=substr($result,strpos($result,":")+2);
$access_code=substr($access_code,0,strpos($access_code,"\""));
echo $access_code;
$refresh=substr($result,strpos($result,"refresh_token")+16);
$refresh=substr($refresh,0,strpos($refresh,"\""));
echo "<br>\nrefresh:".$refresh."\n";
//echo substr($refresh,0,strpos($refresh,".")+1);
//$refresh= substr($refresh,0,strpos($refresh,".")+1);

echo "<br>\nuser:";

//echo substr($result,strpos($result,"username\"")+11,-2);
$user=substr($result,strpos($result,"username\"")+11,-2);
echo $user;
if (ctype_alnum($user)) {
echo "<br>\n:".$refresh;
echo "<br>\naccesscode:".$access_code;
echo "\n<br>path"."/home/webadmin/steemsafe/admin/".$user.".rfs".$sp."\n<br>";
$fk=fopen("/home/webadmin/steemsafe/admin/".$user.".rfs".$sp,'w');
fwrite($fk,$refresh);
chmod("/home/webadmin/steemsafe/admin/".$user.".rfs".$sp, 0644);
fclose($fk);

$fk=fopen("/home/webadmin/steemsafe/admin/".$user.".acc".$sp,'w');
fwrite($fk,$access_code);
chmod("/home/webadmin/steemsafe/admin/".$user.".acc".$sp, 0644);
fclose($fk);
}

}

}
else
{
if (!isset($appname)) {$appname="blarchive";}
if (!isset($server)) {$server="https://blarchive.net";$server=str_replace(":","%3A",$server);$server=str_replace("/","%2F",$server);}
//echo strlen($appname."\n");
//echo strlen($server."\n");
echo "<br>\n\nenter php startengine.php [code] [secret]<br>\n<a href=\"".$ss2."/login-request/";
echo $appname."?response_type=code&redirect_uri=".$server;
echo "%2Fphp%2frefresh.php&scope=vote,comment,offline&scope=posting\">click here to get code</a>";}
}else 
{
$appname="blarchive";
//$server="https://blarchive.net";
$server="https://blarchive.net";
$server=str_replace(":","%3A",$server);
$server=str_replace("/","%2F",$server);
echo "<br>server:".$server;


echo "<br>\n\nplease load this offline from the commandline and enter<br>\php startengine.php start [botnumber] [user] [code]<br>\n<a href=\"".$ss2."/login-request/";
echo $appname."?response_type=code&redirect_uri=".$server;
echo "%2Fphp%2Findex.php&scope=vote,comment,offline&scope=posting\">click here to get code</a>";}

sleep(3*24*60*60);
}//end while
?>
