<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$today = date('Y-m-d');

// ===== KPI =====
$total = $conn->query("SELECT COUNT(*) c FROM customers")->fetch_assoc()['c'];

$done = $conn->query("
SELECT COUNT(*) c FROM bills 
WHERE DATE(created_at)='$today'
")->fetch_assoc()['c'];

$remain = $total - $done;

$income = $conn->query("
SELECT SUM(amount) s FROM bills 
WHERE DATE(created_at)='$today' AND status='paid'
")->fetch_assoc()['s'] ?? 0;

// ===== STATUS =====
$paid = $conn->query("SELECT COUNT(*) c FROM bills WHERE status='paid'")->fetch_assoc()['c'];
$verify = $conn->query("SELECT COUNT(*) c FROM bills WHERE status='verify'")->fetch_assoc()['c'];
$pending = $conn->query("SELECT COUNT(*) c FROM bills WHERE status='pending'")->fetch_assoc()['c'];

// ===== DAILY GRAPH =====
$daily = [];
$res = $conn->query("
SELECT DATE(created_at) d, SUM(amount) s 
FROM bills WHERE status='paid'
GROUP BY DATE(created_at)
ORDER BY d DESC LIMIT 7
");

while($r = $res->fetch_assoc()){
    $daily[$r['d']] = $r['s'];
}
$daily = array_reverse($daily);
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.big-btn{font-size:18px;padding:14px}
.card-box{padding:12px;text-align:center;font-size:16px}
</style>

</head>

<body class="container mt-2">

<h5 class="text-center">📱 Dashboard</h5>

<!-- KPI -->
<div class="row text-center">

<div class="col-6 mb-2">
<div class="card bg-success text-white card-box">
<?= $done ?><br>จดแล้ว
</div>
</div>

<div class="col-6 mb-2">
<div class="card bg-warning card-box">
<?= $remain ?><br>เหลือ
</div>
</div>

<div class="col-12 mb-2">
<div class="card card-box">
💰 <?= number_format($income) ?> บาท
</div>
</div>

</div>

<!-- STATUS -->
<div class="row text-center">

<div class="col-4">
<div class="card bg-success text-white card-box">
🟢 <?= $paid ?>
</div>
</div>

<div class="col-4">
<div class="card bg-warning card-box">
🟡 <?= $verify ?>
</div>
</div>

<div class="col-4">
<div class="card bg-danger text-white card-box">
🔴 <?= $pending ?>
</div>
</div>

</div>

<!-- GRAPH -->
<div class="mt-3">
<h6>📊 7 วันล่าสุด</h6>
<canvas id="dailyChart"></canvas>
</div>

<script>
new Chart(document.getElementById('dailyChart'), {
type: 'line',
data: {
labels: <?= json_encode(array_keys($daily)) ?>,
datasets: [{
data: <?= json_encode(array_values($daily)) ?>,
borderWidth: 2
}]
}
});
</script>

<!-- MENU -->
<div class="d-grid gap-2 mt-3">

<a href="meter_mobile.php" class="btn btn-primary big-btn">📋 จดมิเตอร์</a>
<a href="bills_mobile.php" class="btn btn-success big-btn">💰 บิล</a>
<a href="review.php" class="btn btn-warning big-btn">📩 ตรวจสอบ</a>
<a href="customers.php" class="btn btn-info big-btn">👨‍👩‍👧‍👦 ลูกค้า</a>

</div>

</body>
</html>
