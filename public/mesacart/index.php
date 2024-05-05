<?php
include 'connect.php';
include 'functions/cartfunctions.php';
include 'includes/header.php';
$prodid = $_POST['prodid'];
$qty = $_POST['qty'];

if (isset($qty)){
    addtocart($prodid,$qty);
}


?>

<div class="container">
      <br>
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <?php
$sql = $dbh->prepare("select catid,catname from mesa_categories");
$sql->execute();
$j = 0;

while ($row = $sql->fetch()){
  $catid = $row['catid'];
  $catname = $row['catname'];
  if ($j == 0){
    $active = ' active ';
  }
  else {
    unset($active);
  }
    echo '<li data-target="#myCarousel" data-slide-to="'.$j.'" class="'.$active.'"></li>';
$j++;
}
      ?>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
<?php
$sql = $dbh->prepare("select * from mesa_categories");
$sql->execute();
$i = 1;
while ($row = $sql->fetch()){

  $catid = $row['catid'];
  $pic = 'catimages/'.$catid.'.jpg';
  $catname = $row['catname'];
  if ($i == 1){
  $status = 'active';
    }
  else {
    $status = '';
  }
  echo '<div class="item '.$status.'">
        <img src="'.$pic.'" alt="'.$catname.'">
        <div class="carousel-caption">
          <h3>'.$catname.'</h3>
        </div>
      </div>';
      $i++;
}//closes loop
//include 'recentsearches.php';
?>

     
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>


 <div class="img-nav">
<?php
$sql = $dbh->prepare("select * from mesa_categories");
$sql->execute();
$i = 1;


while ($row = $sql->fetch()){

  $catid = $row['catid'];
  $pic = 'catimages/'.$catid.'.jpg';
  $catname = $row['catname'];
  $link = 'categories.php?catid='.$catid;
  echo '<div>
            <a target="_parent" href="'.$link.'">
                <img src="'.$pic.'" alt="'.$catname.'">
            </a>
            <div class="desc"><a href="'.$link.'">'.strtoupper($catname).'</a></div>
        </div>';
    }
    ?>
    </div>
	
    <section>
    
    	<h1 id="prods">FEATURED PRODUCTS</h1>
    	
    <div class="products">
        <?php

    $sql = $dbh->prepare("select * from mesa_products where featured = 'yes' limit 3");
    $sql->execute();
    $i=1;
    while ($row = $sql->fetch()){
        $prodid = $row['prodid'];
        $prodname = $row['prodname'];
        $proddesc = $row['proddesc'];
        $prodprice = $row['prodprice'];
        $prodlink = $row['link'];
        $picname = 'prodimages/'.$prodid.'.jpg';

   echo   '<div class="prod'.$i.'">
            <a href="'.$prodlink.'"><img src="'.$picname.'" alt="'.$prodname.'"></a>
            <div class="prod-desc">
               <div>
               <form action = "index.php#prods" method="post">
              <input type="hidden" name="qty" value="1" required="required"/>
              <input type="hidden" name="prodid" value="'.$prodid.'" required="required"/>
              <button>Add to Cart <i class="fas fa-shopping-cart"></i></button>
              </form>
               </div>
               <div>
                 <p><strong>&#36;19&#46;53 </strong><span>&#36;27&#46;90</span> 30&#37; OFF</p>
                 <p>'.$prodname.'</p>
                 <p class="review"><a href="">31 Reviews</a></p>
               </div>
            </div>
          </div>';
   
$i++;
}
    ?>
    	
    </div> <!--    End of products class-->
    
    </section>
    	<div class="social">
    		<div class="soc a">
    			<div  class="soc-desc"><a href="">FEATURED VIDEO</a></div>
    		</div>
    		
    		<div class="soc b">
    			<div  class="soc-desc"><a href="">SOCIAL CLUB</a></div>
    		</div>
    		
    		<div class="soc c">

    			<div class="soc-desc"><a href="">EVENTS</a></div>
    		</div>
    		
    	</div>
    	<?php
        include 'includes/footer.php';
        ?>