<?php
session_start();
include 'config/db.php';

if(isset($_POST['login'])){

    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s",$user);
    $stmt->execute();

    $res = $stmt->get_result()->fetch_assoc();

    if($res && password_verify($pass,$res['password'])){
        $_SESSION['user'] = $res;
        header("Location: dashboard.php");
        exit();
    }else{
        $error = "❌ ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<title>เข้าสู่ระบบ</title>

<style>
body{
    background:#f5f5f5;
}
.login-box{
    max-width:400px;
    margin:auto;
    margin-top:80px;
    padding:25px;
    background:white;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,0.1);
}
</style>

</head>

<body>

<div class="login-box">

<h4 class="text-center mb-3">🔐 เข้าสู่ระบบ</h4>

<?php if(isset($error)){ ?>
<div class="alert alert-danger text-center">
<?= $error ?>
</div>
<?php } ?>

<form method="POST">

<div class="mb-3">
<input name="username" class="form-control" placeholder="ชื่อผู้ใช้" required>
</div>

<div class="mb-3">
<input name="password" type="password" class="form-control" placeholder="รหัสผ่าน" required>
</div>

<button name="login" class="btn btn-primary w-100">
เข้าสู่ระบบ
</button>

</form>

</div>

<!-- 🔥 DEV CREDIT -->
<div style="
    text-align:center;
    margin-top:30px;
    font-size:12px;
    color:#aaa;
">
    ⚙️ Dev By Chattep Buttawong © <?= date('Y') ?>
</div>

</body>
</html>
