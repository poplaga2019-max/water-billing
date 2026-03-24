<?php
session_start();
include 'config/db.php';
include 'notify.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? 1;

$list = $conn->query("SELECT * FROM customers ORDER BY id");
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

$prev = $index > 0 ? $customers[$index-1]['id'] : $id;
$next = $index < count($customers)-1 ? $customers[$index+1]['id'] : $id;

$cust = $customers[$index];

if(isset($_POST['save'])){

    $old = $cust['last_unit'];
    $new = $_POST['new_unit'];
    $used = $new - $old;

    $amount = 0;
    $rates = $conn->query("SELECT * FROM water_rates");

    while($r = $rates->fetch_assoc()){
        if($used >= $r['min_unit']){
            $max = min($used, $r['max_unit']);
            $amount += ($max - $r['min_unit'] + 1) * $r['price_per_unit'];
        }
    }

    $today = date('Y-m-d');
    $staff_id = $_SESSION['user']['id'];

    $conn->query("
    INSERT INTO bills (customer_id, old_unit, new_unit, used_unit, amount, bill_date, staff_id)
    VALUES ($id, $old, $new, $used, $amount, '$today', $staff_id)
    ");

    $conn->query("UPDATE customers SET last_unit=$new WHERE id=$id");

    $msg = "📋 จดมิเตอร์\n👤 ".$cust['name']."\n💧 ".$used." หน่วย";
    sendTelegram($msg, 'meter');

    header("Location: meter.php?id=".$next);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-3">

<h4>📋 จดมิเตอร์</h4>

<div class="card p-3">

<h5><?= $cust['name'] ?></h5>
<p><?= $cust['address'] ?></p>
<p>เลขล่าสุด: <?= $cust['last_unit'] ?></p>

<?php if(!empty($cust['lat'])){ ?>
<a href="https://www.google.com/maps/dir/?api=1&destination=<?= $cust['lat'] ?>,<?= $cust['lng'] ?>"
class="btn btn-success w-100 mb-2">🚗 นำทาง</a>
<?php } ?>

<form method="POST">
<input type="number" name="new_unit" class="form-control mb-2" required>
<button name="save" class="btn btn-primary w-100">บันทึก</button>
</form>

</div>

<div class="d-flex justify-content-between mt-2">
<a href="meter.php?id=<?= $prev ?>" class="btn btn-secondary">⬅</a>
<a href="meter.php?id=<?= $next ?>" class="btn btn-primary">➡</a>
</div>

</body>
</html>
