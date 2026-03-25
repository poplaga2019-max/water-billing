<?php include 'layout.php'; include 'config/db.php'; ?>

<h2>👤 ลูกบ้าน</h2>

<div class="card">
<form method="post">
<input name="name" placeholder="ชื่อ">
<input name="address" placeholder="บ้านเลขที่">
<button>เพิ่ม</button>
</form>
</div>

<?php
if($_POST){
    $name = $_POST['name'];
    $addr = $_POST['address'];
    $conn->query("INSERT INTO customers(name,address) VALUES('$name','$addr')");
    echo "<meta http-equiv='refresh' content='0'>";
}
?>

<div class="card">
<?php
$res = $conn->query("SELECT * FROM customers");
while($row=$res->fetch_assoc()){
    echo "<p>🏠 {$row['address']} - {$row['name']}</p>";
}
?>
</div>

<?php include 'layout_footer.php'; ?>
