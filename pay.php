<?php
session_start();
include 'config/db.php';
include 'notify.php';

$id = $_GET['id'];

$data = $conn->query("
SELECT b.*, c.name 
FROM bills b
JOIN customers c ON b.customer_id=c.id
WHERE b.id=$id
")->fetch_assoc();

// 🔒 เช็ค lock
if($data['is_locked'] == 1 && $_SESSION['user']['role'] != 'admin'){
    echo "❌ รอบบิลนี้ถูกปิดแล้ว";
    exit();
}

// ❗ เปลี่ยนเป็น pending
$conn->query("UPDATE bills SET status='pending' WHERE id=$id");

// แจ้ง Telegram
$msg = "💵 รับเงินสด (รอตรวจสอบ)\n";
$msg .= "👤 ".$data['name']."\n";
$msg .= "💰 ".$data['amount']." บาท";

sendTelegram($msg,'payment');

header("Location: bills.php");
?>
