<?php
include 'config/db.php';

// วันนี้
$today = date('Y-m-d');

// 📋 จดมิเตอร์
$meter = $conn->query("
SELECT COUNT(*) c FROM bills 
WHERE bill_date='$today'
")->fetch_assoc()['c'];

// 💵 เงินสด
$cash = $conn->query("
SELECT COUNT(*) c FROM bills 
WHERE status='paid' AND bill_date='$today'
")->fetch_assoc()['c'];

// 💸 โอนเงิน (มีสลิป)
$transfer = $conn->query("
SELECT COUNT(*) c FROM bills 
WHERE slip IS NOT NULL AND DATE(created_at)='$today'
")->fetch_assoc()['c'];

// 💰 รายได้
$income = $conn->query("
SELECT SUM(amount) s FROM bills 
WHERE bill_date='$today' AND status='paid'
")->fetch_assoc()['s'];

// ❌ ค้าง
$unpaid = $conn->query("
SELECT COUNT(*) c FROM bills 
WHERE status='unpaid'
")->fetch_assoc()['c'];

// ===== ส่ง Telegram =====
$set = $conn->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();

$token = $set['telegram_token'];
$chat_id = $set['telegram_payment'];

$msg = "📊 รายงานประจำวัน\n\n";
$msg .= "📋 จดมิเตอร์: $meter บ้าน\n";
$msg .= "💵 เงินสด: $cash รายการ\n";
$msg .= "💸 โอนเงิน: $transfer รายการ\n";
$msg .= "💰 รายได้: ".number_format($income)." บาท\n";
$msg .= "❌ ค้างชำระ: $unpaid รายการ";

$url = "https://api.telegram.org/bot$token/sendMessage";

file_get_contents($url."?chat_id=$chat_id&text=".urlencode($msg));

echo "ส่งรายงานแล้ว";
?>
