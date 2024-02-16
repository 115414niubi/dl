<?php
session_start();

// 检测登录尝试次数
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

// 当达到3次错误尝试，检查时间限制
if ($_SESSION['login_attempts'] >= 3) {
    if (isset($_SESSION['lockout_time']) && (time() - $_SESSION['lockout_time'] < 60)) {
        die("请60秒后重试");
    } else {
//没bug
        $_SESSION['login_attempts'] = 0;
        unset($_SESSION['lockout_time']);
    }
}

// 假定数据库连接信息，要改！！！
$host = 'localhost';
$dbUsername = '';
$dbPassword = '';
$dbName = '';

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password']; 

$sql = "SELECT * FROM yh WHERE username = '$username' AND password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    setcookie("user", $username, time() + (86400 * 30), "/"); // 改一下数据库就可以用，注意，要数据库里面有yh表里面有username和password
    header("Location: jm.html"); 
    exit();
} else {
    // 登录失败
    $_SESSION['login_attempts'] += 1;
    if ($_SESSION['login_attempts'] >= 3) {
        $_SESSION['lockout_time'] = time();
        echo "<script>alert('请60秒后重试'); window.location.href = 'index.html';</script>";
    } else {
        echo "<script>alert('用户名或密码错误'); window.location.href = 'index.html';</script>";
    }
}

$conn->close();
?>
