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
    }else{
        $error = "ผิดพลาด";
    }
}
?>

<form method="POST">
<input name="username" placeholder="user">
<input name="password" type="password">
<button name="login">Login</button>
</form>
