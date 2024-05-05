
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<script type="text/javascript">
function sameship() {
	if (document.getElementById("ship").checked == true)
	   {
document._xclick.billfirst_name.value = document._xclick.first_name.value ; 	
document._xclick.billlast_name.value = document._xclick.last_name.value; 	
document._xclick.billaddress1.value = document._xclick.address1.value   ; 
document._xclick.billcity.value = 	document._xclick.city.value ; 	
  document._xclick.billstate.value =   document._xclick.state.value;  	
  document._xclick.billzip.value =  document._xclick.zip.value; 
	   }
else {
document._xclick.billfirst_name.value = '' ; 	
document._xclick.billlast_name.value = ''; 	
document._xclick.billaddress1.value = ''   ; 
document._xclick.billcity.value = 	'' ; 	
  document._xclick.billstate.value =  '';  	
  document._xclick.billzip.value =  ''; 
	}
}
function makeRequest(url) 
{
	
		request = new XMLHttpRequest();
		sendRequest(url);
}

function sendRequest(url)
{
if (url == 'ship')
  {
	state   = document._xclick.billstate.value;
    service = document._xclick.service.value;
    zip = document._xclick.billzip.value;
	url = "try.php?server=" + service + "&state=" + state + "&zip=" + zip;
  }
 else if (url == 'curr')
   {
 currlink = document.getElementById("currency").value;
url = "curr.php?curr=" + currlink + "&rate=" + tot;	
   }
 else if (url == 'coup')
   {
couponlink = document.getElementById("coupon").value;
url = "coupon.php?coupon=" + couponlink;
   }  
else
   {
    zip = document._xclick.billzip.value;
	url = "zip.php?zip=" + zip;
   }
   	request.onreadystatechange = onResponse;
   	alert(url);
	request.open("GET", url, true);
	request.send(null);
}
function checkReadyState(obj)
{
	if(obj.readyState == 0) { document.getElementById('copy').innerHTML = "Sending Request..."; }
	if(obj.readyState == 1) { document.getElementById('copy').innerHTML = "Loading Response..."; }
	if(obj.readyState == 2) { document.getElementById('copy').innerHTML = "Response Loaded..."; }
	if(obj.readyState == 3) { document.getElementById('copy').innerHTML = ""; }
	if(obj.readyState == 4)
	{
		if(obj.status == 200)
		{
			return true;
		}
		else if(obj.status == 404)
		{
			// Add a custom message or redirect the user to another page
			document.getElementById('copy').innerHTML = "File not found";
		}
		else
		{
			document.getElementById('copy').innerHTML = "There was a problem retrieving the content.";
		}
	}
}
function onResponse() 
{
	if(checkReadyState(request))
	{
		
	if (request.responseText.indexOf("|") != -1)
		  {
	var cityarray  = request.responseText.split("|");
	document._xclick.billcity.value = cityarray[1];
	document._xclick.billstate.value = cityarray[0];
    document.getElementById("citystate").style.display = 'block'; 
		  }
    else if (request.responseText.indexOf("$") != -1)
	     {
		data =  request.responseText;
	curr =	data.substr(data.length-3,3);
	document._xclick.currency_code.value = 	curr;	
		 }
	 else if (request.responseText.indexOf("-") != -1)
	     {
		//this is a coupon	 
		data =  request.responseText;
	coup =	data.split('-');
	typeofdiscount = coup[0];
		var cartval =  document.getElementById("subtotal").value;
	if (typeofdiscount == 'percent') 
	  {
	  mon= '';	  
	   tot = '%';
	   cartval = cartval * (100-coup[1]);
	  }
	if (typeofdiscount == 'amount') 
	 {
	 mon = '$';
	 tot = '';
	cartval =  cartval - coup[1];
    }
   document.getElementById('discount').innerHTML = cartval ;  
	document.getElementById("couponresponse").innerHTML = "Your discount is " +  mon + coup[1] + tot ;
	//document._xclick.currency_code.value = 	curr;	
		 }	 
		else
		 {
    document.getElementById("copy").innerHTML = request.responseText;
	document._xclick.shipping_1.value =  request.responseText;
	    }
      }
}
</script>
</head>
<body>
<form action= "https://www.sandbox.paypal.com/us/cgi-bin/webscr" 
method="post"  name="_xclick">
  Shipping:<br/>
  First Name
  <input type = "text" name = "first_name" />
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
  <br/>
  Billing:<br/>
  First Name
  <input type = "text" name = "billfirst_name" />
  <br/>
  Last Name
  <input type = "text" name = "billlast_name" />
  <br/>
  Address
  <textarea name = "billaddress1"></textarea>
  <br/>
  <span id = "citystate" style="display:none"> City
  <input type = "text" name = "billcity"/>
  <br/>
  State
  <input type = "text" name = "billstate"/>
  <br/>
  </span> Zip:
  <input type = "text" name = "billzip" onblur="makeRequest('state');"/>
  <br/>
  Email:
  <input type = "text" name = "email" />
  <br/>
  Phone:
  <input type = "text" name = "night_phone_a" />
  <br/>
  <!--Service: <select name = "service" onChange="makeRequest('ship')">
<option value = ''>Choose Service Below</option> 
 <option value = "PRIORITYOVERNIGHT" >FedEx Priority Overnight</option>
  <option value = "STANDARDOVERNIGHT" >FedEx Standard Overnight</option>
  <option value = "FIRSTOVERNIGHT" >FedEx First Overnight</option>
  <option value = "FEDEX2DAY" >FedEx 2 Day</option>
  <option value = "FEDEXEXPRESSSAVER" >FedEx Express Saver</option>
  <option value = "INTERNATIONALPRIORITY" >FedEx International Priority</option>
  <option value = "INTERNATIONALECONOMY" >FedEx International Economy</option>
    <option value = "INTERNATIONALFIRST" >FedEx International First</option>
    <option value = "FEDEX1DAYFREIGHT" >FedEx International Freight</option>
    <option value = "FEDEX2DAYFREIGHT" >FedEx 2 day Freight</option>
    <option value = "FEDEX3DAYFREIGHT" >FedEx 3 day Freight</option>
    <option value = "FEDEXGROUND" >FedEx Ground</option>
    <option value = "GROUNDHOMEDELIVERY" >FedEx Home Delivery</option>
</select><br/>--> 
347933438
  <input type="hidden" name="cmd" value="_cart">
  <input type="hidden" name="business" value="ksecor_1326497985_biz@adelphia.net">
  <input type="hidden" name="upload" value="1">
  <input type="hidden" name="currency_code" value="USD">
  <input type = "hidden" name = "shipping_1" value = "25.00"/><input type ="hidden" id ="subtotal" name = "subtotal" value = "0.00"><br/>Subtotal:$<p id = "discount">0.00</p>  <div id = "copy">Shipping is</div>
  <input type="submit" name = "submit" value = "Check Out">
</form>
</body>
</html>
