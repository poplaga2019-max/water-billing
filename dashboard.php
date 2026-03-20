<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body style="font-family: THSarabun, sans-serif;">
    <h2>แดชบอร์ด</h2>

    <p>ยินดีต้อนรับ: <?php echo $user['username']; ?></p>
    <p>สิทธิ์: <?php echo $user['role']; ?></p>

    <a href="logout.php">ออกจากระบบ</a>
</body>
</html>
