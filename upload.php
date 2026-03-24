<?php
include 'config/db.php';

$id = $_POST['id'];

$target = "uploads/" . time() . $_FILES['slip']['name'];
move_uploaded_file($_FILES['slip']['tmp_name'], $target);

$conn->query("UPDATE bills SET slip='$target' WHERE id=$id");

header("Location: mybill.php");
