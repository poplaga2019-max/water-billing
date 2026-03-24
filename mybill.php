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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4" style="font-family: THSarabun, sans-serif;">

<h2 class="mb-3">💧 บิลของฉัน</h2>

<!-- QR พร้อมเพย์ -->
<div class="text-center mb-4">
    <img src="https://promptpay.io/0801234567.png" width="200">
    <p class="text-success">สแกนเพื่อชำระเงิน</p>
    <p>โอนเงินได้ที่: 080-123-4567</p>
</div>

<table class="table table-bordered text-center">
<tr class="table-dark">
    <th>หน่วยใช้</th>
    <th>จำนวนเงิน</th>
    <th>สถานะ</th>
    <th>ชำระเงิน</th>
</tr>

<?php while($row = $res->fetch_assoc()){ ?>
<tr>
    <td><?= $row['used_unit'] ?></td>
    <td><?= $row['amount'] ?> บาท</td>

    <td>
        <?php if($row['status']=='paid'){ ?>
            <span class="badge bg-success">จ่ายแล้ว</span>
        <?php }else{ ?>
            <span class="badge bg-danger">ยังไม่จ่าย</span>
        <?php } ?>
    </td>

    <td>
        <?php if($row['status']!='paid'){ ?>
            <form action="upload.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <input type="file" name="slip" class="form-control mb-2" required>
                <button class="btn btn-primary btn-sm w-100">อัปโหลดสลิป</button>
            </form>
        <?php }else{ ?>
            ✔
        <?php } ?>
    </td>
</tr>
<?php } ?>

</table>

<a href="logout.php" class="btn btn-danger w-100">ออกจากระบบ</a>

</body>
</html>
