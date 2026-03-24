<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

// ดึงข้อมูลบิล
$data = $conn->query("
SELECT b.*, c.name 
FROM bills b
JOIN customers c ON b.customer_id = c.id
WHERE b.id=$id
")->fetch_assoc();

// เมื่อกดยืนยัน
if(isset($_POST['confirm'])){
    $conn->query("UPDATE bills SET status='paid' WHERE id=$id");
    header("Location: bills.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>รับเงิน</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5" style="font-family: THSarabun, sans-serif;">

<div class="row justify-content-center">
<div class="col-md-5">

<div class="card p-4 shadow text-center">

<h3 class="mb-3">💰 รับชำระเงิน</h3>

<p><strong>👤 ลูกค้า:</strong> <?= $data['name'] ?></p>
<p><strong>💧 หน่วยใช้:</strong> <?= $data['used_unit'] ?></p>
<p><strong>💵 จำนวนเงิน:</strong> <?= $data['amount'] ?> บาท</p>

<hr>

<form method="POST">
    <button name="confirm" class="btn btn-success w-100 mb-2">
        ✅ ยืนยันรับเงิน
    </button>
</form>

<a href="bills.php" class="btn btn-secondary w-100">
    ⬅ กลับ
</a>

</div>

</div>
</div>

</body>
</html>
