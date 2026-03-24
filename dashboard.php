<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$total = $conn->query("SELECT COUNT(*) c FROM customers")->fetch_assoc()['c'];
$done = $conn->query("SELECT COUNT(*) c FROM bills WHERE bill_date=CURDATE()")->fetch_assoc()['c'];
?>

<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

<h3>📊 Dashboard</h3>

<div class="row text-center">
<div class="col"><div class="card p-3">บ้านทั้งหมด<br><?= $total ?></div></div>
<div class="col"><div class="card p-3 bg-success text-white">จดแล้ว<br><?= $done ?></div></div>
<div class="col"><div class="card p-3 bg-warning">เหลือ<br><?= $total-$done ?></div></div>
</div>

<hr>

<h5>📊 KPI พนักงาน</h5>

<table class="table">
<tr><th>พนักงาน</th><th>จำนวน</th></tr>

<?php
$kpi = $conn->query("
SELECT u.username, COUNT(b.id) total
FROM bills b
JOIN users u ON b.staff_id=u.id
WHERE b.bill_date=CURDATE()
GROUP BY u.id
");

while($r=$kpi->fetch_assoc()){
?>
<tr>
<td><?= $r['username'] ?></td>
<td><?= $r['total'] ?></td>
</tr>
<?php } ?>
</table>

<a href="meter.php" class="btn btn-success w-100">จดมิเตอร์</a>
<a href="map.php" class="btn btn-dark w-100 mt-2">แผนที่</a>

</body>
</html>
