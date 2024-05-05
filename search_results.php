<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/db.php';
// 设置 PDO 错误模式为异常
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// 获取搜索关键词
$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';

// 查询数据库，根据搜索关键词搜索商品
try {
    // 在这里执行你的数据库查询，根据搜索关键词过滤商品
    // 请确保在此之前已经建立了与数据库的连接并赋值给 $dbh 变量

    // 准备 SQL 语句
    $sql = "SELECT * FROM mesa_products WHERE prodname LIKE '%$searchQuery%'";
    
    // 准备并执行查询
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    
    // 获取查询结果
    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <!-- Add your CSS styles here -->
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .search-results {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            grid-gap: 20px;
            justify-items: center;
            margin-top: 20px;
        }
        .product {
            text-align: center;
        }
        .product img {
            max-width: 100%;
            height: auto;
        }
          a.card-link {
    color: black; /* 将链接颜色设置为黑色 */
    text-decoration: none; /* 去除下划线 */
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
            <li class="nav-item">
              <a class="nav-link" href="signup.php">Sign Up</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="login.php">Log In</a>
            </li>
          </ul>
         <a class="btn btn-outline-dark" href="public/cart.php">
        <i class="bi-cart-fill me-1"></i>
        Cart
        <span class="badge bg-dark text-white ms-1 rounded-pill cart-count">0</span>
    </a>
        </div>
      </div>
    </nav>



   <div class="container">
        <h1>Search Results for "<?php echo htmlspecialchars($searchQuery); ?>"</h1>

        <div class="search-results">
            <?php foreach ($searchResults as $product): ?>
                <div class="product">
                    <a href="product_detail.php?prodid=<?php echo htmlspecialchars($product['prodid']); ?>" class="card-link">
                     <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['prodname']); ?>">
                    </a>
                    <a href="product_detail.php?prodid=<?php echo htmlspecialchars($product['prodid']); ?>" class="card-link">
                     <h2><?php echo htmlspecialchars($product['prodname']); ?></h2>
                    </a>
                    <p>$<?php echo number_format($product['prodprice'], 2); ?></p>
                    <form action="add_to_cart.php" method="post">
                        <input type="hidden" name="product_id" value="<?php echo $product['prodid']; ?>">
                        <input type="hidden" name="quantity" value="1"> <!-- 默认添加数量为1 -->
                        <div class="btn-group" role="group">
                                    <button class="btn btn-outline-dark mt-auto ml-2" data-productid="<?php echo htmlspecialchars($product['prodid']); ?>" onclick="addToCart(this)">Add </button>
                                    <a href="https://www.paypal.com/checkoutnow?token=YOUR_TOKEN&amount=10.00&currency=USD" class="btn btn-outline-dark mt-auto ml-2">Buy</a>
                                </div>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
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
    // 添加到购物车函数
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
            // 更新购物车数量
            document.querySelector('.cart-count').textContent = data;
        })
        .catch(error => console.error('Error:', error));

        // 阻止默认表单提交行为
        event.preventDefault();
    }

    // 更新购物车数量函数
    function updateCartCount() {
        fetch(`get_cart_count.php?_=${new Date().getTime()}`, {
            method: 'GET',
            headers: {
                'Cache-Control': 'no-cache'
            }
        })
        .then(response => response.text())
        .then(data => {
            // 更新购物车数量显示
            document.querySelector('.cart-count').textContent = data;
        })
        .catch(error => console.error('Error:', error));
    }

    // 当页面加载完成时调用更新购物车数量函数
    document.addEventListener('DOMContentLoaded', function() {
        updateCartCount();
    });
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
