<?php
include '../config/db.php';  // 确保数据库连接正确
include 'cartfunctions.php'; // 包含购物车相关函数
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$sessionId = session_id();

// 获取购物车内容
$cartItems = getCartItems($dbh, $sessionId);

// 处理更新购物车中商品数量的请求
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $prodId = $_POST['prodId'];
    $newQty = (int)$_POST['newQty'];  // 确保数量是一个整数

    // 更新购物车中商品数量，允许数量为0，如果为0则从购物车中删除该商品
    $result = updateCartQuantity($dbh, $prodId, $newQty, $sessionId);
    if ($result) {
        header("Location: cart.php"); // 更新成功后重定向回购物车页面
        exit();
    } else {
        $error = "Unable to update the quantity. Please try again.";
    }
}


// 处理删除购物车中商品的请求
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove'])) {
    $prodId = $_POST['prodId'];

    $result = removeCartItem($dbh, $prodId, $sessionId);
    if ($result) {
        header("Location: cart.php"); // 删除成功后重定向回购物车页面
        exit();
    } else {
        $error = "Unable to remove the item from the cart. Please try again.";
    }
}

$totalQuantity = getCartTotalQuantity($dbh, $sessionId);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <link
            href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
            rel="stylesheet"
    />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>Cart</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="../assets/favicon.ico"/>
    <!-- Bootstrap icons-->
    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"
            rel="stylesheet"
    />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="../css/styles.css" rel="stylesheet"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        td {
            vertical-align: middle;
        }

        .update-btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .remove-btn {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .checkout-btn {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            float: right;
            margin-top: 20px;
        }

        .total {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
        }

        @media (max-width: 576px) {
            .container {
                padding: 10px;
            }

            h1 {
                font-size: 24px;
            }

            table {
                font-size: 14px;
            }

            th,
            td {
                padding: 8px;
            }

            .update-btn,
            .remove-btn {
                padding: 3px 8px;
            }

            .checkout-btn {
                padding: 8px 16px;
            }

            .total {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="../index.php">Outfitter</a>
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
                    <a class="nav-link <?php echo (basename($_SERVER['SCRIPT_NAME']) == 'products_men.php' ? 'active' : ''); ?>"
                       href="/outfitter/public/products_men.php">Mens</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['SCRIPT_NAME']) == 'products_women.php' ? 'active' : ''); ?>"
                       href="/outfitter/public/products_women.php">Womens</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['SCRIPT_NAME']) == 'products_sales.php' ? 'active' : ''); ?>"
                       href="/outfitter/public/products_sales.php">Sales</a>
                </li>
            </ul>

            <!-- Search Form -->
              <form class="d-flex" action="../search_results.php" method="get" onsubmit="return submitSearch()">
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
             <!-- User Account and Cart -->
        <ul class="navbar-nav">
          <?php if(isset($_SESSION['username'])) : ?>
            <li class="nav-item">
              <span class="nav-link">Welcome, <?php echo $_SESSION['username']; ?></span>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../logout.php">Log Out</a>
            </li>
          <?php else : ?>
            <li class="nav-item">
              <a class="nav-link" href="../signup.php">Sign Up</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../login.php">Log In</a>
            </li>
          <?php endif; ?>
        </ul>
        
            <a href="cart.php" class="btn btn-outline-dark">
                <i class="bi-cart-fill me-1"></i>
                Cart
                <span class="badge bg-dark text-white ms-1 rounded-pill cart-count"><?= $totalQuantity ?></span>
            </a>

        </div>
    </div>
</nav>

<body>
 <div class="container">
    <h1>Your Shopping Cart</h1>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['prodname']) ?></td>
                    <td>$<?= number_format($item['prodprice'], 2) ?></td>
                    <td>
                        <form action="cart.php" method="post">
                            <input type="number" name="newQty" value="<?= $item['qty'] ?>" min="0">
                            <input type="hidden" name="prodId" value="<?= $item['prodid'] ?>">
                            <button type="submit" class="update-btn" name="update">Update</button>
                        </form>
                    </td>
                    <td>$<?= number_format($item['qty'] * $item['prodprice'], 2) ?></td>
                    <td>
                        <form action="cart.php" method="post">
                            <input type="hidden" name="prodId" value="<?= $item['prodid'] ?>">
                            <button type="submit" class="remove-btn" name="remove">Remove</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="total">
        Total: $<?= calculateTotal($cartItems) ?>
    </div>
    <!--<div class="checkout-btn-container">-->
    <!--    <a href="paypal_checkout.php" class="btn btn-success checkout-btn">Checkout</a>-->
    <!--</div>-->
    <div class="checkout-btn-container">
    <a href="https://www.paypal.com/checkoutnow?token=YOUR_TOKEN&amount=10.00&currency=USD" class="btn btn-success checkout-btn">Checkout with PayPal</a>
</div>
</div>


<script>
document.querySelectorAll('input[type="number"]').forEach(input => {
    // 实时响应用户输入
    input.addEventListener('input', function() {
        const form = this.form;
        const qtyValue = parseInt(this.value, 10); // 获取当前输入的数量值

        if (qtyValue === 0) {
            // 如果输入数量为0，确认是否删除
            if (confirm("Are you sure you want to remove this item from the cart?")) {
                const prodId = form.querySelector('input[name="prodId"]').value;
                const formData = new FormData();
                formData.append('remove', 'true');
                formData.append('prodId', prodId);

                fetch('cart.php', {
                    method: 'POST',
                    body: formData
                }).then(response => {
                    if (response.ok) {
                        window.location.reload(); // 刷新页面以更新购物车数字
                    } else {
                        console.error('Failed to remove item from cart.');
                    }
                }).catch(error => {
                    console.error('Error removing item from cart:', error);
                });
            } else {
                this.value = this.defaultValue; // 如果用户取消，重置为之前的值
            }
        } else if (qtyValue > 0) {
            // 如果输入的数量大于0，实时更新购物车数量显示
            updateCartCount();
        }
    });

    // 确保在用户完成输入（可能在失焦时）后更新购物车
    input.addEventListener('change', function() {
        updateCartCount();
    });
});


// 更新购物车数字的函数
function updateCartCount() {
    const totalQuantity = document.querySelectorAll('input[type="number"]').reduce((total, input) => {
        return total + parseInt(input.value);
    }, 0);
    document.querySelector('.cart-count').textContent = totalQuantity;
}

window.onload = function() {
    updateCartCount();
};


</script>

</body>
</html>
