<?php
require('confc.php');
try {
$conn=new mysqli($servername,$username,$password,"archive");
}
catch (Exception $e) {
echo "database connectivity issue.  Try again Later.";die;
}
if ($conn->connect_error) {die("connection to the database failed:". $conn->connect_error);}


//echo "hello world<br>";
//$a="l";
$c=0;
$a=$_SERVER['REQUEST_URI'];
$url="/php/index.php";
if (strlen($a)<6) {
//echo "made it 1";
if (strrpos($a,"/")!==FALSE && strrpos($a,"/")==0 ) {
//echo "made it 2";
if (strpos(strToLower($a),"a")===FALSE && strpos(strToLower($a),"e")===FALSE && strpos(strToLower($a),"h")===FALSE && strpos(strToLower($a),"i")===FALSE  && strpos(strToLower($a),"o")===FALSE && strpos(strToLower($a),"u")===FALSE ) {
//echo "made it 3";
//echo "<br>".$a;

$a=substr($a,1);
$b['0']=0;
$b['1']=1;
$b['2']=2;
$b['3']=3;
$b['4']=4;
$b['5']=5;
$b['6']=6;
$b['7']=7;
$b['8']=8;
$b['9']=9;
$b['b']=10;
$b['c']=11;
$b['d']=12;
$b['f']=13;
$b['g']=14;
$b['j']=15;
$b['k']=16;
$b['l']=17;
$b['m']=18;
$b['n']=19;
$b['p']=20;
$b['q']=21;
$b['r']=22;
$b['s']=23;
$b['t']=24;
$b['v']=25;
$b['w']=26;
$b['x']=27;
$b['y']=28;
$b['z']=29;
$b['B']=30;
$b['C']=31;
$b['D']=32;
$b['F']=33;
$b['G']=34;
$b['J']=35;
$b['K']=36;
$b['L']=37;
$b['M']=38;
$b['N']=39;
$b['P']=40;
$b['Q']=41;
$b['R']=42;
$b['S']=43;
$b['T']=44;
$b['V']=45;
$b['W']=46;
$b['X']=47;
$b['Y']=48;
$b['Z']=49;
$b['-']=50;
$b['.']=51;
$b['_']=52;
$b['~']=53;
$d=count($b);
//echo "<br>strlen:".strlen($a)." ".$a;
if (strlen($a)==1) {$c=$b[$a];}
if (strlen($a)==2) {$c=$d*$b[substr($a,0,1)]+$b[substr($a,1,2)];}
if (strlen($a)==3) {$c=$d*$d*$b[substr($a,0,1)]+$d*$b[substr($a,1,2)]+$b[substr($a,2,3)];}
if (strlen($a)==4) {$c=$d*$d*$d*$b[substr($a,0,1)]+$d*$d*$b[substr($a,1,2)]+$d*$b[substr($a,2,3)]+$b[substr($a,3,4)];}
if (strlen($a)==5) {$c=$d*$d*$d*$d*$b[substr($a,0,1)]+$d*$d*$d*$b[substr($a,1,2)]+$d*$d*$b[substr($a,2,3)]+$d*$b[substr($a,3,4)]+$b[substr($a,3,4)];}

}}}

$aa="";
if ($c!=0) {
$sql = $conn->prepare("select added from lookup where fileid=?");
$sql->bind_param("i",$c);
if(!$sql->execute()){trigger_error("there was an error....".$conn->error, E_USER_WARNING);}
$result=$sql->get_result();
$row = $result->fetch_assoc(); $aa=$row["added"];
$sql->close();
if ($aa>0){
echo '<head> 
  <meta http-equiv="Refresh" content="0; URL=https://blarchive.net/php/loadfile.php?fileid='.$c.'&index=0">
</head>';}
else {
echo '<head>
  <meta http-equiv="Refresh" content="0; URL=https://blarchive.net/php/preview.php?fileid='.$c.'&index=0">
</head>';}
}

else {echo '<head>
  <meta http-equiv="Refresh" content="0; URL=https://blarchive.net/php/index.php">
</head>';}

?>
