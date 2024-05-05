<?php
//based on prodid 1
include 'connect.php';
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


?>