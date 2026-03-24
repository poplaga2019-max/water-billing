<?php
session_start();
include 'config/db.php';
include 'notify.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? 1;

// โหลดลูกค้า
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

    // คิดเงิน
    $amount = 0;
    $rates = $conn->query("SELECT * FROM water_rates");

    while($r = $rates->fetch_assoc()){
        if($used >= $r['min_unit']){
            $max = min($used, $r['max_unit']);
            $amount += ($max - $r['min_unit'] + 1) * $r['price_per_unit'];
        }
    }

    // ===== รอบบิล =====
    $set = $conn->query("SELECT cycle_day FROM settings WHERE id=1")->fetch_assoc();
    $cycle_day = $set['cycle_day'];

    $day = date('d');
    $month = date('m');
    $year = date('Y');

    if($day < $cycle_day){
        $month--;
        if($month <= 0){
            $month = 12;
            $year--;
        }
    }

    $billing_cycle = $year . "-" . str_pad($month,2,'0',STR_PAD_LEFT);
    $bill_date = $billing_cycle . "-" . str_pad($cycle_day,2,'0',STR_PAD_LEFT);

    $staff_id = $_SESSION['user']['id'];

    $conn->query("
    INSERT INTO bills (
        customer_id, old_unit, new_unit, used_unit, amount,
        bill_date, staff_id, billing_cycle
    )
    VALUES (
        $id, $old, $new, $used, $amount,
        '$bill_date', $staff_id, '$billing_cycle'
    )
    ");

    // update meter
    $conn->query("UPDATE customers SET last_unit=$new WHERE id=$id");

    // telegram
    $msg = "📋 จดมิเตอร์\n👤 ".$cust['name']."\n💧 ".$used." หน่วย";
    sendTelegram($msg, 'meter');

    header("Location: meter.php?id=".$next);
    exit();
}
?>
