<?php
session_start();
include 'config/db.php';

// 🔥 AUTO MOBILE
if(preg_match('/Mobile|Android|iPhone/i', $_SERVER['HTTP_USER_AGENT'])){
    header("Location: meter_mobile.php?id=".$_GET['id']);
    exit();
}

// 🔒 LOGIN
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

// ===== CONFIG =====
$cycle = date('Y-m');

// ===== GET CUSTOMER =====
$id = $_GET['id'] ?? 1;

$list = $conn->query("SELECT * FROM customers ORDER BY id");
$customers = [];

while($c = $list->fetch_assoc()){
    $customers[] = $c;
}

// หา index
$index = 0;
foreach($customers as $i=>$c){
    if($c['id'] == $id){
        $index = $i;
        break;
    }
}

// prev / next
$prev = $customers[$index-1]['id'] ?? $id;
$next = $customers[$index+1]['id'] ?? $id;

$cust = $customers[$index];

// ===== CHECK LOCK =====
$lock = $conn->query("
SELECT is_locked FROM bills 
WHERE billing_cycle='$cycle' LIMIT 1
")->fetch_assoc()['is_locked'] ?? 0;

// ===== SAVE =====
if(isset($_POST['save'])){

    if($lock == 1 && $_SESSION['user']['role'] != 'admin'){
        echo "<script>alert('❌ รอบบิลถูกปิดแล้ว');</script>";
    }else{

        $old = $cust['last_unit'];
        $new = $_POST['new_unit'];
        $used = $new - $old;

        // ❗ กันค่าติดลบ
        if($used < 0){
            echo "<script>alert('❌ เลขมิเตอร์ผิด');</script>";
        }else{

            // ===== คิดขั้นบันได =====
            $amount = 0;
            $rates = $conn->query("SELECT * FROM water_rates");

            while($r = $rates->fetch_assoc()){
                if($used >= $r['min_unit']){
                    $max = min($used, $r['max_unit']);
                    $amount += ($max - $r['min_unit'] + 1) * $r['price_per_unit'];
                }
            }

            // ===== INSERT =====
            $conn->query("
            INSERT INTO bills 
            (customer_id,old_unit,new_unit,used_unit,amount,status,billing_cycle,staff_id)
            VALUES 
            ($id,$old,$new,$used,$amount,'pending','$cycle',{$_SESSION['user']['id']})
            ");

            // update meter
            $conn->query("UPDATE customers SET last_unit=$new WHERE id=$id");

            header("Location: meter.php?id=".$next);
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

<h3>📋 จดมิเตอร์ (Desktop)</h3>

<div class="card p-3 mb-3">
<h5><?= $cust['name'] ?></h5>
<p><?= $cust['address'] ?></p>
</div>

<div class="card p-3 text-center mb-3">
<h3><?= $cust['last_unit'] ?></h3>
<p>เลขครั้งก่อน</p>
</div>

<?php if($lock==1){ ?>
<div class="alert alert-danger">🔒 รอบบิลถูกปิด</div>
<?php } ?>

<form method="POST">
<input type="number" name="new_unit" class="form-control mb-3" required>

<button name="save" class="btn btn-success w-100">
✔ บันทึก
</button>
</form>

<div class="d-flex gap-2 mt-2">
<a href="?id=<?=$prev?>" class="btn btn-secondary w-50">⬅ ก่อนหน้า</a>
<a href="?id=<?=$next?>" class="btn btn-primary w-50">ถัดไป ➡</a>
</div>

<a href="https://www.google.com/maps/dir/?api=1&destination=<?=$cust['lat']?>,<?=$cust['lng']?>" 
class="btn btn-warning w-100 mt-3">
📍 นำทาง
</a>

</body>
</html>
