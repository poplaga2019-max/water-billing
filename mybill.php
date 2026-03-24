<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['customer'])){
    header("Location: login.php");
    exit();
}

$cid = $_SESSION['customer']['id'];

$res = $conn->query("
SELECT * FROM bills 
WHERE customer_id = $cid
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>บิลของฉัน</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

<h3>💰 บิลของฉัน</h3>

<a href="mygraph.php" class="btn btn-primary w-100 mb-3">
📊 กราฟการใช้น้ำ
</a>

<table class="table table-bordered text-center">

<tr>
<th>วันที่</th>
<th>หน่วย</th>
<th>เงิน</th>
<th>สถานะ</th>
<th>จ่าย</th>
</tr>

<?php while($r = $res->fetch_assoc()){ ?>
<tr>
<td><?= $r['created_at'] ?></td>
<td><?= $r['used_unit'] ?></td>
<td><?= number_format($r['amount']) ?> บาท</td>

<td>
<?= $r['status']=='paid' 
? '<span class="text-success">จ่ายแล้ว</span>' 
: '<span class="text-danger">ค้าง</span>' ?>
</td>

<td>
<?php if($r['status']!='paid'){ ?>
<a href="pay.php?id=<?= $r['id'] ?>" class="btn btn-success btn-sm">
จ่าย
</a>
<?php } ?>
</td>

</tr>
<?php } ?>

</table>

</body>
</html>
