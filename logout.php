<?php 
session_start();
session_destroy();
echo "true";
// header("Location: login.php");
?>