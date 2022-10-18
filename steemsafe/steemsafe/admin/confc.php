<?php

//for control by conf
//for windows, put steem.conf in another file and edit filename below.

$filenamex = "/etc/blarchive/hive.conf";

$fpx = fopen($filenamex, 'r');
$myfilex = fread($fpx, filesize($filenamex));
fclose($fpx);

$myfilex = str_replace("\n", "\r", $myfilex);
$username = substr($myfilex, strpos($myfilex, "\r#user\r") + 7);
$username = substr($username, 0, strpos($username, "\r"));
$key = substr($myfilex, strpos($myfilex, "\r#key\r") + 6);
$key = substr($key, 0, strpos($key, "\r"));
$key=base64_decode($key);
$ip=substr($myfilex,strpos($myfilex,"\r#servername\r")+13);
$servername=substr($ip,0,strpos($ip,"\r"));
$ciphertext=substr($myfilex,strpos($myfilex,"\r#pass\r")+7);
$ciphertext=substr($ciphertext,0,strpos($ciphertext,"\r"));
$cc = base64_decode($ciphertext);
$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
$iv = substr($cc, 0, $ivlen);
$hmac = substr($cc, $ivlen, $sha2len=32);
$ciphertext_raw = substr($cc, $ivlen+$sha2len);

$original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);


$calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
$password="";
if (hash_equals($hmac, $calcmac)) {$password=$original_plaintext;}


//end conf control

//for alternte to conf control. uncomment below and comment above.
//$servername="localhost";
//$username="testing";
//$password="nimda";


?>
