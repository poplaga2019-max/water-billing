<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

// ดึงบิล
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
<title>บิลค่าน้ำ</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

<h3>💰 รายการบิล</h3>

<table class="table table-bordered text-center">

<tr>
<th>ลูกค้า</th>
<th>หน่วย</th>
<th>ยอด</th>
<th>สถานะ</th>
<th>จัดการ</th>
</tr>

<?php while($r = $res->fetch_assoc()){ 

// สีสถานะ
$statusColor = [
    'pending' => 'secondary',
    'verify' => 'warning',
    'paid' => 'success'
];

// ข้อความสถานะ
$statusText = [
    'pending' => 'ยังไม่ชำระ',
    'verify' => 'รอตรวจสอบ',
    'paid' => 'ชำระแล้ว'
];
?>

<tr>

<td><?= $r['name'] ?></td>
<td><?= $r['used_unit'] ?></td>
<td><?= number_format($r['amount']) ?></td>

<td>
<span class="badge bg-<?= $statusColor[$r['status']] ?>">
<?= $statusText[$r['status']] ?>
</span>
</td>

<td>

<!-- จ่ายเงินสด -->
<?php if($r['status'] != 'paid'){ ?>
<a href="pay.php?id=<?= $r['id'] ?>" class="btn btn-success btn-sm">
💵 เงินสด
</a>
<?php } ?>

<!-- ใบเสร็จ -->
<a href="receipt.php?id=<?= $r['id'] ?>" target="_blank" class="btn btn-primary btn-sm">
🧾 ใบเสร็จ
</a>

</td>

</tr>

<?php } ?>

</table>

<a href="dashboard.php" class="btn btn-dark w-100">⬅ กลับ</a>

</body>
</html>
