<?php
function numcartitems($sessid){
	global $dbh;
   $sql = $dbh->prepare("select count(productid) from mesa_cartitems where sessionid = '$sessid'");
   $sql->execute();	
   $row = $sql->fetch();
   $num = $row[0];
   return $num;
   $self = $_SERVER['PHP_SELF'];
}



function addtocart($pid,$qty){
	global $sessid;
	global $dbh;
	$checksql = $dbh->prepare("select productid from mesa_cartitems where productid = ? and sessionid = '$sessid'");
	$checksql->bindValue(1,$pid);
	$checksql->execute();
	$checkpid = $checksql->fetch();
	$existingpid = $checkpid[0];
	if (is_numeric($existingpid)){
		$sql = $dbh->prepare("update mesa_cartitems set qty = ? where productid = '$pid' and sessionid = '$sessid'");
		$sql->bindValue(1,$qty);
		$sql->execute();
		   echo '<script>alert("Quantity updated!");</script>';
	}
	else {
	$sql = $dbh->prepare("insert into mesa_cartitems (productid,qty,sessionid) values (?,?,?)");
	$sql->bindValue(1,$pid);
	$sql->bindValue(2,$qty);
	$sql->bindValue(3,$sessid);
	$sql->execute();
	print_r($sql->errorInfo());
    echo '<script>alert("Added to Cart!");</script>';
  }
}
?>