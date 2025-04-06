<?php

session_save_path("/tmp");
session_start();
if(!isset($_SESSION['email'])) {
    header("Location: ../../index.php");
    exit();
}

if($_SESSION['role'] != 'admin'){
    header("Location: ../../index.php");
    exit();
}

?>

<?php include '../../includes/header_admin.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analizė</title>
    <link rel="stylesheet" href="../../css/mdb.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <script defer src="../../js/bootstrap.bundle.min.js"></script>
    <script defer src="../../js/header.js"></script>
</head>
<body>
    
    <div class="container-md min-vh-100">
        <div class="d-flex justify-content-center w-100" style="height:75vh">
            <canvas class="my-5" id="acquisitions"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="module" src="charts/monthly_loans.js"></script>
</body>
</html>

<?php include '../../includes/footer_admin.php'; ?>