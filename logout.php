<?php
session_start();

// ลบ session ทั้งหมด
$_SESSION = [];
session_destroy();

// redirect กลับหน้าแรก
header("Location: index.php");
exit();
