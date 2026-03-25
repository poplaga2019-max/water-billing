<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$today = date('Y-m-d');

// 🏠 บ้านทั้งหมด
$total_house = $conn->query("SELECT COUNT(*) c FROM customers")->fetch_assoc()['c'];

// 📋 จดวันนี้ (ใช้วันจริง)
$done_today = $conn->query("
SELECT COUNT(*) c FROM bills 
WHERE DATE(created_at)='$today'
")->fetch_assoc()['c'];

$remain = $total_house - $done_today;

// 💰 สถานะการเงิน
$paid = $conn->query("SELECT COUNT(*) c FROM bills WHERE status='paid'")->fetch_assoc()['c'];
$pending = $conn->query("SELECT COUNT(*) c FROM bills WHERE status='pending'")->fetch_assoc()['c'];
$unpaid = $conn->query("SELECT COUNT(*) c FROM bills WHERE status='unpaid'")->fetch_assoc()['c'];

// 📅 รายได้วันนี้
$day_income = $conn->query("
SELECT SUM(amount) s FROM bills 
WHERE DATE(created_at)='$today' AND status='paid'
")->fetch_assoc()['s'];

// 📆 รายได้เดือน (ใช้รอบบิล)
$month = date('Y-m');
$month_income = $conn->query("
SELECT SUM(amount) s FROM bills 
WHERE billing_cycle='$month' AND status='paid'
")->fetch_assoc()['s'];

// 📊 รายได้ปี
$year = date('Y');
$year_income = $conn->query("
SELECT SUM(amount) s FROM bills 
WHERE YEAR(created_at)='$year' AND status='paid'
")->fetch_assoc()['s'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

<h3>📊 Dashboard</h3>

<!-- 🏠 งานวันนี้ -->
<div class="row text-center">

<div class="col-6 col-md-3 mb-3">
<div class="card p-3 shadow">
🏠 บ้านทั้งหมด<br>
<h4><?= $total_house ?></h4>
</div>
</div>

<div class="col-6 col-md-3 mb-3">
<div class="card p-3 shadow bg-success text-white">
📋 จดแล้ว<br>
<h4><?= $done_today ?></h4>
</div>
</div>

<div class="col-6 col-md-3 mb-3">
<div class="card p-3 shadow bg-warning">
⏳ เหลือ<br>
<h4><?= $remain ?></h4>
</div>
</div>

<div class="col-6 col-md-3 mb-3">
<div class="card p-3 shadow bg-info text-white">
📈 ความคืบหน้า<br>
<h4><?= $total_house>0 ? round(($done_today/$total_house)*100) : 0 ?>%</h4>
</div>
</div>

</div>

<hr>

<!-- 💰 การเงิน -->
<div class="row text-center">

<div class="col-6 col-md-2 mb-3">
<div class="card p-3 shadow bg-success text-white">
✔ จ่ายแล้ว<br>
<?= $paid ?>
</div>
</div>

<div class="col-6 col-md-2 mb-3">
<div class="card p-3 shadow bg-warning">
📩 รอตรวจ<br>
<?= $pending ?>
</div>
</div>

<div class="col-6 col-md-2 mb-3">
<div class="card p-3 shadow bg-danger text-white">
❌ ค้าง<br>
<?= $unpaid ?>
</div>
</div>

<div class="col-6 col-md-2 mb-3">
<div class="card p-3 shadow">
📅 วันนี้<br>
<?= number_format($day_income) ?>฿
</div>
</div>

<div class="col-6 col-md-2 mb-3">
<div class="card p-3 shadow">
📆 เดือน<br>
<?= number_format($month_income) ?>฿
</div>
</div>

<div class="col-6 col-md-2 mb-3">
<div class="card p-3 shadow">
📊 ปี<br>
<?= number_format($year_income) ?>฿
</div>
</div>

</div>

<hr>

<!-- 👷 KPI พนักงาน -->
<h5>👷 KPI พนักงานวันนี้</h5>

<table class="table text-center">
<tr>
<th>พนักงาน</th>
<th>จำนวนบ้าน</th>
</tr>

<?php
$kpi = $conn->query("
SELECT u.username, COUNT(b.id) total
FROM bills b
JOIN users u ON b.staff_id = u.id
WHERE DATE(b.created_at) = CURDATE()
GROUP BY u.id
");

while($r = $kpi->fetch_assoc()){
?>
<tr>
<td><?= $r['username'] ?></td>
<td><?= $r['total'] ?></td>
</tr>
<?php } ?>
</table>

<hr>

<!-- 🔎 ค้นหา -->
<div class="card p-3 mb-3">
<form method="GET">
<input type="date" name="date" class="form-control mb-2">
<button class="btn btn-primary w-100">ค้นหา</button>
</form>
</div>

<?php
if(isset($_GET['date'])){
$d = $_GET['date'];

$res = $conn->query("
SELECT SUM(amount) s, SUM(used_unit) u 
FROM bills 
WHERE DATE(created_at)='$d'
")->fetch_assoc();

echo "<div class='alert alert-info'>";
echo "📅 วันที่ $d<br>";
echo "💰 รายได้: ".number_format($res['s'])." บาท<br>";
echo "💧 หน่วยน้ำ: ".$res['u'];
echo "</div>";
}
?>

<hr>

<!-- 🚀 เมนู -->
<div class="d-grid gap-
