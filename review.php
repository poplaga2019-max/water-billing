<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role']!='admin'){
    die('no permission');
}

// approve
if(isset($_GET['ok'])){
    $id = (int)$_GET['ok'];
    $conn->query("UPDATE bills SET status='paid' WHERE id=$id");
}

// reject
if(isset($_GET['no'])){
    $id = (int)$_GET['no'];
    $conn->query("UPDATE bills SET status='pending', slip=NULL WHERE id=$id");
}

$res = $conn->query("
SELECT b.*, c.name 
FROM bills b
JOIN customers c ON b.customer_id=c.id
WHERE b.status='verify'
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

<h4>📩 ตรวจสอบการชำระเงิน</h4>

<?php while($r = $res->fetch_assoc()){ ?>
<div class="card p-3 mb-2">

<h5><?= $r['name'] ?></h5>
<p><?= number_format($r['amount']) ?> บาท</p>

<?php if($r['slip']){ ?>
<a href="<?= $r['slip'] ?>" target="_blank">📸 ดูสลิป</a>
<?php } ?>

<div class="d-flex gap-2 mt-2">
<a href="?ok=<?= $r['id'] ?>" class="btn btn-success w-50">✔ อนุมัติ</a>
<a href="?no=<?= $r['id'] ?>" class="btn btn-danger w-50">❌ ปฏิเสธ</a>
</div>

</div>
<?php } ?>

</body>
</html>
