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

$storage_id = -1;
$row = array();

if (isset($_GET['storage_id'])) {
    $storage_id = $_GET['storage_id'];
    $row = getStorageById($storage_id);
}

if (isset($_POST['update_storage'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];

    updateStorage($name, $description, $storage_id);
}

if (isset($_POST['delete_storage'])) {
    deleteStorage($storage_id, $row['name']);
}

$path = "../../images/qr_codes/";

?>

<?php include '../../includes/header_admin.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talpyklos Informacija</title>
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
            <form id="form" method="post">
                <div class="row">
                    <div class="col mb-3">
                        <label for="storage" class="form-label">Pavadinimas</label>
                        <input type="text" class="form-control" id="storage" name="name" placeholder="Pvz.: Arduino UNO R3" value="<?= $row['name'] ?>" required disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <label for="description" class="form-label">Aprašymas</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required disabled><?= $row['description'] ?></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col d-flex justify-content-end">
                        <button type="button" class="btn btn-warning mx-1" name="edit_storage" onclick="enableFields(true)">Redaguoti</button>
                        <button type="submit" class="btn btn-success mx-1" name="update_storage" style="display: none;">Atnaujinti</button>
                        <button type="button" class="btn btn-warning mx-1" name="cancel_storage" style="display: none;" onclick="enableFields(false)">Atšaukti</button>
                        <button type="submit" class="btn btn-danger ms-1" name="delete_storage"><i class="bi bi-trash"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        function enableFields(option) {
            input = document.getElementById("storage");
            textArea = document.getElementById("description");

            input.disabled = !option;
            textArea.disabled = !option;

            if(option) {
                document.getElementsByName("edit_storage")[0].style.display = "none";
                document.getElementsByName("update_storage")[0].style.display = "unset";
                document.getElementsByName("cancel_storage")[0].style.display = "unset";
            } else {
                document.getElementsByName("edit_storage")[0].style.display = "unset";
                document.getElementsByName("update_storage")[0].style.display = "none";
                document.getElementsByName("cancel_storage")[0].style.display = "none";
            }
        }
    </script>
</body>
</html>

<?php include '../../includes/footer_admin.php'; ?>