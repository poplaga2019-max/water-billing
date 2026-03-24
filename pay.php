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

$conn->query("UPDATE bills SET status='paid' WHERE id=$id");

$msg = "💵 รับเงินสด\nลูกค้า: ".$data['name']."\nยอด: ".$data['amount']." บาท";
sendTelegram($msg, 'payment');

header("Location: bills.php");
?>
