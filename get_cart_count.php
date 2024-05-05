<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'config/db.php';  // 确保数据库配置文件路径正确

$sessionId = session_id();
$totalQty = 0;

$stmt = $dbh->prepare("SELECT qty FROM mesa_cartitems WHERE sessionid = ?");
$stmt->execute([$sessionId]);

while ($row = $stmt->fetch()) {
    $totalQty += $row['qty'];
}

echo $totalQty;  // 返回总数量
?>
