<?php
include '../connect.php';
include 'protect.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Main Categories</title>
<link type="text/css" rel="stylesheet" href="css/framework.css" />
<link type="text/css" rel="stylesheet" href="css/style_coffee.css" />
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
        <div class="navhead_blank" style="height:200px;"><span><a href="index.php" title="">Home</a><br/> <a href="coupons.php" title="">Coupons</a></span></div>
       
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
Main Categories<br/>
Not Using Three Tiers? <a href = "subcategories.php">Use Two Levels</a><br/>
<?php
$maincat = addslashes($_POST['maincat']);
$mainid = $_POST['mainid']; 
$deletemain = $_GET['deletemain'];
$deletesub = $_GET['deletesub'];
if (isset($deletesub)){
$dbh->exec("delete from $category where id = '$deletesub'");
}
$editmain = $_GET['editmain'];
$script = $_SERVER['PHP_SELF'];
$subcatname = addslashes($_POST['subcatname']);
$maincatid = $_POST['maincatid'];
if ($subcatname != '' && $maincatid != '' && $subcatid =='')
   {
 $count = $dbh->exec("insert into $category (name,maincatid) values ('$subcatname','$maincatid')");   
   }
   
$msg = 'You are adding a main category<br/>';
 if (isset($deletemain)) mysql_query("delete from $maincategory where id = '$deletemain'");
if (isset($_POST['submit']) && $mainid == '' && $subcatname == '' )
    {
$count = $dbh->exec("insert into $maincategory (name) values ('$maincat')");
	}
if (isset($_POST['submit']) && $mainid != '')
    {
$count = $dbh->exec("update $maincategory set name = '$maincat' where id = '$mainid'");
	}	


$mainsql = "select id, name from $maincategory";
foreach ($dbh->query($mainsql) as $mainrow)
    {
$mainid = $mainrow[0];
$mainname = $mainrow[1];
echo '<a href = "'.$script.'?editmain='.$mainid.'">Edit '.$mainname.'</a>      <a href = "'.$script.'?deletemain='.$mainid.'">Delete</a><br/>';
	}
if (isset($editmain))
  {
  
$mainup = $dbh->prepare("select id, name from $maincategory where id = '$editmain'");
$mainup->execute();
$uprow=$mainup->fetchObject();
$upid = $uprow->id;
$upname = $uprow->name;
$msg = 'You are currently editing main category '.$upname.'<br/>';
if ($subname) 
$val = "Update Sub Category"; 
else
$val = "Add Sub Category";
$subform = 'Add a subcategory for '.$upname.'<br/>
<br/><form action = "indexclean.php?editmain='.$upid.'" method = "post">
Sub Category name:<input type = "text" name = "subcatname"><br/>
<input type = "hidden" name = "maincatid" value = "'.$upid.'">
<input name ="submit" type = "submit" value = "'.$val.'"/>
</form>';
  }
  echo $msg;
?>
<br/>
<form action = "<?= $currpage;?>" method = "post">
            <br/>
            <input type = "hidden" name = "mainid" value = "<?= $upid;?>" />
            <input type="text" name = "maincat" value = "<?= $upname;?>"/>
            <input name ="submit" type = "submit" value = "<? if(isset($editmain)) echo "Update Main Category"; else echo "Add Main Category"; ?>"  />
          </form>
          <?php
          if (isset($editmain))
		    {
			?>	
          <a href = "<?= $script;?>">Return to Add Main Category</a><br/>
            <a href = "subcategories.php?mainid=<?= $editmain;?>">Edit Sub Categories for <?php echo $upname;?></a><br/>
<?php
               }
            $dbh=null;
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
