<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

include 'notify.php';

// เลือกลูกค้า
$id = isset($_GET['id']) ? $_GET['id'] : 1;

// ดึงลูกค้าทั้งหมด (ไว้ทำ next/prev)
$list = $conn->query("SELECT * FROM customers ORDER BY id");

// หา index ปัจจุบัน
$customers = [];
while($c = $list->fetch_assoc()){
    $customers[] = $c;
}

$index = 0;
foreach($customers as $i=>$c){
    if($c['id'] == $id){
        $index = $i;
        break;
    }
}

// prev / next
$prev = $index > 0 ? $customers[$index-1]['id'] : $id;
$next = $index < count($customers)-1 ? $customers[$index+1]['id'] : $id;

// ลูกค้าปัจจุบัน
$cust = $customers[$index];


// บันทึก
if(isset($_POST['save'])){

    $old = $cust['last_unit'];
    $new = $_POST['new_unit'];
    $used = $new - $old;

    // คิดเงิน (ขั้นบันได)
    $amount = 0;
    $rates = $conn->query("SELECT * FROM water_rates");

    while($r = $rates->fetch_assoc()){
        if($used >= $r['min_unit']){
            $max = min($used, $r['max_unit']);
            $amount += ($max - $r['min_unit'] + 1) * $r['price_per_unit'];
        }
    }

    $today = date('Y-m-d');

    // บันทึกบิล
    $conn->query("
    INSERT INTO bills (customer_id, old_unit, new_unit, used_unit, amount, bill_date)
    VALUES ($id, $old, $new, $used, $amount, '$today')
    ");

    // อัปเดตมิเตอร์ล่าสุด
    $conn->query("UPDATE customers SET last_unit=$new WHERE id=$id");

    // 🔔 แจ้ง Telegram
    $msg = "📋 จดมิเตอร์แล้ว\n";
    $msg .= "ลูกค้า: ".$cust['name']."\n";
    $msg .= "หน่วย: $used";

    sendTelegram($msg, 'meter');

    // ไปบ้านถัดไป
    header("Location: meter.php?id=".$next);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>จดมิเตอร์</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4" style="font-family: THSarabun, sans-serif;">

<h3>📋 จดมิเตอร์น้ำ</h3>

<div class="card p-3 shadow mb-3">

<h5><?= $cust['name'] ?></h5>
<p><?= $cust['address'] ?></p>

<p>เลขมิเตอร์ล่าสุด: <b><?= $cust['last_unit'] ?></b></p>

<form method="POST">

    <label>เลขมิเตอร์ใหม่</label>
    <input type="number" name="new_unit" class="form-control mb-3" required>

    <button name="save" class="btn btn-success w-100">
        💾 บันทึก
    </button>

</form>

</div>

<!-- ปุ่มเลื่อน -->
<div class="d-flex justify-content-between mb-3">
    <a href="meter.php?id=<?= $prev ?>" class="btn btn-secondary">⬅ บ้านก่อนหน้า</a>
    <a href="meter.php?id=<?= $next ?>" class="btn btn-primary">บ้านถัดไป ➡</a>
</div>

<a href="dashboard.php" class="btn btn-dark w-100">⬅ กลับแดชบอร์ด</a>

</body>
</html>
