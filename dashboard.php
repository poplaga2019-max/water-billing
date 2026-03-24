<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

// วันนี้
$today = date('Y-m-d');

// บ้านทั้งหมด
$total_house = $conn->query("SELECT COUNT(*) c FROM customers")->fetch_assoc()['c'];

// จดแล้ววันนี้
$done_today = $conn->query("SELECT COUNT(*) c FROM bills WHERE bill_date='$today'")->fetch_assoc()['c'];

// ยังไม่จด
$remain = $total_house - $done_today;

// เก็บเงินแล้ว
$paid = $conn->query("SELECT COUNT(*) c FROM bills WHERE status='paid'")->fetch_assoc()['c'];

// ค้าง
$unpaid = $conn->query("SELECT COUNT(*) c FROM bills WHERE status='unpaid'")->fetch_assoc()['c'];

// รายได้
$day_income = $conn->query("SELECT SUM(amount) s FROM bills WHERE bill_date='$today'")->fetch_assoc()['s'];

$month_income = $conn->query("
SELECT SUM(amount) s FROM bills 
WHERE MONTH(bill_date)=MONTH(CURRENT_DATE())
")->fetch_assoc()['s'];

$year_income = $conn->query("
SELECT SUM(amount) s FROM bills 
WHERE YEAR(bill_date)=YEAR(CURRENT_DATE())
")->fetch_assoc()['s'];

?>

<!DOCTYPE html>
<html>
<head>
<title>แดชบอร์ด</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4" style="font-family: THSarabun, sans-serif;">

<h2>📊 แดชบอร์ดผู้ดูแล</h2>

<!-- 🏠 งานวันนี้ -->
<div class="row text-center">

<div class="col-md-3 mb-3">
<div class="card p-3 shadow">
<h6>🏠 บ้านทั้งหมด</h6>
<h3><?= $total_house ?></h3>
</div>
</div>

<div class="col-md-3 mb-3">
<div class="card p-3 shadow bg-success text-white">
<h6>📋 จดแล้ว</h6>
<h3><?= $done_today ?></h3>
</div>
</div>

<div class="col-md-3 mb-3">
<div class="card p-3 shadow bg-warning">
<h6>⏳ เหลือ</h6>
<h3><?= $remain ?></h3>
</div>
</div>

<div class="col-md-3 mb-3">
<div class="card p-3 shadow bg-info text-white">
<h6>📈 % ความคืบหน้า</h6>
<h3><?= $total_house>0 ? round(($done_today/$total_house)*100) : 0 ?>%</h3>
</div>
</div>

</div>

<hr>

<!-- 💰 การเงิน -->
<div class="row text-center">

<div class="col-md-3 mb-3">
<div class="card p-3 shadow bg-success text-white">
<h6>💵 เก็บแล้ว</h6>
<h3><?= $paid ?></h3>
</div>
</div>

<div class="col-md-3 mb-3">
<div class="card p-3 shadow bg-danger text-white">
<h6>❌ ค้าง</h6>
<h3><?= $unpaid ?></h3>
</div>
</div>

<div class="col-md-2 mb-3">
<div class="card p-3 shadow">
<h6>📅 วันนี้</h6>
<h5><?= number_format($day_income) ?>฿</h5>
</div>
</div>

<div class="col-md-2 mb-3">
<div class="card p-3 shadow">
<h6>📆 เดือน</h6>
<h5><?= number_format($month_income) ?>฿</h5>
</div>
</div>

<div class="col-md-2 mb-3">
<div class="card p-3 shadow">
<h6>📊 ปี</h6>
<h5><?= number_format($year_income) ?>฿</h5>
</div>
</div>

</div>

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
FROM bills WHERE bill_date='$d'
")->fetch_assoc();

echo "<div class='alert alert-info'>";
echo "วันที่ $d<br>";
echo "รายได้: ".number_format($res['s'])." บาท<br>";
echo "หน่วยน้ำ: ".$res['u'];
echo "</div>";
}
?>

<hr>

<!-- เมนู -->
<div class="d-grid gap-3">
<a href="customers.php" class="btn btn-success">📋 จดมิเตอร์</a>
<a href="bills.php" class="btn btn-warning">💰 บิล</a>
<a href="graph_all.php" class="btn btn-primary">📊 กราฟ</a>
<a href="settings.php" class="btn btn-info">⚙️ ตั้งค่า</a>
<a href="logout.php" class="btn btn-danger">ออก</a>
</div>

</body>
</html>
