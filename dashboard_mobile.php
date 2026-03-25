<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$today = date('Y-m-d');

$total = $conn->query("SELECT COUNT(*) c FROM customers")->fetch_assoc()['c'];

$done = $conn->query("
SELECT COUNT(*) c FROM bills 
WHERE DATE(created_at)='$today'
")->fetch_assoc()['c'];

$income = $conn->query("
SELECT SUM(amount) s FROM bills 
WHERE DATE(created_at)='$today'
")->fetch_assoc()['s'];
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.big-btn{font-size:20px;padding:15px}
</style>
</head>

<body class="container mt-3">

<h4 class="text-center">📱 Dashboard</h4>

<div class="row text-center">

<div class="col-6 mb-2">
<div class="card p-3 bg-success text-white">
<h4><?= $done ?></h4>
<p>จดแล้ว</p>
</div>
</div>

<div class="col-6 mb-2">
<div class="card p-3 bg-warning">
<h4><?= $total - $done ?></h4>
<p>เหลือ</p>
</div>
</div>

<div class="col-12">
<div class="card p-3">
<h4><?= number_format($income) ?></h4>
<p>รายได้วันนี้</p>
</div>
</div>

</div>

<div class="d-grid gap-2 mt-3">

<a href="meter.php" class="btn btn-primary big-btn">📋 จดมิเตอร์</a>
<a href="bills.php" class="btn btn-success big-btn">💰 บิล</a>
<a href="customers.php" class="btn btn-info big-btn">👨‍👩‍👧‍👦 ลูกค้า</a>

</div>

<div class="mt-3">
<a href="dashboard.php" class="btn btn-secondary w-100">💻 โหมดคอม</a>
</div>

</body>
</html>
