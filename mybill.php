<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['customer'])){
    header("Location: login.php");
}

$cid = $_SESSION['customer']['id'];

$res = $conn->query("
SELECT * FROM bills 
WHERE customer_id=$cid
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>บิลของฉัน</title>
</head>
<body style="font-family: THSarabun, sans-serif;">

<h2>บิลของฉัน</h2>
    <img src="https://promptpay.io/0801234567.png" width="200">
<p>สแกน QR เพื่อชำระเงิน</p>
<p>โอนเงินได้ที่: 080-xxx-xxxx</p>
<table border="1" cellpadding="5">
<tr>
    <th>หน่วยใช้</th>
    <th>จำนวนเงิน</th>
    <th>สถานะ</th>
</tr>

<?php while($row = $res->fetch_assoc()){ ?>
<tr>
    <td><?= $row['used_unit'] ?></td>
    <td><?= $row['amount'] ?> บาท</td>
    <td><?= $row['status'] ?></td>
</tr>
<?php } ?>

</table>

</body>
</html>
