<?php

session_save_path("/tmp");
session_start();
require_once '../../config/functions.php';

if(!isset($_SESSION['email'])) {
    header("Location: ../../index.php");
    exit();
}

if($_SESSION['role'] != 'student'){
    header("Location: ../../index.php");
    exit();
}

$result = display_loans();

?>

<?php include '../../includes/header_student.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paskolos</title>
    <link rel="stylesheet" href="../../css/mdb.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <script defer src="../../js/bootstrap.bundle.min.js"></script>
    <script defer src="../../js/mdb.umd.min.js"></script>
    <script defer src="../../js/header.js"></script>
    <script defer src="../../js/search_table_1.js"></script>
</head>
<body>
<div class="container-md min-vh-100">
    <div class="row mt-5 mb-3 d-flex justify-content-end">
            <div class="col-12">
                <div class="input-group">
                    <div class="form-outline" data-mdb-input-init>
                        <input type="search" id="search-box" class="form-control" onkeyup="myFunction()"/>
                        <label class="form-label" for="form1">Ieškoti</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <table class="table" id="table">
                    <thead>
                        <tr>
                            <th scope="col">Pavadinimas</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                    <?php
                    while($row = mysqli_fetch_assoc($result)){
                    ?>
                        <tr>
                            <td><?= $row['name']; ?></td>
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

<?php include '../../includes/footer_student.php'; ?>