<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>โปรแกรมประปาหมู่บ้าน</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5 text-center" style="font-family: THSarabun, sans-serif;">

<h1 class="mb-3">💧 โปรแกรมประปาหมู่บ้าน</h1>
<p class="mb-4 text-muted">ระบบจัดการค่าน้ำประปา</p>

<div class="d-grid gap-3 col-md-6 mx-auto">

    <a href="login.php" class="btn btn-primary btn-lg">
        🔐 เข้าสู่ระบบ (ผู้ดูแล / เจ้าหน้าที่)
    </a>

    <a href="customers.php" class="btn btn-success btn-lg">
        📋 จดมิเตอร์น้ำ
    </a>

    <a href="mybill.php" class="btn btn-warning btn-lg">
        💰 ลูกบ้านดูบิล / ชำระเงิน
    </a>

</div>

<hr class="my-4">

<p class="text-muted">
    © โปรแกรมประปาหมู่บ้าน | ระบบพร้อมใช้งานจริง
</p>

</body>
</html>
