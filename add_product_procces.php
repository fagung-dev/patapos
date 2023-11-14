<?php
include 'koneksi.php';

$productName = $_POST['productName'];
$productPrice = $_POST['productPrice'];

$productId = guidv4();
$stmt = $conn->prepare("INSERT INTO product (id, product_name, price) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $productId, $productName, $productPrice);

if ($stmt->execute()) {

    $targetDir = "images/";
    $targetFile = $targetDir . basename($_FILES["productImage"]["name"]);
    var_dump($targetFile);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["productImage"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        header("Location: produk.php?error=File bukan gambar.");
        $uploadOk = 0;
    }

    if ($_FILES["productImage"]["size"] > 500000) {
        header("Location: produk.php?error=Maaf, ukuran file terlalu besar.");
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        header("Location: produk.php?error=Maaf, hanya file JPG, JPEG, PNG, & GIF yang diizinkan.");
        $uploadOk = 0;
    }

    var_dump($uploadOk);
    if ($uploadOk == 0) {
        header("Location: produk.php?error=Unknown error.");
    } else {
        $productNameForFile = preg_replace("/[^a-zA-Z0-9]/", "", $productName);
        $targetFile = $targetDir . $productNameForFile . "." . $imageFileType;
        $productNameForDatabase = $productNameForFile . "." . $imageFileType;

        if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFile)) {
            $stmt = $conn->prepare("UPDATE product SET image_name = ? WHERE id = ?");
            $stmt->bind_param("ss", $productNameForDatabase, $productId);
            $stmt->execute();

            header("Location: produk.php?success=Sukses menambahkan produk");
        } else {
            header("Location: produk.php?error=Unknown error.");
        }
    }
} else {
    header("Location: produk.php?error=Unknown error.");
}

$stmt->close();
$conn->close();

function guidv4($data = null)
{
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
