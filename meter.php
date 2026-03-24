<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
}

$id = $_GET['id'];

$cust = $conn->query("SELECT * FROM customers WHERE id=$id")->fetch_assoc();

if(isset($_POST['save'])){
    $new = $_POST['new_unit'];
    $old = $cust['last_unit'];

    if($new < $old){
        $error = "เลขใหม่ต้องมากกว่าเดิม";
    }else{

        $used = $new - $old;
        $amount = 0;

        $rates = $conn->query("SELECT * FROM water_rates");

        while($r = $rates->fetch_assoc()){
            $min = $r['min_unit'];
            $max = $r['max_unit'];
            $price = $r['price_per_unit'];

            if($used >= $min){
                $unit_in_step = min($used, $max) - $min + 1;
                if($unit_in_step > 0){
                    $amount += $unit_in_step * $price;
                }
            }
        }

        // บันทึกบิล
        $conn->query("INSERT INTO bills (customer_id, old_unit, new_unit, used_unit, amount)
        VALUES ($id, $old, $new, $used, $amount)");

        // อัปเดตมิเตอร์
        $conn->query("UPDATE customers SET last_unit=$new WHERE id=$id");

        header("Location: customers.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>จดมิเตอร์</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4" style="font-family: THSarabun, sans-serif;">

<h2 class="mb-3">📋 จดมิเตอร์น้ำ</h2>

<div class="card p-4 shadow">

    <p><strong>👤 ชื่อ:</strong> <?= $cust['name'] ?></p>
    <p><strong>🏠 บ้าน:</strong> <?= $cust['address'] ?></p>
    <p><strong>🔢 เลขเดิม:</strong> <?= $cust['last_unit'] ?></p>

    <?php if(isset($error)){ ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php } ?>

    <form method="POST">
        <input type="number" name="new_unit" class="form-control mb-3" placeholder="กรอกเลขใหม่" required>

        <button name="save" class="btn btn-primary w-100">💾 บันทึก</button>
    </form>

</div>

<br>

<a href="customers.php" class="btn btn-secondary w-100">⬅ กลับ</a>

</body>
</html>
