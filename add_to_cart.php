<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'config/db.php'; // 确保数据库配置文件路径正确

$productId = $_POST['productid'];
$quantity = $_POST['qty'];
$sessionId = session_id(); // 获取或创建session ID

// 插入或更新数据
if(isset($_POST['productid']) && isset($_POST['qty'])) {
    $check = $dbh->prepare("SELECT * FROM mesa_cartitems WHERE productid = ? AND sessionid = ?");
    $check->execute([$productId, $sessionId]);
    $item = $check->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        $newQty = $item['qty'] + $quantity;
        $update = $dbh->prepare("UPDATE mesa_cartitems SET qty = ? WHERE productid = ? AND sessionid = ?");
        $update->execute([$newQty, $productId, $sessionId]);
    } else {
        $insert = $dbh->prepare("INSERT INTO mesa_cartitems (productid, qty, sessionid) VALUES (?, ?, ?)");
        if ($insert->execute([$productId, $quantity, $sessionId])) {
            // echo "Inserted Successfully";
        } else {
            // echo "Failed to insert: ";
            print_r($insert->errorInfo());
        }
    }

    // 返回购物车商品总数量
    $totalQty = 0;
    $stmt = $dbh->prepare("SELECT qty FROM mesa_cartitems WHERE sessionid = ?");
    $stmt->execute([$sessionId]);
    while ($row = $stmt->fetch()) {
        $totalQty += $row['qty'];
    }
    echo $totalQty;
} else {
    echo 0; // 发送错误或无操作的响应
}
?>
