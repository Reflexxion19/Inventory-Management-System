<?php

session_save_path("/tmp");
session_start();
require_once '../../config/functions.php';

if(!isset($_SESSION['email'])) {
    header("Location: ../../index.php");
    exit();
}

if($_SESSION['role'] != 'admin'){
    header("Location: ../../index.php");
    exit();
}

if (isset($_POST['add_storage'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];

    addStorage($name, $description);
}

?>

<?php include '../../includes/header_admin.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pridėti Inventoriaus Talpyklą</title>
    <link rel="stylesheet" href="../../css/mdb.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <script defer src="../../js/bootstrap.bundle.min.js"></script>
    <script defer src="../../js/header.js"></script>
</head>
<body>
    <div class="container-md min-vh-100">
    <?php 
    if($_SESSION['error_message'] !== ""){
    ?>
        <div class="mt-3 alert alert-danger" role="alert">
        <?= $_SESSION['error_message'] ?>
        </div>
    <?php
    }
    ?>
        <div class="row <?php echo ($_SESSION['error_message'] === "") ? "mt-5" : ""; $_SESSION['error_message'] = ""; ?>">
            <form method="post">
                <div class="row">
                    <div class="col mb-3">
                        <label for="inventory" class="form-label">Pavadinimas</label>
                        <input type="text" class="form-control" id="inventory" name="name" placeholder="Pvz.: 208-1" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="description" class="form-label">Aprašymas</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col d-flex justify-content-end">
                        <button type="submit" class="btn btn-success" name="add_storage">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<?php include '../../includes/footer_admin.php'; ?>