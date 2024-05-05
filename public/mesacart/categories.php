<?php
include 'connect.php';
include 'functions/cartfunctions.php';

$catid = $_GET['catid'];
$catsql = $dbh->prepare("select catname from  mesa_categories where catid = '$catid'");
$catsql->execute();
$catrow = $catsql->fetch();
$seocatname = $catrow[0];
//get the catname and populate

include 'includes/header.php';

echo '<h1>'.$seocatname.'</h1>';
$sql = $dbh->prepare("select * from mesa_products where catid = '$catid'");
$sql->execute();
while ($row = $sql->fetch()){
	var_dump($row);
	$prodid = $row['prodid'];
	$prodname = $row['prodname'];
	$proddesc = $row['proddesc'];
	$prodprice = $row['prodprice'];
	$prodlink = $row['link'];

	echo '<a href="'.$prodlink.'" title="'.$prodname.'"><img src = "catimages/'.$catid.'.jpg" height="100" alt="'.$prodname.'"></a>';
	echo '<a href="'.$prodlink.'" title="'.$prodname.'">'.$prodname.' ...Read more</a><br><br> ';

}

include 'includes/footer.php';
?>