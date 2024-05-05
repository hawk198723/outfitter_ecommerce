<?php
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

// 处理注册表单提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 验证用户名和密码是否为空
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $error = "用户名和密码不能为空";
    } else {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // 对密码进行一些验证，例如长度、包含字符、数字和特殊字符等
        // 这里简单示例，实际应根据具体需求添加更多验证
        if (strlen($password) < 8) {
            $error = "Password must be at least 8 characters long";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT); // 对密码进行哈希加密

            // 插入用户数据到数据库
            $sql = "INSERT INTO mesa_admin (username, password) VALUES ('$username', '$hashed_password')";
            if ($conn->query($sql) === TRUE) {
                // 注册成功，重定向到登录页面
                header("Location: login.php?registered=true");
                exit(); // 确保重定向后不再执行后续代码
            } else {
                $error = "Registration failed: " . $conn->error;
            }
        }
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        .login-link {
            text-align: center;
            margin-top: 10px;
        }
        .login-link a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Registration</h2>
        <?php if (!empty($error)) : ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (isset($_GET['registered']) && $_GET['registered'] == true) : ?>
            <p style="color: green; text-align: center;">Registration successful! Please login.</p>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <input type="submit" value="Register">
        </form>
        <div class="login-link">
            <p>Already have an account? <a href="login.php">Login Here</a></p>
        </div>
    </div>
</body>
</html>