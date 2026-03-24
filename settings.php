<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

// เพิ่มขั้นบันได
if(isset($_POST['add'])){
    $min = $_POST['min'];
    $max = $_POST['max'];
    $price = $_POST['price'];

    $conn->query("INSERT INTO water_rates (min_unit, max_unit, price_per_unit)
    VALUES ($min, $max, $price)");
}

// ลบ
if(isset($_GET['del'])){
    $id = $_GET['del'];
    $conn->query("DELETE FROM water_rates WHERE id=$id");
}

// ดึงข้อมูล
$res = $conn->query("SELECT * FROM water_rates ORDER BY min_unit ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>ตั้งค่าค่าน้ำ</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4" style="font-family: THSarabun, sans-serif;">

<h2 class="mb-3">⚙️ ตั้งค่าค่าน้ำ (ขั้นบันได)</h2>

<div class="card p-3 shadow mb-4">

<form method="POST" class="row g-2">
    <div class="col-md-3">
        <input type="number" name="min" class="form-control" placeholder="หน่วยเริ่ม" required>
    </div>
    <div class="col-md-3">
        <input type="number" name="max" class="form-control" placeholder="หน่วยสุด" required>
    </div>
    <div class="col-md-3">
        <input type="number" name="price" class="form-control" placeholder="บาท/หน่วย" required>
    </div>
    <div class="col-md-3">
        <button name="add" class="btn btn-primary w-100">➕ เพิ่ม</button>
    </div>
</form>

</div>

<div class="card p-3 shadow">

<table class="table table-bordered text-center">
<tr class="table-dark">
    <th>หน่วยเริ่ม</th>
    <th>หน่วยสุด</th>
    <th>บาท/หน่วย</th>
    <th>จัดการ</th>
</tr>

<?php while($row = $res->fetch_assoc()){ ?>
<tr>
    <td><?= $row['min_unit'] ?></td>
    <td><?= $row['max_unit'] ?></td>
    <td><?= $row['price_per_unit'] ?></td>
    <td>
        <a href="settings.php?del=<?= $row['id'] ?>" 
           class="btn btn-danger btn-sm"
           onclick="return confirm('ลบจริงไหม?')">
           ลบ
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
