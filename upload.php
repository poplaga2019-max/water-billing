<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['customer'])){
    header("Location: login.php");
    exit();
}

$id = $_POST['id'];

// ตรวจว่ามีไฟล์ไหม
if(isset($_FILES['slip']) && $_FILES['slip']['error'] == 0){

    $allowed = ['jpg','jpeg','png','pdf'];
    $file_name = $_FILES['slip']['name'];
    $file_tmp = $_FILES['slip']['tmp_name'];

    // นามสกุลไฟล์
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if(in_array($ext, $allowed)){

        // ตั้งชื่อใหม่กันซ้ำ
        $new_name = "uploads/" . time() . "_" . rand(1000,9999) . "." . $ext;

        // ย้ายไฟล์
        if(move_uploaded_file($file_tmp, $new_name)){

            // บันทึกลง DB
            $conn->query("UPDATE bills SET slip='$new_name' WHERE id=$id");

        }else{
            echo "อัปโหลดไม่สำเร็จ";
            exit();
        }

    }else{
        echo "รองรับเฉพาะ jpg, png, pdf";
        exit();
    }

}else{
    echo "ไม่มีไฟล์";
    exit();
}

// กลับหน้าเดิม
header("Location: mybill.php");
exit();
