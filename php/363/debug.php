<?php
$filename="0.mp4";
$fp=fopen($filename,'r');
$myfile=fread($fp,filesize($filename));
fclose($fp);
$myfile=str_replace("\n","\r",$myfile);
$text= substr($myfile,strpos($myfile,"Norman")-1,10);
echo $text."<br>";
var_dump(unpack('C*',$text));
echo "<br>";
?>
