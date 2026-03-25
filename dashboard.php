<?php
session_start();
include 'config/db.php';

$month = date('Y-m');

// ดึงรายได้รายวัน
$data = [];
$res = $conn->query("
SELECT DATE(created_at) d, SUM(amount) s
FROM bills
WHERE billing_cycle='$month' AND status='paid'
GROUP BY DATE(created_at)
");

while($r = $res->fetch_assoc()){
    $data[$r['d']] = $r['s'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-3">

<h4>📊 Dashboard</h4>

<canvas id="chart"></canvas>

<script>
const data = {
labels: <?= json_encode(array_keys($data)) ?>,
datasets: [{
label: 'รายได้',
data: <?= json_encode(array_values($data)) ?>,
borderWidth: 2
}]
};

new Chart(document.getElementById('chart'), {
type: 'line',
data: data
});
</script>

</body>
</html>
