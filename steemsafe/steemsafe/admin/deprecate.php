<?php

/*
* Deletes archived file parts and files for archives that have not been paid for, which were created before a specific time (1 Month and older)
*/

function startsWith($a,$b) 
{
    return (substr($a,0,strlen($b))===$b);
}

function endsWith($a,$b) 
{
    if (strlen($b)==0) 
    {
        return true;
    }

    return (substr($a,-strlen($b))===$b);
}

// calculate 1 month before now
$time=time()-31*24*3600;

$a="select fileid,fileparts from lookup where added=0 and filetime<?";

require("confc.php");

$database="archive";
$conn=new mysqli($servername,$username,$password,"archive");

if ($conn->connect_error) 
{
    die("connection to the database failed:". $conn->connect_error);
}

$filename="/home/webadmin/steemsafe/admin/"."conf.conf";

$fp=fopen($filename,'r');
$myfile=fread($fp,filesize($filename));
fclose($fp);

$myfile=str_replace("\n","\r",$myfile);

$linpath=substr($myfile,strpos($myfile,"\r#path\r")+8);
$linpath=substr($linpath,0,strpos($linpath,"\r"));

if (startsWith($linpath,"\"")) 
{
    $linpath=substr($linpath,1);
}

if (endsWith($linpath,"\"")) 
{
    $linpath=substr($linpath,0,-1);
}

if ($sql=$conn->prepare($a))
{
    $sql->bind_param("i",$time);
    if ($sql->execute())
    {
        $result=$sql->get_result();
        while ($row = $result->fetch_assoc()) 
        {
            $fid=$row["fileid"];
            $z=$row["fileparts"];

            if (strlen($fid)>0 && ctype_digit((string)$fid) && is_dir($linpath.$fid) && strlen($linpath)>2)
            {
                echo "\n".$linpath.$fid."\n\t".$z;

                try 
                {
                    if (!unlink($linpath.$fid."/"."index.html")) {echo "unlink error p1".$linpath."index.html";};
                }
                catch (Exception $e) 
                {
                    echo "e1:".$e;
                }

                for ($i=0;$i<$z;$i++) 
                {
                    try 
                    {
                        if (!(unlink($linpath.$fid."/".$i.".mp4"))) 
                        {
                            echo "unlink error p2".$linpath.$fid."/".$i.".mp4";
                        }
                    }
                    catch (Exception $e) 
                    {
                        echo "e2:".$e;
                    }
                }
                rmdir($linpath.$fid);
            }
            else 
            {
                echo "not a number:".$fid." or not a dir:".$linpath.$fid."\n";
                var_dump(unpack('C*', $fid));
            }
        }

        $sql->close();
    }
    else 
    {
        trigger_error("there was an error....".$conn->error, E_USER_WARNING);
    }
}
else 
{
    echo "Error: " . $a . "" . mysqli_error($conn);
}

sleep(24*3600);

?>
