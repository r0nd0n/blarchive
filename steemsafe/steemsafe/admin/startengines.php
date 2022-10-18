<?php

var_dump($argv);
if (count($argv)==5)  {
if ($argv[1]==="start") {

function startsWith($a,$b) {return (substr($a,0,strlen($b))===$b);}

function endsWith($a,$b) {
if (strlen($b)==0) {return true;}
return (substr($a,-strlen($b))===$b);}


$filename="conf.conf";
$fp=fopen($filename,'r');
$myfile=fread($fp,filesize($filename));
fclose($fp);
echo $myfile;
echo "<br>";

$myfile=str_replace("\n","\r",$myfile);
$linpath=substr($myfile,strpos($myfile,"\r#path\r")+8);
$linpath=substr($linpath,0,strpos($linpath,"\r"));
echo $linpath;
echo "\n";

$server=substr($myfile,strpos($myfile,"\r#server\r")+10);
$server=substr($server,0,strpos($server,"\r"));
echo $server;
echo "\n";

$secret=substr($myfile,strpos($myfile,"\r#secret\r")+10);
$secret=substr($secret,0,strpos($secret,"\r"));
echo $secret;
echo "\n";


if (startsWith($linpath,"\"")) {$linpath=substr($linpath,1);}
if (endsWith($linpath,"\"")) {$linpath=substr($linpath,0,-1);}

$filename2=$linpath."archive.conf";
$fp=fopen($filename2,'r');
$myfile=fread($fp,filesize($filename2));
fclose($fp);
echo $myfile;
echo "<br>";


$myfile=str_replace("\n","\r",$myfile);
$index=substr($myfile,strpos($myfile,"\r#databasejump\r")+16);
$index=substr($index,0,strpos($index,"\r"));
echo $index;
$appname=substr($myfile,strpos($myfile,"\r#appname\r")+11);
$appname=substr($appname,0,strpos($appname,"\r"));
echo $appname;


$mod=substr($myfile,strpos($myfile,"\r#number_of_reader_bots\r")+25);
$mod=substr($mod,0,strpos($mod,"\r"));
echo $mod;
echo $mod;

if (strToLower(PHP_OS)==="winnt" || strToLower(PHP_OS)==="win95" || strToLower(PHP_OS)==="windows")  {
popen("start php postengine.php ".$argv[2]." ".$index." ".$mod." ".$appname." ".$argv[3]." ".$server,'r');
      popen("start php bot1.php ".$argv[2]." ".$index." ".$mod." ".$appname." ".$argv[3]." ".$server,'r');
      popen("start php refresh.php ".$argv[4]." ".$secret." ".$appname." ".$server,'r');
}
else {
popen("lxterminal -e php postengine.php ".$argv[2]." ".$index." ".$mod." ".$appname." ".$argv[3]." ".$server.'r');
      popen("lxterminal -e php bot1.php ".$argv[2]." ".$index." ".$mod." ".$appname." ".$argv[3]." ".$server,'r');
      popen("lxterminal -e php refresh.php ".$argv[4]." ".$secret." ".$appname." ".$server,'r');
}

}
else {echo "botnumber starts at 1\nenter php startengines.php start botnumber user steemconnectkey";}

}
else {
echo "count".count($argv);
echo "botnumber starts at 1\nenter php startengines.php start botnumber user steemconnectkey";}
?>
