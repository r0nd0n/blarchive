<?php

/*
* Blarchive - Config Script | Extract plain text security variables from /etc/blarchive/hive.conf
*/
/*
// open and prepare config file for reading
$filename2 = "/etc/blarchive/hive.conf";
$fp2 = fopen($filename2, 'r');
$myfile2 = fread($fp2, filesize($filename2));
fclose($fp2);
$myfile2 = str_replace("\n", "\r", $myfile2);

// get username
$username = substr($myfile2, strpos($myfile2, "\r#user\r") + 7);
$username = substr($username, 0, strpos($username, "\r"));

// get key and decode it
$key = substr($myfile2, strpos($myfile2, "\r#key\r") + 6);
$key = substr($key, 0, strpos($key, "\r"));
$key = base64_decode($key);

// get servername
$ip = substr($myfile2, strpos($myfile2, "\r#servername\r") + 13);
$servername = substr($ip, 0, strpos($ip, "\r"));

// get database password
$ciphertext = substr($myfile2, strpos($myfile2, "\r#pass\r") + 7);
$ciphertext = substr($ciphertext, 0, strpos($ciphertext, "\r"));

// decode database password
$cc = base64_decode($ciphertext);
$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
$iv = substr($cc, 0, $ivlen);
$hmac = substr($cc, $ivlen, $sha2len=32);
$ciphertext_raw = substr($cc, $ivlen+$sha2len);

$original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);

$calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
$password = "";

if (hash_equals($hmac, $calcmac)) 
{
    $password = $original_plaintext;
}
*/
//for alternte to conf control. uncomment below and comment above.
$servername="localhost";
$username="webadmin";
$password="W2tctcq+ob0nQ7EVCHxyMysuD4/QEMFEjz/AAtK5zrnCUF4+yEOJ0JqtQdrBjAL2kZqCf8IN9wfrvRc+Z79yaQ==";


?>
