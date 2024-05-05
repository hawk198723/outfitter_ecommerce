<?php

include 'connect.php';
include 'functions/cartfunctions.php';
include 'includes/header.php';
//require 'search.php';

$prodid = $_GET['prodid'];
$qty = $_POST['qty'];
if (isset($qty)){
	addtocart($prodid,$qty);
}

$sql = $dbh->prepare("select * from mesa_products where prodid = '$prodid'");
$sql->execute();
   $row = $sql->fetch();
	$prodid = $row['prodid'];
	$prodname = $row['prodname'];
	$proddesc = $row['proddesc'];
	$prodprice = $row['prodprice'];
    echo '<img src = "prodimages/'.$prodid.'.jpg" height="200"><br>';
	echo $prodname.'<br>'.$proddesc.'<br>'.$prodprice;
	echo '<form action = "'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'" method="post">';


     $sql = $dbh->prepare("select distinct mesa_labels.labelid,mesa_labels.labelname 
	from mesa_labels, mesa_attributes
	where mesa_labels.labelid = mesa_attributes.labelid
	and mesa_attributes.prodid = '1'");
$sql->execute();
while ($row = $sql->fetch()){
	$labelid = $row[0];
	$labelname = $row[1];
	echo $labelname.': <select name="'.$labelname.'" value="'.$labelname.'">'."\n";
	$innersql = $dbh->prepare("select value,price from mesa_attributes where prodid = '1' and labelid = '$labelid'");
	$innersql->execute();
	while ($innerrow = $innersql ->fetch()){
		$value = $innerrow[0];
		$price = $innerrow[1];
		echo '<option value = "'.$value.'">'.$value.'-'.$price.'</option>'."\n";

	}
	echo '</select><br>';
}



	echo '<input type="number" name="qty" class="small" size="5" value="1" required="required"/>
	<input type="submit" value="Add to Cart">
	</form>
	';
	include 'includes/footer.php';

?>
