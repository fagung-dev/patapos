<?php
include 'koneksi.php';

$productId = $_POST['id'];
var_dump($productId);


$stmt = $conn->prepare("DELETE FROM product WHERE id = ?");
$stmt->bind_param("s", $productId);
$stmt->execute();
$stmt->close();
$conn->close();