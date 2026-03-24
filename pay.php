<?php
include 'config/db.php';

$id = $_GET['id'];

$conn->query("UPDATE bills SET status='paid' WHERE id=$id");

header("Location: bills.php");
