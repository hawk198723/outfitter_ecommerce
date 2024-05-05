<?php
include '../connect.php';
include 'protect.php';
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Products</title>
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
          
          <div class="box"> Editing Products for Category:
            <br/>
            <?php

            $catid = $_GET['catid'];
            $status = 'Add Item';
            $catsql = $dbh->prepare("select catname from $categories where catid = '$catid'");
            $catsql->execute();

            $catrow = $catsql->fetch();

            $catname = $catrow[0];
            echo '<h5>'.$catname.'</h5>';


            $script = $_SERVER['PHP_SELF'];
            $submit = $_POST['submit'];
            $updateid = $_GET['updateid'];
            $deleteid = $_GET['deleteid'];
            if ($submit != '')
            { 
             $desc = addslashes($_POST['desc']);
             $name = addslashes($_POST['item']);
             $price = $_POST['price'];
             $link = $_POST['link'];
             $catid = $_POST['itemcat'];
             $attribute = $_POST['attribute'];

             $idtoupdate = $_POST['idtoupdate'];
             $tmp = $_FILES['pic']['tmp_name'];
             $tmp2 = $_FILES['prodpic2']['tmp_name'];
             $tmp3 = $_FILES['prodpic3']['tmp_name'];
             $tmp4 = $_FILES['prodpic4']['tmp_name'];
             $tmp5 = $_FILES['prodpic5']['tmp_name'];
             $spectoupdate = $_POST['spec'];
             $qty = $_POST['qty'];
             $link = trim($_POST['link']);
             $weight = $_POST['weight'];
           }

           if ($deleteid) $dbh->exec("delete from $products where prodid = '$deleteid'"); 
           
           if ($idtoupdate != "")
           {
            $sql = $dbh->exec("update $products set prodname = '$name',proddesc = '$desc', prodprice = '$price',link = '$link', catid = '$catid' where prodid = '$idtoupdate'");	

            if ($tmp) move_uploaded_file($tmp,"../".$idtoupdate."/1.jpg"); 	
            if ($tmp2) move_uploaded_file($tmp2,"../".$idtoupdate."/2.jpg");
            if ($tmp3) move_uploaded_file($tmp3,"../".$idtoupdate."/3.jpg");
            if ($tmp4) move_uploaded_file($tmp4,"../".$idtoupdate."/4.jpg");
            if ($tmp5) move_uploaded_file($tmp5,"../".$idtoupdate."/5.jpg");

          }


          
          if ($submit == "Add Item" )
          {
           $dbh->exec("insert into $products (prodname,proddesc,prodprice,link,catid) values ('$name','$desc','$price','$link',$catid')");
           $last = $dbh->prepare("select prodid from $products order by id desc limit 1");
           $last->execute();
           $lastitem = $last->fetchObject();
           $picid= $lastitem->prodid;
           $updateid = $picid;
  ///mkdir('../prodimages'.$picid);
           if ($tmp) move_uploaded_file($tmp,"../prodimages/".$picid.".jpg"); 	
/*if ($tmp2) move_uploaded_file($tmp2,"../".$updateid."/2.jpg");
if ($tmp3) move_uploaded_file($tmp3,"../".$updateid."/3.jpg");
if ($tmp4) move_uploaded_file($tmp4,"../".$updateid."/4.jpg");
if ($tmp5) move_uploaded_file($tmp5,"../".$updateid."/5.jpg");*/
}

if ($updateid != '')
{
	$innersql = $dbh->prepare("select prodname, proddesc, prodprice,link,catid from $products where prodid = '$updateid'");
	$innersql->execute();
	$innerrow = $innersql->fetchObject();
	$innername = $innerrow->prodname;
	$innerdesc = $innerrow->proddesc;
	$innerprice = $innerrow->prodprice;
	$innercat = $innerrow->catid; 
  $innerlink = $innerrow->link;

  $content = "ErrorDocument 404 /index.php\n";
  $content .= "Options +FollowSymLinks\n";
  $content .= "RewriteEngine on\n";
  $linksql = "select prodid,link from $products";
  foreach ($dbh->query($linksql) as $linkrow )
  {
    $id = $linkrow[0];
    $link = trim($linkrow[1]);
    if ($link != "")
    {
      trim($link);	
      $link = str_replace(' ','',$link);
      $content .= 'RewriteRule ^'.$link.'$ products.php?prodid='.$id." [L]\n";
    }
  }
}

/*
$fh = fopen("../.htaccess",w);
fwrite($fh,$content);
fclose($fh);
*/

?>
Add an Item<br/>
<?php
$query = 'catid='.$catid;
if ($updateid != '') {
  $query .= '&updateid='.$updateid;
  $status = 'Edit Item';
}


?>
<form action = "products.php?<?php echo $query;?>" method = "post" enctype="multipart/form-data">
  <input type = "hidden" name = "idtoupdate" value = "<?php echo $updateid;?>" />
  Item:
  <input type = "text" name = "item" value = "<?php echo $innername;?>"/>
  <br/>
  Category:
  <select name = "itemcat">
    <option value = "">Select a Category</option>
    <?php 
    $catsql = "select * from $categories";
    foreach ($dbh->query($catsql) as $catrow)
    {
     $catid = $catrow[0];
     $catname = $catrow[1];
     if ($catid == $innercat) 
     {
       echo '<option value = "'.$catid.'" selected="selected">'.$catname.'</option>';
     }
     else  {
      echo '<option value = "'.$catid.'">'.$catname.'</option>';	
    }	  
  }
  ?>
</select>
<br/>
Description:
<textarea name = "desc"><?php echo $innerdesc;?>
</textarea>
<br/>
Price:
<input type = "text" name = "price" value = "<?php echo $innerprice;?>" />
<br/>
<?php
if (file_exists("../prodimages/".$updateid.".jpg"))
{
  echo '<img src = "../prodimages/'.$updateid.'.jpg" height="100">';
}
?>
Upload main picture(JPEG format only)
<input type = "file" name = "pic" >
<br/>
<?php
/*

if (file_exists("../".$updateid."/2.jpg"))
    {
echo '<img src = "../thumbnail.php?pic='.$updateid.'/2.jpg">';
	}

          Upload picture 2(JPEG format only)
          <input type = "file" name = "prodpic2" >
          <br/>
          
if (file_exists("../".$updateid."/3.jpg"))
    {
echo '<img src = "../thumbnail.php?pic='.$updateid.'/3.jpg">';
	}

          Upload picture 3(JPEG format only)
          <input type = "file" name = "prodpic3" >
          <br/>
          
if (file_exists("../".$updateid."/4.jpg"))
    {
echo '<img src = "../thumbnail.php?pic='.$updateid.'/4.jpg">';
	}

       Upload picture 4(JPEG format only)
          <input type = "file" name = "prodpic4" >
          <br/>
          
if (file_exists("../".$updateid."/5.jpg"))
    {
echo '<img src = "../thumbnail.php?pic='.$updateid.'/5.jpg">';
	}
  */
  ?>
  

  <br/>
  Link:
  <input type = "text" name = "link" value = "<?php echo  $innerlink;?>" />
  <br/>
  <input type = "submit" name = "submit" value = "<?php echo $status;?>">
  

</form>
<br/>
<br/>
Current Items, Click to Update<br/>
<?php
$catid = $_GET['catid'];
if ($catid) { 
  $addtosql = "where catid = '$catid'";
}
$sql = "select * from $products $addtosql";
$getsql = $dbh->prepare($sql);
$getsql->execute();
echo "<table border=\"0\"  cellpadding=\"5\">";
while ($row = $getsql->fetch())
{

	$id = $row[0];
	$itemname = $row[1];
	echo "<tr>";
	echo "<td><a href = \"products.php?updateid=$id&catid=$catid\">Update $itemname</a></td><td><a href = \"products.php?deleteid=$id&catid=$catid\">Delete</a></td>";
	echo "</tr>";
} 
echo "</table>";
$dbh = null;
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
