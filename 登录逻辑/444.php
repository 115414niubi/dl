<?php
// 开始会话
session_start();

// 假定数据库连接信息
$host = 'localhost';
$dbUsername = '';
$dbPassword = '';
$dbName = '';

// 创建数据库连接
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];

if (preg_match("/\p{Han}+/u", $username)) {
    echo "<script>alert('用户名不能包含中文字符'); window.history.go(-1);</script>";
    exit;
}

if (strlen($password) < 6) {
    echo "<script>alert('密码长度至少为6个字符'); window.history.go(-1);</script>";
    exit;
}

// 插入数据到数据库
$sql = "INSERT INTO yh (username, password) VALUES (?, ?)";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("准备语句失败: " . $conn->error);
}

$stmt->bind_param("ss", $username, $password);

if ($stmt->execute()) {
    echo "<script>alert('注册成功！'); window.location.href = 'index.html';</script>";
} else {
    echo "<script>alert('注册失败，可能是用户名已存在'); window.history.go(-1);</script>";
}

$stmt->close();
$conn->close();
?>
