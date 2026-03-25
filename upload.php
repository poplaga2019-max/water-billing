<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

if(isset($_POST['upload'])){

    $file = time().'_'.$_FILES['slip']['name'];
    $tmp = $_FILES['slip']['tmp_name'];

    $path = "uploads/".$file;

    move_uploaded_file($tmp,$path);

    $conn->query("
    UPDATE bills 
    SET slip='$path',status='verify' 
    WHERE id=$id
    ");

    header("Location: bills_mobile.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

<h3>อัปโหลดสลิป</h3>

<form method="POST" enctype="multipart/form-data">
<input type="file" name="slip" required>
<button name="upload">อัปโหลด</button>
</form>

</body>
</html>
