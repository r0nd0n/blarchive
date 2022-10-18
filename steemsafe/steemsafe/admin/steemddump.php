<?php
require("confc.php");
$conn=new mysqli($servername,$username,$password,"archive");
if ($conn->connect_error) {die("connection to the database failed:". $conn->connect_error);}
function startsWith($a,$b) {return (substr($a,0,strlen($b))===$b);}
function endsWith($a,$b) { if (strlen($b)==0) {return true;} return (substr($a,-strlen($b))===$b);}

$filename="/home/webadmin/steemsafe/admin/conf.conf";
$fp=fopen($filename,'r');
$myfile=fread($fp,filesize($filename));
fclose($fp);

$myfile=str_replace("\n","\r",$myfile);
$linpath=substr($myfile,strpos($myfile,"\r#beneficiary\r")+15);
$beneficiary=substr($linpath,0,strpos($linpath,"\r"));
echo $beneficiary;
echo "\n";

$i=1;
$oktorun=true;

while ($oktorun) {
$ch=curl_init("https://steemd.com/@".$beneficiary."?page=".$i);
curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_HTTPHEADER, array('accept: application/json, text/plain, */*','Cache-Control: no-cache ','Content-type: application/json','DNT:1','sec-fetch-mode: cors','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));
if(($result=curl_exec($ch)) === false) {echo "error: quitting"; exit;}
echo "\n<br>size:".strlen($result)." ".$i;
if (strlen($result)<200) {echo "\n<br>result:".$result; echo "\n<br>networ error, exiting";exit;}
//if (strlen($result)<200) {echo "\n<br>result:".$result; echo "\n<br>networ error, exiting";exit;}
if (strpos($result,"create account <span class=\"account\">".$beneficiary."</span>")) {$oktorun=false;} 
$result=substr($result,strpos($result,"<div class=\"op op"));
//echo "\n<br>size:".strlen($result);
$result=substr($result,0, strrpos($result,"</time"));
//echo "\n<br>size:".strlen($result);
$ex=explode("<div class=\"op op",$result);
//echo "\n<br>count:".count($ex);
for($d=1;$d<count($ex);$d++) {


$temp=$temp=substr($ex[$d],strpos($ex[$d]," id=")+5);
$tx=substr($temp,0,strpos($temp,">")-1);
$errortrack=0;
//if (strpos($ex[$d],"tag-virt")>0) 
{
$temp=$temp=substr($ex[$d],strrpos($ex[$d],"<a "));
$temp=$temp=substr($temp,0,strpos($temp,">"));
$temp=$temp=substr($temp,strrpos($temp,"/")+1);
$block=substr($temp,0,strrpos($temp,"#"));
if (startsWith($block,"<")) {$block=substr($block,1);}
if (endsWith($block,">")) {$block=substr($block,0,strlen($block)-1);}
}
//else {
//$temp=$temp=substr($ex[$d],1,strpos($ex[$d],"</a"));
//$block=substr($temp,strrpos($temp,">"));
//if (startsWith($block,"<")) {$block=substr($block,1);}
//if (endsWith($block,">")) {$block=substr($block,0,strlen($block)-1);}
//}
if (strpos($ex[$d],"<canvas width")>0) {$ex[$d]=substr($ex[$d],strpos($ex[$d],"<canvas width"));}
$temp="";
if (strpos($ex[$d],"<a class=\"account\"")>0) {$temp=substr($ex[$d],strpos($ex[$d],"<a class=\"account\"")+19);$temp=substr($temp,strpos($temp,">")+1);$temp=substr($temp,0,strpos($temp,"</a>"));}
else if (strpos($ex[$d],"<span class=\"account\">")>0) {$temp=substr($ex[$d],strpos($ex[$d],"<span class=\"account\">")+22);$temp=substr($temp,0,strpos($temp,"</span>"));}
//else {echo $ex[$d]."\n\n";echo unpack('C*',$ex[$d]);exit;}
$account=$temp;
     if (strpos($ex[$d],"<a class=\"account\"")>0 && strpos($ex[$d],"<span class=\"account\">")>0 && strpos($ex[$d],"<a class=\"account\"")>strpos($ex[$d],"<span class=\"account\">")) {$temp=substr($ex[$d],strpos($ex[$d],"<span class=\"account\">")+22);$temp=substr($temp,strpos($temp,"</span>")+7);$temp=substr($temp,0,strpos($temp,"<a "));$errortrack=1;}
else if (strpos($ex[$d],"<a class=\"account\"")>0 && strpos($ex[$d],"<span class=\"account\">")>0 && strpos($ex[$d],"<a class=\"account\"")<strpos($ex[$d],"<span class=\"account\">")) {$temp=substr($ex[$d],strpos($ex[$d],"<a class=\"account\"")+19);$temp=substr($temp,strpos($temp,">")+1);$temp=substr($temp,strpos($temp,"</a>")+4);$temp=substr($temp,0,strpos($temp,"<a "));;$errortrack=2;}
else if (strpos($ex[$d],"<span class=\"account\">")>0) {$temp=substr($ex[$d],strpos($ex[$d],"<span class=\"account\">")+22);$temp=substr($temp,strpos($temp,"</span>")+7);$temp=substr($temp,0,strpos($temp,"<a "));;$errortrack=3;}
else if (strpos($ex[$d],"<a class=\"account\"")>0) {$temp=substr($ex[$d],strpos($ex[$d],"<a class=\"account\"")+19);$temp=substr($temp,strpos($temp,">")+1);$temp=substr($temp,strpos($temp,"</a>")+4);$temp=substr($temp,0,strpos($temp,"<a "));;$errortrack=4;}
else if (strpos($ex[$d],"update_proposal_votes")>0) {$temp="update_proposal_votes";$errortrack=5;$account=$beneficiary;}
//else {echo "error:".$ex[$d];exit;}

$action=trim($temp);
$reward=0;
$rewardticker="SP";
$memo="";

$oktodo=true;
$oktodo2=true;
$user2="";
$link="";
$id="";
$json="";
$weight="";
$reward2=0;
$rewardticker2="SP";
if ($action==="" && strpos($ex[$d],"with delegation")>0) {$action="with delegation";}
if ($beneficiary!==$account && startsWith($action,"<code") && strpos($ex[$d],"transfer ")>0)  {$action=substr($ex[$d],strpos($ex[$d],"transfer"));}
if ($action==="") {$action="buggy";}
if (startsWith($action, "curation reward")) {
$temp=substr($action,strpos($action,":")+2);
$action=substr($action,0,strpos($action,":"));
$temp=substr($temp,0,strpos($temp,"for"));
$reward=trim(substr($temp,0,strpos($temp," ")));
$rewardticker=trim(substr($temp,strpos($temp," ")));
}
else if (startsWith($action, "custom json")) {
$temp=substr($action,strpos($action,"<")+2);
$action=substr($action,0,strpos($action,"<"));
$tempid=substr($temp,strpos($temp,"<samp>id<"));
$tempjson=substr($temp,strpos($temp,"<samp>json<"));
$tempid=substr($tempid,strpos($tempid,"<td>")+4);
$tempjson=substr($tempjson,strpos($tempjson,"<td>")+4);
$id=substr($tempid,0,strpos($tempid,"</td>"));
$json=substr($tempjson,0,strpos($tempjson,"</td>"));
$oktodo=false;
$user2=$id;
$link=$json;
}
else if (startsWith($action, "update account data")) {
$temp=$ex[$d];
//$action=substr($action,0,strpos($action,"<"));
$tempid=substr($temp,strpos($temp,"<samp>memo_key<"));
$tempjson=substr($temp,strpos($temp,"<samp>json_metadata<"));
$tempid=substr($tempid,strpos($tempid,"<td>")+4);
$tempjson=substr($tempjson,strpos($tempjson,"<td>")+4);
$id=substr($tempid,0,strpos($tempid,"</td>"));
$json=substr($tempjson,0,strpos($tempjson,"</td>"));
$oktodo=false;
$user2=$id;
$link=$json;
}
else if (startsWith($action, "witness_set_properties")) {
$temp=substr($action,strpos($action,"<")+2);
$action=substr($action,0,strpos($action,"<"));
$tempid=substr($temp,strpos($temp,"key"));
$tempjson=substr($temp,strpos($temp,"sbd_exchange_rate"));
$tempid=substr($tempid,strpos($tempid,"<td>")+4);
$tempjson=substr($tempjson,strpos($tempjson,"<td>")+4);
$id=substr($tempid,0,strpos($tempid,"</td>"));
$json=substr($tempjson,0,strpos($tempjson,"</td>"));
$oktodo=false;
$user2=$id;
$link=$json;
}

else if (startsWith($action, "approve witness")) {
$temp=substr($action,strpos($action,"@")+1);
$action=substr($action,0,strpos($action,"<"));
$id=substr($temp,0,strpos($temp,"\""));
$oktodo=false;
$user2=$id;
}
else if (startsWith($action, "with delegation")) {
$temp=substr($ex[$d],strpos($ex[$d],"<samp>delegation<"));
$temp=substr($temp,strpos($temp,"<td><i>")+7);
$temp=substr($temp,0,strpos($temp,"</i>"));
//$action=substr($action,0,strpos($action,"<"));
$action="create account";
$reward=substr($temp,0,strpos($temp," "));
$rewardticker=substr($temp,strpos($temp," ")+1);
$temp=substr($ex[$d],strpos($ex[$d],"@")+1);
$id=substr($temp,0,strpos($temp,"\""));
if ($beneficiary!==$account) {$id=$beneficiary;}
$oktodo=false;
$oktodo2=false;
$user2=$id;
}
else if (startsWith($action, "create account")) {
$temp=substr($ex[$d],strpos($ex[$d],"<samp>delegation<"));
$temp=substr($temp,strpos($temp,"<td><i>")+7);
$temp=substr($temp,0,strpos($temp,"</i>"));
//$action=substr($action,0,strpos($action,"<"));
$action="create account";
$reward=substr($temp,0,strpos($temp," "));
$rewardticker=substr($temp,strpos($temp," ")+1);
$temp=substr($ex[$d],strpos($ex[$d],"@")+1);
$id=substr($temp,0,strpos($temp,"\""));
if ($beneficiary!==$account) {$id=$beneficiary;}
$oktodo=false;
$oktodo2=false;
$user2=$id;
}

else if (startsWith($action, "unapprove witness")) {
$temp=substr($action,strpos($action,"@")+1);
$action=substr($action,0,strpos($action,"<"));
$id=substr($temp,0,strpos($temp,"\""));
$oktodo=false;
$user2=$id;
}

else if (startsWith($action, "follow")) {
$temp=substr($action,strpos($action,"@")+1);
//$action=substr($action,0,strpos($action,"<"));
$id=substr($temp,0,strpos($temp,"\""));
if ($account!==$beneficiary) {$id=$account;$account=$beneficiary;}
$oktodo=false;
$user2=$id;
}
else if (startsWith($action, "unfollow")) {
$temp=substr($action,strpos($action,"@")+1);
//$action=substr($action,0,strpos($action,"<"));
$id=substr($temp,0,strpos($temp,"\""));
if ($account!==$beneficiary) {$id=$account;$account=$beneficiary;}
$oktodo=false;
$user2=$id;
}

else if (startsWith($action, "producer reward")) {
$temp=substr($action,strpos($action,":")+3);
$action=substr($action,0,strpos($action,":"));
$temp=substr($temp,0,strpos($temp,"<")-1);
$reward=substr($temp,0,strpos($temp," "));
$rewardticker=substr($temp,strpos($temp," ")+1);
$rewardticker=substr($rewardticker,0,strlen($rewardticker)-4);
$oktodo=false;
}
else if (startsWith($action, "author reward")) {
$temp=substr($action,strpos($action,":")+3);
$action=substr($action,0,strpos($action,":"));
$temp=substr($temp,0,strpos($temp," for"));
if (strpos($temp," and ")>0) {
$temp2=substr($temp,strpos($temp," and")+5);
$temp=substr($temp,0,strpos($temp," and"));
$reward=trim(substr($temp,0,strpos($temp," ")));
$rewardticker=trim(substr($temp,strpos($temp," ")+1));
$reward2=trim(substr($temp2,0,strpos($temp2," ")));
$rewardticker2=trim(substr($temp2,strpos($temp2," ")+1));
}
else {
$reward=trim(substr($temp,0,strpos($temp," ")));
$rewardticker=trim(substr($temp,strpos($temp," ")+1));
}
//$oktodo=false;
$oktodo2=false;
}
else if (startsWith($action, "un-vote")) {
//$oktodo=false;
//$oktodo2=false;
}
else if (startsWith($action, "comment benefactor reward")) {
$temp=substr($action,strpos($action,":")+3);
$action=substr($action,0,strpos($action,":"));
$temp=substr($temp,0,strpos($temp," for"));
if (strpos($temp," and ")>0) {
$temp2=substr($temp,strpos($temp," and")+4);
$temp=substr($temp,0,strpos($temp," and"));
$reward=trim(substr($temp,0,strpos($temp," ")));
$rewardticker=trim(substr($temp,strpos($temp," ")+1));
$reward2=trim(substr($temp2,0,strpos($temp2," ")));
$rewardticker2=trim(substr($temp2,strpos($temp2," ")+1));
}
else {
$reward=trim(substr($temp,0,strpos($temp," ")));
$rewardticker=trim(substr($temp,strpos($temp," ")+1));
}
$oktodo=false;
$oktodo2=false;
}
else if (startsWith($action, "withdraw ")) {
$temp=substr($action,strpos($action," ")+1);
//echo "\n<br>1temp:".$temp;
$action=substr($action,0,strpos($action," "));
if (strpos($temp," to")>0) {$temp=substr($temp,0,strpos($temp," to"));}
//echo "\n<br>3temp:".$temp;
$temp2=substr($temp,strpos($temp," as")+4);
//echo "\n<br>1temp2:".$temp;
$temp=substr($temp,0,strpos($temp," as"));
//echo "\n<br>4temp:".$temp;
$reward=trim(substr($temp,0,strpos($temp," ")));
$rewardticker=trim(substr($temp,strpos($temp," ")+1));
$reward2=trim(substr($temp2,0,strpos($temp2," ")));
$rewardticker2=trim(substr($temp2,strpos($temp2," ")+1));
if ($beneficiary!==$account) {$user2=$beneficiary;}
$oktodo=false;
$oktodo2=false;
}
else if (startsWith($action, "claim reward")) {
$temp=substr($action,strpos($action,":")+3);
$action=substr($action,0,strpos($action,":"));
$temp=substr($temp,0,strpos($temp,"<")-1);
$reward=substr($temp,0,strpos($temp," "));
$rewardticker=substr($temp,strpos($temp," ")+1);
$rewardticker=substr($rewardticker,0,strlen($rewardticker)-4);
$oktodo=false;
}
else if (startsWith($action, "undelegate to")) {
$temp=substr($action,strpos($action,":")+3);
$action=substr($action,0,strpos($action," "));
$temp=substr($ex[$d],strpos($ex[$d],"@")+1);
$user2=substr($temp,0,strpos($temp,"\""));
if ($account!==$beneficiary) {$user2=$beneficiary;}
$oktodo=false;
$oktodo2=false;
}

else if (startsWith($action, "transfer")) {
$temp=substr($action,strpos($action," ")+1);
$action=substr($action,0,strpos($action," "));
$temp=substr($temp,0,strpos($temp," to "));
//echo "\n<br>1temp:".$temp;
$reward=substr($temp,0,strpos($temp," "));
//echo "\n<br>2temp:".$reward;
$rewardticker=trim(substr($temp,strpos($temp," ")));
//echo "\n<br>3temp:".$rewardticker;
$temp=substr($ex[$d],strpos($ex[$d],"<code>")+6);
$memo=substr($temp,0,strpos($temp,"</code>"));
$temp=substr($ex[$d],strpos($ex[$d],"@")+1);
$user2=substr($temp,0,strpos($temp,"\""));
if ($account!==$beneficiary) {$user2=$beneficiary;}
$oktodo=false;
$oktodo2=false;
}
else if (startsWith($action, "delegate")) {
$temp=substr($action,strpos($action," ")+1);
$action=substr($action,0,strpos($action," "));
$temp=substr($temp,0,strpos($temp," to "));
//echo "\n<br>1temp:".$temp;
$reward=substr($temp,0,strpos($temp," "));
//echo "\n<br>2temp:".$reward;
$rewardticker=trim(substr($temp,strpos($temp," ")));
//echo "\n<br>3temp:".$rewardticker;
$temp=substr($ex[$d],strpos($ex[$d],"<code>")+6);
$memo=substr($temp,0,strpos($temp,"</code>"));
$temp=substr($ex[$d],strpos($ex[$d],"@")+1);
$user2=substr($temp,0,strpos($temp,"\""));
if ($account!==$beneficiary) {$user2=$beneficiary;}
$oktodo=false;
$oktodo2=false;
}

else if (startsWith($action, "paid")) {
$temp=substr($action,strpos($action," ")+1);
$action=substr($action,0,strpos($action," "));
$temp=substr($temp,0,strpos($temp," for "));
//echo "\n<br>1temp:".$temp;
$reward=substr($temp,0,strpos($temp," "));
//echo "\n<br>2temp:".$reward;
$rewardticker=trim(substr($temp,strpos($temp," ")));
//echo "\n<br>3temp:".$rewardticker;
$temp=substr($ex[$d],strpos($ex[$d],"<code>")+6);
$memo=substr($temp,0,strpos($temp,"</code>"));
$temp=substr($ex[$d],strpos($ex[$d],"@")+1);
$user2=substr($temp,0,strpos($temp,"\""));
$oktodo=false;
$oktodo2=false;
}
else if (startsWith($action, "claimed a discounted account")) {
$oktodo=false;
$oktodo2=false;
}

else if (startsWith($action, "<code>Steem Monsters</code> revealed their cards for battle")) {
$memo=$action;
$action="custom_json";
$oktodo=false;
$oktodo2=false;
}
else if (startsWith($action, "<code>Steem Monsters</code> remove cards listed for sale on the market")) {
$memo=$action;
$action="custom_json";
$oktodo=false;
$oktodo2=false;
}

else if (startsWith($action, "<code>Steem Monsters</code> combine all cards")) {
$memo=$action;
$action="custom_json";
$oktodo=false;
$oktodo2=false;
}
else if (startsWith($action, "<code>Steem Monsters</code> started a daily quest")) {
$memo=$action;
$action="custom_json";
$oktodo=false;
$oktodo2=false;
}
else if (startsWith($action, "<code>Steem Monsters</code> requested a new quest")) {
$memo=$action;
$action="custom_json";
$oktodo=false;
$oktodo2=false;
}
else if (startsWith($action, "<code>Steem Monsters</code> claimed end of season reward")) {
$memo=$action;
$action="custom_json";
$oktodo=false;
$oktodo2=false;
}
else if (startsWith($action, "<code>Steem Monsters</code> claimed quest reward")) {
$memo=$action;
$action="custom_json";
$oktodo=false;
$oktodo2=false;
}
else if (startsWith($action, "<code>Steem Monsters</code> submitted a team")) {
$memo=$action;
$action="custom_json";
$oktodo=false;
$oktodo2=false;
}
else if (startsWith($action, "<code>Steem Monsters</code> entered")) {
$memo=$action;
$action="custom_json";
$oktodo=false;
$oktodo2=false;
}
else if (startsWith($action, "<code>Steem Monsters</code> gift cards to")) {
$memo=$action;
$action="custom_json";
$oktodo=false;
$oktodo2=false;
}
else if (startsWith($action, "<code>Steem Monsters</code> combine cards")) {
$memo=$action;
$action="custom_json";
$oktodo=false;
$oktodo2=false;
}
else if (startsWith($action, "wants")) {
$memo=substr($action,strpos($action," ")+1);
$action=substr($action,0,strpos($action," "));
$oktodo=false;
$oktodo2=false;
}
else if (startsWith($action, "comment options")) {
$memo=substr($action,strpos($action,":")+1);
$memo=substr($memo,0,strpos($memo,"<")+1);
$action=substr($action,0,strpos($action,":"));
$oktodo=false;
$oktodo2=false;
}

else if (startsWith($action, "create proposal")) {
$temp=substr($ex[$d],strpos($ex[$d],"<code>")+6);
$memo=substr($temp,0,strpos($temp,"</code>"));
$action="create proposal";
$temp=substr($ex[$d],strpos($ex[$d],"<samp>daily_pay<"));
$temp=substr($temp,strpos($temp,"<td><i>")+7);
$temp=substr($temp,0,strpos($temp,"</i>"));
$reward=substr($temp,0,strpos($temp," "));
$rewardticker=trim(substr($temp,strpos($temp," ")));
$account=$beneficiary;
$oktodo=false;
$oktodo2=false;
}

else if (startsWith($action, "as proxy")) {
$temp=substr($action,strpos($action," ")+1);
$action=substr($action,0,strpos($action," "));
$temp=substr($temp,0,strpos($temp," to "));
$reward=substr($action,0,strpos($temp," "));
$rewardticker=substr($temp,strpos($temp," "));
$temp=substr($ex[$d],strpos($ex[$d],"<code>")+6);
$memo=substr($temp,0,strpos($temp,"</code>"));
$action="set as proxy";
$oktodo=false;
if ($beneficiary!==$account) {$user2=$beneficiary;}
}
else if (startsWith($action, "set")) {
$temp=substr($action,strpos($action," ")+1);
$action=substr($action,0,strpos($action," "));
$temp=substr($temp,0,strpos($temp," to "));
$reward=substr($action,strpos($temp," "));
$rewardticker=substr($temp,strpos($temp," "));
$temp=substr($ex[$d],strpos($ex[$d],"<code>")+6);
$memo=substr($temp,0,strpos($temp,"</code>"));
$action="set as proxy";
$oktodo=false;
if ($beneficiary!==$account) {$user2=$beneficiary;}
}
else if ($action==="upvote" || $action==="replied to" || $action==="reblogged" || startsWith($action, "downvote") || startsWith($action, "update_proposal_votes") || startsWith($action, "authored a post")) {}
else {
echo "\n<br>action:".$action." ".$i." ".$tx." ".$errortrack;
$oktodo=false;
$oktodo2=false;
}

if ($oktodo) {
$temp=substr($ex[$d],strpos($ex[$d],"<a href=")+10);
$temp=substr($temp,0,strpos($temp,">")-1);
$link=trim($temp);
$user2=substr($link,1,strpos($link,"/")-1);
$link=substr($link,strpos($link,"/"));}
if ($oktodo2) {
$temp=substr($ex[$d],strpos($ex[$d],"</a>"));
$temp=substr($temp,strpos($temp,"(")+1);
$temp=substr($temp,0,strpos($temp,")"));
$weight=trim($temp);
}
$time="";
if (strpos($ex[$d],"<time class")>0) {$temp=substr($ex[$d],strpos($ex[$d],"<time class"));
$temp=substr($temp,strpos($temp,">"));
$time=substr($temp,1,strpos($temp,"+")-1);}
else if (strpos($ex[$d],"<time title")>0) {$temp=substr($ex[$d],strpos($ex[$d],"<time title")+13);
$time=substr($temp,0,strpos($temp,">")-1);

}
else {$temp=substr($ex[$d],strpos($ex[$d],"<time"));}

if (strlen($reward)==0) {$reward=0;$rewardticker="sp";}
if (strlen($reward2)==0) {$reward2=0;$rewardticker2="sp";}

//if (startsWith($action, "author reward") || startsWith($action, "withdraw") || startsWith($action, "comment benefactor rewards") || startsWith($action, "downvote") || startsWith($action, "update_proposal_votes") || startsWith($action, "create proposal") || startsWith($action, "un-vote")) 
{
//echo "\n<br>new data";
//echo "\n<br>".$tx;
//echo "\n<br>".$block;
//echo "\n<br>".$account;
//echo "\n<br>".$action;
//echo "\n<br>".$reward." ".$rewardticker;
//echo "\n<br>".$reward2." ".$rewardticker2;

//echo "\n<br>".$memo;
//echo "\n<br>".$link;
//echo "\n<br>".$user2;
//echo "\n<br>".$weight;
//date_default_timezone_set('UTC');
//echo "\n<br>".$time;
//echo "\n<br>".strtotime($time);
$time=strtotime($time);
if (strlen($link)>256) {$link=substr($link,0,256);}
if (strlen($memo)>256) {$memo=substr($memo,0,256);}
if (strlen($action)>256) {$action=substr($action,0,256);}


}

if (startsWith($action, "transfer")) {
if ($sql = $conn->prepare( "INSERT INTO `node` (`ts`,`tx`,`block`,`account`,`user2`,`memo`,`crypto`,`symbol`,`link`,`weight`,`act`,`ty`) VALUES (?, ? , ?, ?,?,?,?,?,?,?,?,1)")) {
$sql->bind_param("isisssdssss",$time,$tx,$block,$account,$user2,$memo,$reward,$rewardticker,$link,$weight,$action);
if(!$sql->execute()){
//trigger_error("there was an error....".$conn->error, E_USER_WARNING);
}}
else {echo "\nsql prepare returned false";}
echo "\ntransferred";
}

}//end for
$i++;
//exit;
echo $i;
if (isset($argv[1])) {if ($argv[1]>0 && $argv[1]<$i) {$oktorun=false;}sleep(30);}
}//end main while
?>
