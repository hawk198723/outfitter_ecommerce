<!DOCTYPE html>
<html  lang="en">

<head>
    <title><?php if (isset($catname)) echo $catname;
    else {
      echo 'Mesa Cart';
    }?></title>
    
<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/bootstrap.css">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
    @import url(style.css);
    .small_pic {
    max-height: 200px;
  }
  .carousel-inner > .item > img,
  .carousel-inner > .item > a > img {
    width: 70%;
    margin: auto;
  }
    </style>
</head>

<body>

  <div class="top-container">
      <div class="ship">
        <h1><a href="">FREE SHIPPING <span>on all orders &#36;75 or more</span></a></h1>
      </div>
      
      <div class="membership">
        <h2 style="padding-top:.6vw;"><a href="">Join Membership Now&#33;</a></h2>
      </div>
         
      <div>
        <ul>
            <li style="padding-top: .5vw;"><a href="viewcart.php"><i class="fas fa-shopping-cart"></i>(<?php
            echo numcartitems($sessid)
            ?>)</a></li>
            <li><a href="">My Account</a></li>
            <li><a href="">Need Help&#63;</a></li> 
        </ul>
      </div>
  </div>
  
  <div class="main-container">   
  
	  <header class="header">
  			  	
	  	<div class="logo">
	  		<p><a href="index.html">Logo Here</span></a></p>
	  	</div>

		<div class="extra10">
			<p><a href="">All Members Get <br><strong>Extra 10% off</strong><br><span>JOIN MEMBERSHIP NOW!</span></a></p>
		</div>
			

		<div class="search">
		<form action="search.php" method="get">
			<input type="text" name="qry" placeholder="Search entire store">
			<button><i class="fas fa-search" ></i></button>
		</form>
		</div>
		
	 </header><!--	 End of header class-->
   <nav>
    <ul>
<?php
$sql = $dbh->prepare("select * from mesa_categories");
$sql->execute();
while ($row = $sql->fetch()){
  $catid = $row['catid'];
  $catname = $row['catname'];
$nav .= '<li><a href="categories.php?catid='.$catid.'">'.strtoupper($catname).'</a></li>';
}
echo $nav;
  ?>
      <li><a href="">MEMBERS</a></li>
      <li><a href="" style="color:red; ">SALE</a></li>      
      </ul>
  </nav>
    
  <main>
 <!--    End of img-nav-->