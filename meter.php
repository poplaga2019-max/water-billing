<?php
session_start();
include 'config/db.php';

$id = $_GET['id'];

$cust = $conn->query("SELECT * FROM customers WHERE id=$id")->fetch_assoc();

if(isset($_POST['save'])){
    $new = $_POST['new_unit'];
    $old = $cust['last_unit'];

    if($new < $old){
        echo "เลขผิด";
    }else{
        $used = $new - $old;
$rate = 10; // หน่วยละ 10 บาท (เดี๋ยวเราทำให้ปรับได้ทีหลัง)
$amount = $used * $rate;

// บันทึกบิล
$conn->query("INSERT INTO bills (customer_id, old_unit, new_unit, used_unit, amount)
VALUES ($id, $old, $new, $used, $amount)");

// อัปเดตมิเตอร์
$conn->query("UPDATE customers SET last_unit=$new WHERE id=$id");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>จดมิเตอร์</title>
</head>
<body style="font-family: THSarabun, sans-serif;">
    <h2>จดมิเตอร์</h2>

    <p>ชื่อ: <?= $cust['name'] ?></p>
    <p>บ้าน: <?= $cust['address'] ?></p>
    <p>เลขเดิม: <?= $cust['last_unit'] ?></p>

    <form method="POST">
        <input type="number" name="new_unit" placeholder="เลขใหม่" required>
        <button name="save">บันทึก</button>
    </form>

</body>
</html>
