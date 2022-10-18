<?php


$servername="localhost";
$username="testing";
$password="nimda";
$conn=new mysqli($servername,$username,$password,"archive");
if ($conn->connect_error) {die("connection to the database failed:". $conn->connect_error);}

if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
    }
}

$perma="-compressed-encoded-binary-data";
function startsWith($a,$b) {return (substr($a,0,strlen($b))===$b);}

$isandroid=false;
if (strpos(strToLower($_SERVER['HTTP_USER_AGENT']),"android")>0) {$isandroid=true;}


if(isset($_GET['fileid']) && isset($_GET['index']))  {
$fileid=$_GET['fileid'];
$index=$_GET['index'];
$type="";
if (isset($_GET['type'])) {$type=$_GET['type'];}

if (ctype_digit(strval($fileid)) && ctype_digit(strval($index)))
{
if (isset($_GET['user'])) {$user=$_GET['user'];} else {
$sql = $conn->prepare( "select bot from lookup WHERE `fileid`=?");
$sql->bind_param("i",$fileid);
if(!$sql->execute()){trigger_error("there was an error....".$conn->error, E_USER_WARNING);}
$result=$sql->get_result();
while($row = $result->fetch_assoc()) {$user=$row["bot"];}
$sql->close();

//legacy
//$user="firstamendment";
//exit;
}

//echo $user;

$result="";
$tx=-1;
$zz=dochop4($fileid,$index);if ($zz[1]) {$result=$zz[0];} else {sleep(1);
$zz=dochop4($fileid,$index);if ($zz[1]) {$result=$zz[0];} else {sleep(1);
$zz=dochop4($fileid,$index);if ($zz[1]) {$result=$zz[0];} else {sleep(1);
}}
}
//echo $result;
$st=explode(";",$result);
$tx=$st[$index];
if (!startsWith($tx,"fp:local")) {
$tx=substr($tx,3);$aa=loadstuff($tx);

}
else {
//$tx=substr($tx,3);
$filename=$fileid."/".$index.".mp4";
//echo $filename;
$fp=fopen($filename,'r');
$aa=fread($fp,filesize($filename));
fclose($fp);
}
$result=$aa;
//echo $result;exit;

if ($type==="src") {header("Content-Type: application/javascript");echo $result;}

else if ($type==="css") {header("Content-Type: text/css");echo $result;}

else if ($type==="img") {
$samp=substr($result,0,4);
$samp2=unpack('C*', substr($result,0,5));
if (strpos(strtolower($samp),"png")!==false) {header("Content-Type: image/png");}
else if (startsWith(strtolower($samp),"riff")) {header("Content-Type: image/webp");}
else if (startsWith(strtolower($samp),"bm")) {header("Content-Type: image/bmp");}
else if (startsWith(strtolower($samp),"gif")) {header("Content-Type: image/gif");}
else if (startsWith(strtolower($samp),"jpg")) {header("Content-Type: image/jpeg");}
else if (startsWith(strtolower($samp),"<svg")) {header("Content-Type: image/svg+xml");}
else if (startsWith(strtolower($samp),"wof2")) {header("Content-Type: font/woff2");}
else if ($samp2[1]==0 && $samp2[2]==0 && $samp2[3]==1 && $samp2[4]==0) {header("Content-Type: image/x-icon");}
else if ($samp2[1]==0 && $samp2[2]==0 && $samp2[3]==2 && $samp2[4]==0) {header("Content-Type: image/x-win-bitmap");}
else if ($samp2[1]==0 && $samp2[2]==1 && $samp2[3]==0 && $samp2[4]==0 && $samp2[5]==0) {header("Content-Type: application/x-font-ttf");}
else if (strpos(substr(strtolower($result),0,10),"jfif")>0) {header("Content-Type: image/jpeg");}
ob_clean();
flush();
echo $result;

}

else if ($type==="vid") {
$samp=substr($result,0,4);
$samp2=unpack('C*', substr($result,0,5));
if (startsWith(strtolower($samp),"riff")) {
//if ($isandroid) {header("Location: ".$fileid."/".$index.".mp4");}
//else 
{header("Content-Type: video/avi");ob_clean();flush();echo $result;}}
else if (startsWith(strtolower($samp),strToLower("Eߣ"))) {
//if ($isandroid) {header("Location: ".$fileid."/".$index.".mp4");}
//else
 {header("Content-Type: video/mkv");ob_clean();flush();echo $result;}}
else if (startsWith(strtolower($samp),"flv")) {
//if ($isandroid) {header("Location: ".$fileid."/".$index.".mp4");}
//else
 {header("Content-Type: video/flv");ob_clean();flush();echo $result;}}
else  {
{header("Content-Type: video/mp4");header("Content-Length: ". filesize($filename));ob_clean();flush();echo $result;}}
}

else if ($type==="emb") {
$samp=substr($result,0,4);
if (startsWith(strtolower($samp),"fws")) {header("Content-Type: x-shockwave-flash");}

ob_clean();
flush();
echo $result;
}

else if ($type==="txt") {
$samp=substr($result,0,4);
$samp10=substr($result,0,4);
if (startsWith(strtolower($samp),"webvtt")) {header("Content-Type: type/vtt");}
ob_clean();
flush();
echo $result;
}

else if ($type==="java") {header("Content-Type: application/x-java-applet");
ob_clean();
flush();
echo $result;}

else if ($type==="snd") {
$samp=substr($result,0,4);
$samp2=substr($result,0,12);
if (startsWith(strtolower($samp),"riff")) {header("Content-Type: audio/wave");}
else if (startsWith(strtolower($samp),"ogg")) {header("Content-Type: audio/ogg");}
else if (startsWith(strtolower($samp),"mthd") || startsWith(strtolower($samp),"mtrk")) {header("Content-Type: audio/ogg");}
else if (strpos(strtolower($samp2),"aifc")!==false) {header("Content-Type: audio/x-aiff");}
else {header("Content-Type: audio/mp3");}
ob_clean();
flush();
echo $result;
}

else if (!isset($_GET['type'])) {
$samp=strtolower(substr($result,0,4));
$samp2=unpack('C*', substr($result,0,5));

if ($index==0) {header("Content-Type: text/html");} 
else if ($samp==="<!do" || $samp==="<htm") {header("Content-Type: text/html");}

else if (strpos(strtolower($samp),"png")!==false) {header("Content-Type: image/png");}
else if (startsWith(strtolower($samp),"riff")) {header("Content-Type: image/webp");}
else if (startsWith(strtolower($samp),"bm")) {header("Content-Type: image/bmp");}
else if (startsWith(strtolower($samp),"gif")) {header("Content-Type: image/gif");}
else if (startsWith(strtolower($samp),"jpg")) {header("Content-Type: image/jpeg");}
else if (startsWith(strtolower($samp),"<svg")) {header("Content-Type: image/svg+xml");}
else if (startsWith(strtolower($samp),"wof2")) {header("Content-Type: font/woff2");}
else if (isset($samp2[4])) {
if ($samp2[1]==0 && $samp2[2]==0 && $samp2[3]==1 && $samp2[4]==0) {header("Content-Type: image/x-icon");}
else if ($samp2[1]==0 && $samp2[2]==0 && $samp2[3]==2 && $samp2[4]==0) {header("Content-Type: image/x-win-bitmap");}
else if ($samp2[1]==0 && $samp2[2]==1 && $samp2[3]==0 && $samp2[4]==0 && $samp2[5]==0) {header("Content-Type: application/x-font-ttf");}}
else if (strpos(substr(strtolower($result),0,10),"jfif")>0) {header("Content-Type: image/jpeg");}
else if (startsWith(strtolower($samp),"gif")) {header("Content-Type: image/gif");}
else {}
ob_clean();
flush();
echo $result;}

else {
ob_clean();
flush();
echo $result;}
}else {echo "not a int".ctype_digit(strval($fileid))." ".ctype_digit(strval($index))."end";}
}else {echo "not found";}



function loadstuff($result) {
//echo "<br>hello world:".$result;
$pagetext=chopper($result);

if (strpos(substr($pagetext,0,10),";")!==false) {
if (strpos(substr($pagetext,0,10),"tk;")>0) {$fd=dochop2($pagetext);} else {$fd=dochop5($pagetext);}
return $fd;} else {
$rr=explode(" ",$pagetext);
$t=count($rr);
$data="";
for($x=0;$x<$t;$x++) {$data.=chopper($rr[$x]);}}

if (strpos(substr($data,0,10),";")!==false) {$fd=dochop2($data);return $fd;} else {
$rr=explode(" ",$data);
$t=count($rr);
$pagetext="";
for($x=0;$x<$t;$x++) {$pagetext.=chopper($rr[$x]);}}

if (strpos(substr($pagetext,0,10),";")!==false) {$fd=dochop2($pagetext);return $fd;} else {
$rr=explode(" ",$pagetext);
$t=count($rr);
$data="";
for($x=0;$x<$t;$x++) {$data.=chopper($rr[$x]);}}


if (strpos(substr($data,0,10),";")!==false) {$fd=dochop2($data);return $fd;}

}




function chopper($resu) {
$pagetext="";
//echo "<br>hello world3:".$resu;

$ch = curl_init("https://steemd.com/tx/".$resu);
curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json, text/plain, */*','content-type: application/json','DNT:1','Origin: http://127.0.0.1:8080/php.steem.php','','Sec-Fetch-Mode: cors','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));
if(($pagetext=curl_exec($ch)) === false) {echo 'chopper:Curl error: ' . curl_error($ch);}
if (strpos($pagetext,"data&quot; :")>0) {
$pagetext=substr($pagetext,strpos($pagetext,"data&quot; :")+18);
$pagetext=substr($pagetext,0,strpos($pagetext,"&quot;"));
}
else if (strpos($pagetext,"d&quot; :")>0) {
$pagetext=substr($pagetext,strpos($pagetext,"d&quot; :")+15);
$pagetext=substr($pagetext,0,strpos($pagetext,"&quot;"));
}
return $pagetext;}


function dochop5($resu) {
//echo "helloworld6:".$resu;
//echo "<br>strlen:".strlen($data);
$aa="";
$aa=gzuncompress(base64_decode($resu));
//if (strlen($aa)==0) {$aa="";}
return $aa;}



function dochop2($resu) {
//echo "helloworld5:".$resu;
$r=explode(" ",$resu);
$t=count($r);
$data="";
for($x=0;$x<$t-1;$x++) {
$zz=dochop3($r[$x],$x);if ($zz[1]) {$data.=$zz[0];} else {sleep(1);
$zz=dochop3($r[$x],$x);if ($zz[1]) {$data.=$zz[0];} else {sleep(1);
$zz=dochop3($r[$x],$x);if ($zz[1]) {$data.=$zz[0];} else {sleep(1);
}}}}
$zz="";
//echo "<br>strlen:".strlen($data);
$aa="";
$aa=gzuncompress(base64_decode($data));
if (strlen($aa)==0) {$aa=$data;}
return $aa;}


function dochop3($resu,$xx){
$ok=true;
$pagetext="";
$resu=substr($resu,strpos($resu,"tk;")+3);
$ch = curl_init("https://steemd.com/tx/".$resu);
curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json, text/plain, */*','content-type: application/json','DNT:1','Origin: http://127.0.0.1:8080/php.steem.php','','Sec-Fetch-Mode: cors','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));
if(($pagetext=curl_exec($ch)) === false) {echo 'dochop3:Curl error: ' . curl_error($ch);$ok=$false;}

if (strlen($pagetext)<1450) {
if (strpos($pagetext,"We're sorry, but something went wrong (500)")>0) {$ok=false;}
else {echo $pagetext;exit();}
}
$resu=substr($pagetext,strpos($pagetext, "d&quot;")+15);
$resu=substr($resu,0,strpos($resu, "&quot;"));
$zz[0]=$resu;
$zz[1]=$ok;
return $zz;}


function dochop4($fileid,$index) {
//echo "hello world2".$fileid;

$ok=true;
//echo $fileid;
global $perma,$user;
//echo "https://steemit.com/datastores/@".$user."/".$fileid.$perma;
$ch = curl_init("https://steemit.com/datastores/@".$user."/".$fileid.$perma);
curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: application/json, text/plain, */*','DNT:1','','Sec-Fetch-Mode: cors','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));
$result="";
$resu="";
if (($result=curl_exec($ch)) === false)  {echo 'dochop4:Curl error: ' . curl_error($ch);$ok=false;}
else
{
if (strpos($result, "Bad Request")<105 && strpos($result, "Bad Request")>0 ) {echo "Bad Request";$ok=false;}
else {
//echo "<br>strlen:".strlen($result)."<br>";
if (strlen($result)<1450) {
if (strpos($result,"We're sorry, but something went wrong (500)")>0) {$ok=false;}
if (strpos($result,"Server error!")>0) {$ok=false;}
else {echo $result;exit();}
}

$result=substr($result,strpos($result,"<p>"));
//echo "<br>strlen:".strlen($result).$ok."<br>";
$result=substr($result,strpos($result,"*****")+11);
//echo "<br>strlen:".strlen($result).$ok."<br>";
//$result=substr($result,strpos($result,"fp:"));
//echo "<br>strlen:".strlen($result).$ok."<br>";
$result=substr($result,0,strpos($result,"</p>"));
//echo "<br>strlen:".strlen($result).$ok."<br>";

}}

$zz[0]=$result;
$zz[1]=$ok;
return $zz;
}


?>