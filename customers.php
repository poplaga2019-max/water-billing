<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
}

$res = $conn->query("SELECT * FROM customers");
?>

<!DOCTYPE html>
<html>
<head>
    <title>ลูกบ้าน</title>
</head>
<body style="font-family: THSarabun, sans-serif;">
    <h2>รายชื่อลูกบ้าน</h2>

    <table border="1" cellpadding="5">
        <tr>
            <th>ชื่อ</th>
            <th>ที่อยู่</th>
            <th>มิเตอร์</th>
            <th>หน่วยล่าสุด</th>
            <th>จดมิเตอร์</th>
        </tr>

        <?php while($row = $res->fetch_assoc()){ ?>
        <tr>
            <td><?= $row['name'] ?></td>
            <td><?= $row['address'] ?></td>
            <td><?= $row['meter_no'] ?></td>
            <td><?= $row['last_unit'] ?></td>
            <td><a href="meter.php?id=<?= $row['id'] ?>">จด</a></td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>
