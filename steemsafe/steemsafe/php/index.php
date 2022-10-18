<?php

$servername="localhost";
$username="testing";
$password="nimda";
$conn=new mysqli($servername,$username,$password,"archive");

if ($conn->connect_error) {die("connection to the database failed:". $conn->connect_error);}

$search="%%";
if (isset($_GET["search"])) {$search="%".$_GET["search"]."%";}
echo $search;
echo "<form method=GET action=index.php>";
echo "<input type=textbox name=search id=search>
<input type=submit value=\"click to search\"></input>
</form>";

$sql = $conn->prepare( "select lookup.fileid,filepath,filetime,added from lookup WHERE added!=\"0\" and `filepath` like ? order by filetime DESC");
$sql->bind_param("s",$search);
if(!$sql->execute()){trigger_error("there was an error....".$conn->error, E_USER_WARNING);}
$result=$sql->get_result();
echo "<table><tr><th>url</th><th>Time</th><th>Link</th>";
    while ($row = $result->fetch_assoc()) {echo "<tr><td>".$row["filepath"]."</td><td>".substr(gmdate('r',$row["filetime"]),4);
echo "</td><td><a href=loadfile.php?fileid=".$row["fileid"]."&index=0>".$row["fileid"]."</a></td><td></a></td></tr>";}
$sql->close();
echo "</table>";



?>