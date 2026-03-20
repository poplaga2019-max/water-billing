<?php
session_start();
include 'config/db.php';

if(isset($_POST['login'])){
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$user' AND password='$pass'";
    $res = $conn->query($sql);

    if($res->num_rows > 0){
        $data = $res->fetch_assoc();
        $_SESSION['user'] = $data;

        header("Location: dashboard.php");
    }else{
        $error = "ชื่อผู้ใช้หรือรหัสผ่านผิด";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>เข้าสู่ระบบ</title>
</head>
<body style="font-family: THSarabun, sans-serif;">
    <h2>เข้าสู่ระบบ</h2>

    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="ชื่อผู้ใช้" required><br><br>
        <input type="password" name="password" placeholder="รหัสผ่าน" required><br><br>
        <button name="login">เข้าสู่ระบบ</button>
    </form>
</body>
</html>
