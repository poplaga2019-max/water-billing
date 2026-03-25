<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role']!='admin'){
    die('no permission');
}

// load
$s = [];
$rs = $conn->query("SELECT * FROM settings");
while($r=$rs->fetch_assoc()){ $s[$r['name']]=$r['value']; }

// save
if(isset($_POST['save'])){
    $site = $_POST['site_name'];
    $pp = $_POST['promptpay'];
    $token = $_POST['telegram_token'];
    $chat_fin = $_POST['telegram_chat_finance'];

    $conn->query("REPLACE INTO settings VALUES('site_name','$site')");
    $conn->query("REPLACE INTO settings VALUES('promptpay','$pp')");
    $conn->query("REPLACE INTO settings VALUES('telegram_token','$token')");
    $conn->query("REPLACE INTO settings VALUES('telegram_chat_finance','$chat_fin')");

    // upload logo
    if(!empty($_FILES['logo']['name'])){
        if(!is_dir('uploads')) mkdir('uploads');
        $path = 'uploads/logo.png';
        move_uploaded_file($_FILES['logo']['tmp_name'],$path);
        $conn->query("REPLACE INTO settings VALUES('logo','$path')");
    }

    header("Location: setting.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="container mt-3">

<h4>⚙️ ตั้งค่า</h4>

<form method="POST" enctype="multipart/form-data">

<p>ชื่อระบบ</p>
<input name="site_name" value="<?= $s['site_name'] ?? '' ?>">

<p>PromptPay</p>
<input name="promptpay" value="<?= $s['promptpay'] ?? '' ?>">

<p>Telegram Token</p>
<input name="telegram_token" value="<?= $s['telegram_token'] ?? '' ?>">

<p>Chat Finance</p>
<input name="telegram_chat_finance" value="<?= $s['telegram_chat_finance'] ?? '' ?>">

<p>Logo</p>
<input type="file" name="logo">

<br><br>
<button name="save">บันทึก</button>

</form>

</body>
</html>
