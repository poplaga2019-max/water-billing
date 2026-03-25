<?php include 'layout.php'; include 'config/db.php'; ?>

<?php
// นับข้อมูล
$total = $conn->query("SELECT COUNT(*) c FROM customers")->fetch_assoc()['c'] ?? 0;
$done = $conn->query("SELECT COUNT(*) c FROM meter WHERE DATE(created_at)=CURDATE()")->fetch_assoc()['c'] ?? 0;
$remain = $total - $done;

$paid = $conn->query("SELECT COUNT(*) c FROM bills WHERE status='paid'")->fetch_assoc()['c'] ?? 0;
$pending = $conn->query("SELECT COUNT(*) c FROM bills WHERE status='pending'")->fetch_assoc()['c'] ?? 0;
$overdue = $conn->query("SELECT COUNT(*) c FROM bills WHERE status='overdue'")->fetch_assoc()['c'] ?? 0;

$income = $conn->query("SELECT SUM(amount) s FROM bills WHERE DATE(paid_at)=CURDATE()")->fetch_assoc()['s'] ?? 0;
?>

<div class="card">
<h2>📊 Dashboard</h2>

<p>🏠 ทั้งหมด: <?= $total ?></p>
<p>📋 จดแล้ว: <?= $done ?></p>
<p>📉 เหลือ: <?= $remain ?></p>

<p>💰 รายได้วันนี้: <?= number_format($income) ?> บาท</p>

<p>🟢 ชำระแล้ว: <?= $paid ?></p>
<p>🟡 รอตรวจสอบ: <?= $pending ?></p>
<p>🔴 ค้าง: <?= $overdue ?></p>
</div>

<?php include 'layout_footer.php'; ?>
