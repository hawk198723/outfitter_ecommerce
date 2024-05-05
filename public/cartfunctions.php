<?php

// 获取购物车中的所有商品
function getCartItems($dbh, $sessionId) {
    try {
        $sql = "SELECT p.prodid, p.prodname, p.prodprice, c.qty 
                FROM mesa_products p 
                JOIN mesa_cartitems c ON p.prodid = c.productid 
                WHERE c.sessionid = ?";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$sessionId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching cart items: " . $e->getMessage());
        return [];
    }
}

// 更新购物车中的商品数量
function updateCartQuantity($dbh, $prodId, $qty, $sessionId) {
    try {
        if ($qty <= 0) {
            // 如果数量小于或等于0，则删除商品
            $sql = "DELETE FROM mesa_cartitems WHERE productid = ? AND sessionid = ?";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$prodId, $sessionId]);
        } else {
            // 否则，更新商品数量
            $sql = "UPDATE mesa_cartitems SET qty = ? WHERE productid = ? AND sessionid = ?";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$qty, $prodId, $sessionId]);
        }
        return true;
    } catch (PDOException $e) {
        error_log("Error updating cart: " . $e->getMessage());
        return false;
    }
}


// 获取购物车中商品的总数量
function getCartTotalQuantity($dbh, $sessionId) {
    try {
        $sql = "SELECT SUM(qty) AS total FROM mesa_cartitems WHERE sessionid = ?";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$sessionId]);
        $result = $stmt->fetch();
        return $result ? (int) $result['total'] : 0;
    } catch (PDOException $e) {
        error_log("Error getting total quantity: " . $e->getMessage());
        return 0;
    }
}

// 从购物车中删除商品
function removeCartItem($dbh, $prodId, $sessionId) {
    try {
        $sql = "DELETE FROM mesa_cartitems WHERE productid = ? AND sessionid = ?";
        $stmt = $dbh->prepare($sql);
        return $stmt->execute([$prodId, $sessionId]);
    } catch (PDOException $e) {
        error_log("Error removing item from cart: " . $e->getMessage());
        return false;
    }
}

function calculateTotal($items) {
    $total = 0;
    foreach ($items as $item) {
        $total += $item['qty'] * $item['prodprice'];
    }
    return number_format($total, 2);
}

?>
