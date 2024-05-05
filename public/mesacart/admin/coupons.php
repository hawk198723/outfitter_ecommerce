<?php
include '../connect.php';
include 'protect.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Coupons</title>
<link type="text/css" rel="stylesheet" href="css/framework.css" />
<link type="text/css" rel="stylesheet" href="css/style_coffee.css" />
 <script type="text/javascript" language="javascript" src="datetimepicker.js"></script>
<script type="text/javascript">
if (document.images)
		{
		calimg= new Image(16,16); 
		calimg.src="images/cal.gif"; 
		}
</script>	
</head>
<body>
<div id="container">
  <!-- Container begins here -->
  <div id="sidebar">
    <!-- Sidebar begins here -->
    <div class="header logo">
      <!-- Logo begins here -->
    </div>
    <!-- END Logo -->
    <div id="navigation">
      <!-- Navigation begins here -->
      <div class="sidenav">
        <!-- Sidenav -->
        <div class="navhead_blank"><span><a href="index.php" title="">Home</a></span></div>
        <!-- Sidenav Headline -->
        <!-- /Sidenav Box -->
        <!-- /Sidenav Box -->
      </div>
      <!-- /Sidenav -->
    </div>
    <!-- END Navigation -->
  </div>
  <!-- END Sidebar -->
  <div id="primary">
    <!-- Primary begins here -->
    <div class="navhead">
      <div class="header top_nav"> <span class="title"><a href="#" title="">Your Website</a></span>
        <!-- Put your website name here -->
        <span class="session" style="float:right;">Signed in as <a href="#" title="">Administrator</a> (<a href="logout.php" title="Sign out">Sign out</a>)</span> </div>
    </div>
    <div id="content">
      <!-- Content begins here -->
      
      <div class="box">
<?php
$status = 'Add Code';
$submit = $_POST['submit'];
$editid = $_GET['editid'];
$code = $_POST['code'];
$amount = $_POST['amount'];
$type = $_POST['type'];
$dateeffect = $_POST['publishstart'];
$dateexpire = $_POST['publishend'];
$deleteid = $_GET['deleteid'];
if (isset($deleteid)) $dbh->exec("delete from $coupons where id = '$deleteid'"); 
$active = $_POST['active'];
if ($submit == 'Add Code')
    {

$inssql =  $dbh->exec("insert into $coupons (code,amount,dateeffect,dateexpire,gifttype,active) values ('$code','$amount','$dateeffect','$dateexpire','$type','$active')");
	if (!$inssql) echo mysql_error();  
	}
if ($submit == 'Edit Code')
    {
$edit =  $dbh->exec("update $coupons set code = '$code',amount='$amount',dateeffect='$dateeffect',dateexpire='$dateexpire',gifttype='$type',active='$active' where id = '$editid'");	
if(!$editsql) echo mysql_error();
	}	
if ($editid)
   {
 $status = 'Edit Code';   
 $coup = $dbh->prepare("select code,amount,gifttype,dateeffect,dateexpire,active from $coupons");
$coup->execute();
$editrow=$coup->fetchObject();

 $upcode = $editrow->code;
 $upamount = $editrow->amount;
 $upgifttype = $editrow->gifttype;
 $updateeffect = $editrow->dateeffect;
 $updateexpire = $editrow->dateexpire;
 $upactive = $editrow->active;
   }
 $typearray = array('money'=>'$','percent'=>'%');
 $dispsql = "select id,code,amount,gifttype from $coupons";
  foreach ($dbh->query($dispsql) as $disprow)
  {
$id = $disprow[0];
$code = $disprow[1];
$amount = $disprow[2];
$type = $disprow[3];
echo $code.' '.$typearray[$type].$amount.' <a href = "coupons.php?editid='.$id.'">Edit</a> <a href ="coupons.php?deleteid='.$id.'">Delete</a><br/>';
  }
$dbh = null;
if ($upcode) echo '<h4>You are currently editing '.$upcode.'</h4>';
else echo '<h4>You are currently in Add Mode</h4>';
?> 
<form action = "coupons.php?<?= $_SERVER['QUERY_STRING'];?>" method = "post">
Gift/Coupon Code:<input type = "text" name = "code" value = "<?= $upcode;?>"/><br/>
Amount<input type = "text" name = "amount" size = "4" value = "<?= $upamount;?>"/><br/>
Input Money:<input type = "radio" name = "type" value ="money" <? if ($upgifttype == 'money') echo 'checked="checked"';?> />or
Percentage<input type = "radio" name = "type" value = "percent" size="4" <? if ($upgifttype == 'percent') echo 'checked="checked"';?>/><br/>
Publish Start:<input type="Text" id="publishstart" maxlength="25" size="25" name="publishstart" value= "<?= $updateeffect;?>" onblur="validate(this);"><label
 class="error" id="publishstart_error"></label><a href="javascript:NewCal('publishstart','yyyymmdd',true,24,'arrow',true)"><img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a><span class="descriptions">Format (date:yyyy/MM/dd time:24hr)</span><br/>
 Publish Stop:<input type="Text" id="publishend" maxlength="25" size="25" name="publishend" value= "<?= $updateexpire;?>" onblur="validate(this);">
 <a href="javascript:NewCal('publishend','yyyymmdd',true,24,'arrow',true)"><img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a><span class="descriptions">Format (date:yyyy/MM/dd time:24hr)</span><br/>
Activate: <br/>On<input type = "radio" name = "active" value = "1"  <? if ($upactive == '1') echo 'checked="checked"';?>>
Off<input type = "radio" name = "active" value = "0"  <? if ($upactive == '0') echo 'checked="checked"';?>>
<input type = "submit" name = "submit" value = "<?= $status;?>" />
</form>
<?php
if ($status == 'Edit Code') echo '<a href = "coupons.php">Return to Add Mode</a>';
?>
</div>
     <!-- END Box-->
    </div>
    <!-- END Box -->
  </div>
  <!-- END Content -->
</div>
<!-- END Primary -->
<div class="clear"></div>
</body>
</html>
