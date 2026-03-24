<?php
session_start();
include 'config/db.php';

$res = $conn->query("
SELECT b.*, c.name 
FROM bills b 
JOIN customers c ON b.customer_id = c.id
ORDER BY b.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>บิลค่าน้ำ</title>
</head>
<body style="font-family: THSarabun, sans-serif;">
    <h2>บิลค่าน้ำ</h2>

    <table border="1" cellpadding="5">
        <tr>
            <th>ชื่อ</th>
            <th>หน่วยใช้</th>
            <th>จำนวนเงิน</th>
            <th>วันที่</th>
        </tr>

        <?php while($row = $res->fetch_assoc()){ ?>
        <tr>
            <td><?= $row['name'] ?></td>
            <td><?= $row['used_unit'] ?></td>
            <td><?= $row['amount'] ?> บาท</td>
            <td><?= $row['created_at'] ?></td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>
