<?php
session_start();
include 'config/db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>ระบบประปาหมู่บ้าน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5 text-center" style="font-family: THSarabun, sans-serif;">

<h2 class="mb-4">💧 ระบบประปาหมู่บ้าน</h2>

<div class="d-grid gap-3 col-6 mx-auto">
    <a href="login.php" class="btn btn-primary btn-lg">เข้าสู่ระบบ</a>
    <a href="customers.php" class="btn btn-success btn-lg">จดมิเตอร์น้ำ</a>
</div>

</body>
</html>
