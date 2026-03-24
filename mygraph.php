<?php
session_start();
include 'config/db.php';

$cid = $_SESSION['customer']['id'];

$res = $conn->query("
SELECT DATE_FORMAT(created_at,'%Y-%m') m, SUM(used_unit) u
FROM bills
WHERE customer_id=$cid
GROUP BY m
");

$month=[]; $unit=[];
while($r=$res->fetch_assoc()){
    $month[]=$r['m'];
    $unit[]=$r['u'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>กราฟ</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

<h3>📊 การใช้น้ำ</h3>
<canvas id="c"></canvas>

<script>
new Chart(c,{
 type:'line',
 data:{
  labels:<?=json_encode($month)?>,
  datasets:[{label:'หน่วยน้ำ',data:<?=json_encode($unit)?>}]
 }
});
</script>

</body>
</html>
