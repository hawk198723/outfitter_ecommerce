<?php
include '../config/db.php'; // 确保这个路径正确

$sessionId = session_id();
$sql = $dbh->prepare("SELECT mesa_products.prodid, mesa_products.prodname, mesa_products.prodprice, mesa_cartitems.qty FROM mesa_products JOIN mesa_cartitems ON mesa_products.prodid = mesa_cartitems.productid WHERE mesa_cartitems.sessionid = ?");
$sql->execute([$sessionId]);

echo '<table class="table">';
echo '<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th></tr>';
while ($row = $sql->fetch()) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($row['prodname']) . '</td>';
    echo '<td>$' . htmlspecialchars($row['prodprice']) . '</td>';
    echo '<td>' . htmlspecialchars($row['qty']) . '</td>';
    echo '<td>$' . (htmlspecialchars($row['prodprice']) * htmlspecialchars($row['qty'])) . '</td>';
    echo '</tr>';
}
echo '</table>';
?>
