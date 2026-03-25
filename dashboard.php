<?php
session_start();
include 'config/db.php';

// 🔥 ตรวจมือถือ
if(preg_match('/Mobile|Android|iPhone|iPad/i', $_SERVER['HTTP_USER_AGENT'])){
    header("Location: dashboard_mobile.php");
    exit();
}

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

// ===== logic เดิม =====
$today = date('Y-m-d');

$total_house = $conn->query("SELECT COUNT(*) c FROM customers")->fetch_assoc()['c'];

$done_today = $conn->query("
SELECT COUNT(*) c FROM bills 
WHERE DATE(created_at)='$today'
")->fetch_assoc()['c'];

$remain = $total_house - $done_today;

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
</head>

<body class="container mt-4">

<h3>💻 Dashboard (Desktop)</h3>

<div class="row text-center">

<div class="col-md-4">
<div class="card p-3 bg-success text-white">
<h4><?= $done_today ?></h4>
<p>จดแล้ว</p>
</div>
</div>

<div class="col-md-4">
<div class="card p-3 bg-warning">
<h4><?= $remain ?></h4>
<p>เหลือ</p>
</div>
</div>

<div class="col-md-4">
<div class="card p-3">
<h4><?= number_format($income) ?></h4>
<p>รายได้วันนี้</p>
</div>
</div>

</div>

<div class="mt-4">
<a href="dashboard_mobile.php" class="btn btn-dark">📱 ไปโหมดมือถือ</a>
</div>

</body>
</html>
