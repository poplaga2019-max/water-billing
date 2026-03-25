<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$today = date('Y-m-d');
$month = date('Y-m');

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

// ===== GRAPH 1: รายได้รายวัน =====
$daily = [];
$res = $conn->query("
SELECT DATE(created_at) d, SUM(amount) s 
FROM bills 
WHERE status='paid'
GROUP BY DATE(created_at)
ORDER BY d ASC
");

while($r = $res->fetch_assoc()){
    $daily[$r['d']] = $r['s'];
}

// ===== GRAPH 2: รายได้รายเดือน =====
$monthly = [];
$res2 = $conn->query("
SELECT DATE_FORMAT(created_at,'%Y-%m') m, SUM(amount) s 
FROM bills 
WHERE status='paid'
GROUP BY m
ORDER BY m ASC
");

while($r = $res2->fetch_assoc()){
    $monthly[$r['m']] = $r['s'];
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.card-big{padding:15px;text-align:center;font-size:18px}
</style>
</head>

<body class="container mt-3">

<h4>📊 Dashboard</h4>

<!-- KPI -->
<div class="row text-center">

<div class="col-4">
<div class="card bg-primary text-white card-big">
🏠 <?= $total ?><br>ทั้งหมด
</div>
</div>

<div class="col-4">
<div class="card bg-success text-white card-big">
📋 <?= $done ?><br>จดแล้ว
</div>
</div>

<div class="col-4">
<div class="card bg-warning card-big">
📉 <?= $remain ?><br>เหลือ
</div>
</div>

</div>

<div class="row text-center mt-2">

<div class="col-12">
<div class="card card-big">
💰 <?= number_format($income) ?> บาท
</div>
</div>

</div>

<!-- STATUS -->
<div class="row text-center mt-2">

<div class="col-4">
<div class="card bg-success text-white card-big">🟢 <?= $paid ?></div>
</div>

<div class="col-4">
<div class="card bg-warning card-big">🟡 <?= $verify ?></div>
</div>

<div class="col-4">
<div class="card bg-danger text-white card-big">🔴 <?= $pending ?></div>
</div>

</div>

<!-- 📊 GRAPH -->
<div class="mt-4">

<h5>📊 รายได้รายวัน</h5>
<canvas id="dailyChart"></canvas>

<h5 class="mt-4">📈 รายได้รายเดือน</h5>
<canvas id="monthChart"></canvas>

<h5 class="mt-4">📉 สถานะบิล</h5>
<canvas id="statusChart"></canvas>

</div>

<script>
// ===== DAILY =====
new Chart(document.getElementById('dailyChart'), {
type: 'line',
data: {
labels: <?= json_encode(array_keys($daily)) ?>,
datasets: [{
label: 'รายได้',
data: <?= json_encode(array_values($daily)) ?>,
borderWidth: 2
}]
}
});

// ===== MONTH =====
new Chart(document.getElementById('monthChart'), {
type: 'bar',
data: {
labels: <?= json_encode(array_keys($monthly)) ?>,
datasets: [{
label: 'รายได้ต่อเดือน',
data: <?= json_encode(array_values($monthly)) ?>,
borderWidth: 2
}]
}
});

// ===== STATUS =====
new Chart(document.getElementById('statusChart'), {
type: 'pie',
data: {
labels: ['Paid','Verify','Pending'],
datasets: [{
data: [<?= $paid ?>,<?= $verify ?>,<?= $pending ?>]
}]
}
});
</script>

</body>
</html>
