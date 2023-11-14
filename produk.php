<?php
session_start();

// Cek apakah sesi sudah dimulai
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

if (isset($_GET['success'])) {
    $success = $_GET['success'];
}


if (isset($_GET['error'])) {
    $error = $_GET['error'];
}

$result = $conn->query("SELECT * FROM product ORDER BY created_at DESC");
$products = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="bootstrap/sweetalert.css" rel="stylesheet" type="text/css" />
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="bootstrap/sweetalert.js"></script>
    <script src="bootstrap/jquery.js"></script>
    <title>PataPOS</title>
</head>

<body class="bg-light">

    <div class="container-fluid">
        <div class="row">

            <nav class="col-md-2 d-none d-md-block bg-dark sidebar">
                <?php include 'navbar.php' ?>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <h2 class="mt-3">Product</h2>
                <hr class="hr">
                <?php if (isset($success)) : ?>
                    <div class="alert alert-success" role="alert">
                        <?= $success; ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($error)) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $error; ?>
                    </div>
                <?php endif; ?>
                <h2>Tambah Produk</h2>
                <form id="form_produk" action="add_product_procces.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="productName" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" id="productName" name="productName" required>
                    </div>

                    <div class="mb-3">
                        <label for="productPrice" class="form-label">Harga Produk</label>
                        <input type="number" class="form-control" id="productPrice" name="productPrice" required>
                    </div>

                    <div id="product_image" class="mb-3">
                        <label for="productImage" class="form-label">Gambar Produk</label>
                        <input type="file" class="form-control" id="productImage" name="productImage" accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-primary">Tambah Produk</button>
                </form>

                <br />
                <h2>Daftar Produk</h2>
                <div style="max-height: 400px; overflow: auto;">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th style="width: 600px;">ID</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product) : ?>
                                <tr>
                                    <td><?php echo $product['id']; ?></td>
                                    <td><?php echo $product['product_name']; ?></td>
                                    <td>Rp. <?php echo number_format($product['price']); ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" onclick="editProduct('<?php echo $product['id']; ?>')">Ubah</button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteProduct('<?php echo $product['id']; ?>')">Hapus</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>
<script src="script.js"></script>

</html>