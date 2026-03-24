<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
}

$total_customer = $conn->query("SELECT COUNT(*) as t FROM customers")->fetch_assoc()['t'];
$total_bill = $conn->query("SELECT SUM(amount) as s FROM bills")->fetch_assoc()['s'];
$total_unit = $conn->query("SELECT SUM(used_unit) as u FROM bills")->fetch_assoc()['u'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>แดชบอร์ด</title>
</head>
<body style="font-family: THSarabun, sans-serif;">

<h2>แดชบอร์ด</h2>

<p>👨‍👩‍👧‍👦 ลูกบ้านทั้งหมด: <?= $total_customer ?></p>
<p>💧 หน่วยน้ำรวม: <?= $total_unit ?> หน่วย</p>
<p>💰 รายได้รวม: <?= $total_bill ?> บาท</p>

<hr>

<a href="customers.php">จัดการลูกบ้าน</a> |
<a href="bills.php">ดูบิล</a> |
<a href="logout.php">ออกจากระบบ</a>

</body>
</html>
