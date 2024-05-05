<?php
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$sessid = session_id();
$dbh = new PDO("mysql:host=localhost;dbname=cbnclamy_outfit", "cbnclamy_outfitadmin", "1qaz2wsx!QAZ@WSX");
// echo "Connected successfully";
$admin = 'mesa_admin';
$categories = 'mesa_categories';
$products = 'mesa_products';
$cartitems = 'mesa_cartitems';

?>