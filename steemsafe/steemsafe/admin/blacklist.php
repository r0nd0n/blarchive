<?php

function startsWith($a,$b) {return (substr($a,0,strlen($b))===$b);}

function endsWith($a,$b) {
if (strlen($b)==0) {return true;}
return (substr($a,-strlen($b))===$b);}

$filename="conf.conf";
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


if (isset($argv[1])) {

if ($argv[1]==="now1") {
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, "https://blocklist.site/app/dl/porn");
curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_HTTPHEADER, array('accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3','accept-language:en-US,en;q=0.9','cache-control: no-cache','DNT:1','pragma:no-cache','sec-fetch-mode: navigate','sec-fetch-site: none','sec-fetch-user: ?1','upgrade-insecure-requests: 1','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36','Connection:Close'));

$result="";

if(($result=curl_exec($ch)) === false) {}

$fk=fopen($linpath."blacklist1.txt",'w');
fwrite($fk,$result);
chmod($linpath."blacklist1.txt", 0644);
fclose($fk);
exit;
}//end now1
else if ($argv[1]==="now2") {
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, "https://blocklist.site/app/dl/porn");
curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_HTTPHEADER, array('accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3','accept-language:en-US,en;q=0.9','cache-control: no-cache','DNT:1','pragma:no-cache','sec-fetch-mode: navigate','sec-fetch-site: none','sec-fetch-user: ?1','upgrade-insecure-requests: 1','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36','Connection:Close'));

$result="";
if(($result=curl_exec($ch)) === false) {}
$fk=fopen($linpath."blacklist2.txt",'w');
fwrite($fk,$result);
chmod($linpath."blacklist2.txt", 0644);
fclose($fk);
$a=explode("\r",$result);
echo "a".count($a);
$b=explode("\n",$result);
echo "b".count($b);

exit;
}//end now2
}//end isset

while (true) {
$time=time();
if ($time%(24*60*60)<(15*60)) {
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, "https://blocklist.site/app/dl/porn");
curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_HTTPHEADER, array('accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3','accept-language:en-US,en;q=0.9','cache-control: no-cache','DNT:1','pragma:no-cache','sec-fetch-mode: navigate','sec-fetch-site: none','sec-fetch-user: ?1','upgrade-insecure-requests: 1','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36','Connection:Close'));

$result="";

if(($result=curl_exec($ch)) === false)

if ($time%(48*60*60)<(15*60)) {
$fk=fopen($linpath."blacklist1.txt",'w');
fwrite($fk,$result);
chmod($linpath."blacklist1.txt", 0644);
fclose($fk);
}
else {
$fk=fopen($linpath."blacklist2.txt",'w');
fwrite($fk,$result);
chmod($linpath."blacklist2.txt", 0644);
fclose($fk);}
sleep(60*15);
}}

?>