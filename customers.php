<?php
session_start();
include 'config/db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

// ===== เพิ่มลูกค้า =====
if(isset($_POST['save'])){
    $name = $_POST['name'];
    $address = $_POST['address'];
    $meter = $_POST['meter'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];

    $conn->query("
    INSERT INTO customers (name, address, meter_no, lat, lng)
    VALUES ('$name','$address','$meter','$lat','$lng')
    ");
}

// ===== แก้ไข =====
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $meter = $_POST['meter'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];

    $conn->query("
    UPDATE customers 
    SET name='$name', address='$address', meter_no='$meter', lat='$lat', lng='$lng'
    WHERE id=$id
    ");
}

// โหลดข้อมูล
$list = $conn->query("SELECT * FROM customers ORDER BY id DESC");

// ถ้ามีการแก้ไข
$edit = null;
if(isset($_GET['edit'])){
    $eid = $_GET['edit'];
    $edit = $conn->query("SELECT * FROM customers WHERE id=$eid")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>ลูกค้า</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Google Maps + Places -->
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY&libraries=places"></script>
</head>

<body class="container mt-4">

<h3>👨‍👩‍👧‍👦 ลูกค้า</h3>

<div class="card p-3 mb-4">

<form method="POST">

<input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">

<input type="text" name="name" class="form-control mb-2" placeholder="ชื่อ"
value="<?= $edit['name'] ?? '' ?>" required>

<input type="text" name="address" id="searchBox" class="form-control mb-2"
placeholder="ค้นหาที่อยู่"
value="<?= $edit['address'] ?? '' ?>">

<input type="text" name="meter" class="form-control mb-2"
placeholder="เลขมิเตอร์"
value="<?= $edit['meter_no'] ?? '' ?>">

<input type="text" name="lat" id="lat" class="form-control mb-2"
value="<?= $edit['lat'] ?? '' ?>" placeholder="Latitude" readonly>

<input type="text" name="lng" id="lng" class="form-control mb-2"
value="<?= $edit['lng'] ?? '' ?>" placeholder="Longitude" readonly>

<!-- ปุ่ม GPS -->
<button type="button" onclick="getLocation()" class="btn btn-warning w-100 mb-2">
📍 ใช้ตำแหน่งปัจจุบัน
</button>

<div id="map" style="height:300px;"></div>

<button name="<?= $edit ? 'update' : 'save' ?>" class="btn btn-success w-100 mt-3">
💾 <?= $edit ? 'อัปเดต' : 'บันทึก' ?>
</button>

</form>

</div>

<!-- ตาราง -->
<table class="table table-bordered text-center">
<tr>
<th>ชื่อ</th>
<th>ที่อยู่</th>
<th>แผนที่</th>
<th>แก้ไข</th>
</tr>

<?php while($r = $list->fetch_assoc()){ ?>
<tr>
<td><?= $r['name'] ?></td>
<td><?= $r['address'] ?></td>

<td>
<?php if($r['lat']){ ?>
<a href="https://www.google.com/maps/dir/?api=1&destination=<?= $r['lat'] ?>,<?= $r['lng'] ?>"
class="btn btn-primary btn-sm">🚗</a>
<?php } ?>
</td>

<td>
<a href="?edit=<?= $r['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
</td>

</tr>
<?php } ?>
</table>

<script>
var map = new google.maps.Map(document.getElementById('map'), {
 zoom: 15,
 center: {lat: 16.8, lng: 100.2}
});

var marker;

// คลิกปักหมุด
map.addListener("click", function(e){

    if(marker) marker.setMap(null);

    marker = new google.maps.Marker({
        position: e.latLng,
        map: map
    });

    document.getElementById('lat').value = e.latLng.lat();
    document.getElementById('lng').value = e.latLng.lng();
});

// 📍 ใช้ GPS
function getLocation(){
    navigator.geolocation.getCurrentPosition(function(pos){
        var lat = pos.coords.latitude;
        var lng = pos.coords.longitude;

        map.setCenter({lat: lat, lng: lng});

        if(marker) marker.setMap(null);

        marker = new google.maps.Marker({
            position: {lat: lat, lng: lng},
            map: map
        });

        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;
    });
}

// 🔎 ค้นหา
var input = document.getElementById('searchBox');
var searchBox = new google.maps.places.SearchBox(input);

searchBox.addListener('places_changed', function() {
    var places = searchBox.getPlaces();

    var place = places[0];
    var loc = place.geometry.location;

    map.setCenter(loc);

    if(marker) marker.setMap(null);

    marker = new google.maps.Marker({
        position: loc,
        map: map
    });

    document.getElementById('lat').value = loc.lat();
    document.getElementById('lng').value = loc.lng();
});
</script>

</body>
</html>
