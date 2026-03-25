<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

// 🔒 check lock
$bill = $conn->query("SELECT * FROM bills WHERE id=$id")->fetch_assoc();

if($bill['is_locked'] == 1 && $_SESSION['user']['role'] != 'admin'){
    echo "❌ รอบบิลถูกปิด";
    exit();
}

// 👉 เงินสด = รอตรวจสอบ
$conn->query("
UPDATE bills SET status='verify' WHERE id=$id
");

header("Location: bills_mobile.php");
?>
