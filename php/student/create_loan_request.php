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

if(isset($_POST['submit'])){
    $inventory_id = $_POST['inventory'];
    $start_date = $_POST['start-date'];
    $end_date = $_POST['end-date'];
    $comments = $_POST['additional-comments'];

    createApplication($inventory_id, $start_date, $end_date, $comments);
}

$result = displayInventory();

?>

<?php include '../../includes/header_student.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sukurti Panaudos Prašymą</title>
    <link rel="stylesheet" href="../../css/mdb.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../css/dropdown_search.css">
    <script defer src="../../js/bootstrap.bundle.min.js"></script>
    <script defer src="../../js/mdb.umd.min.js"></script>
    <script defer src="../../js/header.js"></script>
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
    }
    ?>

    <?php 
    if($_SESSION['error_message'] != ""){
    ?>
        <div class="mt-3 alert alert-danger" role="alert">
        <?= $_SESSION['error_message'] ?>
        </div>
    <?php
    }
    ?>
        <div class="row <?php echo ($_SESSION['success_message'] === "" && $_SESSION['error_message'] === "") ? "mt-5" : "" ?> mb-3 d-flex justify-content-end">
            <form method="post">
                <div class="row">
                    <div class="col-3 mb-3">
                        <label for="full-name" class="form-label">Vardas Pavardė</label>
                        <input type="text" class="form-control" id="full-name" name="full-name" value="<?= $_SESSION['name'] ?>" disabled/>
                    </div>
                    <div class="col-3 mb-3">
                        <label for="academic-group" class="form-label">Akademinė grupė</label>
                        <input type="text" class="form-control" id="academic-group" name="academic-group" value="<?= $_SESSION['academic_group'] ?>" disabled/>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="row">
                            <div class="col d-flex">
                                <label class="form-label" for="start-date">Pradžios data</label>
                            </div>
                            <div class="col-1 d-flex">
                            </div>
                            <div class="col d-flex">
                                <label class="form-label" for="end-date">Pabaigos data</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <input type="date" id="start-date" class="form-control" name="start-date" min="2025-01-01"/>
                            </div>
                            <div class="col-1 d-flex justify-content-center align-items-center">
                                <i class="bi bi-dash"></i>
                            </div>
                            <div class="col">
                                <input type="date" id="end-date" class="form-control" name="end-date" min="2025-01-01"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
                        <label for="inventory" class="form-label">Inventoriaus vienetas</label>
                        <select class="form-select" id="inventory" name="inventory">
                            <option value="">Pasirinkite inventorių</option>
                        <?php
                        while($row = mysqli_fetch_assoc($result)){
                        ?>
                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                        <?php
                        }
                        $_SESSION['success_message'] = "";
                        $_SESSION['error_message'] = "";
                        ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
                        <label for="textArea" class="form-label">Papildomi komentarai</label>
                        <textarea class="form-control" id="textArea" name="additional-comments" rows="3"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col d-flex justify-content-end">
                        <button type="submit" class="btn btn-success mx-1" name="submit">PATEIKTI</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="../../js/dselect.js"></script>
    <script>
        var select_box_element = document.querySelector('#inventory');

        dselect(select_box_element, {
            search: true
        });
    </script>
</body>
</html>

<?php include '../../includes/footer_student.php'; ?>