<?php
session_start();
include 'config/db.php';

// ลูกบ้าน login
if(!isset($_SESSION['customer'])){
    header("Location: login.php");
    exit();
}

$id = $_POST['id'];

// ดึงข้อมูล
$data = $conn->query("
SELECT b.*, c.name 
FROM bills b
JOIN customers c ON b.customer_id=c.id
WHERE b.id=$id
")->fetch_assoc();

if(isset($_FILES['slip']) && $_FILES['slip']['error']==0){

    $ext = strtolower(pathinfo($_FILES['slip']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','pdf'];

    if(in_array($ext,$allowed)){

        $new_name = "uploads/".time()."_".rand(1000,9999).".".$ext;
        move_uploaded_file($_FILES['slip']['tmp_name'],$new_name);

        // ✅ เปลี่ยนเป็น pending
        $conn->query("
        UPDATE bills 
        SET slip='$new_name', status='pending'
        WHERE id=$id
        ");

        // Telegram
        include 'notify.php';

        $msg = "📩 มีการโอนเงิน (รอตรวจสอบ)\n";
        $msg .= "👤 ".$data['name']."\n";
        $msg .= "💰 ".number_format($data['amount'])." บาท";

        sendTelegram($msg,'payment');
    }
}

header("Location: mybill.php");
?>
