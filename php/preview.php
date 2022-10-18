<?php
session_start();
unset($_SESSION['user']);
unset($_SESSION['access_token']);
session_destroy();

require("confc.php");

try {
$conn=new mysqli($servername,$username,$password,"archive");
}
catch (Exception $e) {echo "dbconnectivity issue.  try again later";die;}


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


function startsWith($a,$b) {return (substr($a,0,strlen($b))===$b);}
function endsWith($a,$b) {
if (strlen($b)==0) {return true;}
return (substr($a,-strlen($b))===$b);}

$isandroid=false;
if (strpos(strToLower($_SERVER['HTTP_USER_AGENT']),"android")>0) {$isandroid=true;}

if(isset($_GET['fileid']) && isset($_GET['index']))  {
$fileid=$_GET['fileid'];
$index=$_GET['index'];
$type="";
if (isset($_GET['type'])) {$type=$_GET['type'];}
$aa="";
if (ctype_digit(strval($fileid)) && ctype_digit(strval($index)))
{$result="";
$time=time()-7*24*3600;
$sql = $conn->prepare( "select fileid,filetime,added from lookup WHERE `fileid`=?");
$sql->bind_param("i",$fileid);
if(!$sql->execute()){trigger_error("there was an error....".$conn->error, E_USER_WARNING);}
$result=$sql->get_result();
while($row = $result->fetch_assoc()) {$aa=$row["fileid"];$filetime=$row["filetime"];$added=$row["added"];}
$sql->close();

if ($time>$filetime && $added==0) {
echo "This link is more than a week old and will not be loaded unless payment is made to put them on the block chain.<br>";
$time2=time()-$filetime+6*3600;//artificially aged 6 hours for customer service
if ($time2>30*24*3600) {echo "Because the link is more than 30 days old, the files have been removed from the server.";}
else if ($time2>29*24*3600) {echo "These files are expected to be deleted from the server in ".(int)(((30*24*3600-$time2))/3600)." hours unless full payment is made to put them on the block chain.";}
else {echo "These files are expected to be deleted from the server in ".(int)((30*24*3600-$time2)/(24*3600))." days unless full payment is made to put them on the block chain.";}
exit;}

$filename=$fileid."/".$index.".mp4";
$fp=fopen($filename,'r');
$result=fread($fp,filesize($filename));
fclose($fp);


$result=str_replace("loadfile.php?fileid=","preview.php?fileid=",$result);

if ($type==="src") {header("Content-Type: application/javascript");ob_clean();flush();echo $result;}

else if ($type==="js") {
if (strpos($result,"</")===false && strpos($result,"for(")===false && strpos($result,"if(")===false) {header("Content-Type: text/css");ob_clean();flush();echo $result;}
else {header("Content-Type: application/javascript");ob_clean();flush();echo $result;}
}

else if ($type==="css") {header("Content-Type: text/css");ob_clean();flush();echo $result;}

else if ($type==="img") {
$samp=substr($result,0,4);
$samp2=unpack('C*', substr($result,0,5));
echo $samp;

if (strpos(strtolower($samp),"png")!==false) {header("Content-Type: image/png");ob_clean();flush();echo $result;exit;}
else if (startsWith(strtolower($samp),"riff")) {header("Content-Type: image/webp");}
else if (startsWith(strtolower($samp),"bm")) {header("Content-Type: image/bmp");}
else if (startsWith(strtolower($samp),"gif")) {header("Content-Type: image/gif");ob_clean();flush();echo $result;exit;}
else if (startsWith(strtolower($samp),"jpg")) {header("Content-Type: image/jpeg");}
else if (startsWith(strtolower($samp),"<svg")) {header("Content-Type: image/svg+xml");}
else if (startsWith(strtolower($result),"<?xml") && strpos($result,"<svg")<150 && strpos($result,"<svg")>0) {header("Content-Type: image/svg+xml");}
else if (endsWith(strtolower($result),"svg>")) {header("Content-Type: image/svg+xml");}
else if (startsWith(strtolower($samp),"wof2")) {header("Content-Type: font/woff2");}
else if ($samp2[1]==0 && $samp2[2]==0 && $samp2[3]==1 && $samp2[4]==0) {header("Content-Type: image/x-icon");}
else if ($samp2[1]==0 && $samp2[2]==0 && $samp2[3]==2 && $samp2[4]==0) {header("Content-Type: image/x-win-bitmap");}
else if ($samp2[1]==0 && $samp2[2]==1 && $samp2[3]==0 && $samp2[4]==0 && $samp2[5]==0) {header("Content-Type: application/x-font-ttf");}
else if (strpos(substr(strtolower($result),0,100),"jfif")>0) {header("Content-Type: image/jpeg");}
else if (strpos(substr(strtolower($result),0,100),"<svg ")!==FALSE) {header("Content-Type: image/svg+xml");}
else if (strpos(substr($result,0,100),"GSUB")>0) {header("Content-Type: application/octet-stream");}

ob_clean();
flush();
echo $result;
}
//the android has issues on some servers not loading local absolute paths, but will work properly on a redirect.
//since androids have been indentified in this script, it is possible to redirect them to a lower quality video.
else if ($type==="vid") {

$samp=substr($result,0,4);
$samp2=unpack('C*', substr($result,0,5));
//print_r($samp2);
//echo "vid";
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
//echo "2hell0";
ob_clean();flush();echo $result;
}

else if ($type==="txt") {
$samp=substr($result,0,4);
$samp10=substr($result,0,4);
if (startsWith(strtolower($samp),"webvtt")) {header("Content-Type: type/vtt");}
ob_clean();flush();
echo $result;
}

else if ($type==="java") {header("Content-Type: application/x-java-applet");
//echo "4hell0";
ob_clean();flush();
echo $result;}

else if ($type==="snd") {
$samp=substr($result,0,4);
$samp2=substr($result,0,12);
if (startsWith(strtolower($samp),"riff")) {header("Content-Type: audio/wave");}
else if (startsWith(strtolower($samp),"ogg")) {header("Content-Type: audio/ogg");}
else if (startsWith(strtolower($samp),"mthd") || startsWith(strtolower($samp),"mtrk")) {header("Content-Type: audio/ogg");}
else if (strpos(strtolower($samp2),"aifc")!==false) {header("Content-Type: audio/x-aiff");}
else {header("Content-Type: audio/mp3");}

//echo "5hell0";
ob_clean();flush();
echo $result;
}

else if (!isset($_GET['type'])) {
$samp=strtolower(substr($result,0,4));
$samp2=unpack('C*', substr($result,0,5));

//if ($index==0) {
//$samp=strtolower(substr($result,0,4));
//$ishtml=true;
//if (startsWith(strtolower($samp),"%pdf")) {$ishtml=false;}
//if ($ishtml) {header("Content-Type: text/html");}
//else {header("Content-Type: application/pdf");}
//} 
//https://www.php.net/manual/en/function.chr.php
if ($samp==="<!do" || $samp==="<htm") {header("Content-Type: text/html");
//$result=str_replace(chr(147), "\"", $result);$result=str_replace(chr(148), "\"", $result);$result=str_replace(chr(145), "'", $result);$result=str_replace(chr(146), "'", $result);

}
else if (strpos(strtolower($samp),"png")!==false) {header("Content-Type: image/png");}
else if (startsWith(strtolower($samp),"riff")) {header("Content-Type: image/webp");}
else if (startsWith(strtolower($samp),"bm")) {header("Content-Type: image/bmp");}
else if (startsWith(strtolower($samp),"gif")) {header("Content-Type: image/gif");}
else if (startsWith(strtolower($samp),"jpg")) {header("Content-Type: image/jpeg");}
else if (startsWith(strtolower($samp),"<svg")) {header("Content-Type: image/svg+xml");}
else if (startsWith(strtolower($samp),"wof2")) {header("Content-Type: font/woff2");}
else if ($samp2[1]==0 && $samp2[2]==0 && $samp2[3]==1 && $samp2[4]==0) {header("Content-Type: image/x-icon");}
else if ($samp2[1]==0 && $samp2[2]==0 && $samp2[3]==2 && $samp2[4]==0) {header("Content-Type: image/x-win-bitmap");}
else if ($samp2[1]==0 && $samp2[2]==1 && $samp2[3]==0 && $samp2[4]==0 && $samp2[5]==0) {header("Content-Type: application/x-font-ttf");}
else if (strpos(substr(strtolower($result),0,100),"jfif")>0) {header("Content-Type: image/jpeg");}
else if (startsWith(strtolower($samp),"gif")) {header("Content-Type: image/gif");}
else if (startsWith(strtolower($samp),"%pdf")) {header("Content-Type: application/pdf");}
else if (strpos($result,"</")===false && strpos($result,"for(")===false && strpos($result,"if(")===false) {header("Content-Type: text/css");}
else {header("Content-Type: text/html");
//$result=str_replace(chr(147), "\"", $result);$result=str_replace(chr(148), "\"", $result);$result=str_replace(chr(145), "'", $result);$result=str_replace(chr(146), "'", $result);
}
ob_clean();flush();
echo $result;}

else {
//echo "7hell0";
ob_clean();flush();
echo $result;}
}else {echo "not a int".ctype_digit(strval($fileid))." ".ctype_digit(strval($index))."end";}
}else {echo "not found";}


?>
