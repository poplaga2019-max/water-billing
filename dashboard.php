<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

// ===== DATA =====
$today = date('Y-m-d');

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

$paid = $conn->query("SELECT COUNT(*) c FROM bills WHERE status='paid'")->fetch_assoc()['c'];
$verify = $conn->query("SELECT COUNT(*) c FROM bills WHERE status='verify'")->fetch_assoc()['c'];
$pending = $conn->query("SELECT COUNT(*) c FROM bills WHERE status='pending'")->fetch_assoc()['c'];
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard</title>

<style>
body{margin:0;font-family:sans-serif;background:#f5f5f5}
.topbar{background:#007bff;color:#fff;padding:10px;display:flex;justify-content:space-between}
.sidebar{width:200px;background:#222;height:100vh;position:fixed;color:#fff}
.sidebar a{display:block;color:#ccc;padding:10px;text-decoration:none}
.sidebar a:hover{background:#444}
.content{margin-left:200px;padding:20px}
.card{background:#fff;padding:15px;margin-bottom:10px;border-radius:10px}
@media(max-width:768px){
.sidebar{display:none}
.content{margin-left:0}
}
</style>
</head>

<body>

<div class="topbar">
    <b>💧 ระบบประปา</b>
    <div><?= $_SESSION['user']['username'] ?> | <a href="logout.php" style="color:#fff">ออก</a></div>
</div>

<div class="sidebar">
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="customers.php">👤 ลูกบ้าน</a>
    <a href="meter.php">💧 จดมิเตอร์</a>
    <a href="bills.php">🧾 บิล</a>
    <a href="setting.php">⚙️ ตั้งค่า</a>
</div>

<div class="content">

<div class="card">
<h3>📊 Dashboard</h3>
<p>🏠 ทั้งหมด: <?= $total ?></p>
<p>📋 จดแล้ว: <?= $done ?></p>
<p>📉 เหลือ: <?= $remain ?></p>
<p>💰 รายได้: <?= number_format($income) ?> บาท</p>
<p>🟢 ชำระแล้ว: <?= $paid ?></p>
<p>🟡 ตรวจสอบ: <?= $verify ?></p>
<p>🔴 ค้าง: <?= $pending ?></p>
</div>

</div>

</body>
</html>
