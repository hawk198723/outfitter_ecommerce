<?php 
include '../connect.php';
include 'protect.php';
$mainid = $_GET['mainid'];
$editsub = $_GET['editsub'];
$deletesub = $_GET['deletesub'];
$submit = $_POST['submit'];
$subcatname = addslashes($_POST['subcatname']);
$subcatid = $_POST['subcatid'];
if ($submit == 'Add Category' && $editsub == '' && $deletesub == ''){
 $dbh->exec("insert into $categories (name,maincatid) values ('$subcatname','$mainid')");
 }
 if ($submit == 'Update Category'){
 $dbh->exec("update $categories set name = '$subcatname' where id = '$subcatid'");
 }
if (isset($deletesub)) 
{
$dbh->exec("delete from $categories where id = '$deletesub'");
}

$sqlsub = "select catid,catname from $categories";

foreach ($dbh->query($sqlsub) as $subrow)
   {
$subid = $subrow[0];
$subname = $subrow[1];
$sublinks .= '<a href = "index.php?&editsub='.$subid.'">Edit '.$subname.'</a> 
<a href = "index.php?deletesub='.$subid.'">Delete</a> <a href = "products.php?catid='.$subid.'">View Products</a><br/>';	  
   }
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Categories</title>
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
echo $sublinks;
if (isset($editsub))
   {
   
$editsql = $dbh->prepare("select catname from $categories where id = '$editsub'");
$editsql->execute();
$editrow = $editsql->fetchObject();
$subname = $editrow->name; 
   }
?>
Add a Category  <?phpif ($name) echo 'for '.$name;?> <br/>
<br/><form action = "index.php?mainid=<?php echo $mainid;?>" method = "post">
Category name:<input type = "text" name = "subcatname" value = "<?php echo$subname;?>"><br/>
<input type = "hidden" name = "subcatid" value = "<?php echo $editsub;?>">
<input name ="submit" type = "submit" value = "<?php if (!isset($editsub)) echo "Add Category"; else echo "Update Category";?>"/>
</form>
<?
if (isset($editsub))
   {
?>	   
<a href = "index.php?mainid=<?php echo $mainid;?>">Return to Add Categories</a>
<?
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
