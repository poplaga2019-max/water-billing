<?php
session_start();
include 'config/db.php';

if(isset($_POST['login'])){
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s",$user);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();

    if($result && password_verify($pass,$result['password'])){
        $_SESSION['user'] = $result;
        header("Location: dashboard.php");
    }else{
        $error = "❌ เข้าสู่ระบบไม่ถูกต้อง";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

<h3>เข้าสู่ระบบ</h3>

<?php if(isset($error)){ echo "<div class='alert alert-danger'>$error</div>"; } ?>

<form method="POST">
<input type="text" name="username" class="form-control mb-2" placeholder="Username">
<input type="password" name="password" class="form-control mb-2" placeholder="Password">
<button name="login" class="btn btn-primary w-100">Login</button>
</form>

</body>
</html>
