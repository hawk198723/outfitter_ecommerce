<?php
session_start();
// 数据库连接配置
$servername = "localhost";
$username = "cbnclamy_outfitadmin";
$password = "1qaz2wsx!QAZ@WSX";
$database = "cbnclamy_outfit";

// 创建连接
$conn = new mysqli($servername, $username, $password, $database);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 获取用户 ID，假设用户已经登录并且用户 ID 存储在 $_SESSION['user_id'] 中
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // 删除与用户关联的购物车数据
    $sql = "DELETE FROM cart_items WHERE user_id = $user_id";
    $result = $conn->query($sql);
    if (!$result) {
        // 处理删除失败的情况
    }
}

// 清除所有会话变量
$_SESSION = array();

// 如果要清除会话 Cookie，请设置过期日期为过去的时间
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// 最后销毁会话
session_destroy();

// 重定向到登录页面或其他适当的页面
header("Location: index.php");
exit;
?>
