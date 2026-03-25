<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>

<style>
body {
    margin:0;
    font-family: 'Segoe UI', sans-serif;
    background:#f5f7fb;
}

/* TOPBAR */
.topbar {
    background: linear-gradient(90deg,#4facfe,#00f2fe);
    padding:12px;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:space-between;
}

.topbar h2 {
    margin:0;
    font-size:18px;
}

/* SIDEBAR */
.sidebar {
    position:fixed;
    left:0;
    top:50px;
    width:220px;
    height:100%;
    background:#1e293b;
    padding-top:20px;
}

.sidebar a {
    display:block;
    color:#cbd5e1;
    padding:12px 20px;
    text-decoration:none;
    transition:0.3s;
}

.sidebar a:hover {
    background:#334155;
    color:#fff;
}

/* CONTENT */
.content {
    margin-left:220px;
    padding:20px;
}

/* CARD */
.card {
    background:#fff;
    padding:15px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    margin-bottom:15px;
}

/* MOBILE */
@media(max-width:768px){
    .sidebar{
        display:none;
    }
    .content{
        margin-left:0;
    }
    .bottom-menu{
        position:fixed;
        bottom:0;
        left:0;
        right:0;
        background:#fff;
        display:flex;
        justify-content:space-around;
        border-top:1px solid #ddd;
        padding:8px 0;
    }
    .bottom-menu a{
        text-decoration:none;
        color:#333;
        font-size:12px;
        text-align:center;
    }
}
</style>

<div class="topbar">
    <h2>💧 ระบบประปาหมู่บ้าน</h2>
    <div>
        👤 <?= $_SESSION['user']['username'] ?? '' ?>
        | <a href="logout.php" style="color:#fff">ออก</a>
    </div>
</div>

<div class="sidebar">
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="customers.php">👤 ลูกบ้าน</a>
    <a href="meter.php">💧 จดมิเตอร์</a>
    <a href="bills.php">🧾 บิล</a>
    <a href="setting.php">⚙️ ตั้งค่า</a>
</div>

<div class="content">
