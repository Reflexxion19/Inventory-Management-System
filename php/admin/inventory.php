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

$result = display_inventory();

?>

<?php include '../../includes/header_admin.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventorius</title>
    <link rel="stylesheet" href="../../css/mdb.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <script defer src="../../js/bootstrap.bundle.min.js"></script>
    <script defer src="../../js/mdb.umd.min.js"></script>
    <script defer src="../../js/header.js"></script>
    <script defer src="../../js/search_table_2.js"></script>
</head>
<body>
    <div class="container-md min-vh-100">
        <div class="row mt-5 mb-3 d-flex justify-content-end">
            <div class="col-12">
                <div class="input-group">
                    <div class="form-outline" data-mdb-input-init>
                        <input type="search" id="search-box" class="form-control" onkeyup="myFunction()"/>
                        <label class="form-label" for="search-box">Ieškoti</label>
                    </div>
                    <button type="button" class="btn btn-success mx-1" onClick="document.location.href='add_inventory.php'">PRIDĖTI INVENTORIŲ</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <table class="table" id="table">
                    <thead>
                        <tr>
                            <th scope="col">Pavadinimas</th>
                            <th scope="col" class="col-2">Statusas</th>
                            <th scope="col" class="col-1">Veiksmai</th>
                            <th scope="col" class="col-1"></th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                    <?php
                    while($row = mysqli_fetch_assoc($result)){
                    ?>
                        <tr>
                            <td><?= $row['name']; ?></td>
                    <?php
                        if(check_inventory_availability($row['id'])){
                    ?>
                            <td>LAISVAS</td>
                    <?php
                        } else {
                    ?>
                            <td>PASISKOLINTAS</td>
                    <?php
                        }
                    ?>
                            <td><button type="button" class="btn btn-warning">REDAGUOTI</button></td>
                            <td><button type="button" class="btn btn-danger"><i class="bi bi-trash"></i></button></td>
                        </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<?php include '../../includes/footer_admin.php'; ?>