<?php
session_start();
include 'config/db.php';

$res = $conn->query("
SELECT b.*, c.name 
FROM bills b
JOIN customers c ON b.customer_id=c.id
ORDER BY b.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-3">

<h5>💰 บิล</h5>

<?php while($r = $res->fetch_assoc()){ ?>

<div class="card p-3 mb-2">

<h5><?= $r['name'] ?></h5>

<p>หน่วย: <?= $r['used_unit'] ?></p>
<p>ยอด: <?= number_format($r['amount']) ?> บาท</p>

<p>
<?php
$status = [
    'pending'=>'🔴 ค้าง',
    'verify'=>'🟡 รอตรวจสอบ',
    'paid'=>'🟢 ชำระแล้ว'
];
echo $status[$r['status']];
?>
</p>

<div class="d-flex gap-2">
<a href="pay.php?id=<?=$r['id']?>" class="btn btn-success w-50">เงินสด</a>
<a href="upload.php?id=<?=$r['id']?>" class="btn btn-warning w-50">โอน</a>
</div>

<a href="receipt.php?id=<?=$r['id']?>" class="btn btn-primary w-100 mt-2">
🧾 ใบเสร็จ
</a>

</div>

<?php } ?>

</body>
</html>
