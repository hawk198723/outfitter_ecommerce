<?php
session_start();
$_SESSION['approved'] = '';
header ("Location: login.php");
?>