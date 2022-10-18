<?php

/*
* Blarchive - This File Runs When 'My Page' is Clicked on the Public Index Page.
*/

//notes

//php.ini memory_limit 4G
//php.ini max_execution_time=1800

// Input Variables
//
// blarchivemode
// blarchivejsmode
// accesstoken
// user
// username
// url


// Configure PHP Session

session_set_cookie_params(604800,"/");
session_start();
mysqli_report(MYSQLI_REPORT_STRICT);

// Import PHP files

require('encode.php');
require('webtemplate.php');
require('bittrex.php');
require("confc.php");

// Setup variables

$iswp = false;
$normal = true;
$warn = 0;
$def = -1;
$nn = "";
$mode = 0;
$jsmode = false;
$time = time() - 60*60*24;
$badwords = array();
$debug = true;
$video = "";
$videocount = 0;
$index = 0;
$fileid = "y";
$demo = false;
$demosize = 0;

// Open Blarchive Config File

$filename = "archive.conf";
$fp = fopen($filename, 'r');
$myfile = fread($fp, filesize($filename));
fclose($fp);
$myfile = str_replace("\n", "\r", $myfile);

// Import Blarchive Config Variables

$price = substr($myfile, strpos($myfile, "\r#price\r") + 9);
$price = substr($price, 0, strpos($price, "\r"));
//echo $price;
$appname = substr($myfile, strpos($myfile, "\r#appname\r") + 11);
$appname = substr($appname, 0, strpos($appname, "\r"));
//echo $appname;
$beneficiary = substr($myfile, strpos($myfile, "\r#beneficiary\r") + 15);
$beneficiary = substr($beneficiary, 0, strpos($beneficiary, "\r"));
//echo $beneficiary;
$cookies = "";
$dailylimit = substr($myfile, strpos($myfile, "\r#dailylimit\r") + 14);
$dailylimit = substr($dailylimit, 0, strpos($dailylimit, "\r"));

$chain = substr($myfile, strpos($myfile, "\r#chain\r") + 9);
$chain = strToUpper(substr($chain, 0, strpos($chain, "\r")));

$ss1 = "https://hivesigner.com";
$ss2 = "https://hivesigner.com";

// Get Page Variables

if (ISSET($_GET["blarchivemode"])) 
{
     $mode = $_GET["blarchivemode"];
} 

if (ISSET($_GET["blarchivejsmode"])) 
{
     $jsmode = $_GET["blarchivejsmode"];
}

// Connect to MySQL

try {
     $conn = new mysqli($servername, $username, $password, "archive");
} 
catch (Exception $e) 
{
     echo "dbconnectivity issue. Try again later";
     die;
}

// If user is logged in
if (ISSET($_SESSION["user"])) 
{
     // lookup user in database
     $tsql = "SELECT count(`user`) AS a FROM lookup WHERE `user`=? AND `filetime`>?";
     $sql = $conn->prepare($tsql);
     $sql->bind_param("si", $_SESSION["user"], $time);
     if($sql->execute())
     {
          $result = $sql->get_result();
          while ($row = $result->fetch_assoc()) 
          {
            $nn = $row["a"];
          }
     }
     else 
     {
          echo "Error: " . $tsql . "" . mysqli_error($conn);
     }

     // Check if user has exceeded their daily limit of archives
     if ($nn > $dailylimit)  
     {
          echo "exceeded daily limit";
     }
     else 
     {
          echo "<br>";
          echo ($dailylimit - $nn);
          echo " more stores remaining today<br>";
     }
}

// identify if app is in demo mode
$demo2 = substr($myfile, strpos($myfile, "\r#demo\r") + 8);
$demo2 = substr($demo2, 0, strpos($demo2, "\r"));
if (strToLower($demo2) === "yes")
{
     $demo = true;
}

//echo "demo2:".$demo2."demo".($demo?"true":"false");

// Set server root path
$serverpath = $_SERVER['DOCUMENT_ROOT'] . "/php/";

// Get protocol, port and other data from archive.conf
$siteDomainName = substr($myfile, strpos($myfile, "\r#http\r") + 8);
$siteDomainName = substr($siteDomainName, 0, strpos($siteDomainName, "\r"));
//echo $siteDomainName;

$myport = substr($myfile, strpos($myfile, "\r#port\r") + 8);
$myport = substr($myport, 0, strpos($myport, "\r"));
//echo $myport;

$bot = substr($myfile, strpos($myfile, "\r#posting_bot\r") + 15);
$bot = substr($bot, 0, strpos($bot, "\r"));
//echo $myport;

if ($myport == 443)
{
     $siteDomainUrl = "https://" . $siteDomainName;
}
else
{
     $siteDomainUrl = "https://" . $siteDomainName . ":" . $myport;
}

// build paths...
$siteDomainUrlEncoded = "https:\/\/" . $siteDomainName . ":" . $myport;
$loadFilePath = "/php/loadfile.php";
$loadFilePathEncoded = "\/php\/loadfile.php";
$loadFilePathFull = $siteDomainUrl . $loadFilePath;
$loadFilePathFullEncoded = $siteDomainUrlEncoded . $loadFilePathEncoded;

// all use of this function has been commented out in the code
function str_ireplace2($a, $b, $c) 
{
     return str_ireplace($a, $a. "�", $c);
}

// this function is only used once in this code
function debugmode($a, $b, $c) 
{
     global $debug;
     if ($debug)
     {
          echo "<br>" . $a . ":" . $b . ":" . $c;
     }
}

// checks if $a starts with $b
function startsWith($a, $b) 
{
     return (substr($a, 0, strlen($b)) === $b);
}

// checks if $a ends with $b
function endsWith($a, $b) 
{
     if (strlen($b) == 0) 
     {
          return true;
     }
     return (substr($a, -strlen($b)) === $b);
}

// identify MIME type from file extension - returns URL parameter (Why does this function not include the same extensions as getMimeTypeFromExt?)
// there are better functions to handle MIME types in PHP
function getMimeTypeAsHTMLParam($a) 
{
     $ext = $a;

     if (strpos($ext, "?") > 0) 
     {
          $ext = substr($ext, 0, strpos($ext, "?"));
     }

     // only use the characters following the final period character
     if (strpos($ext, ".") > 0) 
     {
          $ext = substr($ext, 0, strpos($ext, "."));
     }

     // return param for images
     if ((startsWith($ext, "png"))
      || (startsWith($ext, "gif")) 
      || (startsWith($ext, "jpg")) 
      || (startsWith($ext, "png")) 
      || (startsWith($ext, "svg")))
     {
          return "&type=img";
     }

     // return param for audio
     if ((startsWith($ext, "mp3")) 
     || (startsWith($ext, "avi"))
     || (startsWith($ext, "wav")))
     {
          return "&type=snd";
     }

     // return param for video
     if ((startsWith($ext, "mp4")) 
     || (startsWith($ext, "flv")) 
     || (startsWith($ext, "mvp"))
     || (startsWith($ext, "avi")) 
     || (startsWith($ext, "swf"))) 
     {
          return "&type=vid";
     }

     // return param for applet
     if (startsWith($ext, "applet"))
     {
          return "&type=java";
     }

     // return param for text
     if (startsWith($ext, "vtt"))
     {
          return "&type=text";
     }

     return "";
}

// identify MIME type from file extension
function getMimeTypeFromExt($a) 
{
     $ext = $a;
     if (strpos($ext, "?") > 0) 
     {
          $ext = substr($ext, 0, strpos($ext, "?"));
     }

     if (strpos($ext, ".") > 0) 
     {
          $ext = substr($ext, strrpos($ext, "."));
     }

     if ((startsWith($ext, "png")) 
     || (startsWith($ext,"gif")) 
     || (startsWith($ext,"jpg"))
     || (startsWith($ext,"png"))
     || (startsWith($ext,"svg"))
     || (startsWith($ext,"ico"))
     || (startsWith($ext,"cur"))) 
     {
          return "img";
     }

     if ((startsWith($ext, "mp3"))
     || (startsWith($ext, "avi")) 
     || (startsWith($ext, "wav")) 
     || (startsWith($ext, "mid")) 
     || (startsWith($ext, "midi"))) 
     {
          return "snd";
     }

     if ((startsWith($ext, "mp4"))
     || (startsWith($ext, "flv"))
     || (startsWith($ext, "mvp"))
     || (startsWith($ext, "avi"))
     || (startsWith($ext, "swf"))
     || (startsWith($ext, "mkv"))) 
     {
          return "vid";
     }

     if (startsWith($ext, "applet")) 
     {
          return "java";
     }

     if ((startsWith($ext, "vtt")) 
     || (startsWith($ext, "js")) 
     || (startsWith($ext, "css")) 
     || (startsWith($ext, "xml"))) 
     {
          return "text";
     }

     return "html";
}

// get MIME type from HTML type parameter
function getMimeTypeFromHtmlParam($a) 
{
     //echo "<br>getMimeTypeFromHtmlParam".$a;
     if (strpos($a, " type=") !== FALSE) 
     {
          $b = substr($a, strpos($a, " type="));
          $b = substr($b, 0, 10);
          if (strpos($b, "image") !== FALSE) 
          {
               return "img";
          }
          if (strpos($b, "audio") !== FALSE) 
          {
               return "snd";
          }
          if (strpos($b, "vid") !== FALSE) 
          {
               return "vid";
          }
          if (strpos($b, "text") !== FALSE) 
          {
               return "text";
          }
          return "vid";
     }
     else 
     {
          return "";
     }
}

// JSON encoder?
function encodeJSON($a) 
{
     echo "\n<br>encodeJSON:made it" . $a;

     // split input by curly bracket
     $b = explode("{", $a);
     $z = count($b);
     $m = "";

     echo "\n<br>" . $z;

     for($i = 0; $i < $z; $i++)
     {
          echo "\n<br>" . $m . " " . strlen($b[$i]);

          // split each element by commas
          $c = explode(",", $b[$i]);
          $y = count($c);
          echo "\n<br>y:" . $y;

          if ($y > 1)
          {
               // if more than one element is found, then proceed to break apart element's sub items and process them.
               $m .= "{" . encodeJSONStage2($c);
          }
          else 
          {
               // if only one element is present.
               // split element by colon
               $d = explode(":", $b[$i]);
               $x = count($d);
               echo "\n<br>x:" . $x;

               if ($x > 1) 
               {
                    // if more than one sub element is found in $d
                    if (startsWith($d[1], " ")) 
                    {
                         // remove empty space at start
                         $d[1] = substr($d[1], 1);
                    }

                    while ((startsWith($d[1], "\"") && substr_count($d[1], "\"") != 2) && $i < $z) 
                    {
                         $i++;
                         $d[1] .= "{" . $b[$i];
                    }

                    $m .= "{" . encodeJSONStage3($d);
               }
               else 
               {
                    // if only one sub element is found in $d
                    if ($i != 0 || ($i == 0 && !startsWith($a, "{")))
                    {
                         $m .= "{" . $b[$i];
                    }
               }
          }
     }
     return $m;
}

// split array elements on ':' symbol and reconstruct them after adjusting quote symbols 
function encodeJSONStage2($a) 
{
     echo "\n<br>encodeJSONStage2:made it";
     $z = count($a);
     $m = "";

     for($i = 0; $i < $z; $i++) 
     {
          $b = explode(":", $a[$i]);
          $y = count($b);

          if ($y > 1) 
          {
               $m .= encodeJSONStage3($b);
          }
          else
          {
               $m .= $b[0];
          }
     }
     return $m;
}

// add colons between array elements and build a string from elements 
function encodeJSONStage3($a) 
{
     echo "\n<br>encodeJSONStage3:made it";
     echo $a[0];

     $z = count($a);
     $m = "";

     // loop through each array item
     for($i = 0; $i < $z; $i++) 
     {
          if ($i > 0) 
          {
               // insert colon character after item
               $m .= ":";
          } 
          else 
          {
               // trim first item
               $a[0] = trim($a[0]);
          }

          $m .= JSONStringAddQuotes($a[$i]);
     }
     return $m;
}

// Wraps a string in quote symbols if string doesn't start with a quote symbol does end with a quote symbol, plus the string contains two quote symbols. Is this an attempt to fix unclosed quote pairs?
function JSONStringAddQuotes($a) 
{
     if (!(startsWith($a, "\"") && endsWith($a, "\"")))
     {
          if (substr_count($a, "\"") == 2)
          {
               $a= "\"" . $a . "\"";
          }
     }
     return $a;
}

// clean up paths used in CSS images?
function trimCssImagePath($s)
{
     global $isfacebook;

     //echo "<br>trimCssImagePath".$s;

     //if (startsWith($s," ")) {while (startsWith($s," ") && strlen($s)>1) {$s=substr($s,1);}}
     //echo "<br>s:".$s;


     // remove initial characters * 4. why is this done 4 times? why not do it in a loop?
     if ((startsWith($s, " ")) 
     || (startsWith($s, "'")) 
     || (startsWith($s, "\"")) 
     || (startsWith($s, ","))) 
     {
          $s = substr($s, 1);
     }

     if ((startsWith($s, " ")) 
     || (startsWith($s, "'")) 
     || (startsWith($s, "\"")) 
     || (startsWith($s, ","))) 
     {
          $s = substr($s, 1);
     }

     if ((startsWith($s, " "))
     || (startsWith($s, "'")) 
     || (startsWith($s, "\"")) 
     || (startsWith($s, ","))) 
     {
          $s = substr($s, 1);
     }

     if ((startsWith($s, " ")) 
     || (startsWith($s, "'")) 
     || (startsWith($s, "\"")) 
     || (startsWith($s, ","))) 
     {
          $s = substr($s, 1);
     }

     //echo "<br>trim:".$s;

     // convert the input string into an associative array of unsigned characters 
     $byte_array = unpack('C*', $s);

     //var_dump($byte_array);

     // remove trailing characters * 4
     if ((endsWith($s, " "))
     || (endsWith($s, "'"))
     || (endsWith($s, "\""))
     || (endsWith($s, ","))
     || (endsWith($s, "\n"))
     || (endsWith($s, "\r"))
     || (endsWith($s, "\t"))) 
     {
          $s = substr($s, 0, strlen($s) -1);
     }

     if ((endsWith($s, " "))
     || (endsWith($s, "'"))
     || (endsWith($s, "\""))
     || (endsWith($s, ","))
     || (endsWith($s, "\n"))
     || (endsWith($s, "\r"))
     || (endsWith($s, "\t"))) 
     {
          $s = substr($s, 0, strlen($s) -1);
     }

     if ((endsWith($s, " "))
     || (endsWith($s, "'"))
     || (endsWith($s, "\""))
     || (endsWith($s, ","))
     || (endsWith($s, "\n"))
     || (endsWith($s, "\r"))
     || (endsWith($s, "\t"))) 
     {
          $s = substr($s, 0, strlen($s) -1);
     }

     if ((endsWith($s, " "))
     || (endsWith($s, "'"))
     || (endsWith($s, "\""))
     || (endsWith($s, ","))
     || (endsWith($s, "\n"))
     || (endsWith($s, "\r"))
     || (endsWith($s, "\t"))) 
     {
          $s = substr($s, 0, strlen($s) -1);
     }

     //echo "<br>trim2:".$s;

     // remove HTML encoded symbols 
     if (startsWith($s, "&apos;")) 
     {
          $s = substr($s, 6);
     }
     if (endsWith($s, "&apos;")) 
     {
          $s = substr($s, 0, strlen($s) -6);
     }
     if (startsWith($s, "&quot;")) 
     {
          $s = substr($s, 6);
     }
     if (endsWith($s, "&quot;")) 
     {
          $s = substr($s, 0, strlen($s) -6);
     }
     if (startsWith($s, "&#039;")) 
     {
          $s = substr($s, 6);
     }
     if (endsWith($s, "&#039;")) 
     {
          $s = substr($s, 0, strlen($s) -6);
     }

     //echo "<br>trim2:".$s;
     //if (strpos($s,"ttps\3")>0) {echo "<br>1";}
     //if (strpos($s,"ttps\\")>0) {echo "<br>2";}
     //if (strpos($s,"ttps\\3")>0) {echo "<br>3";}
     //if (strpos($s,"ttps\\3a")>0) {echo "<br>4";}
     //if (strpos($s,"ttps\\3a ")>0) {echo "<br>5";}

     if ($isfacebook) 
     {
          // replace various characters if source is facebook
          $s = str_replace("https\\3a ", "https:", $s);
          $s = str_replace(" ", "", $s);
          $s = str_replace("\\3d", "=", $s);
          $s = str_replace("\\26", "&", $s);
     }
     //echo "<br>trim3:".$s;

     // remove newline characters
     $s = str_replace("\n", "", $s);

     // if a space character exists in string
     if (strpos($s, " ") !== FALSE) 
     {
          // if first character is a space character, remove it
          if (strpos($s, " ") == 0) 
          {
               $s = substr($s, 1);
          }
          // trim string to first position of a space character
          if (strpos($s, " ") > 0) 
          {
               $s = substr($s, 0, strpos($s, " "));
          }
          // remove wrapping quote characters
          if (startsWith($s, "\"") && endsWith($s, "\"")) 
          {
               $s = substr($s, 1, strlen($s) -2);
          }
          // remove wrapping apostrophe characters
          if (startsWith($s, "'") && endsWith($s, "'")) 
          {
               $s = substr($s, 1, strlen($s) -2);
          }
          // remove trailing comma character
          if (endsWith($s, ",")) 
          {
               $s = substr($s, 0, strlen($s) -1);
          }
     }

     // remove start/end characters * 4 a second time?
     if ((startsWith($s, " "))
     || (startsWith($s, "'"))
     || (startsWith($s, "\""))
     || (startsWith($s, ","))) 
     {
          $s = substr($s, 1);
     }

     if ((startsWith($s, " ")) 
     || (startsWith($s, "'"))
     || (startsWith($s, "\""))
     || (startsWith($s, ","))) 
     {
          $s = substr($s, 1);
     }

     if ((startsWith($s, " "))
     || (startsWith($s, "'"))
     || (startsWith($s, "\""))
     || (startsWith($s, ","))) 
     {
          $s = substr($s, 1);
     }

     if ((startsWith($s, " ")) 
     || (startsWith($s, "'"))
     || (startsWith($s, "\""))
     || (startsWith($s, ","))) 
     {
          $s = substr($s, 1);
     }

     if ((endsWith($s, " "))
     || (endsWith($s, "'"))
     || (endsWith($s, "\""))
     || (endsWith($s, ","))
     || (endsWith($s, "\n"))
     || (endsWith($s, "\r")) 
     || (endsWith($s, "\t"))) 
     {
          $s = substr($s, 0, strlen($s) -1);
     }

     if ((endsWith($s, " "))
     || (endsWith($s, "'"))
     || (endsWith($s, "\""))
     || (endsWith($s, ","))
     || (endsWith($s, "\n"))
     || (endsWith($s, "\r")) 
     || (endsWith($s, "\t"))) 
     {
          $s = substr($s, 0, strlen($s) -1);
     }

     if ((endsWith($s, " "))
     || (endsWith($s, "'"))
     || (endsWith($s, "\""))
     || (endsWith($s, ","))
     || (endsWith($s, "\n"))
     || (endsWith($s, "\r")) 
     || (endsWith($s, "\t"))) 
     {
          $s = substr($s, 0, strlen($s) -1);
     }

     if ((endsWith($s, " "))
     || (endsWith($s, "'"))
     || (endsWith($s, "\""))
     || (endsWith($s, ","))
     || (endsWith($s, "\n"))
     || (endsWith($s, "\r")) 
     || (endsWith($s, "\t"))) 
     {
          $s = substr($s, 0, strlen($s) -1);
     }

     //may need a smarter system;  One that says &amp; is ok when in quotes.
     $s = str_replace("&amp;", "&", $s);

     //echo "<br>endtrim:".$s;
     return  $s;
}

// disallow specific domains
function blocklist($a) 
{
     //echo "$a";
     global $adsites1, $adc, $iswp, $baseurl;
     //echo "<br>link:".$a;
     $temp = substr($baseurl, 0, strpos($baseurl, "."));
     $temp2 = substr($baseurl, strpos($baseurl, "."));

     if (strpos($temp2, "/") > 0) 
     {
          $temp2 = substr($temp2, 0, strpos($temp2, "/"));
     }

     $baseurlz = $temp . $temp2;
     for ($i = 0; $i < $adc - 1; $i++) 
     {
          if  (startsWith($a, $adsites1[$i])) 
          {
               $a = "https://0.0.0.0/adblockedorhashflag\n";
               if (strpos($adsites1[$i], ".wordpress.") > 0 || strpos($adsites1[$i], ".wp." ) > 0) 
               {
                    $iswp = true;
               }
          }
     }

     if ($iswp && ($a === $baseurlz . "/feed\n" ||$a === $baseurlz . "/feed/\n" )) 
     {
          $a = "https://0.0.0.0/adblockedorhashflag\n";
     }
     else 
     {
          //echo "<br>not culprit:".$a;echo " ".$baseurlz; echo " ".($iswp)?"true":"false";
     }

     if (strpos($a, "vice.com/") > 0 && strpos($a, "/static/vendor.ui-components.") > 0 ) 
     {
          $a = "https://0.0.0.0/scriptblocked\n";
     }

     if (strpos($a, "medium.com/") > 0 && strpos($a, "lite/static/js/vendors~main.") > 0 ) 
     {
          $a = "https://0.0.0.0/scriptblocked\n";
     }

     if (strpos($a, "/status/https:\\/\\/abs.twimg.com\\/hashflags\\/") > 0 ) 
     {
          $a = "https://0.0.0.0/adblockedorhashflag\n";
     }
     return $a;
} 

function filelist($ee, $urls, $urls2, $xurls, $index, $path, $url, $proto, $port) 
{
     global $baseurl;
     global $istwitter;
     global $istwitter2;

     $test = "";

     // take backup of ee
     $oldee = $ee;

     //echo "<br>filelist:".$ee;

     $ee = trimCssImagePath($ee);
     
     //if (strpos($url,"?")>0) {$url=substr($url,0,strpos($url,"?"));}

     //if (strpos(strToLower($ee),"/abs.twimg.com/hashflags/")===FALSE) {
     //if (strpos(strToLower($ee),"lang=")===FALSE)

     debugmode("filelist", "start", $index . " " . strlen($urls) . " " . $ee . " " . $proto . " " . $url . " " . $port . " " . $path . " " . count($urls2) . "<br>");

     // remove url parameters from path
     if (strpos($path, "?") > 0) 
     {    
          $path = substr($path, 0, strpos($path, "?"));
          //echo "path:".$path;
     }

     // if $ee is empty then return and notify
     if (strlen($ee) == 0) 
     {
          $test = $oldee;
          $result = false;
          $return[0] = $result;
          $return[1] = "";
          $return[2] = $index;
          $return[3] = $test;
          return $return;
     }
     else if (startsWith(strtolower($ee), "https://") || startsWith(strtolower($ee), "http://")) 
     {
          // if $ee is hypertext protocol then add a new line
          $test = $ee . "\n";
     }
     else if (startsWith(strToLower($ee), "data:image"))
     {
          // if $ee is an image blob then return and notify
          $test = $ee;
          $result = false;
          $return[0] = $result;
          $return[1] = $test;
          $return[2] = $index;
          $return[3] = $test;
          return $return;
     }
     else if (startsWith(strToLower($ee), "\"data:image")) 
     {
          // if $ee is an image blob then return and notify
          $test = $ee; 
          $result = false;
          $return[0] = $result;
          $return[1] = $test;
          $return[2] = $index;
          $return[3] = $test;
          return $return;
     }
     else if (strpos($ee, "+") !== FALSE || strpos($ee, "[") !== FALSE || strpos($ee, "]") !== FALSE || strpos($ee, "\${") !== FALSE || strpos($ee, "'") !== FALSE || strpos($ee, ")") !== FALSE || strpos($ee, "(") !== FALSE || strpos($ee, "\\") !== FALSE || strpos($ee,",") !== FALSE || strpos($ee, ";") !== FALSE) 
     {
          // if $ee contains these characters then return and notify
          global $scriptopen;
          $scriptopen = true;

          $test = $oldee;
          $result = false;
          $return[0] = $result;
          $return[1] = $test;
          $return[2] = -1;
          $return[3] = $test;

          return $return;
     }
     else if (startsWith($ee, "//")) 
     {
          // if $ee starts with // then strip // from end of protocol and build test/url variable
          $test = substr($proto, 0, strlen($proto) -2) . $ee . "\n";
     }
     else if (startsWith($ee, "/")) 
     {
          if (($proto === "https://") && ($port == 443)) 
          {
               $test = $proto . $url . $ee . "\n";
          }
          else if (($proto === "http://") && ($port == 80)) 
          {
               $test = $proto . $url . $ee . "\n";
          }
          else 
          {
               $test = $proto . $url . $port . $ee . "\n";
          }
     }
     else 
     {
          if (($proto === "https://") && ($port == 443)) 
          {
               $test = $proto . $url . $path . "/" . $ee . "\n";
          }
          else if (($proto === "http://") && ($port == 80))
          {
               $test = $proto . $url . $path ." /" . $ee . "\n";
          }
          else 
          {
               $test = $proto . $url . $port . $path . "/" . $ee . "\n";
          }
     }

     $result = false;
     $baseurl2 = $baseurl;

     // this seems unnecessary
     if(strToLower($test) === strToLower($baseurl) . "\n") 
     {
          $test = $baseurl . "\n";
     }

     //echo $istwitter?"true":"<br>false";

     // If the page is on Twitter
     if ($istwitter || $istwitter2) 
     {
          // add mobile subdomain
          $baseurl2 = "https://mobile." . substr($baseurl, 8);
          $baseurl3 = "null.null";
          $baseurl4 = "null.null";

          // if baseurl has url parameters
          if (strpos($baseurl, "?") > 0) 
          {
               // add main url to baseurl3
               $baseurl3 = substr($baseurl,0,strpos($baseurl, "?"));
               // what is this for?
               $baseurl4 = "https://mobile." . substr($baseurl3, 8);
          }
          //echo "<br>".$baseurl3;

          if(strToLower($test) === strToLower($baseurl) . "\n") 
          {
               $test = $baseurl . "\n";
          }
          else if (startsWith(strToLower($test), strToLower($baseurl) . "https"))
          {
               $test = $baseurl . "\n";
          }
          else if (startsWith(strToLower($test), strToLower($baseurl) . "'https"))
          {
               $test = $baseurl . "\n";
          }
          else if (startsWith(strToLower($test), strToLower($baseurl) . "?lang="))
          {
               $test = $baseurl . "\n";
          }
          else if (startsWith(strToLower($test), strToLower($baseurl2) . "?lang")) 
          {
               $test = $baseurl . "\n";
          }
          else if (startsWith(strToLower($test), strToLower($baseurl3) . "?lang"))
          {
               $test = $baseurl . "\n";
          }
          else if (startsWith(strToLower($test), strToLower($baseurl4) . "?lang"))
          {
               $test = $baseurl . "\n";
          }
          else if (startsWith(strToLower($test), strToLower($baseurl) . "&lang="))
          {
               $test = $baseurl . "\n";
          }
          else if (startsWith(strToLower($test), strToLower($baseurl2) . "&lang"))
          { 
               $test = $baseurl . "\n";
          }
          else if (startsWith(strToLower($test), strToLower($baseurl3) . "&lang"))
          {
               $test = $baseurl . "\n";
          }
          else if (startsWith(strToLower($test), strToLower($baseurl4) . "&lang"))
          {
               $test = $baseurl . "\n";
          }
          else if (startsWith(strToLower($test), strToLower($baseurl) . "&amp;lang=")) 
          {
               $test = $baseurl . "\n";
          }
          else if (startsWith(strToLower($test), strToLower($baseurl2) . "&amp;lang")) 
          {
               $test = $baseurl . "\n";
          }
          else if (startsWith(strToLower($test), strToLower($baseurl3) . "&amp;lang")) 
          {
               $test = $baseurl . "\n";
          }
          else if (startsWith(strToLower($test), strToLower($baseurl4)."&amp;lang"))
          {
               $test = $baseurl . "\n";
          }
          else if (startsWith(strToLower($test), "https://abs.twimg.com/hashflags/")) 
          {
               $test = strToLower("https://abs.twimg.com/hashflags/DisneyStarWarsROSLightSaber_Emoji/DisneyStarWarsROSLightSaber_Emoji.png") . "\n";
          }
     }

     //echo "<br>".$test.":".(!ISSET($urls2[strToLower(substr($test,0,-1))])?"new":"old");
     $test = blocklist($test);

     if (!ISSET($urls2[substr($test, 0, -1)])) 
     {
          $result = true;
     }  //if new, return true;
     else 
     {
          if (strToLower($test) === strToLower($baseurl))  
          {
               $index = 0;
          } 
          else
          {
               //echo "<br>indexurl:".($urls2[substr($test,0,-1)]-1)." ".substr($test,0,-1);
               $index = $urls2[substr($test, 0, -1)] -1;
          }
     }

     $return[0] = $result;
     $return[1] = $test;
     $return[2] = $index;
     $return[3] = substr($test, 0, -1);
     return $return;
}

function filelist2($ee, $urls, $urls2, $xurls, $index, $path, $url, $proto, $port, $loadFilePathFull, $fileid) 
{
     global $baseurl;
     //debugmode("filelist2","enter",$ee);
     $text = "";
     //if (strpos($url,"?")>0) {$url=substr($url,0,strpos($url,"?");}

     // if $ee has commas in it
     if (strpos($ee, ",") > 0)  
     {
          $ff = explode(",", $ee);

          // loop through all the comma separated substrings
          for($i = 0; $i < count($ff); $i++) 
          {
               //echo "<br>srcset:".$i.":".$ff[$i];
               $kk = filelist($ff[$i], $urls, $urls2, $xurls, $index, $path, $url, $proto, $port);
               $size = "";

               // if a space character is in a substring
               if (strrpos($ff[$i], " ") > 0) 
               {
                    // get string that follows the space character? shouldn't this be counted if it's a size variable?
                    $size = substr($ff[$i], strrpos($ff[$i], " "));
               }

               if ($kk[0]) 
               {
                    $urls .= $kk[1];
                    $urls2[$kk[3]] = 1 + count($urls2);
                    $xurls .= "img\n";
                    $index++;
                    $text .= $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=img" . $size;
               }
               else if (startsWith(strToLower($kk[1]), "data:image")) 
               {
                    $text = $kk[1] . $size;
               }
               else if (startsWith(strToLower($kk[1]), "\"data:image")) 
               {
                    $text = $kk[1] . $size;
               }
               else 
               {
                    $text .= $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=img" . $size;
               }
               $text .= ", ";
          }
     }
     else 
     {
          $size = "";
          if (strrpos($ff[$i], " ") > 0) 
          {
               $size = substr($ff[$i], strrpos($ff[$i], " "));
          }

          $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $url, $proto, $port);

          if ($kk[0]) 
          {
               $urls .= $kk[1];
               $urls2[$kk[3]] = 1 + count($urls2);
               $xurls .= "img\n";
               $index++;
               $text .= $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=img" . $size;
          }
          else if (startsWith(strToLower($kk[1]), "data:image")) 
          {
               $text = $kk[1].$size;
          }
          else if (startsWith(strToLower($kk[1]), "\"data:image")) 
          {
               $text = $kk[1].$size;
          }
          else 
          {
               $text .= $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=img" . $size;
          }
          $text .= ", ";
     }

     $text = substr($text, 0, -2);
     $return[0] = $urls;
     $return[1] = $text;
     $return[2] = $index;
     $return[3] = $xurls;
     $return[4] = $urls2;
     //debugmode("filelist2","end","endfilelist");
     return $return;
}

function loadwebpage($a) 
{
     global $normal;
     global $postdata;
     global $cookies;

     echo "<br>loading page" . $a;

     $pagetext = "";
     $cmd = $a;
     $proto = "";
     $url = "";
     $path = "";
     $port = "";
     if (startsWith($cmd , " ")) 
     {
          $cmd = substr($cmd, 1);
     }

     if (!startsWith($cmd, "http")) 
     {
          $cmd = "https://" . $cmd;
     }

     if (startsWith($cmd, "http"))  
     {
          $ll = "";
          $port = 80;
          $protolen = 7;

          if (startsWith($cmd, "https"))  
          {
               $cmd=" ssl" . substr($cmd, 5);
               $port = 443;
               $protolen = 6;
          }

          $url = "";
          $path = "";
          $proto = substr($cmd, 0, $protolen);
          $fullpath = substr($cmd, $protolen);
   
          if (strpos($fullpath, "/") > 0) 
          {
               $url = substr($fullpath, 0, strpos($fullpath, "/"));
               $path = substr($fullpath, strpos($fullpath, "/"));
          }
          else 
          {
               $url = $fullpath;
               if (startsWith($cmd, "http")) 
               {
                    $path = "/index.html";
               }
               $path = "/";
          }
          
          $port2 = $port;

          //if (strpos($url,":")>0) {
          //$junk=substr($url,strpos($url,":")+1);
          //$portt=substr($junk,strpos($junk,":"));
          //if (strpos($portt,"/")) {$portt=substr($portt,0,strpos($portt,"/"));}
          //echo "<br>portt".$portt;
          //$port=$portt;}

          if (strpos($url, ":") > 0) 
          {
               $port = substr($url, strpos($url, ":") +1);
               $port2 = substr($port, 1);
               $url = substr($url, 0, strpos($url, ":"));
          }

          if (startsWith($proto, "http")) 
          {
               $proto = "";
          }

          if (startsWith($proto, "http")) 
          {
               $proto = "";
          }

          $pagetext = "";
          $headers = "\r\n:authority: " . $url . "\r\n:method: GET\r\n:path: " . $path . "\r\n:scheme:https\r\naccept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,;q=0.8,application/signed-exchange;v=b3\r\naccept-language:en-US,en;q=0.9\r\ncache-control: no-cache\r\nDNT:1\r\npragma:no-cache\r\nsec-fetch-mode: navigate\r\nsec-fetch-site: none\r\nsec-fetch-user: ?1\r\nupgrade-insecure-requests: 1\r\nUser-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36\r\nConnection:Close\r\n\n";

          //echo "<br>http".(!startsWith($proto,"s")?"":"s")."://".$url.":".$port.$path;
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, "http" . (!startsWith($proto, "s") ? "" : "s") . "://" . $url . ":" . $port.$path);

          if ((strToLower($url) === "washingtonpost.com") 
               || (strToLower($url) === "www.washingtonpost.com") 
               || (strToLower($url) === "www.bloomberg.com")
               || (strToLower($url) === "bloomberg.com"))
          {
               //curl_setopt($ch, CURLOPT_PROXY, "73.46.67.172:58517");
               curl_setopt($ch,CURLOPT_HEADER, 1);
               curl_setopt($ch,CURLOPT_COOKIE, $cookies);
          } 
          else
          {
               curl_setopt($ch, CURLOPT_COOKIE, "");
          }

          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
          $ct = " ";

          if (!$normal) 
          {
               curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
               $ct = 'Content-type: application/json';
               if (startsWith($postdata[$a], "\"") && endsWith($postdata[$a], "\""))
               {
                    $postdata[$a] = str_replace("'", "\"", $postdata[$a]);
                    $postdata[$a] = substr($postdata[$a], 1);
                    $postdata[$a] = substr($postdata[$a], 0, strlen($postdata[$a]));
               }
               else if ((startsWith($postdata[$a], "{")) && (endsWith($postdata[$a], "}")))
               {
                    $postdata[$a] = str_replace("'", "\"", $postdata[$a]);
                    $postdata[$a] = encodeJSON($postdata[$a]);
               }
               //curl_setopt($ch, CURLOPT_HEADER, 1);
               //curl_setopt($ch, CURLOPT_VERBOSE, 1);
               //curl_setopt($ch, CURLINFO_HEADER_OUT, true);
               curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata[$a]);
               //echo "\n<br>postdata:".$postdata[$a];
          }

          curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
          curl_setopt($ch, CURLOPT_ENCODING, "");

          if ((strToLower($url) === "twitter.com") 
          || (strToLower($url) === "www.twitter.com")
          || (strToLower($url) === "i.imgur.com")
          || (strToLower($url) === "nature.com")
          || (strToLower($url) === "www.nature.com"))  
          {
               curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: */*','Accept-Encoding: identity','User-Agent: Wget/1.19.4 (linux-gnu)'));
          }
          else if ((strToLower($url) === "www.bloomberg.com") 
          || (strToLower($url) === "www.bloomberg.com")) 
          {
               curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: */*','DNT:1','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Geckoo) Chrome/76.0.3809.132 Safari/537.36','Connection:Close'));
          }
          else 
          {
               curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept: */*', $ct,'accept-language:en-US,en','cache-control: no-cache','DNT:1','pragma:no-cache','sec-fetch-mode: cors','upgrade-insecure-requests: 1','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36','Connection:Close'));
               //curl_setopt($ch,CURLOPT_HTTPHEADER, array('accept: text/html,application/xhtml+xml,text/plain,application/xml;q=0.9,image/webp,image/png,;q=0.8,application/signed-exchange;v=b3','accept-language:en-US,en;q=0.9','cache-control: no-cache','DNT:1','Origin:http://127.0.0.1','Referer:http://127.0.0.1','pragma:no-cache','sec-fetch-mode: cors','upgrade-insecure-requests: 1','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36','Connection:Close'));
          }

          $result = "";

          if (($result = curl_exec($ch)) === false)
          {
               echo 'Curl error: ' . curl_error($ch);
               if (strpos(strToLower(curl_error($ch)), "http1") > 0) 
               {
                    echo "doing fsockopen instead";
                    echo (!startsWith($proto, "s") ? "" : "ssl://") . $url;
                    //echo $port2;
                    //echo $path;
                    if ($path === "/") 
                    {
                         $path = "/index.html";
                    } 
                    //echo $path;
                    //echo strlen($path);
                    $fp = fsockopen((!startsWith($proto , "s") ? "" : "ssl://") . $url, $port2, $errno, $errstr, 30);

                    if(!$fp)
                    {
                         echo "$errstr ($errno)\n"; 
                    }

                    fputs($fp, "GET " . $path . " HTTP/1.0" . $headers);
                    while(!feof($fp))
                    {
                         $pagetext = $pagetext . fgets($fp);
                    }
                    fclose($fp);
                    //echo $result;

                    $result = $pagetext;
               }
          }
          else
          {
               if ((strpos($result, "Bad Request") < 105) && (strpos($result, "Bad Request") > 0 )) 
               {     
                    echo "Bad Request";
               }
               //else {}
               if ((strToLower($url) === "washingtonpost.com") 
               || (strToLower($url) === "www.washingtonpost.com") 
               || (strToLower($url) === "www.bloomberg.com") 
               || (strToLower($url) === "bloomberg.com")) 
               {
                    $header_l = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                    $header = substr($result, 0, $header_l);
                    //echo $header;
                    $ex = explode("\r", $header);
                    //echo "\n<br><br>count:".count($ex);
                    $cookies = "";

                    foreach($ex as $a) 
                    {
                         if (strpos($a, "Set-Cookie:") > 0)
                         {
                              //echo "<br>".$a;
                              $a = substr($a, 13);
                              $cookies .= substr($a, 0, strpos($a, ";"));
                         } 
                         else 
                         {
                              //echo "<br>".$a;
                         }
                    }
                    //echo "<br><br>Cookies:";
                    //echo $cookies;

                    $result = substr($result, strlen($header));
               }
               $pagetext=$result;
          }
     }

     $return[0] = $pagetext;
     $return[1] = $proto;
     $return[2] = $url;
     $return[3] = $port;
     $return[4] = $path;

     //if (!$normal) {echo "<br>".$pagetext;exit;}

     return $return;
}

function parsedata($ll, $proto, $url, $port, $path, $index, $loadFilePathFull, $fileid, $urls, $urls2, $xurls, $doreplace, $iscss1, $istwitter, $isvideo) 
{
     global $video;
     global $videocount;
     global $isyoutube;
     global $isfacebook;
     global $bot;
     global $postdata;

     //$fileid=$fileid."&user=".$bot;
     if (strpos($ll, "\x08") !== false) 
     {
          $doreplace = false;
          $return[0] = $urls;
          $return[1] = $ll;
          $return[2] = $index;
          $return[3] = $xurls;
          $return[4] = $urls2;
          return $return;
     }

     //echo "<br>parsedata:".$proto.$url.$port.$path." ".strlen($ll);
     $c_url = $url;
     $c_port = $port;
     $c_proto = $proto;
     $code = "";
     $temp = "";
     $scriptopen = false;
     //echo "<br>path:".$path;

     if (endsWith($path, ".js") || endsWith($path, ".json")) 
     {
          $scriptopen = true;
     }

     //echo "<br>1:is scriptopen?".($scriptopen?"true":"false").$path;
     $opath = $path;
     $commentopen = false;

   //  {
     $cc = explode("<", $ll);
     if (strrpos($path, "/") > 0) 
     {
          $path = substr($path, 0, strrpos($path, "/"));
     }

     $apath1 = $proto . $url . $port . $path;
     $bpath1 = "";
     //$path=$proto.$url.$port.$path;

     $dd = 0;
     for($dd = 0; $dd < count($cc); $dd++) 
     {
          $ee="";
          if ($dd == 2) 
          {
               if ((startsWith(strToLower($cc[1]), "html")) 
               || (startsWith(strToLower($cc[1]), "head>"))
               || (startsWith(strToLower($cc[1]), "!doctype html"))) 
               {
                    $turl = $url;
                    if (strpos($turl, "?")) 
                    {
                         $turl = strpos($turl, 0, strrpos($turl, "?"));
                    }
                    if (strpos($turl, "/")) 
                    {
                         $turl = strpos($turl, 0, strrpos($turl, "/"));
                    }
                    $code .= "<base href=" . $proto . $turl . ">";
               }
          }

          $space = "";

          if (startsWith($cc[$dd], "                ")) 
          {
               $cc[$dd] = substr($cc[$dd], 16);
               $space .= "                ";
          }
          if (startsWith($cc[$dd], "        ")) 
          {
               $cc[$dd] = substr($cc[$dd], 8);
               $space .= "        ";
          }
          if (startsWith($cc[$dd], "    ")) 
          {
               $cc[$dd] = substr($cc[$dd], 4);
               $space .= "    ";
          }
          if (startsWith($cc[$dd] ,"  ")) 
          {
               $cc[$dd] = substr($cc[$dd], 2);
               $space .= "  ";
          }
          if (startsWith($cc[$dd] , " ")) 
          {
               $cc[$dd] = substr($cc[$dd], 1);
               $space .= " ";
          }

          $tmp = $cc[$dd];
          //echo "<br>cc[dd]:".$cc[$dd];
          $tmp1 = "";
          $tmp2 = "";
          $tmp3 = $cc[$dd];

          if ((startsWith($cc[$dd], "!--")) && (!$scriptopen))
          {
               $commentopen = true;
          }
          if ((startsWith(strtolower($cc[$dd]), "/script")) && (!$commentopen)) 
          {
               $scriptopen = false;
          }
          if ((strpos($cc[$dd], "-->") !== FALSE) && (!$scriptopen)) 
          {
               $commentopen = false;
          }

          //debugmode("parsedata","1000",strlen($code));
          if ((endsWith($opath, ".js")) || (endsWith($opath, ".json")))
          {
               $scriptopen = true;
          }

          global $jsmode;

          if ((!$commentopen) && ((!$scriptopen || $jsmode == 0)) && ($doreplace)) 
          {
               //echo "<br>is scriptopen?".($scriptopen?"true":"false").$opath;
               $postccdd = $ee =substr($cc[$dd], strpos($cc[$dd], ">"));
               $cc[$dd] = $ee = substr($cc[$dd], 0, strpos($cc[$dd], ">"));

               //windowstates
               //$cc[$dd]=str_ireplace2(" onblur","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onfocusin","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onfocusout","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onfocus","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onoffline","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" ononline","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onpagehide","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onpageshow","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onresize","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onsearch","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onscroll","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" ontoggle","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onunload","",$cc[$dd]);

               //clipboard
               //$cc[$dd]=str_ireplace2(" oncopy","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" oncut","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onpaste","",$cc[$dd]);

               //other
               //$cc[$dd]=str_ireplace2(" onabort","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onafterprint","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onerror","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onbeforeunload","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onchange","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onhashchange","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onmessage","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onopen","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onshow","",$cc[$dd]);

               //video metadata or video
               //$cc[$dd]=str_ireplace2(" oncanplaythrough","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" oncanplay","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onloadeddata","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onloadstart","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onload","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onloadedmetadata","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onplaying","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onprogress","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onratechange","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onseeked","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onseeking","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onstalled","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onsuspend","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" ontimeupdate","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onvolumechange","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onwaiting","",$cc[$dd]);

               //dragging stuff
               //$cc[$dd]=str_ireplace2(" oncontextmenu","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" ondragend","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" ondragenter","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" ondragleave","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" ondragover","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" ondragstart","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" ondrag","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" ondrop","",$cc[$dd]);

               //keys/forms
               //$cc[$dd]=str_ireplace2(" oninput","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" oninvalid","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onkeyup","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onkeypress","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onkeydown","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onreset","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onsubmit","",$cc[$dd]);

               //mouse
               //$cc[$dd]=str_ireplace2(" onclick","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" ondblclick","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onmousedown","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onmouseenter","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onmouseleave","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onmousemove","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onmouseover","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onmouseout","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onmouseup","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onmousepress","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onselect","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" onwheel","",$cc[$dd]);

               //touch
               //$cc[$dd]=str_ireplace2(" ontouchcancel","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" ontouchend","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" ontouchmove","",$cc[$dd]);
               //$cc[$dd]=str_ireplace2(" ontouchstart","",$cc[$dd]);

               $cc[$dd] .= $postccdd;
               $temp3 = $cc[$dd];
               //echo "<br>cc[dd]".$cc[$dd];

               if ((startsWith(strtolower($cc[$dd]), "script")) 
               || (startsWith(strtolower($cc[$dd]),"img ")) 
               || (startsWith(strtolower($cc[$dd]), "div "))) 
               {
                    $ee = substr($cc[$dd], 0, strpos($cc[$dd], ">"));
                    $tmp2 = $ee;
                    $tmp3 = substr($cc[$dd], strpos($cc[$dd], ">"));

                    if ((startsWith(strtolower($cc[$dd]), "script")) && (!$commentopen)) 
                    {
                         $scriptopen = true;
                    }

                    if (strpos(strtolower($ee), " src=\"") > 0) 
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " src=\"") +6);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2)-strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "\""));
                         $tmp3 = substr($tmp2, strpos($tmp2, "\"")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port); 

                         if ($kk[0]) 
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= (startsWith($cc[$dd] , "s") ? (($isyoutube && strpos($kk[1], "/base.js") > 0) ? "vid\n" :" src\n") : "img\n");
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=" . (startsWith($cc[$dd], "s") ? "src" : "img");
                         } 
                         else if (startsWith(strToLower($kk[1]), "data:image")) 
                         {
                              $tmp2 = $kk[1];
                         } 
                         else if (startsWith(strToLower($kk[1]), "\"data:image"))
                         {
                              $tmp2 = $kk[1];
                         } 
                         else if (strpos($kk[1], "+") !== false) 
                         {
                              $tmp2 = $kk[1];
                         }
                         else 
                         {
                              if ($kk[2] > 0) 
                              {
                                   $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=" . (startsWith($cc[$dd], "s") ? "src" : "img");
                              }
                         }
                    }
                    else if (strpos(strtolower($ee), " src='") > 0)  
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " src='") +6);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "'"));
                         $tmp3 = substr($tmp2, strpos($tmp2, "'")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         if ($kk[0]) 
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              //echo ($isyoutube?"<br>isyoutube":"<br>notyoutube");
                              //echo "<br>kk[1]".$kk[1];
                              $xurls .= (startsWith($cc[$dd], "s") ? (($isyoutube && strpos($kk[1], "/base.js") > 0) ? "vid\n" : "src\n") : "img\n");
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=" . (startsWith($cc[$dd], "s") ? "src" : "img");
                         } 
                         else if (startsWith(strToLower($kk[1]), "data:image")) 
                         {
                              $tmp2 = $kk[1];
                         } 
                         else if (startsWith(strToLower($kk[1]), "\"data:image")) 
                         {
                              $tmp2 = $kk[1];
                         } 
                         else if (strpos($kk[1], "+") !== false) 
                         {
                              $tmp2 = $kk[1];
                         }
                         else {
                              if ($kk[2] > 0) 
                              {
                                   $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=" . (startsWith($cc[$dd], "s") ? "src" : "img");
                              }
                         }
                    }
                    else if (strpos(strtolower($ee)," src=") > 0)   
                    {
                         $ee = substr($ee, 0, strpos(strtolower($ee), " src=") + 5);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         if (strpos($ee," ") > 0) 
                         {
                              $ee = substr($ee, 0, strpos($ee, " "));
                              $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                              $tmp2 = $ee;
                         }
                         else
                         {
                              if (strpos($ee, " ") === true)
                              {
                                   $ee = substr($ee, 1);
                                   if (strpos($ee, " ") > 0) 
                                   {
                                        $ee = substr($ee, 0, strpos($ee, " "));
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;$tmp2 = $ee;
                                   }
                                   else 
                                   {
                                        $ee = substr($ee, 0, strlen($ee) -1);
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                              }
                              else 
                              {
                                   $ee = substr($ee, 0, strlen($ee) -1);
                                   $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                   $tmp2 = $ee;
                              }
                         }

                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port); 
                         if ($kk[0]) 
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= (startsWith($cc[$dd], "s") ? (($isyoutube && strpos($kk[1],"/base.js") >0) ? "vid\n" : "src\n") : "img\n");
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=" . (startsWith($cc[$dd],"s") ? "src" : "img");
                         } 
                         else if (startsWith(strToLower($kk[1]), "data:image")) 
                         {
                              $tmp2 = $kk[1];
                         } 
                         else if (startsWith(strToLower($kk[1]), "\"data:image")) 
                         {
                              $tmp2 = $kk[1];
                         } 
                         else if (strpos($kk[1], "+") !== false) 
                         {
                              $tmp2 = $kk[1];
                         }
                         else 
                         {
                              if ($kk[2] > 0) 
                              {
                                   $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=" . (startsWith($cc[$dd], "s") ? "src" : "img");
                              }
                         }
                    }

                    if ($tmp1 . $tmp2 . $tmp3 === $tmp) 
                    {

                    } 
                    else 
                    {
                         $cc[$dd] = $tmp1 . $tmp2 . $tmp3;
                    }
                    
                    $tmp1 = "";
                    $tmp2 = "";
                    $tmp3 = $cc[$dd];
                    //echo "<br>cc[dd]2:".$cc[$dd];
                    $ee = substr($cc[$dd], 0, strpos($cc[$dd], ">"));
                    $tmp2 = $ee;
                    $tmp3 = substr($cc[$dd], strpos($cc[$dd], ">"));
                    $tmp1 = "";

                    if (strpos(strtolower($ee), " data-url=\"") > 0) 
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " data-url=\"") +11);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "\""));
                         $tmp3 = substr($tmp2, strpos($tmp2, "\"")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);

                         if ($kk[0]) 
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= (startsWith($cc[$dd], "s") ? (($isyoutube && strpos($kk[1], "/base.js") > 0) ? "vid\n" : "src\n") : "img\n");
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=" . (startsWith($cc[$dd], "s") ? "src":"img");
                         }
                         else if (startsWith(strToLower($kk[1]), "data:image")) 
                         {
                              $tmp2 = $kk[1];
                         }
                         else if (startsWith(strToLower($kk[1]), "\"data:image")) 
                         {
                              $tmp2 = $kk[1];
                         }
                         else if (strpos($kk[1], "+") !== false) 
                         {
                              $tmp2 = $kk[1];
                         }
                         else 
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=" . (startsWith($cc[$dd], "s") ? "src" : "img");
                         }
                    }
                    else if (strpos(strtolower($ee)," data-url='") > 0)  
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " data-url='") +11);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "'"));
                         $tmp3 = substr($tmp2, strpos($tmp2, "'")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);

                         if ($kk[0]) 
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              //echo ($isyoutube?"<br>isyoutube":"<br>notyoutube");
                              //echo "<br>kk[1]".$kk[1];
                              $xurls .= (startsWith($cc[$dd], "s") ? (($isyoutube && strpos($kk[1], "/base.js") > 0) ? "vid\n" : "src\n") : "img\n");
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=" . (startsWith($cc[$dd], "s") ? "src" : "img");
                         }
                         else if (startsWith(strToLower($kk[1]), "data:image")) 
                         {
                              $tmp2 = $kk[1];
                         }
                         else if (startsWith(strToLower($kk[1]), "\"data:image"))
                         {
                              $tmp2 = $kk[1];
                         }
                         else if (strpos($kk[1], "+") !== false) 
                         {
                              $tmp2 = $kk[1];
                         }
                         else 
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=" . (startsWith($cc[$dd], "s") ? "src" : "img");
                         }
                    }
                    else if (strpos(strtolower($ee), " data-url=") > 0)   
                    {
                         $ee = substr($ee, 0, strpos(strtolower($ee), " data-url=") + 10);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;

                         if (strpos($ee, " ") > 0) 
                         {
                              $ee = substr($ee, 0, strpos($ee, " "));
                              $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                              $tmp2 = $ee;
                         }
                         else 
                         {
                              if (strpos($ee, " ") === true) 
                              {
                                   $ee = substr($ee, 1);
                                   if (strpos($ee, " ") > 0) 
                                   {
                                        $ee = substr($ee, 0, strpos($ee, " "));
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                                   else 
                                   {
                                        $ee = substr($ee ,0 , strlen($ee) - 1);
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;$tmp2 = $ee;
                                   }
                              }
                              else 
                              {
                                   $ee = substr($ee, 0, strlen($ee) -1);
                                   $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                   $tmp2 = $ee;
                              }
                         }

                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port); 
                         
                         if ($kk[0]) 
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= (startsWith($cc[$dd], "s") ? (($isyoutube && strpos($kk[1], "/base.js") > 0) ? "vid\n" : "src\n") : "img\n");
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=" . (startsWith($cc[$dd], "s") ? "src" : "img");
                         }
                         else if (startsWith(strToLower($kk[1]), "data:image")) 
                         {
                              $tmp2= $kk[1];
                         }
                         else if (startsWith(strToLower($kk[1]), "\"data:image")) 
                         {
                              $tmp2 = $kk[1];
                         }
                         else if (strpos($kk[1], "+") !== false) 
                         {
                              $tmp2 = $kk[1];
                         }
                         else 
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=" . (startsWith($cc[$dd], "s") ? "src" : "img");
                         }
                    }

                    if ($tmp1 . $tmp2 . $tmp3 === $tmp)
                    {}
                    else 
                    {
                         $cc[$dd] = $tmp1 . $tmp2 . $tmp3;
                    }

                    $tmp1 = "";
                    $tmp2 = "";
                    $tmp3 = $cc[$dd];
                    //echo "<br>cc[dd]3:".$cc[$dd];

                    $ee = substr($cc[$dd], 0, strpos($cc[$dd], ">"));
                    $tmp2 = $ee;
                    $tmp3 = substr($cc[$dd], strpos($cc[$dd], ">"));
                    $tmp1 = "";

                    if (strpos(strtolower($ee), " data-src=\"") > 0)
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " data-src=\"") + 11);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "\""));
                         $tmp3 = substr($tmp2, strpos($tmp2, "\"")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         if ($kk[0])
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= (startsWith($cc[$dd], "s") ? (($isyoutube && strpos($kk[1], "/base.js") > 0) ? "vid\n" : "src\n") : "img\n");
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=" . (startsWith($cc[$dd], "s") ? "src" : "img");
                         }
                         else if (startsWith(strToLower($kk[1]), "data:image"))
                         {
                              $tmp2 = $kk[1];
                         }
                         else if (startsWith(strToLower($kk[1]), "\"data:image"))
                         {
                              $tmp2 = $kk[1];
                         }
                         else if (strpos($kk[1], "+") !== false)
                         {
                              $tmp2 = $kk[1];
                         }
                         else 
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=" . (startsWith($cc[$dd], "s") ? "src" : "img");
                         }
                    }
                    else if (strpos(strtolower($ee), " data-src='") > 0)
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " data-src='") + 11);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "'"));
                         $tmp3 = substr($tmp2, strpos($tmp2, "'")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist ($ee, $urls, $urls2, $xurls, $index, $path, $c_url,$c_proto, $c_port);
                         if ($kk[0])
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              //echo ($isyoutube?"<br>isyoutube":"<br>notyoutube");
                              //echo "<br>kk[1]".$kk[1];
                              $xurls .= (startsWith($cc[$dd], "s") ? (($isyoutube && strpos($kk[1],"/base.js") > 0) ? "vid\n" : "src\n") : "img\n");
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=" . (startsWith($cc[$dd], "s") ? "src" : "img");
                         }
                         else if (startsWith(strToLower($kk[1]), "data:image"))
                         {
                              $tmp2 = $kk[1];
                         }
                         else if (startsWith(strToLower($kk[1]), "\"data:image")) 
                         {
                              $tmp2 = $kk[1];
                         }
                         else if (strpos($kk[1], "+") !== false)
                         {
                              $tmp2 = $kk[1];
                         }
                         else 
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=" . (startsWith($cc[$dd], "s") ? "src" : "img");
                         }
                    }
                    else if (strpos(strtolower($ee), " data-src=") > 0)
                    {
                         $ee = substr($ee, 0, strpos(strtolower($ee), " data-src=") + 10);$tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         if (strpos($ee," ") > 0)
                         {
                              $ee = substr($ee, 0, strpos($ee, " "));
                              $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                              $tmp2 = $ee;
                         }
                         else 
                         {
                              if (strpos($ee," ") === true)
                              {
                                   $ee = substr($ee, 1);
                                   if (strpos($ee, " ") > 0)
                                   {
                                        $ee = substr($ee, 0, strpos($ee, " "));
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                                   else 
                                   {
                                        $ee = substr($ee, 0, strlen($ee) - 1);
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                              }
                              else 
                              {
                                   $ee = substr($ee, 0, strlen($ee) -1);
                                   $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                   $tmp2 = $ee;
                              }
                         }

                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         if ($kk[0]) 
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= (startsWith($cc[$dd], "s") ? (($isyoutube && strpos($kk[1], "/base.js" ) > 0) ? "vid\n" : "src\n") : "img\n");
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=" . (startsWith($cc[$dd], "s") ? "src" : "img");
                         }
                         else if (startsWith(strToLower($kk[1]), "data:image")) 
                         {
                              $tmp2 = $kk[1];
                         }
                         else if (startsWith(strToLower($kk[1]), "\"data:image"))
                         {
                              $tmp2 = $kk[1];
                         }
                         else if (strpos($kk[1], "+") !== false) 
                         {
                              $tmp2 = $kk[1];
                         }
                         else 
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=" . (startsWith($cc[$dd], "s") ? "src" : "img");
                         }
                    }

                    if ($tmp1 . $tmp2 . $tmp3 === $tmp)
                    {

                    }
                    else
                    {
                         $cc[$dd] = $tmp1 . $tmp2 . $tmp3;
                    }

                    //echo "<br>cc[dd]4:".$cc[$dd];

                    $tmp1 = "";
                    $tmp2 = "";
                    $tmp3 = $cc[$dd];

                    $ee = substr($cc[$dd], 0, strpos($cc[$dd], ">"));
                    $tmp2 = $ee;
                    $tmp3 = substr($cc[$dd], strpos($cc[$dd], ">"));
                    $tmp1 = "";

                    if (strpos(strtolower($ee), " srcset=\"") > 0)
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " srcset=\"") +9);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "\""));
                         $tmp3 = substr($tmp2, strpos($tmp2, "\"")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist2($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port, $loadFilePathFull, $fileid); 
                         $urls = $kk[0]; 
                         $urls2 = $kk[4]; 
                         $tmp2 = $kk[1];
                         $index = $kk[2];
                         $xurls = $kk[3];
                    }
                    else if (strpos(strtolower($ee), " srcset='") > 0)
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " srcset='") +9);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "'"));
                         $tmp3 = substr($tmp2, strpos($tmp2, "'")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist2($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port, $loadFilePathFull, $fileid); 
                         $urls = $kk[0];
                         $urls2 = $kk[4];
                         $tmp2 = $kk[1];
                         $index = $kk[2];
                         $xurls = $kk[3];
                    }
                    else if (strpos(strtolower($ee), " srcset=") > 0)
                    {
                         $ee = substr($ee, 0, strpos(strtolower($ee), " srcset=") + 8);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;

                         if (strpos($ee, " ") > 0)                                       
                         {
                              $ee = substr($ee, 0, strpos($ee, " "));
                              $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                              $tmp2 = $ee;
                         } 
                         else 
                         {
                              if (strpos($ee, " ") === true)
                              {
                                   $ee = substr($ee, 1);
                                   if (strpos($ee, " ") > 0)
                                   {
                                        $ee = substr($ee, 0, strpos($ee, " "));
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                                   else 
                                   {
                                        $ee = substr($ee, 0, strlen($ee) -1);
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                              }
                              else 
                              {
                                   $ee = substr($ee, 0, strlen($ee) - 1);
                                   $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                   $tmp2 = $ee;
                              }
                         }

                         $kk = filelist2($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port, $loadFilePathFull, $fileid);
                         $urls = $kk[0];
                         $urls2 = $kk[4];
                         $tmp2 = $kk[1];
                         $index = $kk[2];
                         $xurls = $kk[3];
                    }
               }

               if (startsWith(strtolower($cc[$dd]), "audio "))
               {
                    $ee = substr($cc[$dd], 0, strpos($cc[$dd], ">"));
                    $tmp2 = $ee;
                    $tmp3 = substr($cc[$dd], strpos($cc[$dd], ">"));

                    if (strpos(strtolower($ee), " src=\"") > 0) 
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " src=\"") + 6);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "\""));
                         $tmp3 = substr($tmp2, strpos($tmp2, "\"")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         if ($kk[0]) 
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= "snd\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=snd";
                         } 
                         else 
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=snd";
                         }
                    }
                    else if (strpos(strtolower($ee), " src='") > 0)
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " src='") +6);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "'"));
                         $tmp3 = substr($tmp2, strpos($tmp2, "'")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         if ($kk[0])
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= "snd\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=snd";
                         } 
                         else
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=snd";
                         }
                    }
                    else if (strpos(strtolower($ee), " src=") > 0)
                    {
                         $ee = substr($ee, 0, strpos(strtolower($ee), " src=") + 5);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         if (strpos($ee, " ") > 0)                                        
                         {
                              $ee = substr($ee, 0, strpos($ee, " "));
                              $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                              $tmp2 = $ee;
                         }
                         else 
                         {
                              if (strpos($ee, " ") === true)
                              {
                                   $ee = substr($ee, 1);
                                   if (strpos($ee," ") > 0)
                                   {
                                        $ee = substr($ee, 0, strpos($ee, " "));
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                                   else 
                                   {
                                        $ee = substr($ee, 0, strlen($ee) - 1);
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                              } 
                              else 
                              {
                                   $ee = substr($ee, 0, strlen($ee) - 1);
                                   $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                   $tmp2 = $ee;
                              }
                         }

                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         if ($kk[0])
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= "snd\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=vid";
                         }
                         else
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=snd";
                         }
                    }
               }

               if (startsWith(strtolower($cc[$dd]),"embed "))
               {
                    $ee = substr($cc[$dd], 0, strpos($cc[$dd], ">"));
                    $tmp2 = $ee;
                    $tmp3 = substr($cc[$dd], strpos($cc[$dd], ">"));

                    if (strpos(strtolower($ee), " src=\"") > 0)
                    {
                         $ee = substr($ee, strpos(strtolower($ee)," src=\"") + 6);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "\""));
                         $tmp3 = substr($tmp2, strpos($tmp2, "\"")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port); 
                         if ($kk[0])
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= "emb\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=emb";
                         } 
                         else
                         {
                              if ($kk[2] > 0) 
                              {
                                   $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=emb";
                              }
                         }
                    }
                    else if (strpos(strtolower($ee), " src='") > 0)
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " src='") + 6);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee,"'"));
                         $tmp3 = substr($tmp2, strpos($tmp2, "'")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         if ($kk[0])
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= "emb\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=emb";
                         } 
                         else
                         {
                              if ($kk[2] > 0) 
                              {
                                   $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=emb";
                              }
                         }
                    }
                    else if (strpos(strtolower($ee), " src=") > 0)
                    {
                         $ee = substr($ee, 0, strpos(strtolower($ee), " src=") + 5);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         
                         if (strpos($ee, " ") > 0)                                        
                         {
                              $ee = substr($ee, 0, strpos($ee, " "));
                              $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                              $tmp2 = $ee;
                         } 
                         else 
                         {
                              if (strpos($ee, " ") === true)
                              {
                                   $ee = substr($ee, 1);
                                   if (strpos($ee, " ") > 0)
                                   {
                                        $ee = substr($ee, 0, strpos($ee, " "));
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                                   else 
                                   {
                                        $ee = substr($ee, 0, strlen($ee) - 1);
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                              } 
                              else 
                              {
                                   $ee = substr($ee, 0, strlen($ee) -1);
                                   $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                   $tmp2 = $ee;
                              }
                         }

                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         if ($kk[0])
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= "emb\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=vid";
                         }
                         else
                         {
                              if ($kk[2] > 0)
                              {
                                   $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=emb";
                              }
                         }
                    }
               }

               if (startsWith(strtolower($cc[$dd]), "applet "))
               {
                    $ee = substr($cc[$dd], 0, strpos($cc[$dd], ">"));
                    $tmp2 = $ee;
                    $tmp3 = substr($cc[$dd], strpos($cc[$dd], ">"));

                    if (strpos(strtolower($ee), " code=\"") > 0)
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " code=\"") + 7);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "\""));
                         $tmp3 = substr($tmp2, strpos($tmp2, "\"")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         
                         if ($kk[0])
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= "java\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=java";
                         }
                         else 
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=java";
                         }
                    }
                    else if (strpos(strtolower($ee), " code='")>0)
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " code='") + 7);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "'"));
                         $tmp3 = substr($tmp2, strpos($tmp2, "'")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         if ($kk[0]) 
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= "java\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=java";
                         }
                         else
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=java";
                         }
                    }
                    else if (strpos(strtolower($ee), " code=") > 0)
                    {
                         $ee = substr($ee, 0, strpos(strtolower($ee), " code=") + 6);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         
                         if (strpos($ee, " ") > 0)                                       
                         {
                              $ee = substr($ee, 0, strpos($ee, " "));
                              $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                              $tmp2 = $ee;
                         } 
                         else 
                         {
                              if (strpos($ee, " ") === true)
                              {
                                   $ee = substr($ee, 1);
                                   if (strpos($ee, " ") > 0)
                                   {
                                        $ee = substr($ee, 0, strpos($ee, " "));
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                                   else 
                                   {
                                        $ee = substr($ee, 0, strlen($ee) - 1);
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                              } 
                              else
                              {
                                   $ee = substr($ee, 0, strlen($ee) - 1);
                                   $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                   $tmp2 = $ee;
                              }
                         }

                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         
                         if ($kk[0])
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= "java\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=java";
                         } 
                         else 
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=java";
                         }
                    }
               }

               //if (startsWith($cc[$dd], "span class=\"_1-h1 _3q_m\"") && $isfacebook) {$tmp2="span>";}

               if ((startsWith(strtolower($cc[$dd]), "a href=")) && ($isfacebook))
               {
                    if (strpos($cc[$dd], "/videos/") > 0)
                    {
                         $tmp9 = substr($cc[$dd], strpos($cc[$dd], "=") + 1);
                         $tmp9 = substr($tmp9, 0, strpos($tmp9, " "));
                         //echo "tmp9".$tmp9.":".strlen($tmp9);
                         //if (strlen($tmp9)<38) {exit;}

                         if (!((endsWith($tmp9, "/videos/\"")) 
                         || (endsWith($tmp9, "/videos/"))
                         || (endsWith($tmp9, "/videos/\" "))
                         || (strpos($tmp9, ">") > 0 )))
                         {
                              $cc[$dd + 3] = "video controls style=\"width:100%;height:100%\">";
                              $cc[$dd + 6] = $cc[$dd + 4];
                              $cc[$dd + 4] = "source src=";

                              $tmp9 = substr($cc[$dd], strpos($cc[$dd], "=") + 1);
                              $tmp9 = substr($tmp9, 0, strpos($tmp9, " "));
                              $cc[$dd + 4] .= $tmp9 . " type=\"video/mp4\">";
                              $cc[$dd +5] = "/video>";
                              $cc[$dd +1] = "span>";
                              $cc[$dd] = "a>";

                              $cc[$dd + 7] = "!-- -->";
                              $cc[$dd + 8] = "!-- -->";
                              $cc[$dd + 9] = "!-- -->";
                              $cc[$dd + 10] = "!-- -->";
                              $cc[$dd + 11] = "!-- -->";
                              $cc[$dd + 12] = "!-- -->";
                              $cc[$dd + 13] = "!-- -->";
                              $cc[$dd + 14] = "!-- -->";
                         }
                    }
               }

               if (startsWith(strtolower($cc[$dd]), "video "))
               {
                    $ee = substr($cc[$dd], 0, strpos($cc[$dd], ">"));
                    $tmp2 = $ee;
                    $tmp3 = substr($cc[$dd], strpos($cc[$dd], ">"));

                    if (strpos(strtolower($ee), " src=\"") > 0)
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " src=\"") +6);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "\""));

                         //$tmp3=substr($tmp2,strpos($tmp2,"\"")).$tmp3;
                         $tmp3 = substr($ee, strpos($ee, "\"")) . $tmp3;

                         $tmp2 = $ee;
                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);

                         if ($kk[0]) 
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= "vid\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=vid";
                         }
                         else 
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=vid";
                         }
                    }
                    else if (strpos(strtolower($ee), " src='") > 0)
                    {
                         $ee=substr($ee, strpos(strtolower($ee), " src='") + 6);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "'"));
                         $tmp3 = substr($tmp2, strpos($tmp2, "'")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         if ($kk[0])
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= "vid\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=vid";
                         } 
                         else
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=vid";
                         }
                    }
                    else if (strpos(strtolower($ee), " src=") > 0) 
                    {
                         $ee = substr($ee, 0, strpos(strtolower($ee), " src=") + 5);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         if (strpos($ee, " ") > 0)                                        
                         {
                              $ee = substr($ee, 0, strpos($ee, " "));
                              $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                              $tmp2 = $ee;
                         } 
                         else 
                         {
                              if (strpos($ee, " ") === true)
                              {
                                   $ee = substr($ee, 1);
                                   if (strpos($ee, " ") > 0)
                                   {
                                        $ee = substr($ee, 0, strpos($ee, " "));
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                                   else 
                                   {
                                        $ee = substr($ee, 0, strlen($ee) - 1);
                                        $tmp3 = substr($tmp2 , strpos($tmp2 , " ")) . $tmp3;$tmp2 = $ee;
                                   }
                              } 
                              else 
                              {
                                   $ee = substr($ee, 0, strlen($ee) - 1);
                                   $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                   $tmp2 = $ee;
                              }
                         }

                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         if ($kk[0]) 
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= "vid\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=vid";
                         } 
                         else
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=vid";
                         }
                    }

                    if ($tmp1 . $tmp2 . $tmp3 === $tmp) 
                    {} 
                    else
                    {
                         $cc[$dd] = $tmp1 . $tmp2 . $tmp3;
                    }

                    $tmp1 = "";
                    $tmp2 = "";
                    $tmp3 = $cc[$dd];

                    $ee = substr($cc[$dd], 0, strpos($cc[$dd], ">"));
                    $tmp2 = $ee;
                    $tmp3 = substr($cc[$dd], strpos($cc[$dd], ">"));
                    $tmp1 = "";

                    if (strpos(strtolower($ee), " poster=\"") > 0) 
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " poster=\"") + 9);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee,"\""));
                         $tmp3 = substr($tmp2, strpos($tmp2,"\"")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         if ($kk[0])
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= "img\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=img";
                         } 
                         else
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=img";
                         }
                    }
                    else if (strpos(strtolower($ee), " poster='") > 0)
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " poster='") + 9);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee; 
                         $ee = substr($ee, 0, strpos($ee, "'"));
                         $tmp3 = substr($tmp2, strpos($tmp2, "'")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         
                         if ($kk[0])
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= "img\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=img";
                         }
                         else
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" .$kk[2] . "&type=img";
                         }
                    }
                    else if (strpos(strtolower($ee), " poster=") > 0)
                    {
                         $ee = substr($ee, 0, strpos(strtolower($ee), " poster=") + 8);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         if (strpos($ee, " ") > 0)                                        
                         {
                              $ee = substr($ee, 0, strpos($ee, " "));
                              $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                              $tmp2 = $ee;
                         } 
                         else 
                         {
                              if (strpos($ee, " ") === true)
                              {
                                   $ee = substr($ee, 1);
                                   if (strpos($ee, " ") > 0)
                                   {
                                        $ee = substr($ee, 0, strpos($ee, " "));
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                                   else 
                                   {
                                        $ee = substr($ee, 0, strlen($ee) - 1);
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                              } 
                              else
                              {
                                   $ee = substr($ee, 0, strlen($ee) - 1);
                                   $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                   $tmp2 = $ee;
                              }
                         }

                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         
                         if ($kk[0])
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]]= 1 + count($urls2);
                              $xurls .= "img\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=img";
                         } 
                         else
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=img";
                         }
                    }
               }

               if ((startsWith(strtolower($cc[$dd]), "iframe ")) 
               && (strpos(strtolower($cc[$dd]), "www.youtube.com/embed/") > 0) 
               && (strpos(strtolower($cc[$dd]), " src=") > 0)) 
               {
                    $startblock2 = substr($cc[$dd], 0, strpos($cc[$dd], ">"));
                    //echo "<br>startblock2".$startblock2;
                    $startblock2 = "video " . substr($startblock2, 6);
                    //echo "<br>startblock2".$startblock2;
                    $endblock = substr($cc[$dd], strpos($cc[$dd], ">"));
                    //echo "<br>endblock".$endblock;
                    $block2 = " controls><source ";
                    //echo "<br>block2".$block2;
                    $blockhelp = substr($cc[$dd], strpos($cc[$dd], " src=") + 1);
                    //echo "<br>blockhelp".$blockhelp;
                    if (strpos($blockhelp, " ") > 0)
                    {
                         $blockhelp = substr($blockhelp, 0, strpos($blockhelp, " "));
                    }
                    else 
                    {
                         $blockhelp = substr($blockhelp, 0, strpos($blockhelp, ">"));
                    }

                    //echo "<br>blockhelp".$blockhelp;
                    $startblock2 = str_replace($blockhelp, "", $startblock2);
                    //echo "<br><code>startblock2".$startblock2;
                    $blockhelp = str_replace("/embed/", "/watch?v=", $blockhelp);
                    //$blockhelp="src=\"346/8.mp4\"";
                    $cc[$dd] = $startblock2 . $block2 . $blockhelp . "></video" . $endblock;

                    if (startsWith($cc[$dd+1], "/iframe>"))
                    {
                         $cc[$dd + 1] = "/" . substr($cc[$dd + 1], 7);
                    }

                    //echo "<br><".$cc[$dd];
                    //echo "<br></".$cc[$dd+1];

                    $ee = substr($cc[$dd], 0, strpos($cc[$dd], "video>"));
                    $tmp2 = $ee;
                    $tmp3 = substr($cc[$dd], strpos($cc[$dd], "video>"));
                    if (strpos(strtolower($ee), " src=\"")>0)
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " src=\"") + 6);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "\""));
                         $tmp3 = substr($tmp2, strpos($tmp2, "\"")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         if ($kk[0]) 
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= "vid\n";
                              $index++;
                              $tmp2 = "loadfile.php?fileid=" . $fileid . "&index=" . $index . "&type=vid";
                         } 
                         else
                         {
                              $tmp2 = "loadfile.php?fileid=" . $fileid . "&index=" . $kk[2] . "&type=vid";
                         }
                    }
                    else if (strpos(strtolower($ee), " src='") > 0) 
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " src='") + 6);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "'"));
                         $tmp3 = substr($tmp2, strpos($tmp2, "'")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         if ($kk[0])
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= "vid\n";
                              $index++;
                              $tmp2 = "loadfile.php?fileid=" . $fileid . "&index=" . $index . "&type=vid";
                         }
                         else
                         {
                              $tmp2 = "loadfile.php?fileid=" . $fileid . "&index=" . $kk[2] . "&type=vid";
                         }
                    }
                    else if (strpos(strtolower($ee)," src=") > 0)
                    {
                         $ee = substr($ee, 0, strpos(strtolower($ee), " src=") + 5);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         if (strpos($ee, " ") > 0)                                        
                         {
                              $ee = substr($ee, 0, strpos($ee, " "));
                              $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                              $tmp2 = $ee;
                         } 
                         else 
                         {
                              if (strpos($ee, " ") === true)
                              {
                                   $ee = substr($ee, 1);
                                   if (strpos($ee, " ") > 0)
                                   {
                                        $ee = substr($ee, 0, strpos($ee, " "));
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                                   else 
                                   {
                                        $ee = substr($ee, 0, strlen($ee) - 1);
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                              } 
                              else
                              {
                                   $ee = substr($ee, 0, strlen($ee) - 1);
                                   $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                   $tmp2 = $ee;
                              }
                         }

                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         
                         if ($kk[0])
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= "vid\n";
                              $index++;
                              $tmp2 = "loadfile.php?fileid=" . $fileid . "&index=" . $index . "&type=vid";
                         }
                         else
                         {
                              $tmp2 = "loadfile.php?fileid=" . $fileid . "&index=" . $kk[2] . "&type=vid";
                         }
                    }

                    //global $siteDomainUrl;
                    //per android issues:
                    //     if (strpos(strtolower($ee)," src=\"")>0) {$ee=substr($ee,  strpos(strtolower($ee)," src=\"")+6);$tmp1=substr($cc[$dd],0,strlen($tmp2)-strlen($ee));$tmp2=$ee;$ee=substr($ee,0,strpos($ee,"\""));$tmp3=substr($tmp2,strpos($tmp2,"\"")).$tmp3;$tmp2=$ee;$kk=filelist($ee,$urls,$urls2,$xurls,$index,$path,$c_url,$c_proto,$c_port); if ($kk[0]) {$urls.=$kk[1];$urls2[$kk[3]]=1+count($urls2);$xurls.="vid\n";$index++;$tmp2=$siteDomainUrl."/php/".$fileid."/".$index.".mp4";} else {$tmp2=$siteDomainUrl."/php/".$fileid."/".$index.".mp4";}}
                    //else if (strpos(strtolower($ee)," src='")>0)  {$ee=substr($ee,  strpos(strtolower($ee)," src='")+6);$tmp1=substr($cc[$dd],0,strlen($tmp2)-strlen($ee));$tmp2=$ee; $ee=substr($ee,0,strpos($ee,"'"));$tmp3=substr($tmp2,strpos($tmp2,"'")).$tmp3;$tmp2=$ee;$kk=filelist($ee,$urls,$urls2,$xurls,$index,$path,$c_url,$c_proto,$c_port); if ($kk[0]) {$urls.=$kk[1];$urls2[$kk[3]]=1+count($urls2);"vid\n";$index++;$tmp2=$siteDomainUrl."/php/".$fileid."/".$index.".mp4";} else {$tmp2=$siteDomainUrl."/php/".$fileid."/".$index.".mp4";}}
                    //else if (strpos(strtolower($ee)," src=")>0)   {$ee=substr($ee,0,strpos(strtolower($ee)," src=")+5);$tmp1=substr($cc[$dd],0,strlen($tmp2)-strlen($ee));$tmp2=$ee;if (strpos($ee," ")>0)                                        
                    //{$ee=substr($ee,0,strpos($ee," "));$tmp3=substr($tmp2,strpos($tmp2," ")).$tmp3;$tmp2=$ee;} 
                    //else {$ee=substr($ee,0,strlen($ee)-1);$tmp3=substr($tmp2,strpos(" ")).$tmp3;$tmp2=$ee;}$kk=filelist($ee,$urls,$urls2,$xurls,$index,$path,$c_url,$c_proto,$c_port); if ($kk[0]) {$urls.=$kk[1];$urls2[$kk[3]]=1+count($urls2);$xurls.="vid\n";$index++;$tmp2=$siteDomainUrl."/php/".$fileid."/".$index.".mp4";} else {$tmp2=$siteDomainUrl."/php/".$fileid."/".$index.".mp4";}}
               }
               else if (startsWith(strtolower($cc[$dd]), "iframe ")) 
               {
                    $ee = substr($cc[$dd], 0, strpos($cc[$dd], ">"));
                    $tmp2 = $ee;
                    $tmp3 = substr($cc[$dd], strpos($cc[$dd], ">"));
                    
                    if (strpos(strtolower($ee), " src=\"") > 0)
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " src=\"") + 6);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "\""));
                         $tmp3 = substr($tmp2, strpos($tmp2, "\"")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         
                         if ($kk[0]) 
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= getMimeTypeAsHTMLParam($kk[1]) . "\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . getMimeTypeAsHTMLParam($kk[1]);
                         }
                         else
                         {
                              if ($kk[2] > 0)
                              {
                                   $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . getMimeTypeAsHTMLParam($kk[1]);
                              }
                         }
                    }
                    else if (strpos(strtolower($ee)," src='") > 0) 
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " src='") + 6);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "'"));
                         $tmp3 = substr($tmp2, strpos($tmp2, "'")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         
                         if ($kk[0])
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= getMimeTypeAsHTMLParam($kk[1]) . "\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . getMimeTypeAsHTMLParam($kk[1]);
                         } 
                         else
                         {
                              if ($kk[2] > 0) 
                              {
                                   $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . getMimeTypeAsHTMLParam($kk[1]);
                              }
                         }
                    }
                    else if (strpos(strtolower($ee), " src=") > 0)
                    {
                         $ee = substr($ee, 0, strpos(strtolower($ee), " src=") + 5);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         
                         if (strpos($ee, " ") > 0)
                         {
                              $ee = substr($ee, 0, strpos($ee, " "));
                              $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                              $tmp2 = $ee;
                         } 
                         else 
                         {
                              if (strpos($ee, " ") === true)
                              {
                                   $ee = substr($ee, 1);
                                   if (strpos($ee, " ") > 0)
                                   {
                                        $ee = substr($ee, 0, strpos($ee, " "));
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                                   else 
                                   {
                                        $ee = substr($ee, 0, strlen($ee) - 1);
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                              } 
                              else
                              {
                                   $ee = substr($ee, 0, strlen($ee) -1);
                                   $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                   $tmp2 = $ee;
                              }
                         }

                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         
                         if ($kk[0])
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= getMimeTypeAsHTMLParam($kk[1]) . "\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . getMimeTypeAsHTMLParam($kk[1]);
                         }
                         else
                         {
                              if ($kk[2] > 0)
                              {
                                   $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . getMimeTypeAsHTMLParam($kk[1]);
                              }
                         }
                    }
               }

               if (startsWith(strtolower($cc[$dd]), "source ")) 
               {
                    $ee = substr($cc[$dd], 0, strpos($cc[$dd], ">"));
                    $tmp2 = $ee;
                    $tmp3 = substr($cc[$dd], strpos($cc[$dd], ">"));
                    
                    if (strpos(strtolower($ee), " src=\"") > 0)
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " src=\"") + 6);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "\""));
                         $tmp3 = substr($tmp2, strpos($tmp2, "\"")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         if ($kk[0])
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= getMimeTypeFromHtmlParam($tmp3) . "\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=" . getMimeTypeFromHtmlParam($tmp3);
                         }
                         else 
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=" . getMimeTypeFromHtmlParam($tmp3);
                         }
                    }
                    else if (strpos(strtolower($ee), " src='") > 0)
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " src='") + 6);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "'"));
                         $tmp3 = substr($tmp2, strpos($tmp2, "'")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         
                         if ($kk[0])
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= getMimeTypeFromHtmlParam($tmp3) . "\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=" . getMimeTypeFromHtmlParam($tmp3);
                         }
                         else
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=" . getMimeTypeFromHtmlParam($tmp3);
                         }
                    }
                    else if (strpos(strtolower($ee), " src=") > 0) 
                    {
                         $ee = substr($ee, 0, strpos(strtolower($ee), " src=") +5);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         
                         if (strpos($ee, " ") >0)                                        
                         {
                              $ee = substr($ee, 0, strpos($ee, " "));
                              $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                              $tmp2 = $ee;
                         } 
                         else 
                         {
                              if (strpos($ee, " ") === true)
                              {
                                   $ee = substr($ee, 1);
                                   if (strpos($ee, " ") >0)
                                   {
                                        $ee = substr($ee, 0, strpos($ee, " "));
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                                   else 
                                   {
                                        $ee = substr($ee, 0, strlen($ee) - 1);
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                              } 
                              else 
                              {
                                   $ee = substr($ee, 0, strlen($ee) - 1);
                                   $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                   $tmp2 = $ee;
                              }
                         }

                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         
                         if ($kk[0])
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= getMimeTypeFromHtmlParam($tmp3) . "\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=" . getMimeTypeFromHtmlParam($tmp3);
                         }
                         else
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=" . getMimeTypeFromHtmlParam($tmp3);
                         }
                    }

                    if ($tmp1 . $tmp2 . $tmp3 === $tmp)
                    {}
                    else
                    {
                         $cc[$dd] = $tmp1 . $tmp2 . $tmp3;
                    }

                    $tmp1 = "";
                    $tmp2 = "";
                    $tmp3 = $cc[$dd];

                    $ee = substr($cc[$dd], 0, strpos($cc[$dd], ">"));
                    $tmp2 = $ee;
                    $tmp3 = substr($cc[$dd], strpos($cc[$dd], ">"));
                    $tmp1 = "";

                    if (strpos(strtolower($ee)," srcset=\"")>0)
                    {
                         $ee = substr($ee, strpos(strtolower($ee)," srcset=\"") + 9);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "\""));
                         $tmp3 = substr($tmp2, strpos($tmp2, "\"")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist2($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port, $loadFilePathFull, $fileid);
                         $urls = $kk[0];
                         $tmp2 = $kk[1];
                         $index = $kk[2];
                         $xurls = $kk[3];
                    }
                    else if (strpos(strtolower($ee), " setset='") > 0)
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " srcset='") + 9);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "'"));
                         $tmp3 = substr($tmp2, strpos($tmp2, "'")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist2($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port, $loadFilePathFull, $fileid);
                         $urls = $kk[0];
                         $tmp2 = $kk[1];
                         $index = $kk[2];
                         $xurls = $kk[3];
                    }
                    else if (strpos(strtolower($ee), " setset=") > 0) 
                    {
                         $ee = substr($ee, 0, strpos(strtolower($ee), " srcset=") + 8);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         if (strpos($ee, " ") > 0)                                        
                         {
                              $ee = substr($ee, 0, strpos($ee, " "));
                              $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                              $tmp2 = $ee;
                         }
                         else 
                         {
                              if (strpos($ee, " ") === true)
                              {
                                   $ee = substr($ee, 1);
                                   if (strpos($ee, " ") > 0)
                                   {
                                        $ee = substr($ee, 0, strpos($ee, " "));
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                                   else 
                                   {
                                        $ee = substr($ee, 0, strlen($ee) - 1);
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                              } 
                              else
                              {
                                   $ee = substr($ee, 0, strlen($ee) - 1);
                                   $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                   $tmp2 = $ee;
                              }
                         }

                         $kk= filelist2($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port, $loadFilePathFull, $fileid);
                         $urls = $kk[0];
                         $tmp2 = $kk[1];
                         $index = $kk[2];
                         $xurls = $kk[3];
                    }
               }

               if (startsWith(strtolower($cc[$dd]), "track ")) 
               {
                    $ee = substr($cc[$dd], 0, strpos($cc[$dd], ">"));
                    $tmp2 = $ee;
                    $tmp3 = substr($cc[$dd], strpos($cc[$dd], ">"));
                    
                    if (strpos(strtolower($ee), " src=\"") > 0) 
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " src=\"") + 6);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "\""));
                         $tmp3 = substr($tmp2, strpos($tmp2,"\"")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         if ($kk[0]) 
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= getMimeTypeFromHtmlParam($tmp3) . "\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=" . getMimeTypeFromHtmlParam($tmp3);
                         } 
                         else 
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=" . getMimeTypeFromHtmlParam($tmp3);
                         }
                    }
                    else if (strpos(strtolower($ee), " src='") > 0)
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " src='") + 6);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee; 
                         $ee = substr($ee, 0, strpos($ee, "'"));
                         $tmp3 = substr($tmp2, strpos($tmp2, "'")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         
                         if ($kk[0]) 
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= getMimeTypeFromHtmlParam($tmp3) . "\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=" . getMimeTypeFromHtmlParam($tmp3);
                         } 
                         else
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=" . getMimeTypeFromHtmlParam($tmp3);
                         }
                    }
                    else if (strpos(strtolower($ee), " src=") > 0)
                    {
                         $ee = substr($ee, 0, strpos(strtolower($ee), " src=") + 5);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         if (strpos($ee ," ") > 0)                                        
                         {
                              $ee = substr($ee, 0, strpos($ee, " "));
                              $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                              $tmp2 = $ee;
                         }
                         else 
                         {
                              if (strpos($ee, " ") === true) 
                              {
                                   $ee = substr($ee, 1);
                                   if (strpos($ee, " ") > 0) 
                                   {
                                        $ee = substr($ee, 0, strpos($ee, " "));
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                                   else 
                                   {
                                        $ee = substr($ee, 0, strlen($ee) - 1);
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                              }
                              else
                              {
                                   $ee = substr($ee, 0, strlen($ee) - 1);
                                   $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                   $tmp2 = $ee;
                              }
                         }

                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         if ($kk[0]) 
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= getMimeTypeFromHtmlParam($tmp3) . "\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=" . getMimeTypeFromHtmlParam($tmp3);
                         } 
                         else 
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=" . getMimeTypeFromHtmlParam($tmp3);
                         }
                    }
               }

               if ($istwitter) 
               {
                    if (startsWith(strtolower($cc[$dd]), "meta  property=\"og:video:secure_url\""))
                    {
                         $ee = substr($cc[$dd], 0, strpos($cc[$dd], ">"));
                         $tmp2 = $ee;
                         $tmp3 = substr($cc[$dd], strpos($cc[$dd], ">"));
                         echo "video found";
                         if (strpos(strtolower($ee), " content=\"") > 0)
                         {
                              $ee = substr($ee, strpos(strtolower($ee), " content=\"") + 10);$tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                              $tmp2 = $ee;
                              $ee = substr($ee, 0, strpos($ee, "\""));
                              $tmp3 = substr($tmp2, strpos($tmp2, "\"")) . $tmp3;
                              $tmp2 = $ee;
                              $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                              if ($kk[0])
                              {
                                   $urls .= $kk[1];
                                   $urls2[$kk[3]] = 1 + count($urls2);
                                   $xurls .= "vid\n";
                                   $index++;
                                   $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=vid";
                              } 
                              else 
                              {
                                   $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=vid";
                              }
                         }
                         else if (strpos(strtolower($ee), " content='") > 0)  
                         {
                              $ee = substr($ee, strpos(strtolower($ee), " content='") + 10);
                              $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                              $tmp2 = $ee;
                              $ee = substr($ee, 0, strpos($ee, "'"));
                              $tmp3 = substr($tmp2, strpos($tmp2, "'")) . $tmp3;
                              $tmp2 = $ee;
                              $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                              if ($kk[0]) 
                              {
                                   $urls .= $kk[1];
                                   $urls2[$kk[3]] = 1 + count($urls2);
                                   $xurls .= "vid\n";
                                   $index++;
                                   $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=vid";
                              } 
                              else 
                              {
                                   $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=vid";
                              }
                         }
                         else if (strpos(strtolower($ee), " content=") > 0)
                         {
                              $ee = substr($ee, 0, strpos(strtolower($ee), " content=") + 9);
                              $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                              $tmp2 = $ee;
                              if (strpos($ee ," ") > 0)                                        
                              {
                                   $ee = substr($ee, 0, strpos($ee, " "));
                                   $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                   $tmp2 = $ee;
                              } 
                              else 
                              {
                                   if (strpos($ee, " ") === true)
                                   {
                                        $ee = substr($ee, 1);
                                        if (strpos($ee, " ") > 0)
                                        {
                                             $ee = substr($ee, 0, strpos($ee, " "));
                                             $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;$tmp2 = $ee;
                                        }
                                        else 
                                        {
                                             $ee = substr($ee, 0, strlen($ee) - 1);
                                             $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;$tmp2 = $ee;
                                        }
                                   } 
                                   else 
                                   {
                                        $ee = substr($ee, 0, strlen($ee) -1);
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                              }

                              $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                              if ($kk[0]) 
                              {
                                   $urls .= $kk[1];
                                   $urls2[$kk[3] ]= 1 + count($urls2);
                                   $xurls .= "vid\n";
                                   $index++;
                                   $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=vid";
                              } 
                              else
                              {
                                   $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=vid";
                              }
                         }
                         $video = $kk[1];
                         $videocount = $kk[2] + 1;
                    }
               }

               if ($istwitter) 
               {
                    if ((startsWith($cc[$dd], "div")) && strpos($cc[$dd], "PlayableMedia-player") > 0) 
                    {
                         $cc[$dd] = "div><video width=400 controls><source src=\"" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $videocount . "&type=vid" . "\" type=\"video/mp4\"></video>";
                         $tmp1 = "";
                         $tmp2 = "";
                         $tmp3 = $cc[$dd];
                         $tmp = $cc[$dd];
                    }
               }

               global $codes;

               if ($istwitter && startsWith($url, "mobile.twitter.com")) 
               {
                    if (startsWith($cc[$dd], "div aria-label=\"Loading")) 
                    {
                         $code .= "<div class=\"css-1dbjc4n r-1pi2tsx r-13qz1uu r-417010\" aria-hidden=\"false\" style=\"min-height: 806px;\">";

                         $code .= "<div aria-label=\"Skip to recommended content\" role=\"button\" data-focusable=\"true\" tabindex=\"0\" class=\"css-18t94o4 css-1dbjc4n r-1niwhzg r-sdzlij r-1phboty r-4iw3lz r-1xk2f4g r-109y4c4 r-1vuscfd r-1dhvaqw r-1udh08x r-wwvuq4 r-1fneopy r-u8s1d r-o7ynqc r-6416eg r-lrvibr r-92ng3h\" style=\"width: 1px;height:1px;clip: rect(1px, 1px, 1px, 1px);transition-property: background-color, box-shadow;min-width: calc(62.79px);min-height: 39px;background-color: rgba(0, 0, 0, 0);transition-duration: 0.2s;\">";

                         $code .= "<div dir=\"auto\" class=\"css-901oao r-1awozwy r-1re7ezh r-6koalj r-18u37iz r-16y2uox r-1qd0xha r-a023e6 r-vw2c0b r-1777fci r-eljoum r-dnmrzs r-bcqeeo r-q4m81j r-qvutc0\" style=\"color: rgb(101, 119, 134);line-height: 1;font-weight: bold;font-size: 15px;font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;;overflow-wrap: break-word;min-width: 0px;-webkit-box-align: center;align-items: center;-webkit-box-pack: center;justify-content: center;-webkit-box-flex: 1;flex-grow: 1;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;display: flex;cursor: pointer;pointer-events: auto;\">";

                         $code .= "<span class=\"css-901oao css-16my406 css-bfa6kz r-1qd0xha r-ad9z0x r-bcqeeo r-qvutc0\" style=\"font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;max-width: 100%;overflow-x: hidden;overflow-y: hidden;text-overflow: ellipsis;white-space: nowrap;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;cursor: pointer;pointer-events: auto;\"></span></div></div>";

                         $code .= "<div aria-label=\"Skip to secondary content\" role=\"button\" data-focusable=\"true\" tabindex=\"0\" class=\"css-18t94o4 css-1dbjc4n r-1niwhzg r-sdzlij r-1phboty r-4iw3lz r-1xk2f4g r-109y4c4 r-1vuscfd r-1dhvaqw r-1udh08x r-wwvuq4 r-1fneopy r-u8s1d r-o7ynqc r-6416eg r-lrvibr r-92ng3h\" style=\"width: 1px;height:1px;clip: rect(1px, 1px, 1px, 1px);transition-property: background-color, box-shadow;min-width: calc(62.79px);min-height: 39px;background-color: rgba(0, 0, 0, 0);transition-duration: 0.2s;\">";

                         $code .= "<div dir=\"auto\" class=\"css-901oao r-1awozwy r-1re7ezh r-6koalj r-18u37iz r-16y2uox r-1qd0xha r-a023e6 r-vw2c0b r-1777fci r-eljoum r-dnmrzs r-bcqeeo r-q4m81j r-qvutc0\" style=\"color: rgb(101, 119, 134);line-height: 1;font-weight: bold;font-size: 15px;font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;-webkit-box-align: center;align-items: center;-webkit-box-pack: center;justify-content: center;-webkit-box-flex: 1;flex-grow: 1;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;display: flex;cursor: pointer;pointer-events: auto;>";

                         $code .= "<span class=\"css-901oao css-16my406 css-bfa6kz r-1qd0xha r-ad9z0x r-bcqeeo r-qvutc0\" style=\"font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;max-width: 100%;overflow-x: hidden;overflow-y: hidden;text-overflow: ellipsis;white-space: nowrap;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;cursor: pointer;pointer-events: auto;\"></span></div></div>";

                         $code .= "<header role=\"banner\" class=\"css-1dbjc4n r-1g40b8q\" style=\"z-index: 3;font-size: 15px;\">";

                         $code .= "<div class=\"css-1dbjc4n\" style=\"height: calc(106px);\"></div>";

                         $code .= "<div class=\"css-1dbjc4n r-14lw9ot r-uvzvve r-rull8r r-qklmqi r-1d2f490 r-1xcajam r-zchlnj r-ipm5af r-1siec45 r-o7ynqc r-axxi2z r-136ojw6\" style=\"z-index: 2;transition-property: -webkit-transform, transform;transition-duration: 0.2s;border-bottom-width: 1px;border-bottom-style: solid;border-bottom-color: rgb(204, 214, 221);\">";
                         
                         $code .= "<div class=\"css-1dbjc4n r-1jgb5lz r-1ye8kvj r-13qz1uu\" style=\"max-width: 600px;width: 100%;margin-left: auto;margin-right: auto;\">";
                         
                         $code .= "<div class=\"css-1dbjc4n r-14lw9ot r-1w6e6rj r-hx83d4 r-1wtj0ep r-utggzx r-rjfia r-136ojw6\" style=\"height: calc(106px);flex-wrap: wrap;-webkit-box-pack: justify;justify-content: space-between;z-index: 2;padding-bottom: 0px;padding-top: 0px;padding-left: 10px;padding-right: 10px;\">";
                         
                         $code .= "<div class=\"css-1dbjc4n r-18u37iz r-16y2uox r-1h3ijdo r-184en5c\" data-testid=\"leftContainer\" style=\"z-index: 1;height: 53px;-webkit-box-flex: 1;flex-grow: 1;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;\">";
                         
                         $code .= "<div class=\"css-1dbjc4n r-1777fci r-1mf7evn\" style=\"margin-right:20px;-webkit-box-pack: center;justify-content: center;\">";
                         
                         $code .= "<h1 role=\"heading\" class=\"css-4rbku5 css-1dbjc4n r-1awozwy r-1pz39u2 r-1loqt21 r-6koalj r-16y2uox r-1777fci r-18qmn74\" style=\"min-width: 30px;cursor: pointer;align-self: stretch;-webkit-box-align: center;align-items: center;-webkit-box-pack: center;justify-content: center;-webkit-box-flex: 1;flex-grow: 1;display: flex;\">";
                         
                         $code .= "<a href=\"/\" aria-label=\"Twitter\" role=\"button\" data-focusable=\"true\" class=\"css-4rbku5 css-18t94o4 css-1dbjc4n r-1niwhzg r-42olwf r-sdzlij r-1phboty r-rs99b7 r-1loqt21 r-1w2pmg r-1vuscfd r-53xb7h r-mk0yit r-o7ynqc r-6416eg r-lrvibr\" style=\"margin-left: calc(0px + ((-1 * (41px - 1.75rem)) / 2));transition-property: background-color, box-shadow;min-width: 39px;min-height: 39px;background-color: rgba(0, 0, 0, 0);height: 0px;cursor: pointer;transition-duration: 0.2s;\">";
                         
                         $code .= "<div dir=\"auto\" class=\"css-901oao r-1awozwy r-13gxpu9 r-6koalj r-18u37iz r-16y2uox r-1qd0xha r-a023e6 r-vw2c0b r-1777fci r-eljoum r-dnmrzs r-bcqeeo r-q4m81j r-qvutc0\" style=\"line-height: 1;font-weight: bold;font-size: 15px;font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;-webkit-box-align: center;align-items: center;-webkit-box-pack: center;justify-content: center;-webkit-box-flex: 1;flex-grow: 1;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;display: flex;cursor: pointer;\">";
                         
                         $code .= "<svg viewBox=\"0 0 24 24\" class=\"r-13gxpu9 r-4qtqp9 r-yyyyoo r-16y2uox r-1q142lx r-lwhw9o r-dnmrzs r-bnwqim r-1plcrui r-lrvibr\"  style=\"height: 1.75rem;flex-shrink: 0;-webkit-box-flex: 1;flex-grow: 1;line-height: 1;font-weight: bold;font-size: 15px;font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;text-align: center;\"><g>";
                         
                         $code .= "<path d=\"M23.643 4.937c-.835.37-1.732.62-2.675.733.962-.576 1.7-1.49 2.048-2.578-.9.534-1.897.922-2.958 1.13-.85-.904-2.06-1.47-3.4-1.47-2.572 0-4.658 2.086-4.658 4.66 0 .364.042.718.12 1.06-3.873-.195-7.304-2.05-9.602-4.868-.4.69-.63 1.49-.63 2.342 0 1.616.823 3.043 2.072 3.878-.764-.025-1.482-.234-2.11-.583v.06c0 2.257 1.605 4.14 3.737 4.568-.392.106-.803.162-1.227.162-.3 0-.593-.028-.877-.082.593 1.85 2.313 3.198 4.352 3.234-1.595 1.25-3.604 1.995-5.786 1.995-.376 0-.747-.022-1.112-.065 2.062 1.323 4.51 2.093 7.14 2.093 8.57 0 13.255-7.098 13.255-13.254 0-.2-.005-.402-.014-.602.91-.658 1.7-1.477 2.323-2.41z\"></path></g></svg>";
                         
                         $code .= "<span class=\"css-901oao css-16my406 css-bfa6kz r-1qd0xha r-ad9z0x r-bcqeeo r-qvutc0\"></span></div></a></h1></div>";

                         $code .= "<div class=\"css-1dbjc4n r-1oszu61 r-18u37iz r-16y2uox r-1wbh5a2 r-zg41ew\" style=\"flex-shrink: 1;-webkit-box-align: stretch;align-items: stretch;-webkit-box-flex: 1;flex-grow: 1;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;margin-bottom: 10px;margin-top: 10px;\">";
                         
                         $code .= "<div class=\"css-1dbjc4n r-1oszu61 r-1iusvr4 r-18u37iz r-16y2uox\" style=\"flex-basis: 0px;-webkit-box-align: stretch;align-items: stretch;-webkit-box-flex: 1;flex-grow: 1;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;\">";
                         
                         $code .= "<div class=\"css-1dbjc4n r-13awgt0 r-eqz5dr r-bnwqim r-8fdsdq\" style=\"z-index: 4;-webkit-box-direction: normal;-webkit-box-orient: vertical;flex-direction: column;\">";
                         
                         $code .= "<div class=\"r-1oszu61 r-1phboty r-1yadl64 r-deolkf r-6koalj r-13awgt0 r-eqz5dr r-crgep1 r-ifefl9 r-bcqeeo r-t60dpp r-bnwqim r-417010\" style=\"min-height: 0px;box-sizing: border-box;-webkit-box-direction: normal;-webkit-box-orient: vertical;flex-direction: column;min-width: 0px;z-index: 0;padding-bottom: 0px;padding-left: 0px;padding-right: 0px;padding-top: 0px;margin-bottom: 0px;margin-left: 0px;margin-right: 0px;margin-top: 0px;border-bottom-width: 0px;border-left-width: 0px;border-right-width: 0px;border-top-width: 0px;border-bottom-style: solid;border-left-style: solid;border-right-style: solid;border-top-style: solid;display: flex;\">";
                         
                         $code .= "<form action=\"#\" aria-label=\"Search Twitter\" role=\"search\" class=\"r-1oszu61 r-1phboty r-1yadl64 r-deolkf r-6koalj r-13awgt0 r-eqz5dr r-crgep1 r-ifefl9 r-bcqeeo r-t60dpp r-bnwqim r-417010\" style=\"min-height: 0px;box-sizing: border-box;-webkit-box-direction: normal;-webkit-box-orient: vertical;flex-direction: column;min-width: 0px;z-index: 0;padding-bottom: 0px;padding-left: 0px;padding-right: 0px;padding-top: 0px;margin-bottom: 0px;margin-left: 0px;margin-right: 0px;margin-top: 0px;border-bottom-width: 0px;border-left-width: 0px;border-right-width: 0px;border-top-width: 0px;border-bottom-style: solid;border-left-style: solid;border-right-style: solid;border-top-style: solid;display: flex;\">";
                         
                         $code .= "<div class=\"css-1dbjc4n r-1wbh5a2\" style=\"flex-shrink: 1;\">";
                         
                         $code .= "<div class=\"css-1dbjc4n r-e84r5y r-42olwf r-sdzlij r-1phboty r-rs99b7 r-eqz5dr r-16y2uox r-1wbh5a2 r-1777fci\" style=\"background-color: rgb(230, 236, 240);-webkit-box-direction: normal;-webkit-box-orient: vertical;flex-direction: column;flex-shrink: 1;-webkit-box-pack: center;justify-content: center;-webkit-box-flex: 1;flex-grow: 1;border-bottom-color: rgba(0, 0, 0, 0);border-left-color: rgba(0, 0, 0, 0);border-right-color: rgba(0, 0, 0, 0);border-top-color: rgba(0, 0, 0, 0);border-bottom-width: 1px;border-left-width: 1px;border-right-width: 1px;border-top-width: 1px;border-bottom-style: solid;border-left-style: solid;border-right-style: solid;border-top-style: solid;border-bottom-left-radius: 9999px;border-bottom-right-radius: 9999px;border-top-left-radius: 9999px;border-top-right-radius: 9999px;\">";
                         
                         $code .= "<div class=\"css-1dbjc4n r-18u37iz\" style=\"-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;\">";
                         
                         $code .= "<div class=\"css-1dbjc4n r-6koalj r-1777fci\" style=\"-webkit-box-pack: center;justify-content: center;display: flex;\">";

                         $code .= "<svg viewBox=\"0 0 24 24\" class=\"r-1re7ezh r-4qtqp9 r-yyyyoo r-1xvli5t r-dnmrzs r-18qmn74 r-1hfyk0a r-bnwqim r-1plcrui r-lrvibr\" style=\"color: rgb(101, 119, 134);padding-left: 10px;min-width: 30px;\"><g><path d=\"M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z\"></path></g></svg>";

                         $code .= "</div><div dir=\"auto\" class=\"css-901oao r-hkyrab r-6koalj r-16y2uox r-1qd0xha r-a023e6 r-16dba41 r-ad9z0x r-bcqeeo r-qvutc0\" style=\"font-weight: 400;color: rgb(20, 23, 26);font-size: 15px;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;-webkit-box-flex: 1;flex-grow: 1;display: flex;\">";
                         
                         $code .= "<input aria-activedescendant=\"typeaheadFocus-0.02213977317671012\" aria-autocomplete=\"list\" aria-label=\"Search query\" aria-owns=\"typeaheadDropdown-1\" autocapitalize=\"sentences\" autocomplete=\"off\" autocorrect=\"on\" placeholder=\"Search Twitter\" role=\"combobox\" spellcheck=\"true\" enterkeyhint=\"search\" type=\"text\" dir=\"auto\" data-focusable=\"true\" class=\"r-30o5oe r-1niwhzg r-17gur6a r-1yadl64 r-deolkf r-homxoj r-poiln3 r-7cikom r-1ny4l3l r-1sp51qo r-1lrr6ok r-1dz5y72 r-1ttztb7 r-13qz1uu\" data-testid=\"SearchBox_Search_Input\" value=\"\"";
                         
                         $code .= "style=\"text-align: inherit;outline-style: none;font-size: inherit;font-family: inherit;color: inherit;resize: none;-webkit-appearance: none;box-sizing: border-box;background-color: rgba(0, 0, 0, 0);width: 100%;padding-bottom: 10px;padding-left: 10px;padding-right: 10px;padding-top: 10px;border-bottom-left-radius: 0px;border-bottom-right-radius: 0px;border-top-left-radius: 0px;border-top-right-radius: 0px;border-bottom-width: 0px;border-left-width: 0px;border-right-width: 0px;border-top-width: 0px;\">";

                         $code .= "</div><div class=\"css-1dbjc4n r-6koalj r-1777fci\ style=\"-webkit-box-pack: center;justify-content: center;display: flex;\"></div></div></div></div>";
                         
                         $code .= "<div class=\"css-1dbjc4n r-13awgt0 r-bnwqim\"></div></form></div></div></div></div>";
                         
                         $code .= "<div class=\"css-1dbjc4n r-1777fci r-1hfyk0a\" stye=\"padding-left: 10px;-webkit-box-pack: center;justify-content: center;\">";
                         
                         $code .= "<div role=\"button\" data-focusable=\"true\" tabindex=\"0\" class=\"css-18t94o4 css-1dbjc4n r-1niwhzg r-42olwf r-sdzlij r-1phboty r-rs99b7 r-1w2pmg r-1vuscfd r-53xb7h r-mk0yit r-o7ynqc r-6416eg r-lrvibr\" style=\"margin-right: calc(5px + ((-1 * (41px - 1.5em)) / 2));transition-property: background-color, box-shadow;min-width: 39px;min-height: 39px;background-color: rgba(0, 0, 0, 0);height: 0px;transition-duration: 0.2s;padding-left: 0px;padding-right: 0px;border-bottom-color: rgba(0, 0, 0, 0);border-left-color: rgba(0, 0, 0, 0);border-right-color: rgba(0, 0, 0, 0);border-top-color: rgba(0, 0, 0, 0);border-bottom-width: 1px;border-left-width: 1px;border-right-width: 1px;border-top-width: 1px;border-bottom-style: solid;border-left-style: solid;border-right-style: solid;border-top-style: solid;border-bottom-left-radius: 9999px;border-bottom-right-radius: 9999px;border-top-left-radius: 9999px;border-top-right-radius: 9999px;\">";
                         
                         $code .= "<div dir=\"auto\" class=\"css-901oao r-1awozwy r-13gxpu9 r-6koalj r-18u37iz r-16y2uox r-1qd0xha r-a023e6 r-vw2c0b r-1777fci r-eljoum r-dnmrzs r-bcqeeo r-q4m81j r-qvutc0\" style=\"line-height: 1;font-weight: bold;font-size: 15px;font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;-webkit-box-align: center;align-items: center;-webkit-box-pack: center;justify-content: center;-webkit-box-flex: 1;flex-grow: 1;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;text-align: center;color: rgba(29,161,242,1.00);max-width: 100%;display: flex;\">";
                         
                         $code .= "<svg viewBox=\"0 0 24 24\" class=\"r-13gxpu9 r-4qtqp9 r-yyyyoo r-1q142lx r-50lct3 r-dnmrzs r-bnwqim r-1plcrui r-lrvibr\" style=\"height: 1.5em;flex-shrink: 0;line-height: 1;font-weight: bold;overflow-wrap: break-word;-webkit-box-direction: normal;\"><g><path d=\"M19.39 14.882c-1.58 0-2.862-1.283-2.862-2.86s1.283-2.862 2.86-2.862 2.862 1.283 2.862 2.86-1.284 2.862-2.86 2.862zm0-4.223c-.75 0-1.362.61-1.362 1.36s.61 1.36 1.36 1.36 1.362-.61 1.362-1.36-.61-1.36-1.36-1.36zM12 14.882c-1.578 0-2.86-1.283-2.86-2.86S10.42 9.158 12 9.158s2.86 1.282 2.86 2.86S13.578 14.88 12 14.88zm0-4.223c-.75 0-1.36.61-1.36 1.36s.61 1.362 1.36 1.362 1.36-.61 1.36-1.36-.61-1.363-1.36-1.363zm-7.39 4.223c-1.577 0-2.86-1.283-2.86-2.86S3.034 9.16 4.61 9.16s2.862 1.283 2.862 2.86-1.283 2.862-2.86 2.862zm0-4.223c-.75 0-1.36.61-1.36 1.36s.61 1.36 1.36 1.36 1.362-.61 1.362-1.36-.61-1.36-1.36-1.36z\"></path></g></svg>";
                         
                         $code .= "<span class=\"css-901oao css-16my406 css-bfa6kz r-1qd0xha r-ad9z0x r-bcqeeo r-qvutc0\" style=\"font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;max-width: 100%;overflow-x: hidden;overflow-y: hidden;text-overflow: ellipsis;white-space: nowrap;\"></span></div></div></div></div>";

                         $code .= "<div class=\"css-1dbjc4n r-18u37iz r-16y2uox r-1h3ijdo\" data-testid=\"rightContainer\" style=\"height: 53px;-webkit-box-flex: 1;flex-grow: 1;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;\">";
                         
                         $code .= "<div class=\"css-1dbjc4n r-1awozwy r-1pz39u2 r-18u37iz r-16y2uox\" style=\"align-self: stretch;-webkit-box-align: center;align-items: center;-webkit-box-flex: 1;flex-grow: 1;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;\">";
                         
                         $code .= "<div class=\"css-1dbjc4n r-16y2uox\"  style=\"-webkit-box-flex: 1;flex-grow: 1;\">";
                         
                         $code .= "<a href=\"/login\" role=\"button\" data-focusable=\"true\" class=\"css-4rbku5 css-18t94o4 css-1dbjc4n r-1niwhzg r-p1n3y5 r-sdzlij r-1phboty r-rs99b7 r-1loqt21 r-1w2pmg r-1vsu8ta r-aj3cln r-1fneopy r-o7ynqc r-6416eg r-lrvibr\" data-testid=\"login\"  style=\"min-width: calc(48.3px);min-height: 30px;transition-property: background-color, box-shadow;background-color: rgba(0, 0, 0, 0);height: 0px;cursor: pointer;transition-duration: 0.2s;padding-left: 1em;padding-right: 1em;border-bottom-color: rgb(29, 161, 242);border-left-color: rgb(29, 161, 242);border-right-color: rgb(29, 161, 242);border-top-color: rgb(29, 161, 242);border-bottom-width: 1px;border-left-width: 1px;border-right-width: 1px;border-top-width: 1px;border-bottom-style: solid;border-left-style: solid;border-right-style: solid;border-top-style: solid;border-bottom-left-radius: 9999px;border-bottom-right-radius: 9999px;border-top-left-radius: 9999px;border-top-right-radius: 9999px;text-decoration: none;\">";
                         
                         $code .= "<div dir=\"auto\" class=\"css-901oao r-1awozwy r-13gxpu9 r-6koalj r-18u37iz r-16y2uox r-1qd0xha r-a023e6 r-vw2c0b r-1777fci r-eljoum r-dnmrzs r-bcqeeo r-q4m81j r-qvutc0\" style=\"line-height: 1;font-weight: bold;font-size: 15px;font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;-webkit-box-align: center;align-items: center;-webkit-box-pack: center;justify-content: center;-webkit-box-flex: 1;flex-grow: 1;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;display: flex;\">";
                         
                         $code .= "<span class=\"css-901oao css-16my406 css-bfa6kz r-1qd0xha r-ad9z0x r-bcqeeo r-qvutc0\" style=\"font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;max-width: 100%;overflow-x: hidden;overflow-y: hidden;text-overflow: ellipsis;white-space: nowrap;\">";
                         
                         $code .= "<span class=\"css-901oao css-16my406 r-1qd0xha r-ad9z0x r-bcqeeo r-qvutc0\" style=\"font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;\">Log in</span>";

                         $code .= "</span></div></a></div><div class=\"css-1dbjc4n r-16y2uox r-1n0xq6e\" style=\"margin-left: 10px;-webkit-box-flex: 1;flex-grow: 1;\">";

                         $code .= "<a href=\"/i/flow/signup\" role=\"button\" data-focusable=\"true\" class=\"css-4rbku5 css-18t94o4 css-1dbjc4n r-urgr8i r-42olwf r-sdzlij r-1phboty r-rs99b7 r-1loqt21 r-1w2pmg r-1vsu8ta r-aj3cln r-1fneopy r-o7ynqc r-6416eg r-lrvibr\" data-testid=\"signup\" style=\"background-color: rgb(29, 161, 242);min-width: calc(48.3px);min-height: 30px;transition-property: background-color, box-shadow;height: 0px;cursor: pointer;transition-duration: 0.2s;padding-left: 1em;padding-right: 1em;border-bottom-color: rgba(0, 0, 0, 0);border-left-color: rgba(0, 0, 0, 0);border-right-color: rgba(0, 0, 0, 0);border-top-color: rgba(0, 0, 0, 0);border-bottom-width: 1px;border-left-width: 1px;border-right-width: 1px;border-top-width: 1px;border-bottom-style: solid;border-left-style: solid;border-right-style: solid;border-top-style: solid;border-bottom-left-radius: 9999px;border-bottom-right-radius: 9999px;border-top-left-radius: 9999px;border-top-right-radius: 9999px;text-decoration: none;\">";
                         
                         $code .= "<div dir=\"auto\" class=\"css-901oao r-1awozwy r-jwli3a r-6koalj r-18u37iz r-16y2uox r-1qd0xha r-a023e6 r-vw2c0b r-1777fci r-eljoum r-dnmrzs r-bcqeeo r-q4m81j r-qvutc0\" style=\"color: rgb(255, 255, 255);line-height: 1;font-weight: bold;font-size: 15px;font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;-webkit-box-align: center;align-items: center;-webkit-box-pack: center;justify-content: center;-webkit-box-flex: 1;flex-grow: 1;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;display: flex;\">";
                         
                         $code .= "<span class=\"css-901oao css-16my406 css-bfa6kz r-1qd0xha r-ad9z0x r-bcqeeo r-qvutc0\" style=\"font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;max-width: 100%;overflow-x: hidden;overflow-y: hidden;text-overflow: ellipsis;white-space: nowrap;\">";
                         
                         $code .= "<span class=\"css-901oao css-16my406 r-1qd0xha r-ad9z0x r-bcqeeo r-qvutc0\" style=\"overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;\">Sign up</span></span></div></a></div></div></div></div></div></div></header>";

                         $code .= "<main role=\"main\" class=\"css-1dbjc4n r-16y2uox r-1wbh5a2\" style=\"flex-shrink: 1;-webkit-box-flex: 1;flex-grow: 1;\">";
                         
                         $code .= "<div class=\"css-1dbjc4n r-150rngu r-16y2uox r-1wbh5a2\" style=\"flex-shrink: 1;-webkit-box-flex: 1;flex-grow: 1;\">";
                         
                         $code .= "<div class=\"css-1dbjc4n r-aqfbo4 r-16y2uox\" style=\"backface-visibility: hidden;-webkit-box-flex: 1;flex-grow: 1;\">";

                         $code .= "<div class=\"css-1dbjc4n r-1oszu61 r-1niwhzg r-18u37iz r-16y2uox r-1jgb5lz r-2llsf r-13qz1uu\" style=\"min-height: 100%;-webkit-box-align: stretch;align-items: stretch;background-color: rgba(0, 0, 0, 0);-webkit-box-flex: 1;flex-grow: 1;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;width: 100%;margin-left: auto;margin-right: auto;\">";
                         
                         $code .= "<div class=\"css-1dbjc4n r-14lw9ot r-1tlfku8 r-1ljd8xs r-13l2t4g r-1phboty r-1jgb5lz r-1ye8kvj r-13qz1uu r-184en5c\" data-testid=\"primaryColumn\" style=\"border-right-width: 1px;border-left-width: 1px;z-index: 1;max-width: 600px;width: 100%;margin-left: auto;margin-right: auto;border-bottom-color: rgb(230, 236, 240);border-left-color: rgb(230, 236, 240);border-right-color: rgb(230, 236, 240);border-top-color: rgb(230, 236, 240);border-bottom-style: solid;border-left-style: solid;border-right-style: solid;border-top-style: solid;\">";
                         
                         $code .= "<div class=\"css-1dbjc4n\">";
                         
                         $code .= "<div class=\"css-1dbjc4n r-6337vo\" style=\"padding-bottom: calc(99px);\">";
                         
                         $code .= "<div class=\"css-1dbjc4n\">";
                         
                         $code .= "<section aria-labelledby=\"accessible-list-0\" role=\"region\" class=\"css-1dbjc4n\">";
                         $code .= "<h1 aria-level=\"1\" dir=\"auto\" role=\"heading\" class=\"css-4rbku5 css-901oao r-4iw3lz r-1xk2f4g r-109y4c4 r-1udh08x r-wwvuq4 r-u8s1d r-92ng3h\" id=\"accessible-list-0\" style=\"width: 1px;height: 1px;clip: rect(1px, 1px, 1px, 1px);padding-bottom: 0px;padding-left: 0px;padding-right: 0px;padding-top: 0px;overflow-x: hidden;overflow-y: hidden;border-bottom-width: 0px;border-left-width: 0px;border-right-width: 0px;border-top-width: 0px;\">Conversation</h1>";
                         
                         $code .= "<div aria-label=\"Timeline: Conversation\" class=\"css-1dbjc4n\">";
                         
                         $code .= "<div style=\"padding-bottom: 0px;\" style=\"padding-bottom: 0px;div {display: block;}\">";
                         
                         $code .= "<div style=\"padding-top: 0px; padding-bottom: 9200px;\">";
                         
                         $code .= "<div><div class=\"css-1dbjc4n r-my5ep6 r-qklmqi r-1adg3ll\" style=\"border-bottom-color: rgb(230, 236, 240);border-bottom-width: 1px;\">";
                         
                         $code .= "<article aria-haspopup=\"false\" role=\"article\" data-focusable=\"true\" tabindex=\"0\" class=\"css-1dbjc4n r-1udh08x\" style=\"overflow-x: hidden;overflow-y: hidden;\">";
                         
                         $code .= "<div class=\"css-1dbjc4n r-1j3t67a\" style=\"padding-left: 15px;padding-right: 15px;\">";
                         
                         $code .= "<div class=\"css-1dbjc4n r-m611by\" style=\"padding-top: 10px;\">";
                         
                         $code .= "</div><div class=\"css-1dbjc4n r-18u37iz r-thb0q2 r-1mi0q7o\" data-testid=\"tweet\" style=\"padding-bottom: 10px;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;margin-left: -5px;margin-right: -5px;\">";
                         
                         $code .= "<div class=\"css-1dbjc4n r-1awozwy r-1iusvr4 r-16y2uox r-5f2r5o r-1gmbmnb r-bcqeeo\" style=\"max-width: 49px;flex-basis: 0px;min-width: 0px;-webkit-box-align: center;align-items: center;-webkit-box-flex: 1;flex-grow: 1;margin-left: 5px;margin-right: 5px;\">";
                         
                         $code .= "<div class=\"css-1dbjc4n r-18kxxzh r-1wbh5a2 r-13qz1uu\" style=\"-webkit-box-flex: 0;flex-grow: 0;flex-shrink: 1;width: 100%;\">";
                         
                         $code .= "<div class=\"css-1dbjc4n r-1wbh5a2 r-dnmrzs\" style=\"flex-shrink: 1;\">";
                         
                         global $baseurl;
                         
                         $twitteruser = substr($baseurl, strpos($baseurl, ".com"));
                         $twitteruser = substr($twitteruser, strpos($twitteruser, "/") + 1);
                         $twitteruser = substr($twitteruser, 0, strpos($twitteruser, "/"));
                         global $codes;

                         $twitterpath = substr($baseurl, strpos($baseurl, "status") + 7);

                         $twitterimage = substr($codes[0], strpos($codes[0], "avatar js-action-profile-avatar"));
                         $twitterimage = substr($twitterimage, strpos($twitterimage, " src=") + 6);
                         $twitterimage = substr($twitterimage, 0, strpos($twitterimage, "\""));

                         $twittername = substr($codes[0], strpos($codes[0], "<title>"));
                         $twittername = substr($twittername, strpos($twittername, ">") + 1);
                         $twittername = substr($twittername, 0, strpos($twittername, " on Twitter:"));

                         $twittermess = substr($codes[0], strpos($codes[0], "TweetTextSize TweetTextSize--jumbo"));
                         $twittermess = substr($twittermess, strpos($twittermess, ">") + 1);
                         $twittermess = substr($twittermess, 0, strpos($twittermess, "<a "));

                         $twittertime = substr($codes[0], strpos($codes[0], "tweet-timestamp js-permalink js-nav js-tooltip"));
                         $twittertime = substr($twittertime, strpos($twittertime,"title=") + 6);
                         $twittertime = substr($twittertime, 0, strpos($twittertime, "data") - 2);

                         $twitterreplies = "";
                         $twitterlikes = "";
                         $twitterretweets = "";

                         $twitterlikes = substr($codes[0], strpos($codes[0], "js-stat-count js-stat-favorites stat-count"));
                         $twitterlikes = substr($twitterlikes, strpos($twitterlikes, "data-compact-localized-count"));
                         $twitterlikes = substr($twitterlikes, strpos($twitterlikes,"=") + 2);
                         $twitterlikes = substr($twitterlikes, 0, strpos($twitterlikes, "\""));

                         $twitterretweets = substr($codes[0], strpos($codes[0], "js-stat-count js-stat-retweets stat-count"));
                         $twitterretweets = substr($twitterretweets, strpos($twitterretweets, "data-compact-localized-count"));
                         $twitterretweets = substr($twitterretweets, strpos($twitterretweets, "=") + 2);
                         $twitterretweets = substr($twitterretweets, 0, strpos($twitterretweets, "\""));

                         //echo "<br>twitteruser:".($twitteruser);
                         //echo "<br>twitterpath:".($twitterpath);
                         //echo "<br>twitterimage:".($twitterimage);
                         //echo "<br>twittername:".($twittername);
                         //echo "<br>twittermess:".($twittermess);
                         //echo "<br>twittertime:".($twittertime);
                         //echo "<br>twitterreplies:".($twitterreplies);
                         //echo "<br>twitterlikes:".($twitterlikes);
                         //echo "<br>twitterretweets:".($twitterretweets);

                         $code .= "<a aria-haspopup=\"false\" href=\"" . $twitteruser . "\" role=\"link\" data-focusable=\"true\" class=\"css-4rbku5 css-18t94o4 css-1dbjc4n r-sdzlij r-1loqt21 r-1adg3ll r-1udh08x r-13qz1uu\" style=\"cursor: pointer;width: 100%;overflow-x: hidden;overflow-y: hidden;border-bottom-left-radius: 9999px;border-bottom-right-radius: 9999px;border-top-left-radius: 9999px;border-top-right-radius: 9999px;text-decoration: none;\">";

                         $code .= "<div class=\"css-1dbjc4n r-1adg3ll r-1udh08x\" style=\"overflow-x: hidden;overflow-y: hidden;\">";
                         
                         $code .= "<div class=\"r-1adg3ll r-13qz1uu\" style=\"padding-bottom: 100%;width: 100%;\"></div>";
                         
                         $code .= "<div class=\"r-1p0dtai r-1pi2tsx r-1d2f490 r-u8s1d r-ipm5af r-13qz1uu\" style=\"width: 100%;height:100%;\">";
                         
                         $code .= "<div class=\"css-1dbjc4n r-sdzlij r-1p0dtai r-1mlwlqe r-1d2f490 r-1udh08x r-u8s1d r-zchlnj r-ipm5af r-417010\" style=\"flex-basis: auto;z-index: 0;overflow-x: hidden;overflow-y: hidden;border-bottom-left-radius: 9999px;border-bottom-right-radius: 9999px;border-top-left-radius: 9999px;border-top-right-radius: 9999px;\">";
                         
                         $code .= "<div class=\"css-1dbjc4n r-1niwhzg r-vvn4in r-u6sd8q r-4gszlv r-1p0dtai r-1pi2tsx r-1d2f490 r-u8s1d r-zchlnj r-ipm5af r-13qz1uu r-1wyyakw\" style=\"background-image: url(&quot;" . $twitterimage . "&quot;);z-index: -1;background-size: cover;background-repeat: no-repeat;background-position: center center;background-color: rgba(0, 0, 0, 0);width: 100%;height: 100%;\"></div>";
                         
                         $code .= "<img alt=\"\" draggable=\"true\" src=\"" . $twitterimage . "\" class=\"css-9pa8cd\" style=\"bottom: 0px;height: 100%;left: 0px;position: absolute;right: 0px;top: 0px;width: 100%;z-index: -1;\"></div></div></div>";
                         
                         $code.="<div class=\"css-1dbjc4n r-1twgtwe r-sdzlij r-rs99b7 r-1p0dtai r-1mi75qu r-1d2f490 r-u8s1d r-zchlnj r-ipm5af\" style=\"box-shadow: rgba(0, 0, 0, 0.02) 0px 0px 2px inset;border-bottom-color: rgba(0, 0, 0, 0.04);border-left-color: rgba(0, 0, 0, 0.04);border-right-color: rgba(0, 0, 0, 0.04);border-top-color: rgba(0, 0, 0, 0.04);border-bottom-width: 1px;border-left-width: 1px;border-right-width: 1px;border-top-width: 1px;border-bottom-left-radius: 9999px;border-bottom-right-radius: 9999px;border-top-left-radius: 9999px;border-top-right-radius: 9999px;\"></div></a></div></div></div>";

                         $code.="<div class=\"css-1dbjc4n r-1iusvr4 r-46vdb2 r-1777fci r-5f2r5o r-bcqeeo\" style=\"-webkit-box-flex: 7;flex-grow: 7;flex-basis: 0px;min-width: 0px;-webkit-box-pack: center;justify-content: center;margin-left: 5px;margin-right: 5px;\">";
                         
                         $code.="<div class=\"css-1dbjc4n r-18u37iz r-1wtj0ep r-zl2h9q\" style=\"margin-bottom: 2px;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;-webkit-box-pack: justify;justify-content: space-between;\">";
                         
                         $code.="<div class=\"css-1dbjc4n r-1wbh5a2 r-dnmrzs\" style=\"flex-shrink: 1;\">";
                         
                         $code.="<a aria-haspopup=\"false\" href=\"/".$twitteruser."\" role=\"link\" data-focusable=\"true\" class=\"css-4rbku5 css-18t94o4 css-1dbjc4n r-1loqt21 r-1wbh5a2 r-dnmrzs r-1ny4l3l\" style=\"outline-style: none;flex-shrink: 1;cursor: pointer;text-decoration: none;\">";
                         
                         $code.="<div class=\"css-1dbjc4n r-1wbh5a2 r-dnmrzs r-1ny4l3l\" style=\"outline-style: none;flex-shrink: 1;\">";
                         
                         $code.="<div class=\"css-1dbjc4n r-18u37iz r-dnmrzs\" style=\"-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;\">";
                         
                         $code.="<div dir=\"auto\" class=\"css-901oao css-bfa6kz r-hkyrab r-1qd0xha r-a023e6 r-vw2c0b r-ad9z0x r-bcqeeo r-3s2u2q r-qvutc0\" style=\"white-space: nowrap;color: rgb(20, 23, 26);font-weight: bold;font-size: 15px;font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;max-width: 100%;overflow-x: hidden;overflow-y: hidden;text-overflow: ellipsis;\">";
                         
                         $code.="<span class=\"css-901oao css-16my406 r-1qd0xha r-ad9z0x r-bcqeeo r-qvutc0\" style=\"font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;\">";
                         
                         $code.="<span class=\"css-901oao css-16my406 r-1qd0xha r-ad9z0x r-bcqeeo r-qvutc0\" style=\"font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;\">".$twittername."</span></span></div>";
                         //verified
                         
                         $code.="<div dir=\"auto\" class=\"css-901oao r-hkyrab r-18u37iz r-1q142lx r-1qd0xha r-a023e6 r-16dba41 r-ad9z0x r-bcqeeo r-qvutc0\" style=\"font-weight: 400;color: rgb(20, 23, 26);flex-shrink: 0;font-size: 15px;font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;\">";
                         
                         $code.="<svg viewBox=\"0 0 24 24\" aria-label=\"Verified account\" class=\"r-13gxpu9 r-4qtqp9 r-yyyyoo r-1xvli5t r-9cviqr r-dnmrzs r-bnwqim r-1plcrui r-lrvibr\" style=\"margin-left: 2px;height: 1.25em;line-height: 1.3125;-webkit-box-direction: normal;\"><g>";
                         
                         $code.="<path d=\"M22.5 12.5c0-1.58-.875-2.95-2.148-3.6.154-.435.238-.905.238-1.4 0-2.21-1.71-3.998-3.818-3.998-.47 0-.92.084-1.336.25C14.818 2.415 13.51 1.5 12 1.5s-2.816.917-3.437 2.25c-.415-.165-.866-.25-1.336-.25-2.11 0-3.818 1.79-3.818 4 0 .494.083.964.237 1.4-1.272.65-2.147 2.018-2.147 3.6 0 1.495.782 2.798 1.942 3.486-.02.17-.032.34-.032.514 0 2.21 1.708 4 3.818 4 .47 0 .92-.086 1.335-.25.62 1.334 1.926 2.25 3.437 2.25 1.512 0 2.818-.916 3.437-2.25.415.163.865.248 1.336.248 2.11 0 3.818-1.79 3.818-4 0-.174-.012-.344-.033-.513 1.158-.687 1.943-1.99 1.943-3.484zm-6.616-3.334l-4.334 6.5c-.145.217-.382.334-.625.334-.143 0-.288-.04-.416-.126l-.115-.094-2.415-2.415c-.293-.293-.293-.768 0-1.06s.768-.294 1.06 0l1.77 1.767 3.825-5.74c.23-.345.696-.436 1.04-.207.346.23.44.696.21 1.04z\"></path></g></svg>";
                         //end verified
                         
                         $code.="</div></div><div class=\"css-1dbjc4n r-18u37iz r-1wbh5a2\" style=\"flex-shrink: 1;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;\">";
                         
                         $code.="<div dir=\"ltr\" class=\"css-901oao css-bfa6kz r-1re7ezh r-18u37iz r-1qd0xha r-a023e6 r-16dba41 r-ad9z0x r-bcqeeo r-qvutc0\" style=\"font-weight: 400;color: rgb(101, 119, 134);font-size: 15px;font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;max-width: 100%;overflow-x: hidden;overflow-y: hidden;text-overflow: ellipsis;white-space: nowrap;\">";
                         
                         $code.="<span class=\"css-901oao css-16my406 r-1qd0xha r-ad9z0x r-bcqeeo r-qvutc0\" style=\"font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;\">@".$twitteruser."</span></div></div></div></a></div>";
                         //begin dropdown caret
                         
                         $code.="<div class=\"css-1dbjc4n r-k200y r-18u37iz r-1h0z5md r-1joea0r\" style=\"margin-left: 20px;align-self: flex-start;-webkit-box-pack: start;justify-content: flex-start;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;\">";
                         
                         $code.="<div aria-haspopup=\"true\" aria-label=\"More\" role=\"button\" data-focusable=\"true\" tabindex=\"0\" class=\"css-18t94o4 css-1dbjc4n r-1777fci r-11cpok1 r-1ny4l3l r-bztko3 r-lrvibr\" data-testid=\"caret\" style=\"outline-style: none;-webkit-box-pack: center;justify-content: center;overflow-x: visible;overflow-y: visible;\">";
                         
                         $code.="<div dir=\"ltr\" class=\"css-901oao r-1awozwy r-1re7ezh r-6koalj r-1qd0xha r-a023e6 r-16dba41 r-1h0z5md r-ad9z0x r-bcqeeo r-o7ynqc r-clp7b1 r-3s2u2q r-qvutc0\" style=\"transition-property: color;-webkit-box-pack: start;justify-content: flex-start;white-space: nowrap;font-weight: 400;color: rgb(101, 119, 134);font-size: 15px;font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;-webkit-box-align: center;align-items: center;transition-duration: 0.2s;display: flex;\">";
                         
                         $code.="<div class=\"css-1dbjc4n r-xoduu5\" style=\"display: inline-flex;\">";
                         
                         $code.="<div class=\"css-1dbjc4n r-sdzlij r-1p0dtai r-xoduu5 r-1d2f490 r-podbf7 r-u8s1d r-zchlnj r-ipm5af r-o7ynqc r-6416eg\" style=\"transition-property: background-color, box-shadow;transition-duration: 0.2s;position: absolute;top: 0px;right: 0px;left: 0px;bottom: 0px;margin-bottom: -6px;margin-left: -6px;margin-right: -6px;margin-top: -6px;display: inline-flex;border-bottom-left-radius: 9999px;border-bottom-right-radius: 9999px;border-top-left-radius: 9999px;border-top-right-radius: 9999px;\"></div>";
                         
                         $code.="<svg viewBox=\"0 0 24 24\" class=\"r-4qtqp9 r-yyyyoo r-ip8ujx r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-27tl0q\" style=\"width: 1em;height: 2em;overflow: hidden;\"><g><path d=\"M20.207 8.147c-.39-.39-1.023-.39-1.414 0L12 14.94 5.207 8.147c-.39-.39-1.023-.39-1.414 0-.39.39-.39 1.023 0 1.414l7.5 7.5c.195.196.45.294.707.294s.512-.098.707-.293l7.5-7.5c.39-.39.39-1.022 0-1.413z\"></path></g></svg>";
                         //endcaret

                         $code.="</div></div></div></div></div></div></div>";
                         
                         $code.="<div lang=\"en\" dir=\"auto\" class=\"css-901oao r-hkyrab r-1qd0xha r-1blvdjr r-16dba41 r-ad9z0x r-bcqeeo r-19yat4t r-bnwqim r-qvutc0\" style=\"padding-bottom: 15px;font-size: 23px;font-weight: 400;color: rgb(20, 23, 26);font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;\">";
                         
                         $code.="<span class=\"css-901oao css-16my406 r-1qd0xha r-ad9z0x r-bcqeeo r-qvutc0\" style=\"font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;\">".$twittermess."</span></div>";
                         //beginvideo
                         
                         if (strpos($cc[$dd], "PlayableMedia-player") > 0) 
                         {
                              //startvideo
                              $code.="<div class=\"css-1dbjc4n r-117bsoe r-mvpalk r-156q2ks\" style=\"margin-top: 10px;margin-bottom: 20px;margin-left: 0px;margin-right: 0px;\">";

                              $code.="<div class=\"css-1dbjc4n r-1udh08x\" style=\"overflow-x: hidden;overflow-y: hidden;\">";

                              $code.="<div class=\"css-1dbjc4n r-9x6qib r-t23y2h r-1phboty r-rs99b7 r-1udh08x\" style=\"border-bottom-left-radius: 14px;border-bottom-right-radius: 14px;border-top-left-radius: 14px;border-top-right-radius: 14px;border-bottom-color: rgb(204, 214, 221);border-left-color: rgb(204, 214, 221);border-right-color: rgb(204, 214, 221);border-top-color: rgb(204, 214, 221);overflow-x: hidden;overflow-y: hidden;border-bottom-width: 1px;border-left-width: 1px;border-right-width: 1px;border-top-width: 1px;border-bottom-style: solid;border-left-style: solid;border-right-style: solid;border-top-style: solid;\">";

                              $code.="<div class=\"css-1dbjc4n r-1adg3ll r-1udh08x\" style=\"overflow-x: hidden;overflow-y: hidden;\">";

                              $code.="<div class=\"r-1adg3ll r-13qz1uu\" style=\"padding-bottom: 56.25%;width: 100%;\"></div>";

                              $code.="<div class=\"r-1p0dtai r-1pi2tsx r-1d2f490 r-u8s1d r-ipm5af r-13qz1uu\" style=\"width: 100%;height:100%;\">";

                              $code.="<div class=\"css-1dbjc4n r-1p0dtai r-1d2f490 r-u8s1d r-zchlnj r-ipm5af\">";

                              $code.="<div class=\"css-1dbjc4n r-1p0dtai r-1d2f490 r-u8s1d r-zchlnj r-ipm5af\">";

                              $code.="<div style=\"cursor: pointer; height: 100%; width: 100%; position: relative; color: rgba(255, 255, 255, 0.85); font-size: 13px; font-weight: 400; font-family: &quot;helvetica neue&quot;, arial; line-height: normal; transform: translateZ(0px);\">";

                              $code.="<div role=\"button\" tabindex=\"0\" style=\"position: absolute; height: 100%; width: 100%;\">";

                              $code.="<div style=\"height: 100%; position: relative; transform: translateZ(0px); width: 100%;\">";

                              $code.="<div style=\"height: 100%; position: absolute; width: 100%;\">";

                              $code.="<div style=\"position: relative; width: 100%; height: 100%;transform: translateZ(0px);\">";

                              $code.="<video style=\"width: 100%; height: 100%; position: absolute; background-color: black; top: 0%; left: 0%; transform: rotate(0deg) scale(1.005);\" loop=\"\" controls><source src=\"".$loadFilePathFull."?fileid=".$fileid."&index=".$videocount."&type=vid\" type=\"video/mp4\"></video>";

                              $code.="</div></div></div></div><div style=\"margin-top: 5px;\">";

                              $code.="<span style=\"opacity: 0; transition: opacity 0.15s ease-in-out 0s;\">";

                              $code.="<div data-testid=\"controlBar\" style=\"background: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.65)); bottom: 0px; cursor: auto; height: 65px; padding-bottom: 5px; position: absolute; width: 100%;\"><span>";
                              
                              $code.="<div class=\"scrubber\" style=\"touch-action: none; cursor: pointer; direction: ltr; height: 30px; margin: 0px 15px -4px; position: relative; user-select: none;\">";
                              
                              $code.="<div class=\"scrubBar\" style=\"height: 3px; width: 100%; position: relative; top: 16px; background-color: rgba(255, 255, 255, 0.3); border-radius: 5px;\"></div>";
                              
                              $code.="<div class=\"playedBar\" style=\"height: 3px; width: 76.3158%; position: absolute; top: 16px; background-color: rgb(255, 255, 255); border-radius: 5px;\"></div>";
                              
                              $code.="<div class=\"scrubHandle\" style=\"background-color: rgb(255, 255, 255); height: 17px; width: 17px; border-radius: 19px; top: 9px; margin-left: -9px; position: absolute; left: 76.3158%; box-shadow: rgba(0, 0, 0, 0.5) 0px 0px 3px; transform: scale(0.3); opacity: 0; transition: transform 125ms cubic-bezier(0.15, 0.75, 0.5, 0.95) 0s;\"></div></div></span>";
                              
                              $code.="<div style=\"bottom: 0px; display: flex; line-height: 43px; margin: 0px 6px; position: absolute; width: calc(100% - 12px);\">";
                              
                              $code.="<div style=\"display: flex; align-items: center; flex: 1 1 0%; overflow: hidden;\">";
                              
                              $code.="<span style=\"flex-shrink: 0;\"><span>";
                              
                              $code.="<button data-testid=\"pause\" style=\"color: rgba(255, 255, 255, 0.85); background-color: transparent; box-sizing: content-box; border-width: 0px; outline-style: none; user-select: none; font-size: 20px; cursor: pointer; padding: 2px 6px 6px; box-shadow: none; font-family: &quot;helvetica neue&quot;, arial; line-height: normal; vertical-align: middle; height: 1.25em; width: 1.25em;\">";
                              
                              $code.="<svg viewBox=\"0 0 24 24\" class=\"r-4qtqp9 r-yyyyoo r-1xvli5t r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-1hdv0qi\" style=\"vertical-align: middle;\"><g><path d=\"M10 20.5c0 .828-.672 1.5-1.5 1.5h-2c-.828 0-1.5-.672-1.5-1.5v-17C5 2.672 5.672 2 6.5 2h2c.828 0 1.5.672 1.5 1.5v17zm9 0c0 .828-.672 1.5-1.5 1.5h-2c-.828 0-1.5-.672-1.5-1.5v-17c0-.828.672-1.5 1.5-1.5h2c.828 0 1.5.672 1.5 1.5v17z\"></path></g></svg></button></span></span>";
                              
                              $code.="<div class=\"css-1dbjc4n r-1n0xq6e\">";
                              
                              $code.="<div data-testid=\"viewCount\" dir=\"auto\" class=\"css-901oao r-homxoj r-lrvibr\">";
                              
                              $code.="<span>6.1M views</span></div></div></div>";
                              
                              $code.="<span data-testid=\"timePlayed\" style=\"color: rgba(255, 255, 255, 0.85); cursor: default; font-size: 13px; font-weight: 400; font-family: &quot;helvetica neue&quot;, arial; user-select: none; flex-shrink: 0; margin-right: 15px;\">0:29 / 0:38</span><span class=\"volume-control\" style=\"flex-shrink: 0; position: relative;\"><span><button data-testid=\"mute\" style=\"color: rgba(255, 255, 255, 0.85); background-color: transparent; box-sizing: content-box; border-width: 0px; outline-style: none; user-select: none; font-size: 20px; cursor: pointer; padding: 2px 6px 6px; box-shadow: none; font-family: &quot;helvetica neue&quot;, arial; line-height: normal; vertical-align: middle;\">";
                              
                              $code.="<svg viewBox=\"0 0 24 24\" class=\"r-4qtqp9 r-yyyyoo r-1xvli5t r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-1hdv0qi\" style=\"vertical-align: middle;\"  width=\"25\"><g><path d=\"M12.253 22.75c-.166 0-.33-.055-.466-.162L4.74 17H2.254c-1.24 0-2.25-1.01-2.25-2.25v-5.5C.003 8.01 1.013 7 2.253 7H4.74l7.047-5.588c.225-.18.533-.215.792-.087.258.125.423.387.423.675v20c0 .288-.165.55-.424.675-.104.05-.216.075-.327.075zm-10-14.25c-.413 0-.75.337-.75.75v5.5c0 .413.337.75.75.75h2.75c.17 0 .333.058.466.162l6.033 4.786V3.552L5.47 8.338c-.134.104-.298.162-.467.162h-2.75zm14.33 8.226c-.308 0-.596-.19-.705-.495-.14-.39.06-.818.45-.96 1.373-.495 2.296-1.81 2.296-3.27s-.923-2.774-2.296-3.27c-.39-.14-.592-.57-.45-.96.14-.39.57-.59.958-.45 1.967.707 3.288 2.59 3.288 4.68s-1.32 3.972-3.286 4.68c-.084.03-.17.046-.255.046z\"  width=\"25\"></path><path d=\"M17.82 20.135c-.306 0-.594-.19-.704-.495-.14-.39.06-.82.45-.96 2.802-1.014 4.684-3.698 4.684-6.68 ";
                              
                              $code.="0-2.98-1.883-5.665-4.684-6.68-.39-.14-.592-.57-.45-.96.14-.39.573-.59.96-.45C21.47 5.14 23.75 8.39 23.75 12c0 3.61-2.28 6.862-5.674 8.09-.084.03-.17.045-.255.045z\"></path></g></svg>";
                              
                              $code.="</button></span></span><span style=\"flex-shrink: 0;\"><span><button data-testid=\"fullscreen\" style=\"color: rgba(255, 255, 255, 0.85); background-color: transparent; box-sizing: content-box; border-width: 0px; outline-style: none; user-select: none; font-size: 20px; cursor: pointer; padding: 2px 6px 6px; box-shadow: none; font-family: &quot;helvetica neue&quot;, arial; line-height: normal; vertical-align: middle; flex-shrink: 0; height: 1.25em; width: 1.25em;\">";
                              
                              $code.="<svg viewBox=\"0 0 24 24\" class=\"r-4qtqp9 r-yyyyoo r-1xvli5t r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-1hdv0qi\" style=\"vertical-align: middle;\"><g><path d=\"M3.5 19.44v-4c0-.414-.336-.75-.75-.75s-.75.336-.75.75v5.81c0 .414.336.75.75.75h5.81c.414 0 .75-.336.75-.75s-.336-.75-.75-.75h-4l6.577-6.576c.146-.146.22-.338.22-.53s-.073-.384-.22-.53c-.293-.293-.768-.293-1.06 0L3.5 19.44zM14.69 2.75c0 .414.336.75.75.75h4l-6.577 6.576c-.293.293-.293.768 0 1.06s.768.294 1.06 0L20.5 4.562v4c0 .414.336.75.75.75s.75-.336.75-.75V2.75c0-.414-.336-.75-.75-.75h-5.81c-.414 0-.75.336-.75.75z\"></path></g></svg>";
                              
                              $code.="</button></span></span></div></div></span><div class=\"css-1dbjc4n r-1nlw0im r-1r74h94 r-u8s1d\">";
                              
                              $code.="<span style=\"opacity: 1; transition: opacity 0.15s ease-in-out 0s;\"></span></div>";
                              
                              $code.="<div class=\"css-1dbjc4n r-1nlw0im r-u8s1d r-3mc0re\">";
                              
                              $code.="<span style=\"opacity: 1; transition: opacity 0.15s ease-in-out 0s;\"></span></div></div></div></div></div></div></div></div></div><div class=\"css-1dbjc4n r-1g94qm0\"></div></div>";
                         }
                         //endvideo

                         //start image
                         else if (strpos($codes[0], "class=\"AdaptiveMedia-photoContainer js-adaptive-photo \"") > 0)
                         {
                              $code .= "<img style=\"width:100%;\" src=";
                              $twing = substr($codes[0], strpos($codes[0], "data-aria-label-part src=") + 26);
                              $twing = substr($twing, 0, strpos($twing, "\""));
                              $code .= $twing;
                              $code .= ">";
                         }
                         //end image

                         $code .= "<div class=\"css-1dbjc4n r-1awozwy r-18u37iz r-1wtj0ep r-ku1wi2\" style=\"margin-bottom: 15px;-webkit-box-align: center;align-items: center;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;-webkit-box-pack: justify;justify-content: space-between;\">";
                         
                         $code.="<div dir=\"auto\" class=\"css-901oao r-1re7ezh r-1qd0xha r-a023e6 r-16dba41 r-ad9z0x r-zso239 r-bcqeeo r-qvutc0\" style=\"margin-right: 10px;font-weight: 400;color: rgb(101, 119, 134);font-size: 15px;font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;\">";
                         
                         $code.="<span class=\"css-901oao css-16my406 r-1re7ezh r-1qd0xha r-ad9z0x r-bcqeeo r-qvutc0\" style=\"color: rgb(101, 119, 134);font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;\">";
                         
                         $code.="<span class=\"css-901oao css-16my406 r-1qd0xha r-ad9z0x r-bcqeeo r-qvutc0\" style=\"font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;\">".$twittertime."</span></span>";
                         
                         $code.="<span aria-hidden=\"true\" class=\"css-901oao css-16my406 r-1re7ezh r-1q142lx r-1qd0xha r-ad9z0x r-bcqeeo r-ou255f r-qvutc0\" style=\"color: rgb(101, 119, 134);flex-shrink: 0;font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;padding-left: 5px;padding-right: 5px;\">";
                         
                         $code.="<span class=\"css-901oao css-16my406 r-1qd0xha r-ad9z0x r-bcqeeo r-qvutc0\" style=\"font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;\"></span></span>";
                         
                         $code.="<a href=\"https://help.twitter.com/using-twitter/how-to-tweet#source-labels\" target=\"_blank\" role=\"link\" data-focusable=\"true\" class=\"css-4rbku5 css-18t94o4 css-901oao css-16my406 r-1n1174f r-1loqt21 r-1qd0xha r-ad9z0x r-bcqeeo r-1jeg54m r-qvutc0\" rel=\" noopener noreferrer\" style=\"white-space: normal;color: rgb(27, 149, 224);font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;cursor: pointer;text-decoration: none;\">";
                         
                         $code.="<span class=\"css-901oao css-16my406 r-1qd0xha r-ad9z0x r-bcqeeo r-qvutc0\" style=\"font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;\">Twitter for iPhone</span></a></div></div>";
                         
                         $code.="<div class=\"css-1dbjc4n r-1gkumvb r-1efd50x r-5kkj8d r-18u37iz r-9qu9m4\" style=\"border-top-width: 1px;border-top-style: solid;border-top-color: rgb(230, 236, 240);-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;padding-bottom: 15px;padding-top: 15px;\">";
                         
                         $code.="<div class=\"css-1dbjc4n\">";
                         
                         $code.="<a href=\"/".$twitteruser."/status/".$twitterpath."/retweets\" dir=\"auto\" role=\"link\" data-focusable=\"true\" class=\"css-4rbku5 css-18t94o4 css-901oao r-hkyrab r-1loqt21 r-1qd0xha r-a023e6 r-16dba41 r-ad9z0x r-bcqeeo r-qvutc0\" style=\"font-weight: 400;color: rgb(20, 23, 26);font-size: 15px;font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;cursor: pointer;\">";
                       
                         $code.="<div class=\"css-1dbjc4n r-xoduu5 r-1udh08x\" style=\"display: inline-flex;overflow-x: hidden;overflow-y: hidden;\">";
                       
                         $code.="<span class=\"css-901oao css-16my406 r-1qd0xha r-vw2c0b r-ad9z0x r-bcqeeo r-d3hbe1 r-1wgg2b2 r-axxi2z r-qvutc0\" style=\"transition-duration: 0.3s;font-weight: bold;font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;transition-property: -webkit-transform, transform;transform: translate3d(0px, 0px, 0px);\">";
                       
                         $code.="<span class=\"css-901oao css-16my406 r-1qd0xha r-ad9z0x r-bcqeeo r-qvutc0\" style=\"font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;\">".$twitterretweets."</span></span></div> ";
                       
                         $code.="<span class=\"css-901oao css-16my406 r-1re7ezh r-1qd0xha r-ad9z0x r-bcqeeo r-qvutc0\" style=\"color: rgb(101, 119, 134);font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;\">";
                       
                         $code.="<span class=\"css-901oao css-16my406 r-1qd0xha r-ad9z0x r-bcqeeo r-qvutc0\" style=\"font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;\">Retweets</span></span></a></div>";
                       
                         $code.="<div class=\"css-1dbjc4n r-1joea0r\" style=\"margin-left: 20px;\">";
                       
                         $code.="<a href=\"/".$twitteruser."/status/".$twitterpath."/likes\" dir=\"auto\" role=\"link\" data-focusable=\"true\" class=\"css-4rbku5 css-18t94o4 css-901oao r-hkyrab r-1loqt21 r-1qd0xha r-a023e6 r-16dba41 r-ad9z0x r-bcqeeo r-qvutc0\" style=\"font-weight: 400;color: rgb(20, 23, 26);font-size: 15px;font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;cursor: pointer;text-decoration: none;\">";
                       
                         $code.="<div class=\"css-1dbjc4n r-xoduu5 r-1udh08x\" style=\"display: inline-flex;overflow-x: hidden;overflow-y: hidden;\">";
                       
                         $code.="<span class=\"css-901oao css-16my406 r-1qd0xha r-vw2c0b r-ad9z0x r-bcqeeo r-d3hbe1 r-1wgg2b2 r-axxi2z r-qvutc0\" style=\"transition-duration: 0.3s;font-weight: bold;font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;transition-property: -webkit-transform, transform;transform: translate3d(0px, 0px, 0px);\">";
                       
                         $code.="<span class=\"css-901oao css-16my406 r-1qd0xha r-ad9z0x r-bcqeeo r-qvutc0\" style=\"font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;\">".$twitterlikes."</span></span></div>";
                       
                         $code.="<span class=\"css-901oao css-16my406 r-1re7ezh r-1qd0xha r-ad9z0x r-bcqeeo r-qvutc0\" style=\"color: rgb(101, 119, 134);font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;\">";
                       
                         $code.="<span class=\"css-901oao css-16my406 r-1qd0xha r-ad9z0x r-bcqeeo r-qvutc0\" style=\"font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Ubuntu, 'Helvetica Neue', sans-serif;overflow-wrap: break-word;min-width: 0px;line-height: 1.3125;\">Likes</span></span></a></div></div>";

                         //end 1section tweets/likes
                         //start second section
                       
                         $code.="<div aria-label=\"".$twitterreplies." replies, ".$twitterretweets." Retweets, ".$twitterlikes." likes\" role=\"group\" class=\"css-1dbjc4n r-1oszu61 r-1gkumvb r-1efd50x r-5kkj8d r-18u37iz r-ahm1il r-a2tzq0\" style=\"height: 49px;-webkit-box-pack: justify;justify-content: space-around;border-top-width: 1px;border-top-style: solid;border-top-color: rgb(230, 236, 240);-webkit-box-align: stretch;align-items: stretch;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;\">";

                         $code.="<div class=\"css-1dbjc4n r-18u37iz r-1h0z5md r-3qxfft r-h4g966 r-rjfia\" style=\"min-height: 1.875rem;-webkit-box-pack: start;justify-content: flex-start;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;padding-left: 0.85rem;padding-right: 0.85rem;padding-bottom: 0px;padding-top: 0px;\">";
                       
                         $code.="<div aria-label=\"Reply\" role=\"button\" data-focusable=\"true\" tabindex=\"0\" class=\"css-18t94o4 css-1dbjc4n r-1777fci r-11cpok1 r-1ny4l3l r-bztko3 r-lrvibr\" data-testid=\"reply\">";
                       
                         $code.="<div dir=\"ltr\" class=\"css-901oao r-1awozwy r-1re7ezh r-6koalj r-1qd0xha r-a023e6 r-16dba41 r-1h0z5md r-ad9z0x r-bcqeeo r-o7ynqc r-clp7b1 r-3s2u2q r-qvutc0\">";
                       
                         $code.="<div class=\"css-1dbjc4n r-xoduu5\">";
                       
                         $code.="<div class=\"css-1dbjc4n r-sdzlij r-1p0dtai r-xoduu5 r-1d2f490 r-xf4iuw r-u8s1d r-zchlnj r-ipm5af r-o7ynqc r-6416eg\"></div>";
                       
                         $code.="<svg viewBox=\"0 0 24 24\"  width=\"25\" class=\"r-4qtqp9 r-yyyyoo r-50lct3 r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-1srniue\"><g><path d=\"M14.046 2.242l-4.148-.01h-.002c-4.374 0-7.8 3.427-7.8 7.802 0 4.098 3.186 7.206 7.465 7.37v3.828c0 .108.044.286.12.403.142.225.384.347.632.347.138 0 .277-.038.402-.118.264-.168 6.473-4.14 8.088-5.506 1.902-1.61 3.04-3.97 3.043-6.312v-.017c-.006-4.367-3.43-7.787-7.8-7.788zm3.787 12.972c-1.134.96-4.862 3.405-6.772 4.643V16.67c0-.414-.335-.75-.75-.75h-.396c-3.66 0-6.318-2.476-6.318-5.886 0-3.534 2.768-6.302 6.3-6.302l4.147.01h.002c3.532 0 6.3 2.766 6.302 6.296-.003 1.91-.942 3.844-2.514 5.176z\"></path></g></svg>";

                         $code.="</div></div></div></div><div class=\"css-1dbjc4n r-18u37iz r-1h0z5md r-3qxfft r-h4g966 r-rjfia\" style=\"min-height: 1.875rem;-webkit-box-pack: start;justify-content: flex-start;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;padding-left: 0.85rem;padding-right: 0.85rem;padding-bottom: 0px;padding-top: 0px;\">";
                       
                         $code.="<div aria-haspopup=\"true\" aria-label=\"Retweet\" role=\"button\" data-focusable=\"true\" tabindex=\"0\" class=\"css-18t94o4 css-1dbjc4n r-1777fci r-11cpok1 r-1ny4l3l r-bztko3 r-lrvibr\" data-testid=\"retweet\">";
                       
                         $code.="<div dir=\"ltr\" class=\"css-901oao r-1awozwy r-1re7ezh r-6koalj r-1qd0xha r-a023e6 r-16dba41 r-1h0z5md r-ad9z0x r-bcqeeo r-o7ynqc r-clp7b1 r-3s2u2q r-qvutc0\">";
                       
                         $code.="<div class=\"css-1dbjc4n r-xoduu5\">";
                       
                         $code.="<div class=\"css-1dbjc4n r-sdzlij r-1p0dtai r-xoduu5 r-1d2f490 r-xf4iuw r-u8s1d r-zchlnj r-ipm5af r-o7ynqc r-6416eg\"></div>";
                       
                         $code.="<svg viewBox=\"0 0 24 24\" class=\"r-4qtqp9 r-yyyyoo r-50lct3 r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-1srniue\"  width=\"25\"><g><path d=\"M23.77 15.67c-.292-.293-.767-.293-1.06 0l-2.22 2.22V7.65c0-2.068-1.683-3.75-3.75-3.75h-5.85c-.414 0-.75.336-.75.75s.336.75.75.75h5.85c1.24 0 2.25 1.01 2.25 2.25v10.24l-2.22-2.22c-.293-.293-.768-.293-1.06 0s-.294.768 0 1.06l3.5 3.5c.145.147.337.22.53.22s.383-.072.53-.22l3.5-3.5c.294-.292.294-.767 0-1.06zm-10.66 3.28H7.26c-1.24 0-2.25-1.01-2.25-2.25V6.46l2.22 2.22c.148.147.34.22.532.22s.384-.073.53-.22c.293-.293.293-.768 0-1.06l-3.5-3.5c-.293-.294-.768-.294-1.06 0l-3.5 3.5c-.294.292-.294.767 0 1.06s.767.293 1.06 0l2.22-2.22V16.7c0 2.068 1.683 3.75 3.75 3.75h5.85c.414 0 .75-.336.75-.75s-.337-.75-.75-.75z\"></path></g></svg>";

                         $code.="</div></div></div></div><div class=\"css-1dbjc4n r-18u37iz r-1h0z5md r-3qxfft r-h4g966 r-rjfia\" style=\"min-height: 1.875rem;-webkit-box-pack: start;justify-content: flex-start;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;padding-left: 0.85rem;padding-right: 0.85rem;padding-bottom: 0px;padding-top: 0px;\">";
                       
                         $code.="<div aria-label=\"Like\" role=\"button\" data-focusable=\"true\" tabindex=\"0\" class=\"css-18t94o4 css-1dbjc4n r-1777fci r-11cpok1 r-1ny4l3l r-bztko3 r-lrvibr\" data-testid=\"like\">";
                       
                         $code.="<div dir=\"ltr\" class=\"css-901oao r-1awozwy r-1re7ezh r-6koalj r-1qd0xha r-a023e6 r-16dba41 r-1h0z5md r-ad9z0x r-bcqeeo r-o7ynqc r-clp7b1 r-3s2u2q r-qvutc0\">";
                       
                         $code.="<div class=\"css-1dbjc4n r-xoduu5\">";
                         
                         $code.="<div class=\"css-1dbjc4n r-sdzlij r-1p0dtai r-xoduu5 r-1d2f490 r-xf4iuw r-u8s1d r-zchlnj r-ipm5af r-o7ynqc r-6416eg\"></div>";
                         
                         $code.="<svg viewBox=\"0 0 24 24\" class=\"r-4qtqp9 r-yyyyoo r-50lct3 r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-1srniue\"  width=\"25\"><g><path d=\"M12 21.638h-.014C9.403 21.59 1.95 14.856 1.95 8.478c0-3.064 2.525-5.754 5.403-5.754 2.29 0 3.83 1.58 4.646 2.73.814-1.148 2.354-2.73 4.645-2.73 2.88 0 5.404 2.69 5.404 5.755 0 6.376-7.454 13.11-10.037 13.157H12zM7.354 4.225c-2.08 0-3.903 1.988-3.903 4.255 0 5.74 7.034 11.596 8.55 11.658 1.518-.062 8.55-5.917 8.55-11.658 0-2.267-1.823-4.255-3.903-4.255-2.528 0-3.94 2.936-3.952 2.965-.23.562-1.156.562-1.387 0-.014-.03-1.425-2.965-3.954-2.965z\"></path></g></svg>";

                         $code.="</div></div></div></div><div class=\"css-1dbjc4n r-18u37iz r-1h0z5md r-3qxfft r-h4g966 r-rjfia\" style=\"min-height: 1.875rem;-webkit-box-pack: start;justify-content: flex-start;-webkit-box-direction: normal;-webkit-box-orient: horizontal;flex-direction: row;padding-left: 0.85rem;padding-right: 0.85rem;padding-bottom: 0px;padding-top: 0px;\">";
                         
                         $code.="<div aria-haspopup=\"true\" aria-label=\"Share Tweet\" role=\"button\" data-focusable=\"true\" tabindex=\"0\" class=\"css-18t94o4 css-1dbjc4n r-1777fci r-11cpok1 r-1ny4l3l r-bztko3 r-lrvibr\">";
                         
                         $code.="<div dir=\"ltr\" class=\"css-901oao r-1awozwy r-1re7ezh r-6koalj r-1qd0xha r-a023e6 r-16dba41 r-1h0z5md r-ad9z0x r-bcqeeo r-o7ynqc r-clp7b1 r-3s2u2q r-qvutc0\">";
                         
                         $code.="<div class=\"css-1dbjc4n r-xoduu5\">";
                         
                         $code.="<div class=\"css-1dbjc4n r-sdzlij r-1p0dtai r-xoduu5 r-1d2f490 r-xf4iuw r-u8s1d r-zchlnj r-ipm5af r-o7ynqc r-6416eg\"></div>";
                         
                         $code.="<svg viewBox=\"0 0 24 24\" class=\"r-4qtqp9 r-yyyyoo r-50lct3 r-dnmrzs r-bnwqim r-1plcrui r-lrvibr r-1srniue\"  width=\"25\"><g><path d=\"M17.53 7.47l-5-5c-.293-.293-.768-.293-1.06 0l-5 5c-.294.293-.294.768 0 1.06s.767.294 1.06 0l3.72-3.72V15c0 .414.336.75.75.75s.75-.336.75-.75V4.81l3.72 3.72c.146.147.338.22.53.22s.384-.072.53-.22c.293-.293.293-.767 0-1.06z\"></path><path d=\"M19.708 21.944H4.292C3.028 21.944 2 20.916 2 19.652V14c0-.414.336-.75.75-.75s.75.336.75.75v5.652c0 .437.355.792.792.792h15.416c.437 0 .792-.355.792-.792V14c0-.414.336-.75.75-.75s.75.336.75.75v5.652c0 1.264-1.028 2.292-2.292 2.292z\"></path></g></svg>";

                         $code.="</div></div></div></div></div></div></article></div></div></div></div></div></section></div></div></div></div></div></div></div></main></div></div></div></div>";
                         
                         $t9 = substr($code, strpos($code, "preload") + 10);
                         $t9 = substr($t9, strpos($t9, "preload"));
                         $t9 = substr($t9, strpos($t9, " href=") + 7);
                         $t9 = substr($t9, 0, strpos($t9, " ") - 1);
                         //echo "<br>t9:".$t9;
                         
                         $code .= "<script type=\"text/javascript\" charset=\"utf-8\" nonce=\"Mzc2Y2ViYmMtOTYzZi00NTdmLTk3YTEtNGFjZDU3MWExYWRk\" crossorigin=\"anonymous\" src=\"".$t9."\"></script>";
                         $dd = count($cc);
                         break;
                    }
               }

               //if (cc[dd].toLowerCase().startsWith("a href=") || cc[dd].toLowerCase().startsWith("base href=")) {var ee=cc[dd].substring(0,cc[dd].indexOf(">"));
               //console.log(path);
               //if (ee.toLowerCase().startsWith("a href=\"")) {ee=ee.substring(ee.toLowerCase().indexOf("a href=\"")+8);ee=ee.substring(0,ee.indexOf("\""));if (ee.toLowerCase().startsWith("https://") || ee.toLowerCase().startsWith("http://")) {urls+=ee+"\n"} else {urls+=path+ee+"\n"}}
               //else if (ee.toLowerCase().startsWith("a href='")) {ee=ee.substring(ee.toLowerCase().indexOf("a href='")+8);ee=ee.substring(0,ee.indexOf("'"));if (ee.toLowerCase().startsWith("https://") || ee.toLowerCase().startsWith("http://")) {urls+=ee+"\n"} else {urls+=path+ee+"\n"}}
               //else if (ee.toLowerCase().indexOf(" src=")) {ee=ee.substring(ee.toLowerCase().indexOf("a href=")+7);
               //if (ee.indexOf(" ")) {ee=ee=ee.substring(0,ee.indexOf(" "));} else {ee=ee.substring(0,ee.length-1);};if (ee.toLowerCase().startsWith("https://") || ee.toLowerCase().startsWith("http://")) {urls+=ee+"\n"} else {urls+=path+ee+"\n"}}

               //if (startsWith(strtolower($cc[$dd]),"base ")) {$ee=substr($cc[$dd],0,strpos($cc[$dd],">"));$tmp2=$ee;$tmp3=substr($cc[$dd],strpos($cc[$dd],">"));
               //if (strpos(strtolower($ee),"href=\"")>0)     {$ee=substr($ee,strpos(strtolower($ee)," href=\"")+10);$tmp1=substr($cc[$dd],0,strlen($tmp2)-strlen($ee));$tmp2=$ee;$ee=substr($ee,0,strpos($ee,"\""));$tmp3=substr($tmp2,strpos($tmp2,"\"")).$tmp3;$tmp2=$ee;$kk=filelist($ee,$urls,$urls2,$xurls,$index,$path,$c_url,$c_proto,$c_port); if ($kk[0]) {$urls.=$kk[1];$urls2[$kk[3]]=1+count($urls2);$xurls.="";} else {$tmp2="f";}}
               //else if (strpos(strtolower($ee),"href='")>0) {$ee=substr($ee,strpos(strtolower($ee)," href='")+10);$tmp1=substr($cc[$dd],0,strlen($tmp2)-strlen($ee));$tmp2=$ee; $ee=substr($ee,0,strpos($ee,"'")) ;$tmp3=substr($tmp2,strpos($tmp2,"'")).$tmp3;$tmp2=$ee;$kk=filelist($ee,$urls,$urls2,$xurls,$index,$path,$c_url,$c_proto,$c_port); if ($kk[0]) {$urls.=$kk[1];$urls2[$kk[3]]=1+count($urls2);$xurls.="href\n";$index++;$tmp2=$loadFilePathFull."?fileid=".$fileid."&index=".$index."&type=href";} else {$tmp2=$loadFilePathFull."?fileid=".$fileid."&index=".$kk[2]."&type=href";}}
               //else if (strpos(strtolower($ee),"href=")>0)  {$ee=substr(ee,strpos(strtolower($ee)," href=")+9);$tmp1=substr($cc[$dd],0,strlen($tmp2)-strlen($ee));$tmp2=$ee;if (strpos(ee," ")>0)                               {$ee=substr($ee,0,substr($ee," "));;$tmp3=substr($tmp2,strpos($tmp2," ")).$tmp3;$tmp2=$ee;} else {$ee=substr($ee,0,strlen($ee)-1);$tmp3=substr($tmp2,strpos($tmp2," ")).$tmp3;$tmp2=$ee;}$bpath=$ee;$path=$bpath;   $kk=filelist($ee,$urls,$urls2,$xurls,$index,$path,$c_url,$c_proto,$c_port); if ($kk[0]) {$urls.=$kk[1];$urls2[$kk[3]]=1+count($urls2);$xurls.="href\n";$index++;$tmp2=$loadFilePathFull."?fileid=".$fileid."&index=".$index."&type=href";} else {$tmp2=$loadFilePathFull."?fileid=".$fileid."&index=".$kk[2]."&type=href";}}
               //}

               if (startsWith(strtolower($cc[$dd]), "link ")) 
               {
                    //echo "<br>$cc[$dd]:".$cc[$dd]."<br>";
                    $casetype = "css";
                    if (strpos(strtolower($cc[$dd]), " rel=\"icon\"") > 0)
                    {
                         $casetype="img";
                    }
                    if (strpos(strtolower($cc[$dd]), " rel='icon'") > 0)
                    {
                         $casetype="img";
                    }
                    if (strpos(strtolower($cc[$dd]), " rel=icon") > 0)
                    {
                         $casetype="img";
                    }

                    if (strpos(strtolower($cc[$dd]), " rel=\"mask-icon\"") > 0)
                    {
                         $casetype="img";
                    }
                    if (strpos(strtolower($cc[$dd]), " rel='mask-icon'") > 0)
                    {
                         $casetype="img";
                    }
                    if (strpos(strtolower($cc[$dd]), " rel=mask-icon") > 0)
                    {
                         $casetype="img";
                    }

                    if (strpos(strtolower($cc[$dd]), " rel=\"shortcut icon\"") > 0) 
                    {
                         $casetype = "img";
                    }
                    if (strpos(strtolower($cc[$dd]), " rel='shortcut icon'") > 0)
                    {
                         $casetype = "img";
                    }
                    if (strpos(strtolower($cc[$dd]), " rel=shortcut icon") > 0)
                    {
                         $casetype = "img";
                    }

                    if (strpos(strtolower($cc[$dd]), " rel=\"apple-touch-icon\"") > 0) 
                    {
                         $casetype = "img";
                    }
                    if (strpos(strtolower($cc[$dd]), " rel='apple-touch-icon'") > 0) 
                    {
                         $casetype = "img";
                    }
                    if (strpos(strtolower($cc[$dd]), " rel=apple-touch-icon") > 0) 
                    {
                         $casetype = "img";
                    }

                    if (strpos(strtolower($cc[$dd]) ," rel=\"preload\"") > 0)
                    {
                         if (strpos(strtolower($cc[$dd]), " as=\"style\"") > 0)
                         {
                              $casetype = "css";
                         }
                         else if (strpos(strtolower($cc[$dd]), " as=style") > 0)
                         {
                              $casetype = "css";
                         }
                         else if (strpos(strtolower($cc[$dd]), " as='style'") > 0) 
                         {
                              $casetype = "css";
                         }
                         else 
                         {
                              $casetype = "js";
                         }
                    }

                    if (strpos(strtolower($cc[$dd])," rel='preload'")>0) 
                    {
                         if (strpos(strtolower($cc[$dd]), " as=\"style\"") > 0)
                         {
                              $casetype = "css";
                         }
                         else if (strpos(strtolower($cc[$dd]), " as=style") > 0)
                         {
                              $casetype = "css";
                         }
                         else if (strpos(strtolower($cc[$dd]), " as='style'") > 0) 
                         {
                              $casetype = "css";
                         }
                         else 
                         {
                              $casetype = "js";
                         }
                    }

                    if (strpos(strtolower($cc[$dd]), " rel=preload") > 0) 
                    {
                         if (strpos(strtolower($cc[$dd]), " as=\"style\"") > 0)
                         {
                              $casetype = "css";
                         }
                         else if (strpos(strtolower($cc[$dd]), " as=style") > 0)
                         {
                              $casetype = "css";
                         }
                         else if (strpos(strtolower($cc[$dd]), " as='style'") > 0) 
                         {
                              $casetype="css";
                         }
                         else 
                         {
                              $casetype = "js";
                         }
                    }

                    if (strpos(strtolower($cc[$dd]), " rel=\"manifest\"") > 0)
                    {
                         $casetype = "js";
                    }
                    if (strpos(strtolower($cc[$dd]), " rel='manifest'") > 0)
                    {
                         $casetype = "js";
                    }
                    if (strpos(strtolower($cc[$dd]), " rel=manifest") > 0) 
                    {
                         $casetype = "js";
                    }

                    if (strpos(strtolower($cc[$dd]), " rel=\"alternate\"") > 0)
                    {
                         $casetype = "js";
                    }
                    if (strpos(strtolower($cc[$dd]), " rel='alternate'") > 0)
                    {
                         $casetype = "js";
                    }
                    if (strpos(strtolower($cc[$dd]), " rel=alternate") > 0)
                    {
                         $casetype = "js";
                    }

                    if (strpos(strtolower($cc[$dd]), " rel=\"canonical\"") > 0) 
                    {
                         $casetype = "js";
                    }
                    if (strpos(strtolower($cc[$dd]), " rel='canonical'") > 0) 
                    {
                         $casetype = "js";
                    }
                    if (strpos(strtolower($cc[$dd]), " rel=canonical") > 0) 
                    {
                         $casetype = "js";
                    } 

                    if (strpos(strtolower($cc[$dd]), " rel=\"dns-prefetch\"") > 0) 
                    {
                         $casetype = "css";
                    }
                    if (strpos(strtolower($cc[$dd]), " rel='dns-prefetch'") > 0)
                    {
                         $casetype = "css";
                    }
                    if (strpos(strtolower($cc[$dd]), " rel=dns-prefetch") > 0)
                    {
                         $casetype = "css";
                    }

                    $ee = substr($cc[$dd], 0, strpos($cc[$dd], ">"));
                    $tmp2 = $ee;
                    $tmp3 = substr($cc[$dd], strpos($cc[$dd], ">"));

                    if (strpos(strtolower($ee) ," href=\"") > 0) 
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " href=\"") + 7);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "\""));
                         $tmp3 = substr($tmp2, strpos($tmp2, "\"")) . $tmp3;
                         $tmp2 = $ee;
                         //echo $ee;

                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         if ($kk[0]) 
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= $casetype . "\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index."&type=" . $casetype;
                         }
                         else 
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=" . $casetype;
                         }
                    }
                    else if (strpos(strtolower($ee), " href='") > 0)
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " href='") + 7);
                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;
                         $ee = substr($ee, 0, strpos($ee, "'"));
                         $tmp3 = substr($tmp2, strpos($tmp2, "'")) . $tmp3;
                         $tmp2 = $ee;
                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                         if ($kk[0])
                         {
                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= $casetype . "\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=" . $casetype;
                         } 
                         else 
                         {
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=" . $casetype;
                         }
                    }
                    else if (strpos(strtolower($ee), " href=") > 0)
                    {
                         $ee = substr($ee, strpos(strtolower($ee), " href=") + 6);

                         $tmp1 = substr($cc[$dd], 0, strlen($tmp2) - strlen($ee));
                         $tmp2 = $ee;

                         if (strpos($ee, " ") > 0)
                         {
                              $ee = substr($ee, 0, strpos($ee, " "));
                              $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                              $tmp2 = $ee;
                         } 
                         else 
                         {
                              if (strpos($ee, " ") === true)
                              {
                                   $ee = substr($ee, 1);
                                   if (strpos($ee, " ") > 0)
                                   {
                                        $ee = substr($ee, 0, strpos($ee, " "));
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                                   else 
                                   {
                                        $ee = substr($ee, 0, strlen($ee) -1);
                                        $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                        $tmp2 = $ee;
                                   }
                              } 
                              else 
                              {
                                   $ee = substr($ee, 0, strlen($ee) -1);
                                   $tmp3 = substr($tmp2, strpos($tmp2, " ")) . $tmp3;
                                   $tmp2 = $ee;
                              }
                         } 
                         //echo "<br>ee6:".$ee;

                         $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port); 
                         if ($kk[0]) 
                         {
                              //echo "<br>kk:".$kk[1];

                              $urls .= $kk[1];
                              $urls2[$kk[3]] = 1 + count($urls2);
                              $xurls .= $casetype . "\n";
                              $index++;
                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=" . $casetype;
                         }
                         else 
                         {
                              //echo "<br>2:".$kk[1];

                              $tmp2 = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=" . $casetype;
                         }
                    }
               }

               //debugmode("parsedata","1500",strlen($code));
               if ($tmp1 . $tmp2 . $tmp3 === $tmp)
               {}
               else
               {
                    $cc[$dd] = $tmp1 . $tmp2 . $tmp3;
               }
               //debugmode("parsedata","2000",strlen($code));

               if (($iscss1) 
               || (startsWith(strtolower($cc[$dd]), "style")) 
               || strpos($cc[$dd], ":url(")<strpos($cc[$dd], ">") 
               || (strpos($cc[$dd], "json") < strpos($cc[$dd], ">")) 
               || (strpos($cc[$dd], "navigator.serviceWorker.register(") !== FALSE) 
               || ($jsmode == 0)) 
               {
                    //if ($iscss1 || startsWith(strtolower($cc[$dd]),"style") || strpos($cc[$dd],":url(")<strpos($cc[$dd],">")) {
                    //if ($iscss1 || startsWith(strtolower($cc[$dd]),"style")) {
                    //echo "\n<br>dd:".$dd;
                    global $loadFilePathFullEncoded;

                    $tmp4 = "";
                    if (strpos($cc[$dd], " url(") !== FALSE) 
                    {
                         $gg = explode(" url(", $cc[$dd]);
                         $xx = 0;
                         for($xx = 0; $xx < count($gg); $xx++) 
                         {
                              //echo "<br>css bug".$gg[$xx];
                              if (!(startsWith(strtolower($gg[$xx]), "data:") || startsWith(strtolower($gg[$xx]), "\"data:") )) 
                              {
                                   //echo "<br>doing something";
                                   if ($xx == 0 && !startsWith($cc[$dd], " url("))
                                   {
                                        $tmp4 .= $gg[$xx];
                                   }
                                   else 
                                   {
                                        $ee = substr($gg[$xx], 0, strpos($gg[$xx], ")"));
                                        $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                                        if ($kk[0]) 
                                        {
                                             $urls .= $kk[1];
                                             $urls2[$kk[3]] = 1 + count($urls2);
                                             $xurls .= "img\n";
                                             $index++;
                                             $tmp4 .= " url(" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=img";
                                        } 
                                        else 
                                        {
                                             if ($kk[2] > 0) 
                                             {
                                                  $tmp4 .= " url(" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=img";
                                             }
                                        }
                                        if ($kk[2] > 0)
                                        {
                                             $tmp4 .= substr($gg[$xx], strpos($gg[$xx], ")"));
                                        }
                                        else 
                                        {
                                             $tmp4 .= " url(" . $gg[$xx];
                                        }
                                   }
                              }
                              else 
                              {
                                   $tmp4 .= " url(". $gg[$xx];
                              }
                         }
                         $cc[$dd] = $tmp4;
                         //echo "<br>strlen2:".strlen($cc[$dd]);
                    }

                    //testing

                    $tmp4 = "";
                    if (strpos($cc[$dd], ".get(\"") !== FALSE) 
                    {
                         $gg = explode(".get(\"", $cc[$dd]);
                         $xx = 0;

                         for($xx = 0; $xx < count($gg); $xx++) 
                         {
                              if ($xx == 0 && !startsWith($cc[$dd], ".get(\"")) 
                              {
                                   $tmp4 .= $gg[$xx];
                              }
                              else 
                              {
                                   $h = strpos($gg[$xx], ")");
                                   if (strpos($gg[$xx], ",") > 0 && strpos($gg[$xx], ",") < $h) 
                                   {
                                        $h = strpos($gg[$xx], ",");
                                   }

                                   $ee = substr($gg[$xx], 0, $h);
                                   
                                   if (strpos($ee, "http") !== false || strpos($ee, "/") !== false || strpos($ee, ".") !== false ) 
                                   {
                                        $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                                        if ($kk[0]) 
                                        {
                                             $urls .= $kk[1];
                                             $urls2[$kk[3]] = 1 + count($urls2);
                                             $xurls .= "src\n";
                                             $index++;
                                             $tmp4 .= ".get(\"" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=img\"";
                                        }
                                        else 
                                        {
                                             if ($kk[2] > 0) 
                                             {
                                                  $tmp4 .= ".get(\"" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=img\"";
                                             }
                                        }
                                        if ($kk[2] > 0) 
                                        {
                                             $tmp4 .= substr($gg[$xx], $h);
                                        }
                                        else
                                        {
                                             $tmp4 .= ".get(\"" . $gg[$xx];
                                        }
                                   }
                                   else 
                                   {
                                        $tmp4 .= ".get(\"" . $gg[$xx];
                                   }
                              }
                         }
                         $cc[$dd] = $tmp4;
                    }

                    $tmp4 = "";

                    if (strpos($cc[$dd], "navigator.serviceWorker.register(") !== FALSE) 
                    {
                         $gg = explode("navigator.serviceWorker.register(", $cc[$dd]);
                         $xx = 0;
                         
                         for($xx = 0; $xx < count($gg); $xx++) 
                         {
                              if ($xx == 0 && !startsWith($cc[$dd], "navigator.serviceWorker.register("))
                              {
                                   $tmp4 .= $gg[$xx];
                              }
                              else 
                              {
                                   $h = strpos($gg[$xx], ")");
                                   if (strpos($gg[$xx], ",") > 0 && strpos($gg[$xx], ",") < $h)
                                   {
                                        $h = strpos($gg[$xx], ",");
                                   }
                                   $ee = substr($gg[$xx], 0, $h);
                                   $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                                   if ($kk[0]) 
                                   {
                                        $urls .= $kk[1];
                                        $urls2[$kk[3]] = 1 + count($urls2);
                                        $xurls .= "src\n";
                                        $index++;
                                        $tmp4 .= "navigator.serviceWorker.register(\"" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=src\n";
                                   } 
                                   else
                                   {
                                        $tmp4 .= "navigator.serviceWorker.register(\"" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[1] . "&type=src";
                                   }
                                   $tmp4 .= substr($gg[$xx], $h);
                              }
                         }

                         $cc[$dd] = $tmp4;
                    }

                    // "testing2"; bad facebook can have 10k+ pages.
                    //$tmp4="";
                    //if (strpos($cc[$dd],"\"src\":\"")!==FALSE) {
                    //$gg=explode("\"src\":\"",$cc[$dd]);
                    //$xx=0;
                    //for($xx=0;$xx<count($gg);$xx++) {
                    //if ($xx==0 && !startsWith($cc[$dd],"\"src\":\"")) {$tmp4.=$gg[$xx];}
                    //else {
                    //$h=strpos($gg[$xx],",");
                    //if (strpos($gg[$xx],"}")>0 && strpos($gg[$xx],"}")<$h) {$h=strpos($gg[$xx],"}");}
                    //$ee=substr($gg[$xx],0,$h);
                    //$kk=filelist($ee,$urls,$urls2,$xurls,$index,$path,$c_url,$c_proto,$c_port);
                    //if ($kk[0]) {$urls.=$kk[1];$urls2[$kk[3]]=1+count($urls2);$xurls.="img\n";$index++;$tmp4.="\"src\":\"".$loadFilePathFull."?fileid=".$fileid."&index=".$index."&type=img\"";} else {$tmp4.="\"src\":\"".$loadFilePathFull."?fileid=".$fileid."index=".$kk[2]."&type=img\"";}
                    //$tmp4.=substr($gg[$xx],$h);
                    //}//end else xx==0
                    //}//endfor
                    //$cc[$dd]=$tmp4;
                    //}//endif

                    $tmp4 = "";

                    if (strpos($cc[$dd], ";:&quot;https:\\/\\/") !== FALSE) 
                    {
                         //insert condition here/below to get rid of hashflags.
                         //echo "<br>css bug2:";
                         $gg = explode(";:&quot;https:\\/\\/", $cc[$dd]);
                         $xx = 0;

                         for($xx = 0; $xx < count($gg); $xx++) 
                         {
                              if (!(startsWith(strtolower($gg[$xx]), "data:") || startsWith(strtolower($gg[$xx]), "\ndata:"))) 
                              {
                                   if ($xx == 0 && !startsWith($cc[$dd], ";:&quot;https:\\/\\/")) 
                                   {
                                        $tmp4 .= $gg[$xx];
                                   }
                                   else 
                                   {
                                        $ee = "https:\\/\\/" . substr($gg[$xx], 0, strpos($gg[$xx],"&quot"));

                                        //$ee="https:\\/\\/".substr($gg[$xx],0,strpos($gg[$xx],")")+1);
                                        $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                                        if ($kk[0])
                                        {
                                             $urls .= $kk[1];
                                             $urls2[$kk[3]] = 1 + count($urls2);
                                             $xurls .= "img\n";
                                             $index++;
                                             $tmp4 .= ";:&quot;" . $loadFilePathFullEncoded . "?fileid=" . $fileid . "&index=" . $index . "&type=img";
                                        } 
                                        else 
                                        {
                                             if ($kk[2] > 0)
                                             {
                                                  $tmp4 .= ";:&quot;" . $loadFilePathFullEncoded . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=img";
                                             }
                                        }

                                        if ($kk[2] > 0)
                                        {
                                             $tmp4 .= substr($gg[$xx], strpos($gg[$xx], ")"));
                                        }
                                        else 
                                        {
                                             $tmp4 .= ";:&quot;https:\\/\\/" . $gg[$xx];
                                        }
                                   }
                              }
                              else 
                              {
                                   $tmp4 .= ";:&quot;https:\\/\\/" . $gg[$xx];
                              }
                         }

                         $cc[$dd] = $tmp4;
                    }

                    $tmp4 = "";

                    if (strpos($cc[$dd], ":url(") !== FALSE)
                    {
                         //echo "<br>css bug3:";
                         //echo "hello1";
                         $gg = explode(":url(", $cc[$dd]);
                         $xx = 0;

                         for($xx = 0; $xx < count($gg); $xx++) 
                         {
                              if (!(startsWith(strtolower($gg[$xx]), "data:") || startsWith(strtolower($gg[$xx]), "\"data:"))) 
                              {
                                   if ($xx == 0 && !startsWith($cc[$dd], ":url(")) 
                                   {
                                        $tmp4 .= $gg[$xx];
                                   }
                                   else 
                                   {
                                        $ee = substr($gg[$xx], 0, strpos($gg[$xx], ")"));
                                        $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                                        if ($kk[0]) 
                                        {
                                             $urls .= $kk[1];
                                             $urls2[$kk[3]] = 1 + count($urls2);
                                             $xurls .= "img\n";
                                             $index++;
                                             $tmp4 .= ":url(" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=img";
                                        } 
                                        else 
                                        {
                                            if ($kk[2] > 0) 
                                             {
                                                 $tmp4 .= ":url(" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=img";
                                             }
                                        }

                                        if ($kk[2] > 0)
                                        {
                                             $tmp4 .= substr($gg[$xx], strpos($gg[$xx], ")"));
                                        } 
                                        else 
                                        {
                                             $tmp4 .= ":url(" . $gg[$xx];
                                        }
                                   }
                              }
                              else 
                              {
                                   $tmp4 .= ":url(" . $gg[$xx];
                              }
                         }    

                         $cc[$dd] = $tmp4;
                    }
                    //debugmode("parsedata","2750",strlen($code));

                    $tmp4 = "";

                    if (strpos($cc[$dd], ",url(") !== FALSE) 
                    {
                         //echo "<br>css bug4:";
                         $gg = explode(",url(", $cc[$dd]);
                         $xx = 0;

                         for($xx = 0; $xx < count($gg); $xx++) 
                         {
                              if (!(startsWith(strtolower($gg[$xx]), "data:")|| startsWith(strtolower($gg[$xx]), "\ndata:"))) 
                              {
                                   if ($xx==0 && !startsWith($cc[$dd], ",url(")) 
                                   {
                                        $tmp4 .= $gg[$xx];
                                   }
                                   else 
                                   {
                                        $ee = substr($gg[$xx], 0, strpos($gg[$xx], ")"));
                                        $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                                        if ($kk[0]) 
                                        {
                                             $urls .= $kk[1];
                                             $urls2[$kk[3]] = 1 + count($urls2);
                                             $xurls .= "img\n";
                                             $index++;
                                             $tmp4 .= ",url(" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=img";
                                        } 
                                        else 
                                        {
                                             if ($kk[2] > 0)
                                             {
                                                  $tmp4 .= ",url(" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2]."&type=img";
                                             }
                                        }
                                        if ($kk[2] > 0)
                                        {
                                             $tmp4 .= substr($gg[$xx], strpos($gg[$xx], ")"));
                                        } 
                                        else
                                        {
                                             $tmp4 .= ",url(" . $gg[$xx];
                                        }
                                   }
                              }
                              else 
                              {
                                   $tmp4 .= ",url(" . $gg[$xx];
                              }
                         }

                         $cc[$dd] = $tmp4;
                    }
                    //debugmode("parsedata","2750",strlen($code));

                    if (strpos($cc[$dd],",\nurl(")!==FALSE) 
                    {
                         //echo "<br>css bug4:";
                         $gg = explode(",\nurl(", $cc[$dd]);
                         $xx = 0;

                         for($xx = 0; $xx < count($gg); $xx++) 
                         {
                              if (!(startsWith(strtolower($gg[$xx]), "data:") || startsWith(strtolower($gg[$xx]), "\ndata:"))) 
                              {
                                   if ($xx == 0 && !startsWith($cc[$dd], ",\nurl("))
                                   {
                                        $tmp4 .= $gg[$xx];
                                   }
                                   else 
                                   {
                                        $ee = substr($gg[$xx], 0, strpos($gg[$xx], ")"));
                                        $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);

                                        if ($kk[0])
                                        {
                                             $urls .= $kk[1];
                                             $urls2[$kk[3]] = 1 + count($urls2);
                                             $xurls .= "img\n";
                                             $index++;
                                             $tmp4 .= ",\nurl(" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=img";
                                        }
                                        else 
                                        {
                                             if ($kk[2]>0)
                                             {
                                                  $tmp4 .= ",url(" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=img";
                                             }
                                        }
                                        if ($kk[2] > 0)
                                        {
                                             $tmp4 .= substr($gg[$xx], strpos($gg[$xx], ")"));
                                        } 
                                        else
                                        {
                                             $tmp4 .= ",\nurl(" .$gg[$xx];
                                        }
                                   }
                              }
                              else 
                              {
                                   $tmp4 .= ",\nurl(" . $gg[$xx];
                              }
                         }

                         $cc[$dd] = $tmp4;
                    }

                    //new
                    $tmp4 = "";

                    if (strpos($cc[$dd], ",uri:\"http") !== FALSE) 
                    {
                         //echo "<br>css bug5:";
                         $gg = explode(",uri:", $cc[$dd]);
                         $xx = 0;

                         for($xx = 0; $xx < count($gg); $xx++) 
                         {
                              if (!(startsWith(strtolower($gg[$xx]), "data:") || startsWith(strtolower($gg[$xx]) ,"\"data:"))) 
                              {
                                   if ($xx == 0 && !startsWith($cc[$dd], ",uri:"))
                                   {
                                        $tmp4 .= $gg[$xx];
                                   }
                                   else 
                                   {
                                        $ee = substr($gg[$xx], 0, strpos($gg[$xx], ")"));
                                        $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                                        if ($kk[0])
                                        {
                                             $urls .= $kk[1];
                                             $urls2[$kk[3]] = 1 + count($urls2);
                                             $xurls .= "img\n";
                                             $index++;
                                             $tmp4 .= ",uri:" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=img";
                                        } 
                                        else 
                                        {
                                             if ($kk[2] > 0)
                                             {
                                                  $tmp4 .= ",uri:" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=img";
                                             }
                                        }
                                        if ($kk[2] > 0)
                                        {
                                             $tmp4 .= substr($gg[$xx], strpos($gg[$xx], ")"));
                                        } 
                                        else 
                                        {
                                             $tmp4 .= ",url:" . $gg[$xx];
                                        }
                                   }
                              }
                              else 
                              {
                                   $tmp4 .= ",url:" . $gg[$xx];
                              }
                         }

                         $cc[$dd] = $tmp4;
                    }

                    $tmp4 = "";

                    //echo "\n<br>dd:".$dd." ".$jsmode;

                    if ($scriptopen || $jsmode==0) 
                    {
                         //echo "\n<br>madeitin:";

                         if (strpos($cc[$dd], " fetch(") !== FALSE) 
                         {
                              $gg = explode(" fetch(", $cc[$dd]);
                              $xx = 0;

                              for($xx=0; $xx<count($gg); $xx++) 
                              {
                                   if (!(startsWith(strtolower($gg[$xx]), "data:") || startsWith(strtolower($gg[$xx]) ," fetch(") )) 
                                   {
                                        if ($xx == 0 && !startsWith($cc[$dd], " fetch(")) 
                                        {
                                             $tmp4 .= $gg[$xx];
                                        }
                                        else 
                                        {
                                             $mark = ")";
                                             $ee = substr($gg[$xx], 0, strpos($gg[$xx], ")"));
                                             $ef = substr($gg[$xx], 0, strpos($gg[$xx], ","));

                                             if (strlen($ee)==0) 
                                             {
                                                  $ee = $ef;
                                                  $mark = ",";
                                             }
                                             else if (strlen($ef) == 0) 
                                             {}
                                             else if (strlen($ee) > strlen($ef)) 
                                             {
                                                  $ee = $ef;
                                                  $mark = ",";
                                             }

                                             $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);

                                             if ($kk[0]) 
                                             {
                                                  $urls .= $kk[1];
                                                  $urls2[$kk[3]] = 1 + count($urls2);
                                                  $xurls .= "\n";
                                                  $index++;
                                                  $tmp4 .= " fetch('" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "'";
                                             }
                                             else 
                                             {
                                                  if ($kk[2] > 0) 
                                                  {
                                                       $tmp4 .= " fetch('" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "'";
                                                  }
                                                  else 
                                                  {
                                                       $tmp4 .= " fetch(" . $gg[$xx];
                                                  }
                                             }

                                             if ($kk[2] > 0) 
                                             {
                                                  $tmp4 .= substr($gg[$xx], strlen($ee));

                                                  if ($mark === ",") 
                                                  {
                                                       $ttmp = substr($gg[$xx], strlen($ee));
                                                       if (strpos($ttmp, "body:") !== false) 
                                                       {
                                                            $ttmp2 = substr($ttmp, strpos($ttmp, "body:"));
                                                            $ttmp2 = substr($ttmp2, 0, strpos($ttmp2, ")"));
                                                            echo "\n<br>ttmp2:" . $ttmp2;
                                                            if (strpos($ttmp2, "JSON.stringify(") !== FALSE)
                                                            {
                                                                 $ttmp2 = substr($ttmp2, strpos($ttmp2, "JSON.stringify(") + 15);
                                                                 echo "\n<br>ttmp2:" . $ttmp2;
                                                                 $postdata[$kk[3]] = $ttmp2;
                                                            }
                                                            else
                                                            {
                                                                 echo "\n<br>ttmp2" . $ttmp2;
                                                            }
                                                       }
                                                  }
                                             }
                                        }
                                   }
                                   else 
                                   {
                                        $tmp4 .= " fetch(" . $gg[$xx];
                                   }
                                   echo "<br>temp4" . $tmp4;
                              }

                              $cc[$dd] = $tmp4;
                              $tmp4 = "";
                         }
                         //echo "\n<br>cc[dd]".$cc[$dd];

                         if (strpos($cc[$dd],"= 'https")!==FALSE) 
                         {
                              //echo "\n<br>madeitin2:";

                              $gg = explode("= 'https", $cc[$dd]);
                              $xx = 0;

                              for($xx = 0; $xx < count($gg); $xx++) 
                              {
                                   if (!(startsWith(strtolower($gg[$xx]), "data:") || startsWith(strtolower($gg[$xx]), "= 'https"))) 
                                   {
                                        if ($xx == 0 && !startsWith($cc[$dd], "= 'https"))
                                        {
                                             $tmp4 .= $gg[$xx];
                                        }
                                        else 
                                        {
                                             $ee = "https" . substr($gg[$xx], 0, strpos($gg[$xx], "'"));
                                             echo "<br>ee:" . $ee;

                                             if (strpos($ee, "blarchive.net") > 0)
                                             {
                                                  $tmp4 .="= 'https" . $gg[$xx];
                                             } 
                                             else 
                                             {
                                                  $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);

                                                  if ($kk[0])
                                                  {
                                                       $urls .= $kk[1];
                                                       $urls2[$kk[3]] = 1 + count($urls2);
                                                       $xurls .= "\n";
                                                       $index++;
                                                       $tmp4 .= "= '" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index;
                                                  }
                                                  else 
                                                  {
                                                       $tmp4 .= "= '" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2];
                                                  }

                                                  $tmp4 .= substr($gg[$xx], strpos($gg[$xx], "'"));
                                             }
                                        }
                                   }
                                   else 
                                   {
                                        $tmp4 .="= 'https" . $gg[$xx];
                                   }
                              }
                              $cc[$dd] = $tmp4;
                              $tmp4 = "";
                         }

                         if (strpos($cc[$dd], "= 'http:") !== FALSE) 
                         {
                              $gg = explode("= 'http:", $cc[$dd]);
                              $xx = 0;

                              for($xx=0; $xx < count($gg); $xx++) 
                              {
                                   if (!(startsWith(strtolower($gg[$xx]), "data:") || startsWith(strtolower($gg[$xx]), "= 'http:"))) 
                                   {
                                        if ($xx == 0 && !startsWith($cc[$dd], "= 'http:"))
                                        {
                                             $tmp4 .= $gg[$xx];
                                        }
                                        else 
                                        {
                                             $ee = "http:" . substr($gg[$xx], 0, strpos($gg[$xx], "'"));

                                             if (strpos($ee, "blarchive.net") > 0)
                                             {
                                                  $tmp4 .= "= 'http:" . $gg[$xx];
                                             }
                                             else
                                             {
                                                  $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                                                  if ($kk[0]) 
                                                  {
                                                       $urls .= $kk[1];
                                                       $urls2[$kk[3]] = 1 + count($urls2);
                                                       $xurls .= "\n";
                                                       $index++;
                                                       $tmp4 .= "= '" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index;
                                                  }
                                                  else 
                                                  {
                                                       $tmp4 .= "= '" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2];
                                                  }

                                                  $tmp4 .= substr($gg[$xx], strpos($gg[$xx], "'"));
                                             }
                                        }
                                   }
                                   else 
                                   {
                                        $tmp4 .= "= 'http:" . $gg[$xx];
                                   }
                              }

                              $cc[$dd] = $tmp4;
                              $tmp4 = "";
                         }

                         if (strpos($cc[$dd], "='https") !== FALSE) 
                         {
                              $gg = explode("='https", $cc[$dd]);
                              $xx = 0;

                              for($xx = 0; $xx < count($gg); $xx++) 
                              {
                                   if (!(startsWith(strtolower($gg[$xx]), "data:") || startsWith(strtolower($gg[$xx]), "='https"))) 
                                   {
                                        if ($xx == 0 && !startsWith($cc[$dd], "='https"))
                                        {
                                             $tmp4 .= $gg[$xx];
                                        }
                                        else 
                                        {
                                             $ee = "https" . substr($gg[$xx], 0, strpos($gg[$xx], "'"));

                                             if (strpos($ee, "blarchive.net") > 0)
                                             {
                                                  $tmp4 .= "='https" . $gg[$xx];
                                             }
                                             else 
                                             {
                                                  $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);

                                                  if ($kk[0]) 
                                                  {
                                                       $urls .= $kk[1];
                                                       $urls2[$kk[3]] = 1 + count($urls2);
                                                       $xurls .= "\n";
                                                       $index++; 
                                                       $tmp4 .= "='" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index;
                                                  }
                                                  else 
                                                  {
                                                       $tmp4 .= "='" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2];
                                                  }

                                                  $tmp4 .= substr($gg[$xx], strpos($gg[$xx], "'"));
                                             }
                                        }
                                   }
                                   else 
                                   {
                                        $tmp4 .= "='https" . $gg[$xx];
                                   }
                              }

                              $cc[$dd] = $tmp4;
                              $tmp4 = "";
                         }

                         if (strpos($cc[$dd], "='http:") !== FALSE) 
                         {
                              $gg = explode("='http:", $cc[$dd]);
                              $xx = 0;

                              for($xx = 0; $xx < count($gg); $xx++) 
                              {
                                   if (!(startsWith(strtolower($gg[$xx]), "data:") || startsWith(strtolower($gg[$xx]), "='http:"))) 
                                   {
                                        if ($xx == 0 && !startsWith($cc[$dd], "='http"))
                                        {
                                             $tmp4 .= $gg[$xx];
                                        }
                                        else 
                                        {
                                             $ee = "http:" . substr($gg[$xx], 0, strpos($gg[$xx], "'"));

                                             if (strpos($ee, "blarchive.net") > 0)
                                             {
                                                  $tmp4 .= "='http:" . $gg[$xx];
                                             } 
                                             else 
                                             {
                                                  $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);

                                                  if ($kk[0]) 
                                                  {
                                                       $urls .= $kk[1];
                                                       $urls2[$kk[3]] = 1 + count($urls2);
                                                       $xurls .= "\n";
                                                       $index++;
                                                       $tmp4 .= "='" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index;
                                                  }
                                                  else 
                                                  {
                                                       $tmp4 .= "='" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2];
                                                  }

                                                  $tmp4 .= substr($gg[$xx], strpos($gg[$xx], "'"));
                                             }
                                        }
                                   }
                                   else 
                                   {
                                        $tmp4 .= "='http:" . $gg[$xx];
                                   }
                              }

                              $cc[$dd] = $tmp4;
                              $tmp4 = "";
                         }

                         if (strpos($cc[$dd], "= \"/") !== FALSE) 
                         {
                              $gg = explode("= \"/", $cc[$dd]);
                              $xx = 0;

                              for($xx = 0; $xx < count($gg); $xx++) 
                              {
                                   if (!(startsWith(strtolower($gg[$xx]), "data:") || startsWith(strtolower($gg[$xx]), "= \"/"))) 
                                   {
                                        if ($xx == 0 && !startsWith($cc[$dd], "= \"/"))
                                        {
                                             $tmp4 .= $gg[$xx];
                                        }
                                        else 
                                        {
                                             $ee = "/" . substr($gg[$xx], 0, strpos($gg[$xx], "\""));
                                             $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);

                                             if ($kk[0]) 
                                             {
                                                  $urls .= $kk[1];
                                                  $urls2[$kk[3]] = 1 + count($urls2);
                                                  $xurls .= "\n";
                                                  $index++;
                                                  $tmp4 .= "= \"" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index;
                                             }
                                             else 
                                             {
                                                  $tmp4 .= "= \"" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2];
                                             }

                                             $tmp4 .= substr($gg[$xx], strpos($gg[$xx], "\""));
                                        }
                                   }
                                   else 
                                   {
                                        $tmp4 .= "= \"/" . $gg[$xx];
                                   }
                              }

                              $cc[$dd] = $tmp4;
                              $tmp4 = "";
                         }

                         if (strpos($cc[$dd], "= '/") !== FALSE) 
                         {
                              $gg = explode("= '/", $cc[$dd]);
                              $xx = 0;

                              for($xx = 0; $xx < count($gg); $xx++) 
                              {
                                   if (!(startsWith(strtolower($gg[$xx]), "data:") || startsWith(strtolower($gg[$xx]), "= '/"))) 
                                   {
                                        if ($xx == 0 && !startsWith($cc[$dd], "= '/")) 
                                        {
                                             $tmp4 .= $gg[$xx];
                                        }
                                        else 
                                        {
                                             $ee = "/" . substr($gg[$xx], 0, strpos($gg[$xx], "'"));
                                             $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);

                                             if ($kk[0]) 
                                             {
                                                  $urls .= $kk[1];
                                                  $urls2[$kk[3]] = 1 + count($urls2);
                                                  $xurls .= "\n";
                                                  $index++;
                                                  $tmp4 .= "= '" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index;
                                             }
                                             else 
                                             {
                                                  $tmp4 .= "= '" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2];
                                             }
                                             
                                             $tmp4 .= substr($gg[$xx], strpos($gg[$xx], "'"));
                                        }
                                   }
                                   else 
                                   {
                                        $tmp4 .= "= '/" . $gg[$xx];
                                   }
                              }

                              $cc[$dd] = $tmp4;
                              $tmp4 = "";
                         }

                         if (strpos($cc[$dd], ".src=\"/") !== FALSE) 
                         {
                              $gg = explode(".src=\"/", $cc[$dd]);
                              $xx = 0;

                              for($xx = 0; $xx < count($gg); $xx++) 
                              {
                                   if (!(startsWith(strtolower($gg[$xx]), "data:") || startsWith(strtolower($gg[$xx]), ".src=\"/"))) 
                                   {
                                        if ($xx == 0 && !startsWith($cc[$dd], ".src=\"/")) 
                                        {
                                             $tmp4 .= $gg[$xx];
                                        }
                                        else 
                                        {
                                             $ee = "/" . substr($gg[$xx], 0, strpos($gg[$xx], "\""));
                                             $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);

                                             if ($kk[0]) 
                                             {
                                                  $urls .= $kk[1];
                                                  $urls2[$kk[3]] = 1+count($urls2);
                                                  $xurls .= "\n";
                                                  $index++;
                                                  $tmp4 .= ".src=\"" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index;
                                             }
                                             else 
                                             {
                                                  $tmp4 .= ".src=\"" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2];
                                             }
                                             $tmp4 .= substr($gg[$xx], strpos($gg[$xx], "\""));
                                        }
                                   }
                                   else 
                                   {
                                        $tmp4 .= ".src=\"/" . $gg[$xx];
                                   }
                              }

                              $cc[$dd] = $tmp4;
                              $tmp4 = "";
                         }

                         if (strpos($cc[$dd], ".src='/") !== FALSE) 
                         {
                              $gg = explode(".src='/", $cc[$dd]);
                              $xx = 0;

                              for($xx = 0; $xx < count($gg); $xx++) 
                              {
                                   if (!(startsWith(strtolower($gg[$xx]), "data:") || startsWith(strtolower($gg[$xx]), ".src='/"))) 
                                   {
                                        if ($xx == 0 && !startsWith($cc[$dd], ".src='/")) 
                                        {
                                             $tmp4 .= $gg[$xx];
                                        }
                                        else 
                                        {
                                             $ee = "/" . substr($gg[$xx], 0, strpos($gg[$xx], "'"));
                                             $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);

                                             if ($kk[0]) 
                                             {
                                                  $urls .= $kk[1];
                                                  $urls2[$kk[3]] = 1 + count($urls2);
                                                  $xurls .= "\n";
                                                  $index++;
                                                  $tmp4 .= ".src='" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index;
                                             }
                                             else 
                                             {
                                                  $tmp4 .= ".src='" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2];
                                             }

                                             $tmp4 .= substr($gg[$xx], strpos($gg[$xx], "'"));
                                        }
                                   }
                                   else 
                                   {
                                        $tmp4 .= ".src='/" . $gg[$xx];
                                   }
                              }

                              $cc[$dd] = $tmp4;
                              $tmp4 = "";
                         }


                         /*
                         This would spider out to all the hrefs.
                         if (strpos($cc[$dd],"=\"/")!==FALSE) {
                         $gg=explode("=\"/",$cc[$dd]);
                         $xx=0;
                         for($xx=0;$xx<count($gg);$xx++) {
                         if (!(startsWith(strtolower($gg[$xx]),"data:") || startsWith(strtolower($gg[$xx]),"=\"/"))) {
                         if ($xx==0 && !startsWith($cc[$dd],"=\"/")) {$tmp4.=$gg[$xx];}
                         else {
                         $ee="/".substr($gg[$xx],0,strpos($gg[$xx],"\""));
                         $kk=filelist($ee,$urls,$urls2,$xurls,$index,$path,$c_url,$c_proto,$c_port);
                         if ($kk[0]) {$urls.=$kk[1];$urls2[$kk[3]]=1+count($urls2);$xurls.="\n";$index++;$tmp4.="=".$loadFilePathFull."?fileid=".$fileid."&index=".$index;}
                         else {$tmp4.="=".$loadFilePathFull."?fileid=".$fileid."&index=".$kk[2];}
                         $tmp4.=substr($gg[$xx],strpos($gg[$xx],"\""));}}
                         else {$tmp4.="=\"/".$gg[$xx];}}
                         $cc[$dd]=$tmp4;
                         $tmp4="";}

                         if (strpos($cc[$dd],"='/")!==FALSE) {
                         $gg=explode("='/",$cc[$dd]);
                         $xx=0;
                         for($xx=0;$xx<count($gg);$xx++) {
                         if (!(startsWith(strtolower($gg[$xx]),"data:") || startsWith(strtolower($gg[$xx]),"='/"))) {
                         if ($xx==0 && !startsWith($cc[$dd],"='/")) {$tmp4.=$gg[$xx];}
                         else {
                         $ee="/".substr($gg[$xx],0,strpos($gg[$xx],"\""));
                         $kk=filelist($ee,$urls,$urls2,$xurls,$index,$path,$c_url,$c_proto,$c_port);
                         if ($kk[0]) {$urls.=$kk[1];$urls2[$kk[3]]=1+count($urls2);$xurls.="\n";$index++;$tmp4.="=".$loadFilePathFull."?fileid=".$fileid."&index=".$index;}
                         else {$tmp4.="=".$loadFilePathFull."?fileid=".$fileid."&index=".$kk[2];}
                         $tmp4.=substr($gg[$xx],strpos($gg[$xx],"\""));}}
                         else {$tmp4.="='/".$gg[$xx];}}
                         $cc[$dd]=$tmp4;
                         $tmp4="";}
                         */

                         if (strpos($cc[$dd], "image:") !== FALSE) 
                         {
                              $gg = explode("image:", $cc[$dd]);
                              $xx = 0;

                              for($xx = 0; $xx < count($gg); $xx++) 
                              {
                                   if (!(startsWith(strtolower($gg[$xx]), "data:") || startsWith(strtolower($gg[$xx]), "image:"))) 
                                   {
                                        if ($xx == 0 && !startsWith($cc[$dd], "image:")) 
                                        {
                                             $tmp4 .= $gg[$xx];
                                        }
                                        else 
                                        {
                                             $ee = substr($gg[$xx], 0, strpos($gg[$xx], ","));
                                             $ef = substr($gg[$xx], 0, strpos($gg[$xx], "}"));
                                             if ($ee == 0)
                                             {
                                                  $ee = $ef;
                                             }
                                             else if ($ef == 0)
                                             {}
                                             else if (strlen($ee) > strlen($ef))
                                             {
                                                  $ee = $ef;
                                             }
                                             
                                             $ef = substr($gg[$xx], 0, strpos($gg[$xx], "\n"));
                                             
                                             if ($ee == 0)
                                             {
                                                  $ee = $ef;
                                             }
                                             else if ($ef == 0)
                                             {}
                                             else if (strlen($ee) > strlen($ef))
                                             {
                                                  $ee=$ef;
                                             }

                                             //echo "<br>ee2:".$ee;
                                             if ((strpos($ee, "/") === FALSE) 
                                             && (strpos($ee, ".gif") === FALSE) 
                                             && (strpos($ee, ".jpg") === FALSE) 
                                             && (strpos($ee, ".png") === FALSE) 
                                             && (strpos($ee, ".svg") === FALSE))
                                             {
                                                  $tmp4 .= "image: " . $gg[$xx];
                                             }
                                             else
                                             {
                                                  $kk  =filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);

                                                  if ($kk[0])
                                                  {
                                                       $urls .= $kk[1];
                                                       $urls2[$kk[3]] = 1 + count($urls2);
                                                       $xurls .= "img\n";
                                                       $index++;
                                                       $tmp4 .= "image: '" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "&type=img'";
                                                  }
                                                  else 
                                                  {
                                                       if ($kk[2] > 0) 
                                                       {
                                                            $tmp4 .= "image: '" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "&type=img'";
                                                       }
                                                  }
                                                  if ($kk[2] > 0) 
                                                  {
                                                       $tmp4 .= substr($gg[$xx], strlen($ee));
                                                  }
                                                  else 
                                                  {
                                                       $tmp4 .= "image: " . $gg[$xx];
                                                  }
                                                  //echo "<br>ee3:".$ee.":".$kk[1];
                                             }
                                        }
                                   }
                                   else 
                                   {
                                        $tmp4 .= "image: " . $gg[$xx];
                                   }
                              }

                              $cc[$dd] = $tmp4;
                              $tmp4 = "";
                         }

                         if (strpos($cc[$dd], "link:") !== FALSE) 
                         {
                              $gg = explode("link:", $cc[$dd]);
                              $xx = 0;
                              for($xx = 0; $xx < count($gg); $xx++) 
                              {
                                   if (!(startsWith(strtolower($gg[$xx]), "data:") 
                                   || startsWith(strtolower($gg[$xx]), "link:"))) 
                                   {
                                        if ($xx == 0 && !startsWith($cc[$dd], "link:")) 
                                        {
                                             $tmp4 .= $gg[$xx];
                                        }
                                        else 
                                        {
                                             $mark = "";
                                             $ee = substr($gg[$xx], 0, strpos($gg[$xx], ","));
                                             $mark = ",";
                                             $ef = substr($gg[$xx], 0, strpos($gg[$xx], "}"));

                                             if ($ee == 0) 
                                             {
                                                  $ee = $ef;
                                             }
                                             else if ($ef == 0) 
                                             {

                                             }
                                             else if (strlen($ee) > strlen($ef)) 
                                             {
                                                  $ee = $ef;
                                                  $mark="}";
                                             }
                                             $ef = substr($gg[$xx], 0, strpos($gg[$xx], "\n"));
                                             
                                             if ($ee == 0) 
                                             {
                                                  $ee = $ef;
                                             }
                                             else if ($ef == 0) 
                                             {}
                                             else if (strlen($ee) > strlen($ef))
                                             {
                                                  $ee = $ef;
                                                  $mark = "\n";
                                             }
                                             if (strpos($ee, "https://") !== FALSE 
                                                  || strpos($ee, "https://") !== FALSE ) 
                                             {
                                                  $kk = filelist($ee, $urls, $urls2, $xurls, $index, $path, $c_url, $c_proto, $c_port);
                                                  
                                                  if ($kk[0]) 
                                                  {
                                                       $urls .= $kk[1];
                                                       $urls2[$kk[3]] = 1 + count($urls2);
                                                       $xurls .= "\n";
                                                       $index++;
                                                       $tmp4 .= "link: '" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $index . "'" . $mark;
                                                  }
                                                  else 
                                                  {
                                                       $tmp4 .= "link: '" . $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $kk[2] . "'" . $mark;
                                                  }
                                                  $tmp4 .= substr($gg[$xx], strlen($ee));
                                             }
                                             else 
                                             {
                                                  $tmp4 .= "link: " . $gg[$xx];
                                             }
                                        }
                                   }
                                   else 
                                   {
                                        $tmp4 .= "link: " . $gg[$xx];
                                   }
                              }
                              $cc[$dd] = $tmp4;
                              $tmp4 = "";
                         }
                    }
               }

               $code .= ($dd == 0 ? "" : "<") . $space . $cc[$dd];

               //($dd==0?(startsWith($ll,"<")?"<":""):"<")
          } //end script/comment check
          else if ($scriptopen && !$commentopen)  
          {
               //$cc[$dd]=str_ireplace(".cookie","",$cc[$dd]);
               //$cc[$dd]=str_ireplace("alert(","(",$cc[$dd]);
               //echo "<br>css bug6:";

               //windowstates
               //$cc[$dd]=str_ireplace(".onblur","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onfocusin","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onfocusout","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onfocus","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onoffline","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".ononline","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onpagehide","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onpageshow","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onresize","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onsearch","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onscroll","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".ontoggle","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onunload","",$cc[$dd]);

               //clipboard
               //$cc[$dd]=str_ireplace(".oncopy",".onabort",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".oncut",".onabort",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onpaste",".onabort",$cc[$dd]);

               //other
               //$cc[$dd]=str_ireplace(".onabort","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onafterprint","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onerror","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onbeforeunload","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onchange",".onabort",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onhashchange","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onmessage",".onabort",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onopen","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onshow","",$cc[$dd]);

               //video metadata or video
               //$cc[$dd]=str_ireplace(".oncanplaythrough","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".oncanplay","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onloadeddata","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onloadstart","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onload","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onloadedmetadata","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onplaying","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onprogress","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onratechange","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onseeked","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onseeking","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onstalled","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onsuspend","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".ontimeupdate","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onvolumechange","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onwaiting","",$cc[$dd]);

               //dragging stuff
               //$cc[$dd]=str_ireplace(".oncontextmenu","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".ondragend","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".ondragenter","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".ondragleave","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".ondragover","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".ondragstart","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".ondrag","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".ondrop","",$cc[$dd]);

               //keys/forms
               //$cc[$dd]=str_ireplace(".oninput",".onabort",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".oninvalid",".onabort",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onkeyup",".onabort",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onkeypress",".onabort",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onkeydown",".onabort",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onreset",".onabort",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onsubmit",".onabort",$cc[$dd]);

               //mouse
               //$cc[$dd]=str_ireplace(".onclick","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".ondblclick","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onmousedown","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onmouseenter","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onmouseleave","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onmousemove","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onmouseover","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onmouseout","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onmouseup","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onmousepress","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onselect","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".onwheel","",$cc[$dd]);

               //touch
               //$cc[$dd]=str_ireplace(".ontouchcancel","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".ontouchend","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".ontouchmove","",$cc[$dd]);
               //$cc[$dd]=str_ireplace(".ontouchstart","",$cc[$dd]);

               //general
               //$cc[$dd]=str_ireplace(".addEventListener(",".onabort(",$cc[$dd]);

               //debugmode("parsedata","2500",strlen($code));
               $code .= ($dd == 0 ? "" : "<") . $space . $cc[$dd];
               //debugmode("parsedata","3000",strlen($code));
          }
          else 
          {
               //echo "<br>css bug7:";
               $code .= ($dd == 0 ? "" : "<") . $space . $cc[$dd];
               //echo "<br>strlen3:".strlen($cc[$dd]);
          }
          //echo "<br>strlen4:".strlen($cc[$dd]);
     }//endfor
  //   }//endfor
     //debugmode("parsedata","end",strlen($code));

     $return[0] = $urls;
     $return[1] = $code;
     $return[2] = $index;
     $return[3] = $xurls;
     $return[4] = $urls2;

     return $return;
}
//end functions

// Begin Page Code

// If no URL Exists
if (!isset($_GET["url"])) 
{
     // start rendering HTML for page
     echo "<html>";

     // handle user login status and user lookup
     if (isset($_GET["access_token"])) 
     {
          $_SESSION["access_token"] = $_GET["access_token"];
          $li = true;

          if (!isset($_SESSION["user"])) 
          {
               $li = false;
          }

          // access hivesigner
          $ch = curl_init($ss1 . "/api/me");

          curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "POST");
          curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

          curl_setopt($ch,CURLOPT_HTTPHEADER, array('accept: application/json, text/plain, */*','Content-type: application/json','authorization: ' . $_SESSION["access_token"],'DNT:1','Origin:https://blarchive.net','Referer:https://blarchive.net','sec-fetch-mode: cors','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));

          $result = curl_exec($ch);          

          //echo "get[username]:".$_GET["username"];

          // if the user is found in the result from hivesigner
          if (strpos($result, "\"user\":\"" . $_GET["username"] . "\",\"") > 0)
          {
               $_SESSION["user"] = $_GET["username"];

               if (!$li) 
               {
                    if (ISSET($_SESSION["user"])) 
                    {
                         // grab user from local database
                         $tsql = "SELECT count(`user`) as a from lookup where `user` = ? AND `filetime` > ?";
                         $sql = $conn->prepare($tsql);
                         $sql->bind_param("si", $_SESSION["user"], $time);
                         if ($sql->execute())
                         {
                              $result = $sql->get_result();
                              while ($row = $result->fetch_assoc()) 
                              {
                                   $nn = $row["a"];
                              }
                         }
                         else 
                         {
                              echo "Error: " . $tsql . "" . mysqli_error($conn); 
                         }

                         if ($nn > $dailylimit) 
                         {
                              // warn if daily usage limit is exceeded
                              echo "exceeded daily limit";
                         }
                         else 
                         {
                              // display remaining usage for the day
                              echo "<br>";echo ($dailylimit - $nn);
                              echo " more stores remaining today<br>";
                         }
                    }
               }
          }
          else 
          {
               echo "invalid credentials";
               $_SESSION["user"] = "";
          }
     }

     if (isset($_SESSION["access_token"])) 
     {
          // display logged in user navigation
          echo "Welcome" . $_SESSION["user"];
          echo "<a href=logout.php>logout</a>";
          echo "<form method=GET action=dourl4.php>";
          echo "<input type=textbox name=url id=url>
          <br>
          <table><tr><td><input type=radio name=blarchivemode value=0 checked> All file types
          </td><td><input type=radio name=blarchivemode value=1> No videos
          </td><td><input type=radio name=blarchivemode value=2> Limited videos, sounds, images
          </td></tr></table>
          <br>
          Attempt Javascript?<br>
          <table><tr><td><input type=radio name=blarchivejsmode value=1 checked> No
          </td><tr><td><input type=radio name=blarchivejsmode value=0 > Yes (buggy)
          </td></tr></table>

          <input type=submit value=\"click to add\"></input>
          </form>";
          echo "<!--form method=GET action=dourl2.php>";
          echo "<input type=textbox name=url id=url>
          <input type=submit value=\"click to add without videos\"></input>
          </form>";

          echo "<form method=GET action=dourl3.php>";
          echo "<input type=textbox name=url id=url>
          <input type=submit value=\"click to add without images nor videos\"></input>
          </form>-->";

          // connect to local database
          $conn = new mysqli($servername, $username, $password, "archive");

          global $siteDomainUrl;

          if ($conn->connect_error) 
          {
               die("connection to the database failed:". $conn->connect_error);
          }

          $serverz = $siteDomainUrl;
          $serverz = str_replace(":", "%3A", $serverz);
          $serverz = str_replace("/", "%2F", $serverz);
          $timen = time() - 29*3600*24;

          // get user account info from local database
          $sql = $conn->prepare( "select lookup.fileid,filepath,filetime,payment,added,  accounting.fileid, sum(accounting.paid) as m2 from lookup join accounting on lookup.fileid=accounting.fileid WHERE lookup.user=? AND warn=0 AND payment>=0 AND filetime>?  group by accounting.fileid order by lookup.fileid DESC");
          $sql->bind_param("sd", $_SESSION["user"], $timen);

          if(!$sql->execute())
          {
               trigger_error("there was an error...." . $conn->error, E_USER_WARNING);
          }

          $result = $sql->get_result();

          // list previous account actions and accounting info
          echo "<table><tr><th>transaction</th><th>url<th>Time</th><th>paid</th>";
         
          while ($row = $result->fetch_assoc())
          {
               echo "<tr><td><a href=preview1.php?fileid=" . $row["fileid"] . "&index=0>" . $row["fileid"] . "</a><br>" . (!($row["added"] > 0)?" <br><a href=" . $siteDomainUrl . "/" . encode($row["fileid"]) . ">" . $siteDomainUrl . "/" . encode($row["fileid"]) . "</a>":"")."</td><td>" . $row["filepath"] . "</td><td>" . substr(gmdate('r', $row["filetime"]), 4);
               echo "</td><td>";
               echo (floatval($row["m2"]) >= floatval($row["payment"]))?"Thank you." . ($row["added"] > 0?" <br><a href=" . $siteDomainUrl . "/" . encode($row["fileid"]) . ">" . $siteDomainUrl . "/" . encode($row["fileid"]) . "</a>":""):"<a href=" . $ss2 . "/sign/transfer?redirect_uri=" . $serverz . "%2Fphp%2Fdourl4.php&from=" . $_SESSION["user"] . "&to=" . $beneficiary . "&amount=" . $row["payment"] . $chain . "&memo=" . $row["fileid"] . ">Buy Now for " . $row["payment"] . " " . $chain . "</a></td></tr>";
          }
          $sql->close();
          echo "</table>";
     }
     else
     {
          // if user is not logged in, then provide a login link
          echo "<a href='" . $ss2 . "/oauth2/authorize?client_id=" . $appname . "&redirect_uri=https://blarchive.net/php/dourl4.php&scope=vote,comment,custom_json,comment_option'>log in here</a>";
     }
}
else 
{
     // if a url exists
     {
          echo "Welcome " . $_SESSION["user"];

          if (strlen($_SESSION["user"]) == 0)
          {
               echo "User not logged in<br><a href='" . $ss2 . "/oauth2/authorize?client_id=" . $appname . "&redirect_uri=https://blarchive.net/php/dourl4.php&scope=vote,comment,custom_json,comment_option'>log in here</a>";
               exit;
          }

          $conn = new mysqli($servername,$username,$password,"archive");

          if ($conn->connect_error) 
          {
               die("connection to the database failed:" . $conn->connect_error);
          }

          // why are random numbers being used to define keys?
          $token = rand(0,2100000000);
          $token2 = rand(0,2100000000);

          $def = -1;
          // nn = looksups made by users in previous 24 hours
          $nn = "";
          $user = $_SESSION["user"];
          $session = $_SESSION["access_token"];
          $time = time() -60*60*24;

          $tsql = "SELECT count(user) from lookup where filetime > " . $time;

          if ($result = mysqli_query($conn, $tsql)) 
          {
               while ($row = $result->fetch_row()) 
               {
                    printf ($nn = $row[0]);
               }
          } 
          else
          {
               echo "Error: " . $tsql . "" . mysqli_error($conn);
          }

          if ( $nn > $dailylimit)  
          {
               echo "exceeded daily limit";
               exit;
          }

          $xbad = count($badwords);

          $url = strToLower($_GET['url']);

          for ($i = 0; $i < $xbad; $i++)
          {
               if (strpos($url, $badwords[$i]) > 0)
               {
                    $warn = 1;
                    break;
               }
          }

          echo "<br>warn" . $warn;

          if ($warn == 1)
          {
               // output warning due to presence of bad words
               echo "The url:\"" . $url . "\" has a naughty word in it.<br> It'll store locally for now, but will need manual review before putting on the block chain. Unless this is later found to be bad content.";
          }

          $time2 = time();
          $fileload = "blacklist1";

          if ($time2%(24*60*60) > 20*60) 
          {
               if ($time2%(48*60*60) > 24*60*60) 
               {
                    $fileload="blacklist2";
               }
          }
          else 
          {
               if ($time2%(48*60*60) > 24*60*60) 
               {} 
               else 
               {
                    $fileload = "blacklist2";
               }
          }

          //echo "<br>fileload:".$fileload;
          $filename = $fileload . ".txt";
          $fp = fopen($filename, 'r');
          $result = fread($fp, filesize($filename));
          fclose($fp);

          $badwords3 = $result;
          $badwords2 = explode("\n", $result);
          $xbad2 = count($badwords2);
          echo "<br>xbad" . $xbad;

          if (($url !== "www.google.com") && ($url !== "gravatar.com"))
          {
               if (strpos($badwords3, "\n" . $url . "\n") > 0 || startsWith($badwords3 , $url ."\n")) {
                    $warn=2;
               }
          }

          //for($i=0;$i<$xbad2-1;$i++) {//if ($i>2154000) {echo "<br>i:".$i;}
          //if (strlen($badwords2[$i])>0) {
          //if ((strpos($url,$badwords2[$i])>0 || startsWith($url,$badwords2[$i])) && $badwords2[$i]!=="www.google.com" && $badwords[$i]!=="gravatar.com") {$warn=2;echo "<br>bad domain:".$badwords2[$i]." pevious:".$badwords2[$i-1];break;}}}

          if ($warn == 2)
          {
               echo "<br>" . $url . "<br>You broke the internet, stop loading that nasty \xf0\x9f\x92\xa9.  <br><iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/9Deg7VrpHbM\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe><br>Niether McDonalds nor Michael Jordan have endorsed anything to do with this website, nor do we have their copyrights or permission to use this video.<br> This video is used in fair use because you need psychiatric help for what you attempted";
               //$warn=0;
               exit;
          }

          $sql = $conn->prepare( "INSERT INTO `lookup` (`fileid`,`filepath`,`fileparts`,`token`,`token2`,`filetime`,`added`,`user`,`trans`,`payment`,`warn`,`bot`) VALUES (NULL, ? , ?, ?,?," . time() . ",0,?,0,-1,?,NULL)");

          $sql->bind_param("siiisi", $_GET['url'], $def, $token, $token2, $user, $warn);

          if(!$sql->execute())
          {
               trigger_error("there was an error...." . $conn->error, E_USER_WARNING);
          }

          $tsql = "SELECT fileid from lookup where token=" . $token . " and token2=" . $token2;

          if ($result = mysqli_query($conn, $tsql))
          {
               while ($row = $result->fetch_row()) 
               {       
                    printf ($fileid = $row[0]);
               }
               echo "<br>sqlrow:" . $fileid . "<br>";
               echo "New record created successfully";
          }
          else
          {
               echo "Error: " . $tsql . "" . mysqli_error($conn);
          }
          //$fileid=1;
          
          $sql = $conn->prepare( "INSERT INTO `accounting` (`tx`,`user`,`paid`,`fileid`) VALUES (NOW(),?,0,?)");
          $sql->bind_param("si", $_SESSION["user"], $fileid);

          if (!$sql->execute())
          {
               trigger_error("there was an error...." . $conn->error, E_USER_WARNING);
          }
          echo "<br>";

          $filenamez = "adserver.txt";
          $fpz = fopen($filenamez, 'r');
          $myfilez = fread($fpz, filesize($filenamez));
          fclose($fpz);
          $myfilez = str_replace("\n", "\r", $myfilez);
          //echo "<br>adserver strlen".strlen($myfilez);
          $adsites1 = explode("\r", $myfilez);
          $adc = count($adsites1);
          //echo "<br>adserver count".$adc;

          //echo "<br>list of ads:<br>";
          //foreach ($adsites1 as $ia) {echo "<br>".$ia;}
          //trip1

          echo "<br>trip 1 of 3";

          $codeindex=0;

          echo $_GET["url"];

          $baseurl = $_GET["url"];
          $pagetext = loadwebpage($_GET["url"]);
          $kk = $pagetext;
          $ll = $kk[0];
          $proto = $kk[1];
          $url = $kk[2];
          $port = $kk[3];
          $path = $kk[4];
          $urls = "\n";
          $urls2[strToLower($baseurl)] = 1;

          // why is this variable stated with no operation?
          $postdata;

          //echo "<br>urlscount:".count($urls)."<br>";
          $xurls = getMimeTypeFromExt($kk[2]) . "\n";
          $istwitter = false;
          $istwitter2 = false;
          $isyoutube = false;
          $isvideo = false;
          $isfacebook = false;

          if (strToLower($url) === "youtu.be") 
          {
               $pre = substr($ll, 0, strpos($ll, "div id=\"player-api") -1);
               $post = substr($ll, strpos($ll, "div id=\"player-api"));
               $post = substr($post, strpos($post, "<script"));
               $post = substr($post, strpos($post, "<\div"));
               $isyoutube = true;
          }

          if (strToLower($url) === "gab.com" || strToLower($url) === "www.gab.com") 
          {
               if (strpos($path, "/posts/") !== FALSE) 
               {
                    $pre = "https://gab.com/api/v1/statuses" . substr($path, strrpos($path, "/"));
                    //echo "<br>pre:".$pre;
                    $post = $pre . "/context";
                    //echo "<br>post:".$post;

                    $pagetext = loadwebpage($pre);
                    $pre = $pagetext[0];
                    $pagetext = loadwebpage($post);
                    $post = $pagetext[0];
                    //echo "<br>pre:".$pre;
                    //echo "<br>post:".$post;
                    //echo "<br>path:".$path;
                    //echo "<br>path(last slash):".substr($path,strrpos($path,"/"));

                    $ll = template("gab.com", $path, $pre, $post, "");
               }
               else if ((strpos($path, "/") !== FALSE) && (strrpos($path, "/") == 0))
               {
                    $avatar = substr($ll, strrpos($ll, "/avatars/")+9);
                    echo "<br>:" . $avatar;
                    $avatar = substr($avatar, 0, strrpos($avatar, "/original/"));
                    echo "<br>:" . $avatar;
                    $avatar = (int)(str_replace("/", "", $avatar));
                    echo "<br>:" . $avatar;
                    $pre = "https://gab.com/api/v1/accounts/" . $avatar . "/statuses";
                    echo "<br>pre:" . $pre;
                    $post = "https://gab.com/api/v1/accounts/" . $avatar . "/statuses" . "?pinned=true";
                    echo "<br>post:" . $post;
                    echo "<br>path:" . $path;

                    $pagetext = loadwebpage($pre);
                    $pre = $pagetext[0];
                    $pagetext = loadwebpage($post);
                    $post = $pagetext[0];
                    $extra = "https://gab.com/api/v1/account_by_username" . $path;
                    echo "<br>extra:" . $extra;
                    $pagetext = loadwebpage($extra);
                    $extra = $pagetext[0];
                    $ll = template("gab.com", $path, $pre, $post, $extra);
               }
          }

          if ((strToLower($url) === "www.instagram.com") || (strToLower($url) === "instagram.com"))
          {
               if (startsWith($path, "/p/"))
               {
                    $ll = template("instagram.com", "/p/", templatehelp($url.$path), "", "");
               }
               else
               {
                    $ll = template("instagram.com" , "", templatehelp($url.$path) , "", "");
               }
          }

          if ((strToLower($url) === "www.reddit.com") || (strToLower($url) === "reddit.com"))
          {
               $ll = template("reddit.com", "", templatehelp($url.$path), "", "");
          }

          if ((strToLower($url) === "www.cnn.com") || (strToLower($url) === "cnn.com" ))
          {
               $ll=template("cnn.com", "", templatehelp($url.$path), templatehelp("https://www.cnn.com/data/ocs/section/_homepage-zone-injection/index.html:homepage-injection-zone-1/views/zones/common/zone-manager.izl"), templatehelp("https://edition.cnn.com/data/ocs/section/index.html:intl_homepage1-zone-1/views/zones/common/zone-manager.izl") . templatehelp("https://edition.cnn.com/data/ocs/section/index.html:intl_homepage1-zone-2/views/zones/common/zone-manager.izl") . templatehelp("https://edition.cnn.com/data/ocs/section/index.html:intl_homepage1-zone-3/views/zones/common/zone-manager.izl") . templatehelp("https://edition.cnn.com/data/ocs/section/index.html:intl_homepage1-zone-4/views/zones/common/zone-manager.izl") . templatehelp("https://edition.cnn.com/data/ocs/section/index.html:intl_homepage2-zone-1/views/zones/common/zone-manager.izl") . templatehelp("https://edition.cnn.com/data/ocs/section/index.html:intl_homepage3-zone-1/views/zones/common/zone-manager.izl") . templatehelp("https://edition.cnn.com/data/ocs/section/index.html:intl_homepage3-zone-3/views/zones/common/zone-manager.izl") . templatehelp("https://edition.cnn.com/data/ocs/section/index.html:intl_homepage3-zone-4/views/zones/common/zone-manager.izl"));
               $ll = str_replace(".pg-hidden{display:none}", "", $ll);
          }

          if (strToLower($url) === "imgur.com" || strToLower($url) === "www.imgur.com" )
          {
               $ll=template("imgur.com", $url.$path, templatehelp($url.$path), "", "");
          }

          if ((strToLower($url) === "pinterest.com") || (strToLower($url) === "www.pinterest.com"))
          {
               if (startsWith($path, "/pin/")) 
               {
                    $ll = template("pinterest.com", "/pin/", templatehelp($url . $path), "", "");
               }
               else 
               {
                    $cpath = $path;
                    if (startsWith($cpath, "/")) 
                    {
                         $cpath = substr($cpath, 1);
                    }
                    if (startsWith($cpath, "/")) 
                    {
                         $cpath = substr($cpath, 0, strlen($cpath) -1);
                    }
                    // removed this section as it has an error and am not sure what it is needed to do
                    /*
                    if (substr_count($cpath) == 0)
                    {
                         $ll = template("pinterest.com", "/user/", templatehelp($url . $path), $url . $path, "");
                    }
                    */
                    else 
                    {
                         $ll = template("pinterest.com" , "" , templatehelp($url . $path), "", "");
                    }
               }
          }

          if ((strToLower($url) === "www.facebook.com") || (strToLower($url) === "facebook.com") || (strToLower($url) === "m.facebook.com"))
          { 
               $isfacebook=true;

               if (strpos($ll,"\"\"><form id=\"login_form")>0) 
               {
                    //echo "<br>found it";exit;
                    $pre = substr($ll, 0, strpos($ll, "\"\"><form id=\"login_form") +3);
                    $post = substr($ll, strlen($pre));
                    $post = "</div></div></div></div></div>" . substr($post, strpos($post, "<div class=\"clearfix"));
                    $ll = $pre.$post;
               }

               if (strpos(strToLower($path),"/videos/")>0)
               {
                    $pre = "";
                    $post = "";
                    if (strpos($ll, "<div id=\"permalink_video_pagelet") > 0) 
                    {
                         $pre = substr($ll, 0, strpos($ll, "<div id=\"permalink_video_pagelet"));
                         $post = substr($ll, strlen($pre));
                         $post = substr($post, strpos($post,">") +1);
                         $insert = "<div><video controls style=\"width:100%;height:100%\"><source src=\"https://blarchive.net/php/loadfile.php?fileid=" . $fileid . "&index=k&type=vid\" type=\"video/mp4\"></video>";
                         $ll = $pre . $insert . $post;
                    }
               }
          }

          if ((strToLower($url) === "www.youtube.com") || (strToLower($url) === "youtube.com"))
          {
               if (strpos(strToLower($path),"watch?")>0) 
               {
                    $pre = substr($ll, 0, strpos($ll, "div id=\"player-api") -1) . "<div id=\"player-api\"></div>";
                    $post = substr($ll, strpos($ll, "div id=\"player-api"));
                    $post = substr($post, strpos($post, "<script"));
                    $post = substr($post, strpos($post, "<\div"));
                    $middle = "<video controls style=\"width:100%;height:100%\">";
                    $middle .= "<source src=\"". $loadFilePathFull . "?fileid=" . $fileid . "&index=8&type=vid\" type=\"video/mp4\"></video>";
                    $ppost = substr($post, strrpos($post, "</body>"));
                    $post = substr($post, 0, strrpos($post, "</body>"));
                    $post .= "<script>document.getElementById(\"player\").remove();document.getElementById(\"player\").remove();";
                    $post .= "var div=document.createElement(\"DIV\");";
                    $post .= "div.innerHTML='<video controls style=\"width:90%;\"><source src=\"https://blarchive.net/php/loadfile.php?fileid=" . $fileid . "&index=k&type=vid\" type=\"video/mp4\"></video>';";
                    $post .= "var list = document.getElementById(\"primary-inner\");";
                    $post .= "list.insertBefore(div, list.childNodes[0]);";
                    $post .= "</script>";
                    $ll = $pre . $middle . $post . $ppost;
                    $isyoutube = true;
               }
          }
          //echo "<br>url:".$url." ".strlen($url);

          if ((strToLower($url )=== "www.twitter.com") || (strToLower($url) === "twitter.com"))
          {
               if (strpos(strToLower($path), "?s=") === FALSE) 
               {
                    $istwitter2 = true;
               }

               if (strpos(strToLower($path), "/status/") > 0 && strpos(strToLower($path), "?s=")=== FALSE) 
               {
                    //echo $ll;
                    $llinks = substr($ll, strpos($ll, "<head"));
                    $llinks = substr($llinks, 0, strpos($llinks, "</head>") +8);
                    $lbody = substr($ll, strpos($ll, "<body"));
                    $lbody = substr($lbody, 0, strpos($lbody, "<a href="));
                    $lmess = substr($ll, strpos($ll, "PermalinkOverlay-modal")- 12);
                    $lmess = substr($lmess, 0, strpos($lmess, "replies-to  permalink-inner permalink-replies") -12);
                    $ll = substr($ll, strpos($ll, "init-data\"") -25);
                    $ll = substr($ll, 0, strpos($ll, "</body>"));
                    $ll = $llinks . $lbody. "<base href=\"https://twitter.com/i\"><div id=\"permalink-overlay\">". $lmess . "</div><script nonce=\"UtNYW88tgAp6hFfnehH2Yw==\" id=\"track-ttft-body-script\">if(window.ttft){window.ttft.recordMilestone('page', document.getElementById('swift-page-name').getAttribute('content'));window.ttft.recordMilestone('section', document.getElementById('swift-section-name').getAttribute('content'));window.ttft.recordMilestone('client_record_time', window.ttft.now());}</script> . $ll . </body>";
                    $istwitter = true;
                    $istwitter2 = false;
               }

               if (strpos(strToLower($path), "?s=") > 0) 
               {
                    $istwitter = true;
               }
          }

          $kk = parsedata($ll, (startsWith($proto, "ssl") ? "https://" : "http://"), $url, ":" . $port, $path, $index, $loadFilePathFull, $fileid, $urls, $urls2, $xurls, true, false, $istwitter, $isvideo);
          $urls = $kk[0];
          $codes[$codeindex] = $kk[1];
          $demosize += strlen($kk[1]);
          $index = $kk[2];
          $xurls = $kk[3];
          $urls2 = $kk[4];

          mkdir($fileid);

          //trip2
          echo "<br>trip 2 of 3.<br>more than " . $index . " more pages to load";
          $zurls = str_replace("\n", "<br>url ", $urls);
          $rr = explode("\n", $urls);
          $ss = explode("\n", $xurls);
          $k = count($rr);
          $zurls = str_replace("\n", "<br>url ", $urls);
          echo "<br>k:". $k ."<br>start" . $zurls;
          echo "end";

          for($b=1; $b < $k-1; $b++)
          {
               $codeindex++;

               $url2 = substr($rr[$b], strpos($rr[$b], ":") +3);
               global $normal;
               $normal = true;
               if (ISSET($postdata[$rr[$b]]))
               {
                    $normal = false;
               }

               if (strpos($url2,":") > 0)
               {
                    $url2 = substr($url2, 0, strpos($url2, ":"));
               }

               if (strpos($url2, "/") > 0)
               {
                    $url2 = substr($url2, 0, strpos($url2, "/"));
               }

               for($i=0; $i<$xbad; $i++) 
               {
                    if (strpos($rr[$b], $badwords[$i]) > 0)
                    {
                         $warn=1;
                         echo "<br>bad word:" . $badwords[$i] . " pevious:" . $badwords[$i-1];break;
                    }
               }

               if ($warn == 1)
               {
                    echo "<br>The url:\"" . $rr[$b] . "\" has a naughty word in it.<br> It'll store locally for now, but will need manual review before putting on the block chain.  Unless this is later found to be bad content.";
               }

               if (($url2 !== "www.google.com") && ($url2 !=="gravatar.com"))
               {
                    if (strpos($badwords3, "\n" . $url2 . "\n") >0 || startsWith($badwords3,$url2 . "\n"))
                    {
                         $warn = 2;
                    }
               }

               if ($warn == 2)
               {
                    echo "<br>" . $url2 . "<br>You broke the internet, stop loading that nasty \xf0\x9f\x92\xa9.  <br><iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/9Deg7VrpHbM\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe><br>Niether McDonalds nor Michael Jordan have endorsed anything to do with this website, nor do we have their copyrights or permission to use this video.<br> This video is used in fair use because you need psychiatric help for what you attempted";
                    $sql = $conn->prepare( "UPDATE `lookup` SET `warn`=? WHERE `fileid`=?");
                    $sql->bind_param("ii", $warn,$fileid);
                    // why is there an exclamation mark here??
                    !$sql->execute();
                    exit;
               }

               echo "video:" . $video;
               echo "istwitter:" . $istwitter ? "true" : "false";
               echo "mode:" . $mode;

               if (($rr[$b] . "\n" === $video) && ($istwitter) && ($mode == 0))
               {
                    system("youtube-dl  -f bestvideo[ext=mp4]+bestaudio[ext=m4a]/mp4 -o " .$serverpath . $fileid . "/" . $b . ".mp4 " . $rr[$b]);
                    echo "<br>youtube-dl  -f bestvideo[ext=mp4]+bestaudio[ext=m4a]/mp4 -o " . $serverpath . $fileid . "/" . $b . ".mp4 " . $rr[$b] . "<br>";
                    $filename = $fileid . "/" . $b . ".mp4";
                    $fp = fopen($filename, 'r');
                    $result = fread($fp, filesize($filename));
                    fclose($fp);
                    $codes[$codeindex] = $result;
                    $demosize += strlen($result);
                    if (($demosize > 1024*10240) && ($demo))
                    {
                         echo "space exceeded demo";
                         exit;
                    }
               }
               else if ((($mode == 0) && (startsWith($rr[$b], "https://www.youtube.com/watch?v"))) 
                    || (($rr[$b] === "https://youtu.be/") && (strpos($rr[$b], "player") == 0) && (strpos($rr[$b], "jsbin") == 0)))
               {
                    system("youtube-dl  -f bestvideo[ext=mp4]+bestaudio[ext=m4a]/mp4 -o " .$serverpath . $fileid . "/" . $b . ".mp4 " . $rr[$b]);
                    echo "youtube-dl  -f bestvideo[ext=mp4]+bestaudio[ext=m4a]/mp4 -o " . $serverpath . $fileid . "/" . $b . ".mp4 " . $rr[$b];
                    $filename = $fileid . "/" . $b . ".mp4";
                    $fp = fopen($filename, 'r');
                    $result = fread($fp, filesize($filename));
                    fclose($fp);
                    $codes[$codeindex] = $result;
                    $demosize += strlen($result);
                    if (($demosize >1024*10240) && ($demo))
                    {
                         echo "space exceeded demo";
                         exit;
                    }
               }
               else
               {
                    // Does this URL have a letter missing in the protocol?
                    if ((strpos($rr[$b], "ttps://blarchive.net/php/loadfile.php?") > 0) && (($isyoutube || $isfacebook)) && ($mode == 0)) 
                    {
                         system("youtube-dl  -f bestvideo[ext=mp4]+bestaudio[ext=m4a]/mp4 -o " . $serverpath . $fileid . "/" . $b . ".mp4 " . $baseurl);

                         echo "youtube-dl  -f bestvideo[ext=mp4]+bestaudio[ext=m4a]/mp4 -o " . $serverpath . $fileid . "/" . $b . ".mp4 " . $baseurl;

                         $sstr = $loadFilePathFull . "?fileid=" . $fileid . "&index=" . $b . "&type=vid";
                         $codes[0] = str_replace("https://blarchive.net/php/loadfile.php?fileid=" . $fileid . "&index=k&type=vid", $sstr, $codes[0]);
                         $filename = $fileid . "/" . $b . ".mp4";
                         $fp = fopen($filename, 'r');
                         $result = fread($fp, filesize($filename));
                         fclose($fp);
                         $codes[$codeindex] = $result;
                         $demosize += strlen($result);
                         if (($demosize > 1024*10240) && ($demo))
                         {
                              echo "space exceeded demo";
                              exit;
                         }
                    }
                    else if ((strpos($rr[$b], "facebook.com") >0) && (strpos($rr[$b], "/videos/")>0) && ($isfacebook) && ($mode==0))
                    {
                         system("youtube-dl  -f bestvideo[ext=mp4]+bestaudio[ext=m4a]/mp4 -o " . $serverpath . $fileid . "/" . $b . ".mp4 " . $rr[$b]);
                         echo "youtube-dl  -f bestvideo[ext=mp4]+bestaudio[ext=m4a]/mp4 -o " . $serverpath . $fileid . "/" . $b . ".mp4 " . $rr[$b];

                         //$sstr=$loadFilePathFull."?fileid=".$fileid."&index=".$b."&type=vid";
                         //$codes[0]=str_replace("https://blarchive.net/php/loadfile.php?fileid=".$fileid."&index=k&type=vid",$sstr,$codes[0]);

                         $filename = $fileid . "/" . $b . ".mp4";
                         $fp = fopen($filename, 'r');
                         $result = fread($fp, filesize($filename));
                         fclose($fp);
                         $codes[$codeindex] = $result;
                         $demosize += strlen($result);
                    }
                    else 
                    {
                         if ((($ss[$b] === "vid") && ($mode != 0)) || (($ss[$b] === "img") && ($mode == 2)))
                         {
                              $ll = "";
                              $proto = "https";
                              $port = 443;
                         }
                         else
                         {
                              $kk = loadwebpage($rr[$b]);
                              $ll = $kk[0];
                              $proto = $kk[1];
                              $url = $kk[2];
                              $port = $kk[3];
                              $path = $kk[4];
                         }

                         $doreplace = true;
                         $iscss = false;
                         $ext = $rr[$b];

                         if (strpos($ext, "?") > 0)
                         {
                              $ext = substr($ext, 0, strpos($ext, "?"));
                         }
                         if (strpos($ext, ".") > 0)
                         {
                              $ext = substr($ext, strrpos($ext, ".") +1);
                              if ((startsWith($ext, "png")) || (startsWith($ext,"gif")) || (startsWith($ext,"jpg")) || (startsWith($ext,"png")) || (startsWith($ext,"mp3")) || (startsWith($ext,"mp4")) || (startsWith($ext,"avi")) || (startsWith($ext,"wav")) || (startsWith($ext,"swf"))) 
                              {
                                   if ($mode == 2) 
                                   {
                                        $ll = "";
                                   }

                                   if (($mode == 1) && ((startsWith($ext,"mp4")) || (startsWith($ext,"avi")) || (startsWith($ext,"wav")) || (startsWith($ext,"swf" )))) 
                                   {
                                        $ll = "";
                                   }

                                   $doreplace = false;
                              }

                              if (startsWith($ext, "css")) 
                              {
                                   $iscss = true;
                              }
                         }

                         if (strpos($rr[$b], "=high") > 0)
                         {
                              //echo "<br>rr[b]:".$rr[$b];
                              //echo "<br>ext:".$ext;
                              //echo "<br>doreplace:".($doreplace?"true":"false");
                              //echo "<br>strlen:".strlen($ll);
                         }

                         if (!$doreplace == true)
                         {
                              $codes[$codeindex] = $ll;
                              $demosize += strlen($ll);
                              if (($demosize > 1024*10240) && ($demo))
                              {
                                   echo "space exceeded demo";
                                   exit;
                              }
                         } 
                         else 
                         {
                              $kk = parsedata($ll, (startsWith($proto, "ssl") ? "https://" : "http://"), $url, ":" . $port, $path, $index, $loadFilePathFull, $fileid, $urls, $urls2, $xurls, $doreplace, $iscss, $istwitter, $isvideo);
                              //echo "<br>999";
                              $urls = $kk[0];
                              $codes[$codeindex] = $kk[1];
                              $index = $kk[2];
                              $xurls = $kk[3];
                              $urls2 = $kk[4];
                              //echo "<br>".count($urls2);
                              $demosize += strlen($kk[1]);
                              if (($demosize > 1024*10240) && ($demo))
                              {
                                   echo "space exceeded demo";
                                   exit;
                              }
                         }
                    }
               }
          }
     }

     //trip3
     echo "<br>trip 3 of 3.<br>Presently " . $index . "total pages to load";
     $rr = explode("\n", $urls);
     $l = count($rr);
     $tt = explode("\n", $urls);
     $ss = explode("\n", $xurls);
     $ttt = (count($tt));

     $zurls = str_replace("\n", "<br>url ", $urls);
     echo "<br>l:" . $l . "<br>start" . $zurls;
     echo "end";

     for($b = $k-1; $b < $l-1; $b++)
     {
          $codeindex++;

          //echo "<br>codeindex".$codeindex."<br>";
          //echo "<br>rr".$b.":".$rr[$b]."<br>";
          //echo "<br>".count($rr)."<br>";

          $url2 = substr($rr[$b], strpos($rr[$b], ":") +3);
          $normal = true;

          if (ISSET($postdata[$rr[$b]]))
          {
               $normal=false;
          }

          if (strpos($url2, ":") > 0)
          {
               $url2 = substr($url2, 0, strpos($url2, ":"));
          }

          if (strpos($url2, "/") > 0)
          {
               $url2 = substr($url2, 0, strpos($url2, "/"));
          }

          $url2 = strToLower($url2);
          //echo "<br>url2:".$url2;

          for($i = 0; $i < $xbad; $i++) 
          {
               if (strpos($rr[$b], $badwords[$i]) > 0) 
               {
                    $warn=1;
                    echo "<br>badword" . $badwords[$i];
                    break;
               }
          }

          if ($warn == 1)
          {
               echo "<br>The url:\"" . $rr[$b] . "\" has a naughty word in it.<br> It'll store locally for now, but will need manual review before putting on the block chain.  Unless this is later found to be bad content.";
          }

          //for($i=0;$i<$xbad2-1;$i++) {if (strlen($badwords2[$i])>0) {
          //if ((strpos($rr[$b],$badwords2[$i])>0 || startsWith($rr[$b],$badwords2[$i])) && $badwords2[$i]!=="www.google.com"  && $badwords2[$1]!=="gravatar.com") {
          if (($url2 !== "www.google.com") && ($url2 !== "gravatar.com"))
          {
               if ((strpos($badwords3, "\n" . $url2 . "\n") > 0) || (startsWith($badwords3, $url2 . "\n")))
               {
                    $warn = 2;
               }
          }

          //}}}

          if ($warn == 2)
          {
               echo "<br>" . $$url2 . "<br>You broke the internet, stop loading that nasty \xf0\x9f\x92\xa9.  <br><iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/9Deg7VrpHbM\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe><br>Neither McDonalds nor Michael Jordan have endorsed anything to do with this website, nor do we have their copyrights or permission to use this video.<br> This video is used in fair use because you need psychiatric help for what you attempted";

               $sql = $conn->prepare( "UPDATE `lookup` SET `warn`=? WHERE `fileid`=?");
               $sql->bind_param("ii", $warn, $fileid);
               !$sql->execute();
               exit;
          }

          if (($ss[$b] === "vid" && $mode != 0) || ($ss[$b] === "img" && $mode == 2)) 
          {
               $ll = "";
               $proto = "https";
               $port = 443;
          }
          else 
          {
               $kk = loadwebpage($rr[$b]);
               $ll = $kk[0];
               $proto = $kk[1];
               $url = $kk[2];
               $port = $kk[3];
               $path = $kk[4];
          }

          $doreplace = true;
          $iscss = false;
          $ext = $rr[$b];

          if (strpos($ext, "?") > 0) 
          {
               //echo "helloext1";
               $ext = substr($ext, 0, strpos($ext, "?"));
               //echo "<br>ext:3:".$ext."</br>";
          }

          if (strpos($ext, ".") > 0) 
          {
               //echo "helloext2";
               $ext = substr($ext, strrpos($ext, ".") +1);
               if (startsWith($ext, "png") || startsWith($ext, "gif") || startsWith($ext, "jpg") || startsWith($ext, "png") || startsWith($ext, "mp3") || startsWith($ext, "mp4") || startsWith($ext, "avi") || startsWith($ext, "wav") || startsWith($ext, "swf")) 
               {
                    if ($mode == 2) 
                    {
                         $ll = "";
                    }
                    if (($mode == 1) && ( startsWith($ext, "mp4") || startsWith($ext, "avi") || startsWith($ext, "wav") || startsWith($ext, "swf" )))
                    {
                         $ll = "";
                    }

                    $doreplace=false;
               }

               if (startsWith($ext, "css"))
               {
                    $iscss=true;
               }
          }

          //echo "<br>ext:3:".$ext."</br>";
          if (!$doreplace == true)
          {
               $codes[$codeindex] = $ll;
               $demosize += strlen($ll);
               if (($demosize > 1024*10240) && $demo) 
               {
                    echo "space exceeded demo";
                    exit;
               }
          } else 
          {
               //ths doreplace parameter set to true is to disable potential keyloggers.
               $kk = parsedata($ll, (startsWith($proto, "ssl") ? "https://" : "http://"), $url,":" . $port, $path, $index, $loadFilePathFull, $fileid, $urls, $urls2, $xurls, true, $iscss, $istwitter, $isvideo);
               $urls = $kk[0];
               $index = $kk[2];
               $xurls = $kk[3];
               $urls = $kk[4];
               $codes[$codeindex] = $kk[1];
               $demosize += strlen($kk[1]);
               if (($demosize > 1024*10240) && ($demo)) 
               {
                    echo "space exceeded demo";
                    exit;
               }
          }
     }

     //start saving locally and to database

     $zurls = str_replace("\n", "<br>url ", $urls);
     //echo "<br>ttt:".$ttt."<br>start".$zurls;
     echo "<br>total links found:" . $index;
     echo "<br>end";

     if ($ttt == 2)
     {
          $ttt = 1;
     } 
     else if ($ttt == 0)
     {

     } 
     else
     {
          $ttt = $ttt-1;
     }

     $totalprice = ($demosize * getprice($price)) / 1000000;

     $fk = fopen($fileid . "/0.mp4", 'w');
     fwrite($fk, $codes[0]);
     chmod($fileid . "/0.mp4", 0644);
     fclose($fk);

     $fk = fopen($fileid . "/index.html", 'w');
     fwrite($fk, $xurls);
     chmod($fileid . "/index.html", 0644);
     fclose($fk);
     $md = md5($codes[0]);

     $sql = $conn->prepare( "INSERT INTO `stores` (`fileid`,`fileindex`,`tickerid`,`filedate`,`filename`, md) VALUES (" . $fileid . ",'0','0',".time().",?,?)");
     $sql->bind_param("ss", $_GET["url"], $md);
     if(!$sql->execute())
     {
          trigger_error("there was an error...." . $conn->error, E_USER_WARNING);
     }
     else
     {
          echo "sql:sucess0";
     }

     for($c = 1; $c <= $codeindex; $c++) 
     {

          $fk=fopen($fileid."/".$c.".mp4",'w');
          fwrite($fk, $codes[$c]);

          //chmod($fk, 0644);

          fclose($fk);
          chmod($fileid . "/" . $c . ".mp4", 0644);

          //echo "<br>".$c." ".$tt[$c]." ".$ss[$c]."<br>";
          $md= md5($codes[$c]);
          $tick = "0";

          if ($sql = $conn->prepare( "select tickerid from stores join lookup on lookup.fileid=stores.fileid where stores.filename=? and `md`=? and added!=0 and length(tickerid)=40 limit 1")) 
          {
               $sql->bind_param("ss", $tt[$c], $md);
               if ($sql->execute())
               {
                    $result=$sql->get_result();
                    while ($row = $result->fetch_assoc()) 
                    {
                         $tick = $row["tickerid"];
                    }
               }
          }
          else
          {
               echo "sql failed";
          }
          //echo "<br>tick:".$tick;

          if ($tick === "0") 
          {
               //$fk=fopen($fileid."/".$c.".mp4",'w');
               //fwrite($fk,$codes[$c]);
               //fclose($fk);
               //chmod($fileid."/".$c.".mp4", 0644);
          }
          else 
          {
               $demosize = $demosize - strlen($codes[$c]);
               $totalprice = $demosize * getprice($price) / 1000000;
               $codes[$c] = "";
          }

          $sql = $conn->prepare( "INSERT INTO `stores` (`fileid`,`fileindex`,`tickerid`,`filedate`,`filename`,`md`) VALUES (". $fileid .",". $c .",?," . time() . ",?,?)");

          //                  echo "<br".("INSERT INTO `stores` (`fileid`,`fileindex`,`tickerid`,`filedate`,`filename`,`md`) VALUES (".$fileid.",".$c.",?,".time().",?,?)");
          //$sql = $conn->prepare( "INSERT INTO `stores` (`fileid`,`fileindex`,`tickerid`,`filedate`,`filename`) VALUES (".$fileid.",".$c.",'0',".time().",?)");

          $sql->bind_param("sss", $tick, $tt[$c], $md);
          //$sql->bind_param("s",$tt[$c]);

          if(!$sql->execute())
          {
               trigger_error("there was an error...." . $conn->error, E_USER_WARNING);
          } 
          else {}
     }

     $sql = $conn->prepare( "UPDATE `lookup` SET `fileparts`=?,`warn`=?, `payment`=? WHERE `token`=? AND `token2`=?");
     $sql->bind_param("iidii", $ttt, $warn, $totalprice, $token, $token2);
     if(!$sql->execute())
     {
          trigger_error("there was an error....".  $conn->error, E_USER_WARNING);
     } 
     else
     {
          echo "success update";
     }

     echo "<br>Cost:" . $totalprice . " " . $chain;
}