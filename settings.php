<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

// ดึงค่าเดิม
$set = $conn->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();


// 💾 บันทึกชื่อระบบ
if(isset($_POST['save_name'])){
    $name = $_POST['site_name'];
    $conn->query("UPDATE settings SET site_name='$name' WHERE id=1");
}


// 🖼️ อัปโหลดโลโก้
if(isset($_POST['upload_logo'])){
    $path = "uploads/logo.png";
    move_uploaded_file($_FILES['logo']['tmp_name'], $path);

    $conn->query("UPDATE settings SET logo='$path' WHERE id=1");
}


// 📢 บันทึก Telegram
if(isset($_POST['save_tg'])){
    $token = $_POST['telegram_token'];
    $meter = $_POST['telegram_meter'];
    $payment = $_POST['telegram_payment'];

    $conn->query("
        UPDATE settings SET
        telegram_token='$token',
        telegram_meter='$meter',
        telegram_payment='$payment'
        WHERE id=1
    ");
}

// reload ค่าใหม่
$set = $conn->query("SELECT * FROM settings WHERE id=1")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>ตั้งค่า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4" style="font-family: THSarabun, sans-serif;">

<h2 class="mb-3">⚙️ ตั้งค่าระบบ</h2>

<!-- 🏷️ ชื่อระบบ -->
<div class="card p-3 shadow mb-4">
<form method="POST">
    <label>ชื่อระบบ</label>
    <input type="text" name="site_name" class="form-control mb-2"
           value="<?= $set['site_name'] ?>" required>

    <button name="save_name" class="btn btn-success w-100">
        💾 บันทึกชื่อระบบ
    </button>
</form>
</div>


<!-- 🖼️ โลโก้ -->
<div class="card p-3 shadow mb-4">
<form method="POST" enctype="multipart/form-data">
    <label>โลโก้</label>
    <input type="file" name="logo" class="form-control mb-2">

    <button name="upload_logo" class="btn btn-primary w-100">
        อัปโหลดโลโก้
    </button>
</form>

<?php if(!empty($set['logo'])){ ?>
    <img src="<?= $set['logo'] ?>" width="100">
<?php } ?>
</div>


<!-- 📢 Telegram -->
<div class="card p-3 shadow mb-4">
<form method="POST">

    <label>Telegram Token</label>
    <input type="text" name="telegram_token" class="form-control mb-2"
           value="<?= $set['telegram_token'] ?>">

    <label>ห้องจดมิเตอร์</label>
    <input type="text" name="telegram_meter" class="form-control mb-2"
           value="<?= $set['telegram_meter'] ?>">

    <label>ห้องการเงิน</label>
    <input type="text" name="telegram_payment" class="form-control mb-2"
           value="<?= $set['telegram_payment'] ?>">

    <button name="save_tg" class="btn btn-warning w-100">
        💾 บันทึก Telegram
    </button>

</form>
</div>


<a href="dashboard.php" class="btn btn-secondary w-100">
⬅ กลับแดชบอร์ด
</a>

</body>
</html>
