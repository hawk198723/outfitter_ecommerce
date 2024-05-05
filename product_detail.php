<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// phpinfo();
// var_dump($_SESSION);
// echo session_id();
error_reporting(E_ALL); // 仅在开发过程中使用
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// } // 如果需要使用会话
// 引入数据库连接文件
require_once 'config/db.php';
// require_once 'public/mesacart/functions/cartfunctions.php';



$sessionId = session_id();
$totalQuantity = 0;

// 从数据库查询购物车总数量
$stmt = $dbh->prepare("SELECT SUM(qty) AS total FROM mesa_cartitems WHERE sessionid = ?");
$stmt->execute([$sessionId]);
if ($row = $stmt->fetch()) {
    $totalQuantity = $row['total'];
}

// 获取所有产品
$stmt = $dbh->prepare("SELECT * FROM mesa_products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 检查是否存在prodid参数
$prodid = $_GET['prodid'] ?? null;

if (!$prodid) {
    // 如果prodid参数不存在，可以添加错误处理逻辑或重定向到其他页面
    header("Location: index.php");
    exit; // 确保脚本结束运行
}

$sessionId = session_id();
$prodid = $_GET['prodid'] ?? null;

if (!$prodid) {
    header("Location: index.php");
    exit;
}

// 假设已经通过GET方法获取了prodid
$prodid = $_GET['prodid'] ?? null;
if (!$prodid) {
    header("Location: index.php");
    exit;
}

try {
    $dbh->beginTransaction();
    // 获取产品详细信息
    $productStmt = $dbh->prepare("SELECT * FROM mesa_products WHERE prodid = ?");
    $productStmt->execute([$prodid]);
    $product = $productStmt->fetch(PDO::FETCH_ASSOC);

    // 获取尺寸信息，注意这里labelid应该是尺寸对应的标签ID，在你的mesa_labels表中确认
    $sizesStmt = $dbh->prepare("SELECT value FROM mesa_attributes WHERE prodid = ? AND labelid = 1");
    $sizesStmt->execute([$prodid]);
    $sizes = $sizesStmt->fetchAll(PDO::FETCH_ASSOC);
    $dbh->commit();
} catch (Exception $e) {
    $dbh->rollBack();
    header("Location: error_page.php"); // 错误页面
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Homepage</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap icons-->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"
      rel="stylesheet"
    />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    <style>
.container1 {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    background: #f9f9f9;
}

.product-img img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.product-price {
    font-size: 24px;
    color: #333;
    margin: 20px 0;
}

.btn-group .btn {
    padding: 12px 24px;
    font-size: 18px;
    border-radius: 5px;
    border: none;
    color: #fff;
    background-color: #007bff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    transition: background-color 0.3s ease;
}

.btn-group .btn:hover {
    background-color: #0056b3;
}

.card-footer {
    width: 100%;
    padding: 20px 0;
}

.btn-group {
    display: flex;
    justify-content: center;
    gap: 10px;
}

       

    </style>
  </head>
  <body>
    <!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container px-4 px-lg-5">
    <a class="navbar-brand" href="index.php">Outfitter</a>
    <button
      class="navbar-toggler"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
        <li class="nav-item">
           <a class="nav-link <?php echo (basename($_SERVER['SCRIPT_NAME']) == 'products_men.php' ? 'active' : ''); ?>" href="/outfitter/public/products_men.php">Mens</a>
        </li>
        <li class="nav-item">
           <a class="nav-link <?php echo (basename($_SERVER['SCRIPT_NAME']) == 'products_women.php' ? 'active' : ''); ?>" href="/outfitter/public/products_women.php">Womens</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link <?php echo (basename($_SERVER['SCRIPT_NAME']) == 'products_sales.php' ? 'active' : ''); ?>" href="/outfitter/public/products_sales.php">Sales</a>
        </li>
      </ul>

      <!-- Search Form -->
      <form class="d-flex" action="search_results.php" method="get" onsubmit="return submitSearch()">
        <div class="search-container">
            <span class="search-icon" onclick="submitSearch()">&#x1F50D;</span>
            <input
                type="search"
                class="search-input"
                placeholder="Search..."
                name="query"
                id="searchQuery"
            />
        </div>
    </form>

      <!-- User Account and Cart -->
        <ul class="navbar-nav">
          <?php if(isset($_SESSION['username'])) : ?>
            <li class="nav-item">
              <span class="nav-link">Welcome, <?php echo $_SESSION['username']; ?></span>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">Log Out</a>
            </li>
          <?php else : ?>
            <li class="nav-item">
              <a class="nav-link" href="signup.php">Sign Up</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="login.php">Log In</a>
            </li>
          <?php endif; ?>
        </ul>
        <a class="btn btn-outline-dark" href="public/cart.php">
          <i class="bi-cart-fill me-1"></i>
          Cart
          <span class="badge bg-dark text-white ms-1 rounded-pill cart-count">0</span>
        </a>
    </div>
  </div>
</nav>




   <div class="container1">
        <h1><?php echo htmlspecialchars($product['prodname']); ?></h1>
        <div class="product-img">
            <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['prodname']); ?>">
        </div>
        <div class="product-price">$<?php echo htmlspecialchars($product['prodprice']); ?></div>
        <form action="add_to_cart.php" method="post">
            
            
            
        
            <input type="hidden" name="productid" value="<?php echo $prodid; ?>">
            <!-- Product actions -->
            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                <div class="text-center d-flex justify-content-center">
                    <div class="btn-group" role="group">
                        <button class="btn btn-outline-dark mt-auto" data-productid="<?php echo htmlspecialchars($product['prodid']); ?>" onclick="addToCart(this)">Add</button>
                        <a href="https://www.paypal.com/checkoutnow?token=YOUR_TOKEN&amount=10.00&currency=USD" class="btn btn-outline-dark mt-auto ml-2">Buy</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
            



    <!-- Footer-->
    <footer class="py-5 bg-dark">
      <div class="container">
        <div class="m-0 text-center text-white">
          <h3>Find us</h3>
          <div class="contact-info">
            <address>SD Loft: 330 Park Blvd, San Diego, CA 92101</address>
            <p>Phone: (858)-888-0120</p>
          </div>
        </div>

        <div class="social-media">
          <h3>Follow us</h3>

          <div class="icons-grid">
            <a href="https://facebook.com" class="social-icon"
              ><i class="bx bxl-facebook-square"></i
            ></a>
            <a href="https://instagram.com" class="social-icon"
              ><i class="bx bxl-instagram-alt"></i
            ></a>
            <a href="https://twitter.com" class="social-icon"
              ><i class="bx bxl-twitter"></i
            ></a>
            <a href="https://tiktok.com" class="social-icon"
              ><i class="bx bxl-tiktok"></i
            ></a>
          </div>
        </div>
      </div>
      <div class="copyright">
        <p>Copyright &copy; Jason Wang 2024</p>
      </div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
    
   
     <!-- addToCart on sale JS-->
    <script>
        function addToCart(button) {
            const productId = button.getAttribute('data-productid');
            const quantity = 1;  // 假设每次添加数量为1
        
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `productid=${productId}&qty=${quantity}`
            })
            .then(response => response.text())
            .then(data => {
                document.querySelector('.cart-count').textContent = data;  // 更新购物车的数量
            })
            .catch(error => console.error('Error:', error));
        }
        // 经常更新数据库的购物车数量，根据sessionID走
        document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
});

function updateCartCount() {
    fetch(`get_cart_count.php?_=${new Date().getTime()}`, {
        method: 'GET',
        headers: {
            'Cache-Control': 'no-cache'
        }
    })
    .then(response => response.text())
    .then(data => {
        document.querySelector('.cart-count').textContent = data;
    })
    .catch(error => console.error('Error:', error));
}

    </script>
<script>
function submitSearch() {
    var searchQuery = document.getElementById("searchQuery").value.trim();
    if (searchQuery !== "") {
        window.location.href = "search_results.php?query=" + encodeURIComponent(searchQuery);
    }
    return false; // 阻止表单提交
}
</script>

  </body>
</html>
