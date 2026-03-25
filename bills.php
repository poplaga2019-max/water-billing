<?php
session_start();
include 'config/db.php';

// 🔥 AUTO MOBILE
if(preg_match('/Mobile|Android|iPhone/i', $_SERVER['HTTP_USER_AGENT'])){
    header("Location: bills_mobile.php");
    exit();
}

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
<title>รายการบิล</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

<h3>💻 รายการบิล</h3>

<table class="table table-bordered text-center">

<tr>
<th>ลูกค้า</th>
<th>หน่วย</th>
<th>ยอด</th>
<th>สถานะ</th>
<th>จัดการ</th>
</tr>

<?php while($r = $res->fetch_assoc()){ ?>

<tr>

<td><?= $r['name'] ?></td>
<td><?= $r['used_unit'] ?></td>
<td><?= number_format($r['amount']) ?></td>

<td>
<?php
$color = [
    'pending'=>'secondary',
    'verify'=>'warning',
    'paid'=>'success'
];
?>
<span class="badge bg-<?= $color[$r['status']] ?>">
<?= $r['status'] ?>
</span>
</td>

<td>

<?php if($r['status'] != 'paid'){ ?>

<a href="pay.php?id=<?=$r['id']?>" class="btn btn-success btn-sm">
💵
</a>

<a href="upload.php?id=<?=$r['id']?>" class="btn btn-warning btn-sm">
📤
</a>

<?php }else{ ?>

<span class="badge bg-success">✔ จ่ายแล้ว</span>

<?php } ?>

<a href="receipt.php?id=<?=$r['id']?>" target="_blank" class="btn btn-primary btn-sm">
🧾
</a>

</td>

</tr>

<?php } ?>

</table>

</body>
</html>
