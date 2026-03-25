<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? 1;

// ===== GET CUSTOMER =====
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

$prev = $customers[$index-1]['id'] ?? $id;
$next = $customers[$index+1]['id'] ?? $id;

$cust = $customers[$index];

// ===== SAVE =====
if(isset($_POST['save'])){

    $old = $cust['last_unit'];
    $new = $_POST['new_unit'];
    $used = $new - $old;

    if($used >= 0){

        $amount = 0;
        $rates = $conn->query("SELECT * FROM water_rates");

        while($r = $rates->fetch_assoc()){
            if($used >= $r['min_unit']){
                $max = min($used, $r['max_unit']);
                $amount += ($max - $r['min_unit'] + 1) * $r['price_per_unit'];
            }
        }

        $cycle = date('Y-m');

        $conn->query("
        INSERT INTO bills 
        (customer_id,old_unit,new_unit,used_unit,amount,status,billing_cycle,staff_id)
        VALUES 
        ($id,$old,$new,$used,$amount,'pending','$cycle',{$_SESSION['user']['id']})
        ");

        $conn->query("UPDATE customers SET last_unit=$new WHERE id=$id");

        header("Location: meter_mobile.php?id=".$next);
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.big-input{height:60px;font-size:22px;text-align:center}
.big-btn{padding:15px;font-size:18px}
</style>
</head>

<body class="container mt-3">

<h5 class="text-center"><?= $cust['name'] ?></h5>

<div class="card text-center p-3 mb-3">
<h3><?= $cust['last_unit'] ?></h3>
<p>เลขครั้งก่อน</p>
</div>

<form method="POST">
<input type="number" name="new_unit" class="form-control big-input mb-3" required>

<button name="save" class="btn btn-success w-100 big-btn">
✔ บันทึก
</button>
</form>

<div class="d-flex gap-2">
<a href="?id=<?=$prev?>" class="btn btn-secondary w-50">⬅</a>
<a href="?id=<?=$next?>" class="btn btn-primary w-50">➡</a>
</div>

<a href="https://www.google.com/maps/dir/?api=1&destination=<?=$cust['lat']?>,<?=$cust['lng']?>" 
class="btn btn-warning w-100 big-btn mt-2">
📍 นำทาง
</a>

</body>
</html>
