<?php
if (isset($_GET['fileid']))
{
$fileid=$_GET['fileid'];
echo '
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Refresh" content="0; url=//blarchive.net/php/loadfile.php?fileid='.$fileid.'&index=0" />
  </head>
  <body>
  </body>
</html>';
}
else {
echo '
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Refresh" content="0; url=//blarchive.net/php/dourl.php" />
  </head>
  <body>
  </body>
</html>';




}
?>

