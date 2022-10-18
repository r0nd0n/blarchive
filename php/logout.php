<?php
session_start();
session_destroy();
echo "you have successfully logged out";
echo "<a href=dourl4.php>Go home</a>";
?>
