<?php
include 'connect.php';
include 'functions/cartfunctions.php';
include 'includes/header.php';
$sql = $dbh->prepare("select mesa_products.prodid, mesa_products.prodname, mesa_products.prodprice, mesa_cartitems.qty
	from mesa_products, mesa_cartitems
	where mesa_products.prodid = mesa_cartitems.productid
	and mesa_cartitems.sessionid = '$sessid'");
$sql->execute();
  

?>
<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<script>
	function sameship(){
		if (document.getElementById('ship').checked == true){
	document._xclick.billfirst_name.value = document._xclick.first_name.value;
	    }
	    else {
	document._xclick.billfirst_name.value = '';
	    }

	}

function getCity(){
  var clientKey = 'CN6hzsaqhfixadEbtzDVsHCOLxOWqoeCVvpLxFdXtcYFzFK1buLhGqhJ6uHhZPID';
    var zipcode = document.getElementById("zip").value;    
       var url = "https://mm214.com/zipcode/zipcode/getzip.php?zip=" + zipcode;


    $.getJSON( url, function( data ) {
        console.log(data);
        var cityname = data.city;
        document.getElementById("city").value = cityname;
        
         var statename = data.state;
        document.getElementById("state").value = statename; 
      
     document.getElementById("citystate").style.display = 'block';    
        
    });
    }

</script>
<form action= "https://www.sandbox.paypal.com/us/cgi-bin/webscr" 
method="post"  name="_xclick">
<?php
$i=1;
while  ($row = $sql->fetch()){
  	$prodid = $row['prodid'];
	$prodname = $row['prodname'];
	$prodprice = $row['prodprice'];
	$qty = $row['qty'];
    echo '<img src = "prodimages/'.$prodid.'.jpg" height="200"><br>';
	echo $prodname.'<br>'.$prodprice.' QTY: '.$qty.'<br><br>';
echo '<input type = "hidden" name = "item_name_'.$i.'" value = "'.$prodname.'" /><input type = "hidden" name = "amount_'.$i.'" value = "'.$prodprice.'" /> 
<input type = "hidden" name = "quantity_'.$i.'" value = "'.$qty.'" /> ';
$i++;
}
?>
  <!--Shipping:<br/>
  First Name
  <input type = "text" id = "firstname" name = "first_name" />
  <br/>
  Last Name
  <input type = "text" name = "last_name" />
  <br/>
  Address
  <textarea name = "address1" cols="10" rows="5"></textarea>
  <br/>
  City
  <input type = "text" name = "city"  id = "city"/>
  <br/>
  State
  <input type = "text" name = "state"  id = "state" />
  <br/>
  Zip:
  <input type = "text" name = "zip" id = "zip"/>
  <br/>
  Same as Shipping?
  <input type = "checkbox" id = "ship" onClick="sameship();"/>
  <br/>-->
  Billing:<br/>
  First Name
  <input type = "text" id = "billfirst_name" name = "billfirst_name" />
  <br/>
  Last Name
  <input type = "text" name = "billlast_name" />
  <br/>
  Address
  <textarea name = "billaddress1"></textarea>
  <br/>
  <span id = "citystate" style="display:none"> City
  <input type = "text" name = "billcity" id="city"/>
  <br/>
  State
  <input type = "text" name = "billstate" id="state"/>
  <br/>
  </span> 
  Zip:
  <input type = "text" name = "billzip" id="zip" onblur="getCity()"/>
  <br/>
  Email:
  <input type = "text" name = "email" />
  <br/>
  Phone:
  <input type = "text" name = "night_phone_a" />
  <br/>
  <input type="hidden" name="cmd" value="_cart">
  <input type="hidden" name="business" value="kdsecor+350@gmail.com">
  <input type="hidden" name="upload" value="1">
  <input type="hidden" name="currency_code" value="USD">
  <input type = "hidden" name = "shipping_1" value = "0.00"/><input type ="hidden" id ="subtotal" name = "subtotal" value = "0.00"><br/>Subtotal:$ <div id = "copy">Shipping is</div>
  <input type="submit" name = "submit" value = "Check Out">
</form>
<?php
include 'includes/footer.php';
?>