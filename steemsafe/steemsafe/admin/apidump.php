<?php

/*
* Blarchive - Get latest block from blockchain and ?.
*/

require("confc.php");

function startsWith($a,$b) {return (substr($a,0,strlen($b))===$b);}
function endsWith($a,$b) { if (strlen($b)==0) {return true;} return (substr($a,-strlen($b))===$b);}

$lasttx=0;

$filename="/home/webadmin/steemsafe/admin/conf.conf";
$fp=fopen($filename,'r');
$myfile=fread($fp,filesize($filename));
fclose($fp);

$myfile=str_replace("\n","\r",$myfile);
$linpath=substr($myfile,strpos($myfile,"\r#beneficiary\r")+15);
$beneficiary=substr($linpath,0,strpos($linpath,"\r"));

$linpath=substr($myfile,strpos($myfile,"\r#path\r")+8);
$linpath=substr($linpath,0,strpos($linpath,"\r"));
if (startsWith($linpath,"\"")) {$linpath=substr($linpath,1);}
if (endsWith($linpath,"\"")) {$linpath=substr($linpath,0,-1);}

echo $beneficiary;
echo "\n";


$filename2=$linpath."archive.conf";
$fp=fopen($filename2,'r');
$myfile=fread($fp,filesize($filename2));
fclose($fp);
$myfile=str_replace("\n","\r",$myfile);

$chain=substr($myfile,strpos($myfile,"\r#chain\r")+9);
$chain=substr($chain,0,strpos($chain,"\r"));
$chain=strToUpper($chain);
$sp="";

$sp="2";
$ss1="https://hivesigner.com";
$ss2="https://hivesigner.com";
$ss3="https://hiveblocks.com";
$ss4="https://api.hive.blog";

echo "<br>chain:".$chain;

While (true) {
$conn=new mysqli($servername,$username,$password,"archive");
if ($conn->connect_error) {die("connection to the database failed:". $conn->connect_error);}


$time=time();
//echo "\n<br>".$time;
$temp="";
$result="";
$payload = '{"jsonrpc":"2.0", "method":"condenser_api.get_dynamic_global_properties", "params":[], "id":1}';
$ch=curl_init($ss4);
curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch,CURLOPT_HTTPHEADER, array('accept: application/json, text/plain, */*','Content-type: application/json','DNT:1','Origin:http://127.0.0.1:8080','Referer:http://127.0.0.1:8080','sec-fetch-mode: cors','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));
//*/
if(($result=curl_exec($ch)) === false) {echo 'Curl error: ' . curl_error($ch);}
//echo "\n<br>".$result;


// identify head block from blockchain
$temp=substr($result,0,strpos($result,"head_block_id"));
$temp=substr($temp,0,strrpos($temp,","));
$head=substr($temp,strrpos($temp,":")+1);

if ($lasttx==0) 
{
    $lasttx=$head;
}
else 
{
    if ($lasttx==$head)  
    {
        sleep(1);
        continue;
    }
    else 
    {
        $lasttx++;
    }
}

//echo "\n<br>".$head;

$temp=substr($result,0,strpos($result,"current_witness"));
$temp=substr($temp,0,strrpos($temp,",")-1);
//$ts=$temp;
$ts=substr($temp,strrpos($temp,"\":\"")+3);
//echo "\n<br>".$ts;
echo "\n<br>".strtotime($ts);
$ts=strtotime($ts)-3;

// get head block from blockchain
$payload='{"jsonrpc":"2.0", "method":"condenser_api.get_block", "params":['.$lasttx.'], "id":1}';
//echo "\n<br>".$payload;
curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch,CURLOPT_HTTPHEADER, array('accept: application/json, text/plain, */*','Content-type: application/json','DNT:1','Origin:http://127.0.0.1:8080','Referer:http://127.0.0.1:8080','sec-fetch-mode: cors','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));
if(($result=curl_exec($ch)) === false) {echo 'Curl error: ' . curl_error($ch);}
//echo "\n<br>".$result;


$temp=substr($result,strpos($result,"[{"));
//echo "\n<br>".$temp;
$ex=explode("{\"ref_block_num\":",$temp);
$d=count($ex);

for($i=1;$i<$d;$i++) 
{
    if (strpos($ex[$i],"\",\"operations\":[[\"transfer\",{\"from\":\"")>0)
    {
        $temp=substr($ex[$i],strpos($ex[$i],"from")+7);
        $from=substr($temp,0,strpos($temp,",")-1);
        $temp=substr($temp,strpos($temp,",\"to\":")+7);
        $to=substr($temp,0,strpos($temp,",")-1);
        $temp=substr($temp,strpos($temp,",\"amount\":")+11);
        $temp2=substr($temp,0,strpos($temp,",")-1);
        $crypto=substr($temp2,0,strpos($temp2," "));
        $symbol=trim(substr($temp2,strpos($temp2," ")));
        $temp=substr($temp,strpos($temp,",\"memo\":")+9);
        $memo=substr($temp,0,strpos($temp,"}")-1);
        $temp=substr($temp,strpos($temp,",\"transaction_id\":")+19);
        $tx=substr($temp,0,strpos($temp,",")-1);

        if ($to===$beneficiary) {
        echo "\n<br>".$lasttx;
        echo "\n<br>".$from;
        echo "\n<br>".$to;
        echo "\n<br>".$crypto;
        echo "\n<br>".$symbol;
        echo "\n<br>".$memo;
        echo "\n<br>".$tx;
        echo "\n<br>".$ex[$i];


        //if (strlen($link)>256) {$link=substr($link,0,256);}
        $link="";
        $weight="";
        $action="transfer";
        if (strlen($memo)>256) {$memo=substr($memo,0,256);}

        if (startsWith($action, "transfer")) {
        if ($sql = $conn->prepare( "INSERT INTO `node` (`ts`,`tx`,`block`,`account`,`user2`,`memo`,`crypto`,`symbol`,`link`,`weight`,`act`,`ty`) VALUES (?, ? , ?, ?,?,?,?,?,?,?,?,1)")) {
        $sql->bind_param("isisssdssss",$ts,$tx,$lasttx,$from,$to,$memo,$crypto,$symbol,$link,$weight,$action);
        if(!$sql->execute()){
        //trigger_error("there was an error....".$conn->error, E_USER_WARNING);
        }}
        else {echo "\nsql prepare returned false";}
        echo "\ntransferred";
        }}
    }
}
if ($lasttx!=$head) {continue;}
$time2=time();
$dif=$time2-$time;
if ($dif==0) {sleep(2);}
if ($dif==1) {sleep(1);}
echo "\n<br>process took:".$dif." seconds";
$conn->close();
}
?>
