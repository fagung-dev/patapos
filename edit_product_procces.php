<?php
include 'koneksi.php';

$productId = $_POST['productId'];
$productName = $_POST['productName'];
$productPrice = $_POST['productPrice'];

$stmt = $conn->prepare("UPDATE product SET product_name = ?, price = ? WHERE id = ?");
$stmt->bind_param("sss", $productName, $productPrice, $productId);
$stmt->execute();

$stmt->close();
$conn->close();

header('Location: produk.php?success=Berhasil update produk!');