<?php
error_reporting(E_ALL);
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

// 创建一个空变量来保存可能的错误消息
$error = "";

// 处理登录表单提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 查询数据库中是否存在匹配的用户名
    $sql = "SELECT id, username, password, last_session_id FROM mesa_admin WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) { // 检查结果是否存在
        $row = $result->fetch_assoc(); // 获取查询结果的行
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $old_session_id = $row['last_session_id']; // 获取旧的 session_id

            // 生成一个新的 session_id
            session_regenerate_id(true);
            $new_session_id = session_id(); // 获取新的 session_id

            // 更新用户的 last_session_id 到数据库
            $update_session_sql = "UPDATE mesa_admin SET last_session_id='$new_session_id' WHERE id=".$_SESSION['user_id'];
            $conn->query($update_session_sql);

            // 迁移购物车数据
            if (!empty($old_session_id)) {
                $migrate_cart_sql = "UPDATE mesa_cartitems SET sessionid='$new_session_id' WHERE sessionid='$old_session_id'";
                $conn->query($migrate_cart_sql);
            }

            // 重定向到首页
            header("Location: index.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "User does not exist!";
    }
}

// 关闭数据库连接
if (isset($conn)) {
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 300px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="password"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
        .signup-link {
            text-align: center;
            margin-top: 10px;
        }
        .signup-link a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Login</h2>
        <?php if (!empty($error)) : ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <input type="submit" value="Login">
        </form>
        <div class="signup-link">
            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
        </div>
    </div>
</body>
</html>
