<?php
session_start();
include 'config/db.php';

// เช็ค login ลูกบ้าน
if(!isset($_SESSION['customer'])){
    header("Location: login.php");
    exit();
}

$id = $_POST['id'];

// ดึงข้อมูลบิล + ลูกค้า
$data = $conn->query("
SELECT b.*, c.name 
FROM bills b
JOIN customers c ON b.customer_id = c.id
WHERE b.id = $id
")->fetch_assoc();

if(isset($_FILES['slip']) && $_FILES['slip']['error'] == 0){

    $allowed = ['jpg','jpeg','png','pdf'];
    $file_name = $_FILES['slip']['name'];
    $file_tmp = $_FILES['slip']['tmp_name'];

    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if(in_array($ext, $allowed)){

        $new_name = "uploads/" . time() . "_" . rand(1000,9999) . "." . $ext;

        if(move_uploaded_file($file_tmp, $new_name)){

            // บันทึก
            $conn->query("UPDATE bills SET slip='$new_name' WHERE id=$id");

            // ===== 🔔 ส่ง Telegram =====
            $set = $conn->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();

            $token = $set['telegram_token'];
            $chat_id = $set['telegram_payment'];

            // ข้อความ
            $msg = "💸 มีการโอนเงิน\n";
            $msg .= "👤 ".$data['name']."\n";
            $msg .= "💰 ".number_format($data['amount'])." บาท";

            // 📸 ถ้าเป็นรูป → ส่งเป็น photo
            if(in_array($ext, ['jpg','jpeg','png'])){

                $url = "https://api.telegram.org/bot$token/sendPhoto";

                $post_fields = [
                    'chat_id' => $chat_id,
                    'caption' => $msg,
                    'photo' => new CURLFile(realpath($new_name))
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
                curl_exec($ch);
                curl_close($ch);

            }else{
                // 📄 ถ้าเป็น PDF ส่งข้อความแทน
                $url = "https://api.telegram.org/bot$token/sendMessage";

                file_get_contents($url."?chat_id=$chat_id&text=".urlencode($msg));
            }

        }else{
            echo "อัปโหลดไม่สำเร็จ";
            exit();
        }

    }else{
        echo "รองรับเฉพาะ jpg, jpeg, png, pdf";
        exit();
    }

}else{
    echo "ไม่มีไฟล์";
    exit();
}

header("Location: mybill.php");
exit();
?>
