<?php
session_start();
include 'config/db.php';

if($_SESSION['user']['role'] != 'admin'){
    exit("❌ ไม่มีสิทธิ์");
}

// อนุมัติ
if(isset($_GET['approve'])){
    $id = $_GET['approve'];
    $conn->query("UPDATE bills SET status='paid' WHERE id=$id");
}

// ปฏิเสธ
if(isset($_GET['reject'])){
    $id = $_GET['reject'];
    $conn->query("UPDATE bills SET status='unpaid', slip=NULL WHERE id=$id");
}

$res = $conn->query("
SELECT b.*, c.name 
FROM bills b
JOIN customers c ON b.customer_id=c.id
WHERE b.status='pending'
ORDER BY b.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>ตรวจสอบบิล</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

<h3>📩 ตรวจสอบการชำระเงิน</h3>

<table class="table table-bordered text-center">

<tr>
<th>ลูกค้า</th>
<th>ยอด</th>
<th>สลิป</th>
<th>จัดการ</th>
</tr>

<?php while($r=$res->fetch_assoc()){ ?>
<tr>

<td><?= $r['name'] ?></td>
<td><?= number_format($r['amount']) ?> บาท</td>

<td>
<?php if($r['slip']){ ?>
<a href="<?= $r['slip'] ?>" target="_blank">ดูสลิป</a>
<?php } ?>
</td>

<td>
<a href="?approve=<?= $r['id'] ?>" class="btn btn-success btn-sm">✔ อนุมัติ</a>
<a href="?reject=<?= $r['id'] ?>" class="btn btn-danger btn-sm">❌ ปฏิเสธ</a>
</td>

</tr>
<?php } ?>

</table>

</body>
</html>
