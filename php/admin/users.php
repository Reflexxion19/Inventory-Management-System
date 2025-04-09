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

$result = display_users();

?>

<?php include '../../includes/header_admin.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Naudotojai</title>
    <link rel="stylesheet" href="../../css/mdb.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <script defer src="../../js/bootstrap.bundle.min.js"></script>
    <script defer src="../../js/mdb.umd.min.js"></script>
    <script defer src="../../js/header.js"></script>
    <script defer src="../../js/search_w_role.js"></script>
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
                            <th scope="col">Naudotojas</th>
                            <th scope="col" class="col-4">Rolė</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                    <?php
                    while($row = mysqli_fetch_assoc($result)){
                    ?>
                        <tr>
                            <td><?= $row['name']; ?></td>
                            <td>
                                <div class="row">
                                    <div class="col-7">
                                        <select class="form-select" aria-label="Role select">
                    <?php
                        if($row['role'] == 'admin'){
                    ?>
                                            <option selected value="admin">Administratorius</option>
                                            <option value="employee">Darbuotojas</option>
                                            <option value="student">Studentas</option>
                    <?php
                        } elseif($row['role'] == 'employee'){
                    ?>
                                            <option value="admin">Administratorius</option>
                                            <option selected value="employee">Darbuotojas</option>
                                            <option value="student">Studentas</option>
                    <?php
                        } elseif($row['role'] == 'student'){
                    ?>
                                            <option value="admin">Administratorius</option>
                                            <option value="employee">Darbuotojas</option>
                                            <option selected value="student">Studentas</option>
                    <?php
                        }
                    ?>
                                        </select>
                                    </div>
                                    <div class="col-5">
                                        <button type="button" class="btn btn-danger">Keisti rolę</button>
                                    </div>
                                </div>
                            </td>
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