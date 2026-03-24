
<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

// 📊 ใช้น้ำรายเดือน
$m1 = $conn->query("
SELECT DATE_FORMAT(created_at,'%Y-%m') m, SUM(used_unit) u
FROM bills GROUP BY m ORDER BY m
");

$month=[]; $unit=[];
while($r=$m1->fetch_assoc()){
    $month[]=$r['m'];
    $unit[]=$r['u'];
}

// 💰 รายได้รายเดือน
$m2 = $conn->query("
SELECT DATE_FORMAT(created_at,'%Y-%m') m, SUM(amount) a
FROM bills GROUP BY m ORDER BY m
");

$income=[];
while($r=$m2->fetch_assoc()){
    $income[]=$r['a'];
}

// 🏠 รายบ้าน
$m3 = $conn->query("
SELECT c.name, SUM(b.used_unit) u
FROM bills b JOIN customers c ON b.customer_id=c.id
GROUP BY c.id
");

$names=[]; $units=[];
while($r=$m3->fetch_assoc()){
    $names[]=$r['name'];
    $units[]=$r['u'];
}

// 🏆 Top 5
$m4 = $conn->query("
SELECT c.name, SUM(b.used_unit) u
FROM bills b JOIN customers c ON b.customer_id=c.id
GROUP BY c.id ORDER BY u DESC LIMIT 5
");

$top_names=[]; $top_units=[];
while($r=$m4->fetch_assoc()){
    $top_names[]=$r['name'];
    $top_units[]=$r['u'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard กราฟ</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4" style="font-family: THSarabun, sans-serif;">

<h2 class="mb-4">📊 Dashboard กราฟ</h2>

<!-- 📊 ใช้น้ำ -->
<canvas id="c1"></canvas><br>

<!-- 💰 รายได้ -->
<canvas id="c2"></canvas><br>

<!-- 🏠 รายบ้าน -->
<canvas id="c3"></canvas><br>

<!-- 🏆 Top 5 -->
<canvas id="c4"></canvas>

<script>
// ใช้น้ำ
new Chart(c1,{
 type:'line',
 data:{labels:<?=json_encode($month)?>,
 datasets:[{label:'หน่วยน้ำ',data:<?=json_encode($unit)?>}]}
});

// รายได้
new Chart(c2,{
 type:'bar',
 data:{labels:<?=json_encode($month)?>,
 datasets:[{label:'รายได้',data:<?=json_encode($income)?>}]}
});

// รายบ้าน
new Chart(c3,{
 type:'bar',
 data:{labels:<?=json_encode($names)?>,
 datasets:[{label:'หน่วยน้ำ',data:<?=json_encode($units)?>}]}
});

// top5
new Chart(c4,{
 type:'pie',
 data:{labels:<?=json_encode($top_names)?>,
 datasets:[{data:<?=json_encode($top_units)?>}]}
});
</script>

<br>
<a href="dashboard.php" class="btn btn-secondary w-100">⬅ กลับ</a>

</body>
</html>
