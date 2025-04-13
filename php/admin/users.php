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

if (isset($_POST['user_id'])) {
    changeRole($_POST['user_id'], $_POST['user_role']);
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
    <?php 
    if($_SESSION['success_message'] != ""){
    ?>
        <div class="mt-3 alert alert-success" role="alert">
            <?= $_SESSION['success_message'] ?>
        </div>
    <?php
    $_SESSION['success_message'] = "";
    }
    ?>

    <?php 
    if($_SESSION['error_message'] != ""){
    ?>
        <div class="mt-3 alert alert-danger" role="alert">
        <?= $_SESSION['error_message'] ?>
        </div>
    <?php
    $_SESSION['error_message'] = "";
    }
    ?>
        <div class="row <?php echo ($_SESSION['success_message'] === "" && $_SESSION['error_message'] === "") ? "mt-5" : "" ?> mb-3 d-flex justify-content-end">
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
                        <tr data-id="<?= $row['id'] ?>">
                            <td><?= $row['name']; ?></td>
                            <td>
                                <div class="row">
                                    <div class="col-7">
                                        <select class="form-select" aria-label="Role select" name="role_select">
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
                                        <button type="button" class="btn btn-danger" name="change_role" onclick="changeRole()">Keisti rolę</button>
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
    <script>
        function changeRole() {
            const row = event.target.closest("tr");
            const cells = row.getElementsByTagName("td");

            form = document.createElement("form");
            form.method = "POST";
            form.action = "users.php";

            input_id = document.createElement("input");
            input_id.type = "hidden";
            input_id.name = "user_id";
            input_id.value = row.dataset.id;

            input_role = document.createElement("input");
            input_role.type = "hidden";
            input_role.name = "user_role";
            input_role.value = cells[1].getElementsByTagName("select")[0].value;

            form.appendChild(input_id);
            form.appendChild(input_role);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>

<?php include '../../includes/footer_admin.php'; ?>