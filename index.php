<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>โปรแกรมประปาหมู่บ้าน</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5 text-center" style="font-family: THSarabun, sans-serif;">

<h1 class="mb-3">💧 โปรแกรมประปาหมู่บ้าน</h1>
<p class="mb-4 text-muted">ระบบจัดการค่าน้ำประปา</p>

<div class="d-grid gap-3 col-6 mx-auto">

    <a href="login.php" class="btn btn-primary btn-lg">
        🔐 เข้าสู่ระบบ (Admin / เจ้าหน้าที่)
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
    © ระบบประปาหมู่บ้าน | พัฒนาโดยคุณ
</p>

</body>
</html>
