<?php
/*
* Blarchive - Main Index For PHP
*/

session_start();


// Redirect to HTTPS
/*
if (!(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || 
   $_SERVER['HTTPS'] == 1) ||  
   isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&   
   $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'))
{
   $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
   header('HTTP/1.1 301 Moved Permanently');
   header('Location: ' . $redirect);
   exit();
}
else 
{*/
   // Import Security Config Variables
   require("confc.php");
   // Import Cypher Encoding Stuff
   require("encode.php");
   error_log ($password);
   // Connect to MySQL
   try 
   {
      $conn = new mysqli($servername, $username, $password, "archive");
   }
   catch (Exception $e) 
   {
      echo "Database is unreachable.";
      die;
   }

   // Get Page Input Parameter: 'start'
   $start = $_GET['start'] or 0;

   // Open Blarchive Config File
   $filename = "archive.conf";
   $fp = fopen($filename, 'r');
   $myfile = fread($fp, filesize($filename));
   fclose($fp);
   $myfile=str_replace("\n","\r",$myfile);

   // Import Blarchive Config Variables
   
   // WHY DOES 'PRICE' ACTUALLY GET 'RESULTS PER PAGE' AND NOT 'PRICE' FROM CONFIG FILE?
   $price = substr($myfile, strpos($myfile, "\r#results_per_page\r") + 20);

   $appname = substr($myfile, strpos($myfile, "\r#appname\r") + 11);
   $appname = substr($appname, 0, strpos($appname, "\r"));
   $server = substr($myfile, strpos($myfile, "\r#http\r") + 8);
   $server = substr($server, 0, strpos($server, "\r"));
   $chain = substr($myfile, strpos($myfile, "\r#chain\r") + 9);
   $chain = strToUpper(substr($chain, 0, strpos($chain, "\r")));

   $ss1 = "https://hive.blog";
   $ss2 = "https://hivesigner.com";

   // Output Messages
   echo "Project is in testing and development<br>You shouldn't use this yet. <br> The services  may not be running at this time, or we may kill them in testing";

   echo "<br>Not everything is stored to the chain.<br>Some langauge packs or other pages may be skipped<br>Some pages are rebuilt<br>Prices are not final<br>You shouldn't use this yet.";

   // Display Login Menu or Login Link
   if (isset($_SESSION["user"])) 
   {
      echo "<br>Welcome:".$_SESSION["user"];
      echo "<a href=\"dourl4.php\">My Page<br></a>";
      echo "<a href=\"logout.php\">logout</a>";
   }
   else 
   {
      // replace this with keychain login interface
      echo "<a href='" . $ss2 . "/oauth2/authorize?client_id=" . $appname . "&redirect_uri=https://" . $server . "/php/dourl4.php&scope=vote,comment,custom_json,comment_option'>log in here</a>";
   }

   // IS THIS NEEDED IN THIS FORMAT?
   $l = substr($price, 0, strpos($price, "\r"));
   $m = $l + 1;

   // Test local MySQL connection
   if ($conn->connect_error) 
   {
      die("connection to the database failed:" . $conn->connect_error);
   }

   $search = "%%";

   // Look for Page Input Variable 'Search'
   if (isset($_GET["search"]))
   {
      $search = "%" . $_GET["search"] . "%";
   }

   // Render Search Input
   echo "<form method=GET action=index.php>";
   echo "<input type=textbox name=search id=search>
   <input type=submit value=\"click to search\"></input>
   </form>";

   $c=0;

   // Retrieve List Of Files From Local Database, Based On Input Variables
   $sql = $conn->prepare( "SELECT lookup.fileid,filepath,filetime,added FROM lookup WHERE added!=\"0\" AND `filepath` LIKE ? ORDER BY filetime DESC LIMIT ?,?");

   $sql->bind_param("sii", $search,$start,$m);

   if(!$sql->execute()){trigger_error("There was a database error... " . $conn->error, E_USER_WARNING);}
   $result = $sql->get_result();

   // Render list of files in table
   echo "<table><tr><th>url</th><th>Time</th><th>block chain post</th><th>short link</th>";

   while ($row = $result->fetch_assoc()) 
   {
      $c++;
      if ($c <= $l)
      {
         echo "<tr><td>".$row["filepath"]."</td><td>".substr(gmdate('r',$row["filetime"]),4,-9);
         echo "</td><td><a href=".$ss1."/@".$appname."/".$row["fileid"]."-compressed-encoded-binary-data>".$row["fileid"]."</a></td><td><a href=/".encode($row["fileid"]).">".$_SERVER['SERVER_NAME']."/".encode($row["fileid"])."</a></td></tr>";
      }
   }

   $sql->close();

   echo "<tr>";

   if ($start > 0) 
   {
      echo "<td><a href=".parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)."?search=".$_GET['search']."&start=0>&#9198; </a></td><td><a href=".parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)."?search=".$_GET['search']."&start=".($start-$l).">&#9194; </a></td>";
   } 
   else 
   {
      echo "<td></td><td></td><td></td>";
   }

   if ($c>$l) 
   {
      echo "<td><a href=".parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)."?search=".$_GET['search']."&start=".($start+$l).">&#9193;</a></td>";
   }

   echo "</td></table>";
   //echo parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
/*}*/
?>
