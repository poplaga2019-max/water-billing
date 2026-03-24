<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
}

$total_customer = $conn->query("SELECT COUNT(*) as t FROM customers")->fetch_assoc()['t'];
$total_bill = $conn->query("SELECT SUM(amount) as s FROM bills")->fetch_assoc()['s'];
$total_unit = $conn->query("SELECT SUM(used_unit) as u FROM bills")->fetch_assoc()['u'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>แดชบอร์ด</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4" style="font-family: THSarabun, sans-serif;">

<h2 class="mb-4">📊 แดชบอร์ด</h2>

<div class="row text-center mb-4">

    <div class="col-md-4 mb-3">
        <div class="card p-3 shadow">
            <h5>👨‍👩‍👧‍👦 ลูกบ้านทั้งหมด</h5>
            <h3><?= $total_customer ?></h3>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card p-3 shadow">
            <h5>💧 หน่วยน้ำรวม</h5>
            <h3><?= $total_unit ?> หน่วย</h3>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card p-3 shadow">
            <h5>💰 รายได้รวม</h5>
            <h3><?= $total_bill ?> บาท</h3>
        </div>
    </div>

</div>

<hr>

<div class="d-grid gap-3 col-md-6 mx-auto">

    <a href="customers.php" class="btn btn-success btn-lg">จดมิเตอร์น้ำ</a>

    <a href="bills.php" class="btn btn-warning btn-lg">ดูบิล</a>

    <a href="settings.php" class="btn btn-info btn-lg">ตั้งค่าค่าน้ำ</a>

    <a href="logout.php" class="btn btn-danger btn-lg">ออกจากระบบ</a>

</div>

</body>
</html>
