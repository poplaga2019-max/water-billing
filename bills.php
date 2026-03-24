<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

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

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4" style="font-family: THSarabun, sans-serif;">

<h2 class="mb-3">💰 บิลค่าน้ำ</h2>

<div class="table-responsive">

<table class="table table-bordered text-center align-middle">

<tr class="table-dark">
    <th>ชื่อลูกค้า</th>
    <th>หน่วยใช้</th>
    <th>จำนวนเงิน</th>
    <th>สถานะ</th>
    <th>จัดการ</th>
    <th>ใบเสร็จ</th>
</tr>

<?php while($row = $res->fetch_assoc()){ ?>
<tr>

    <td><?= $row['name'] ?></td>

    <td><?= $row['used_unit'] ?></td>

    <td><?= number_format($row['amount']) ?> บาท</td>

    <td>
        <?php if($row['status']=='paid'){ ?>
            <span class="badge bg-success">จ่ายแล้ว</span>
        <?php }else{ ?>
            <span class="badge bg-danger">ยังไม่จ่าย</span>
        <?php } ?>
    </td>

    <td>
        <?php if($row['status']!='paid'){ ?>
            <a href="pay.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm">
                รับเงิน
            </a>
        <?php }else{ ?>
            ✔
        <?php } ?>
    </td>

    <td>
        <a href="receipt.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">
            PDF
        </a>
    </td>

</tr>
<?php } ?>

</table>

</div>

<br>

<a href="dashboard.php" class="btn btn-secondary w-100">⬅ กลับแดชบอร์ด</a>

</body>
</html>
