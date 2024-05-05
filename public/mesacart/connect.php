<?php
error_reporting(E_ALL);
session_start();
$sessid = session_id();
$dbh = new PDO("mysql:host=localhost;dbname=cbnclamy_outfit", "cbnclamy_outfitadmin", "1qaz2wsx!QAZ@WSX");
echo "Connected successfully";
$admin = 'mesa_admin';
$categories = 'mesa_categories';
$products = 'mesa_products';
$cartitems = 'mesa_cartitems';

?>

<?php
error_reporting(E_ALL);
session_start();

$sessid = session_id();
try {
    $dbh = new PDO("mysql:host=localhost;dbname=cbnclamy_outfit", "cbnclamy_outfitadmin", "1qaz2wsx!QAZ@WSX");
    echo "Connected successfully";
    
    // 测试查询
    $stmt = $dbh->query("SELECT * FROM mesa_products LIMIT 1");
    if ($stmt) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<pre>";
        print_r($row);
        echo "</pre>";
    }
    
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>