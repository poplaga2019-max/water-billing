<?php
session_start();
include 'config/db.php';

$res = $conn->query("SELECT * FROM water_rates");
?>

<!DOCTYPE html>
<html>
<head>
    <title>ตั้งค่าค่าน้ำ</title>
</head>
<body style="font-family: THSarabun, sans-serif;">

<h2>ตั้งค่าค่าน้ำ (ขั้นบันได)</h2>

<table border="1" cellpadding="5">
<tr>
    <th>หน่วยเริ่ม</th>
    <th>หน่วยสุด</th>
    <th>บาท/หน่วย</th>
</tr>

<?php while($r = $res->fetch_assoc()){ ?>
<tr>
    <td><?= $r['min_unit'] ?></td>
    <td><?= $r['max_unit'] ?></td>
    <td><?= $r['price_per_unit'] ?></td>
</tr>
<?php } ?>

</table>

</body>
</html>
