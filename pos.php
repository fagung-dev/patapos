<?php
session_start();

// Cek apakah sesi sudah dimulai
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';
$query = 'SELECT * FROM product ORDER BY product_name ASC';
$result = $conn->query($query);

$menu_list = [];
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $menu_list[] = $row;
    }
} else {
    echo "0 results";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Point of Sale</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="bootstrap/sweetalert.css" rel="stylesheet" type="text/css" />
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="bootstrap/sweetalert.js"></script>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>

<body>
    <div class="row fixed-top" style="background-color: #212529; text-align: center; padding: 10px 0px 10px 0px">
        <div class="col-md-1">
            <a href="index.php" class="btn btn-primary">
                Back
            </a>
        </div>
        <div class="col-md-11">
            <marquee scrollamount="10">
                <h4 style="font-weight: 600; margin-bottom: 0; color: #f8f8f8">
                    PATAPOS
                </h4>
            </marquee>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9 p-3" style="margin-top: 48px;">
                <div class="row">
                    <?php foreach ($menu_list as $menu) : ?>
                        <div key="<?php echo $menu['id']; ?>" class="card menu col-md-3" style="width: 18rem; padding: 0px;">
                            <img src="images/<?php echo $menu['image_name'] ?>" class="card-img-top" alt="Food Image" style="width: 100%; height: 15vw; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $menu['product_name'] ?></h5>
                                <p class="card-text">Rp. <?php echo number_format($menu['price']); ?></p>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-primary" onclick="kurangiQty('<?php echo $menu['id']; ?>')" style="border-radius: 5px 0px 0px 5px">-</button>
                                    </div>
                                    <input type="text" class="form-control" id="qty-<?php echo $menu['id']; ?>" value="1" style="text-align: center;">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" onclick="tambahQty('<?php echo $menu['id']; ?>')" style="border-radius: 0px 5px 5px 0px">+</button>
                                    </div>
                                </div>
                                <a href="#" class="btn btn-primary" style="width: 100%;" onclick="showDetails(<?php echo htmlspecialchars(json_encode($menu)); ?>)">Pilih</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-md-3 position-fixed" style="background-color: #dedede; height: 100vh; top: 48px; right: 0">
                <div id="keranjang">
                    <h3 style="margin-top: 10px;">Keranjang</h3>
                    <hr />
                    <ul id="keranjang-list" style="list-style: none;">
                    </ul>
                </div>
                <div id="summary" class="position-fixed" style="width: 24%; height: 130px; bottom: 0;">
                    <div style="padding: 10px" class="d-flex justify-content-between align-items-center">
                        <div>
                            Total:
                        </div>
                        <div>
                            <input type="hidden" id="totalBelanja" value="0" />
                            <span style="font-size: 24px; font-weight: 600" id="totalBelanjaCurrencies">Rp. 0</span>
                        </div>
                    </div>
                    <div style="text-align: center; padding: 10px;">
                        <button id="buttonOrder" class="btn btn-primary" style="width: 90%;" disabled onclick="createOrder()">Place Order</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
</body>

</html>