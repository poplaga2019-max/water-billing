<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

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

<style>
.big-btn{padding:12px;font-size:16px}
</style>
</head>

<body class="container mt-3">

<h5 class="text-center">💰 รายการบิล</h5>

<?php while($r = $res->fetch_assoc()){ ?>

<div class="card p-3 mb-3">

<h5><?= $r['name'] ?></h5>

<p>📊 หน่วย: <?= $r['used_unit'] ?></p>
<p>💰 ยอด: <?= number_format($r['amount']) ?> บาท</p>

<p>
<?php
$status = [
    'pending'=>'🔴 ค้างชำระ',
    'verify'=>'🟡 รอตรวจสอบ',
    'paid'=>'🟢 ชำระแล้ว'
];
echo $status[$r['status']];
?>
</p>

<?php if($r['status'] != 'paid'){ ?>

<div class="d-flex gap-2">

<a href="pay.php?id=<?=$r['id']?>" class="btn btn-success w-50 big-btn">
💵 เงินสด
</a>

<a href="upload.php?id=<?=$r['id']?>" class="btn btn-warning w-50 big-btn">
📤 โอน
</a>

</div>

<?php }else{ ?>

<div class="alert alert-success text-center">
✔ ชำระแล้ว
</div>

<?php } ?>

<a href="receipt.php?id=<?=$r['id']?>" target="_blank"
class="btn btn-primary w-100 big-btn mt-2">
🧾 ใบเสร็จ
</a>

</div>

<?php } ?>

<a href="dashboard_mobile.php" class="btn btn-dark w-100">
⬅ กลับ
</a>

</body>
</html>
