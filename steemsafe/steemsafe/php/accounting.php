<?php
$servername="localhost";
$username="testing";
$password="nimda";

function startsWith($a,$b) {return (substr($a,0,strlen($b))===$b);}

function endsWith($a,$b) {
if (strlen($b)==0) {return true;}
return (substr($a,-strlen($b))===$b);}


$filename="conf.conf";
$fp=fopen($filename,'r');
$myfile=fread($fp,filesize($filename));
fclose($fp);

$myfile=str_replace("\n","\r",$myfile);
$linpath=substr($myfile,strpos($myfile,"\r#path\r")+8);
$linpath=substr($linpath,0,strpos($linpath,"\r"));
echo $linpath;
echo "\n";

if (startsWith($linpath,"\"")) {$linpath=substr($linpath,1);}
if (endsWith($linpath,"\"")) {$linpath=substr($linpath,0,-1);}

$filename2=$linpath."archive.conf";
$fp=fopen($filename2,'r');
$myfile=fread($fp,filesize($filename2));
fclose($fp);

$myfile=str_replace("\n","\r",$myfile);
$auser=substr($myfile,strpos($myfile,"\r#beneficiary\r")+15);
$auser=substr($auser,0,strpos($auser,"\r"));
echo $auser;
echo "\n";


$conn=new mysqli($servername,$username,$password,"archive");
if ($conn->connect_error) {die("connection to the database failed:". $conn->connect_error);}
while (true) {
$ch = curl_init("https://steemdb.com/@".$auser."/transfers");
curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('text/plain, */*','DNT:1','Sec-Fetch-Mode: cors','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));

if(($result=curl_exec($ch)) === false) {echo 'Curl error: ' . curl_error($ch);$success=false;$ok=false;}
else
{echo 'Operation completed without any errors';
if (strpos($result, "Bad Request")<105 && strpos($result, "Bad Request")>0 ) {echo "Bad Request";$success=false;$ok=false;}
else {
echo "<br>";
$result=substr($result,strpos($result,"class=\"collapsing"));
$a=explode("lass=\"collapsing",$result);
$j=count($a);
$c;
$index=-1;
for($i=1;$i<$j;$i++) {
if (strpos($a[$i],"href=\"/@")>0) {
$index++;
$time=substr($a[$i],strpos($a[$i],">")+2);
$time=substr($time,0,strpos($time,"<")-1);
$c[$index][3]=$time;
$user=substr($a[$i],strpos($a[$i],"href=\"/@")+8);
$user=substr($user,0,strpos($user,">")-1);
$c[$index][0]=$user;
$price=substr($a[$i],strpos($a[$i],"ui small header"));
$price=substr($price,strpos($price,">")+1);
$price=substr($price,0,strpos($price,"<")-1);
$c[$index][1]=$price;
$c[$index][2]="";
}
else if (strpos($a[$i],"data-popup data-content")>0) {
$memo=substr($a[$i],strpos($a[$i],"data-popup data-content"));
$memo=substr($memo,strpos($memo,"=")+2);
$memo=substr($memo,0,strpos($memo,">")-1);
$c[$index][2]=$memo;
}}
$d=count($c);
for($i=0;$i<$d;$i++) {

if (ctype_digit(strval($c[$i][2]))) {
echo $c[$i][0];echo "<br>";
echo $c[$i][1];echo "<br>";
echo $c[$i][2];echo "<br>";
echo $c[$i][3];echo "<br>";

if ($c[$i][2]>0) {
$sql = $conn->prepare( "INSERT INTO `accounting` (`tx`,`user`,`paid`,`fileid`) VALUES (? , ?, ?,?)");
$sql->bind_param("ssdi",$c[$i][3],$c[$i][0],$c[$i][1],$c[$i][2]);
if(!$sql->execute()){trigger_error("there was an error....".$conn->error, E_USER_WARNING);}
}}



}
}}
sleep(900);
}


?>