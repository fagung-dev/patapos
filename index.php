<?php
session_start();

// Cek apakah sesi sudah dimulai
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <title>PataPOS</title>
</head>

<body class="bg-light">

    <div class="container-fluid">
        <div class="row">

            <nav class="col-md-2 d-none d-md-block bg-dark sidebar">
                <?php include 'navbar.php' ?>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <?php include 'dashboard.php' ?>
            </main>

        </div>
    </div>

</body>

</html>