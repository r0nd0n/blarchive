<?php

/*
* Blarchive - Connect to Bittrex API to access Crypto Prices
*/

function getprice($price) 
{
    $filename="archive.conf";
    $fp=fopen($filename,'r');
    $myfile=fread($fp,filesize($filename));
    fclose($fp);
    $myfile=str_replace("\n","\r",$myfile);

    $chain=substr($myfile,strpos($myfile,"\r#chain\r")+9);
    $chain=strToUpper(substr($chain,0,strpos($chain,"\r")));
    //echo $chain;

    $ch=curl_init("https://bittrex.com/api/v2.0/pub/Markets/GetMarketSummaries");
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    //curl_setopt($ch,CURLOPT_HEADER, 1);
    //curl_setopt($ch,CURLOPT_COOKIE,$cookie);
    curl_setopt($ch,CURLOPT_HTTPHEADER, array('accept: application/json, text/plain, */*','Content-type: application/json','DNT:1','x-csrf-token: 1roGkKaaXTpiSNjFK7GWXaxKJi6M67c9hBGMxcSOXOxfdSjXp9i5cn7ES/MiyMjJLUMUJ4fLfCTvSnNzX2Js6g==','authorization: Bearer FkHwa-RTUHuqofqCpGQNnAwEVeE4rgkSDOV7sRlB49Y','sec-fetch-mode: cors','User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36'));

    if(($result=curl_exec($ch)) === false) 
    {
        echo 'Curl error: ' . curl_error($ch);
    }
    $start=substr($result,strpos($result,"BTC-".$chain));
    $start=substr($start,strpos($start,"Last\":")+6);
    $start=substr($start,0,strpos($start,","));
    //echo $start;
    $start2=substr($result,strpos($result,"USDT-BTC"));
    $start2=substr($start2,strpos($start2,"Last\":")+6);
    $start2=substr($start2,0,strpos($start2,","));
    //echo $start2;
    //echo $start*$start2;
    return $price/($start*$start2);
}

?>
