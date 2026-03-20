<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "water_db";

$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลไม่ได้");
}
?>
