<?php
if ($_SESSION['approved'] != 'wysiwyg')
   {
header("Location: login.php"); 
   }
$id = $_SESSION['id'];
$currpage = $_SERVER['PHP_SELF']; 
?>