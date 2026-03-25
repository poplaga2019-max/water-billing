<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

// รับค่า filter
$search = $_GET['search'] ?? '';
$month = $_GET['month'] ?? '';

$sql = "SELECT b.*, c.name 
FROM bills b
JOIN customers c ON b.customer_id=c.id
WHERE 1";

// 🔍 ค้นหา
if($search){
    $sql .= " AND c.name LIKE '%$search%'";
}

// 📅 filter เดือน
if($month){
    $sql .= " AND billing_cycle='$month'";
}

$sql .= " ORDER BY b.id DESC";

$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>บิลค่าน้ำ</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-3">

<h4>💰 รายการบิล</h4>

<!-- 🔍 FILTER -->
<form class="row mb-3">
    <div class="col-6">
        <input type="text" name="search" class="form-control" placeholder="ค้นหาชื่อ" value="<?=$search?>">
    </div>
    <div class="col-4">
        <input type="month" name="month" class="form-control" value="<?=$month?>">
    </div>
    <div class="col-2">
        <button class="btn btn-primary w-100">ค้นหา</button>
    </div>
</form>

<table class="table table-bordered text-center">

<tr>
<th>ลูกค้า</th>
<th>หน่วย</th>
<th>ยอด</th>
<th>สถานะ</th>
<th>จัดการ</th>
</tr>

<?php while($r = $res->fetch_assoc()){ 

$statusColor = [
    'pending' => 'secondary',
    'verify' => 'warning',
    'paid' => 'success'
];

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
<a href="pay.php?id=<?=$r['id']?>" class="btn btn-success btn-sm">💵</a>
<a href="receipt.php?id=<?=$r['id']?>" target="_blank" class="btn btn-primary btn-sm">🧾</a>
</td>
</tr>

<?php } ?>

</table>

</body>
</html>
