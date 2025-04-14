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

$result = display_student_loan_requests();
$collapse_count = 0;
$input_count = 0;
$date_input_count1 = 0;
$date_input_count2 = 0;
$expanded_check = true;

?>

<?php include '../../includes/header_student.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paskolos Prašymai</title>
    <link rel="stylesheet" href="../../css/mdb.min.css">
    <script defer src="../../js/bootstrap.bundle.min.js"></script>
    <script defer src="../../js/mdb.umd.min.js"></script>
    <script defer src="../../js/header.js"></script>
    <script defer src="../../js/search_accordion.js"></script>
</head>
<body>
    <div class="container-md min-vh-100">
        <div class="row mt-5 mb-3 d-flex justify-content-end">
            <div class="col-12">
                <div class="input-group">
                    <div class="form-outline" data-mdb-input-init>
                        <input type="search" id="search-box" class="form-control" onkeyup="search()"/>
                        <label class="form-label" for="search-box">Ieškoti</label>
                    </div>
                    <button type="button" class="btn btn-success mx-1" onClick="document.location.href='create_loan_request.php'">SUKURTI PRAŠYMĄ</button>
                </div>
            </div>
        </div>

        <div class="row my-5">
            <div class="accordion" id="accordion">
            <?php
            while($row = mysqli_fetch_assoc($result)){
            ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button <?php if(!$expanded_check){echo 'collapsed';}?>" type="button" 
                        data-bs-toggle="collapse" data-bs-target="#collapse<?= $collapse_count ?>" 
                        aria-expanded="<?= $expanded_check ?>" aria-controls="collapse<?= $collapse_count ?>">
                        <?= $row['student_name'] . " " . $row['student_group'] . " : " . $row['inventory_name'] ?></button> 
                    </h2>
                    <div id="collapse<?= $collapse_count++ ?>" class="accordion-collapse collapse 
                    <?php if($expanded_check){echo 'show';}?>" data-bs-parent="#accordion">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-3 mb-3">
                                    <label for="text<?= $input_count ?>" class="form-label">Vardas Pavardė</label>
                                    <input type="text" class="form-control" id="text<?= $input_count++ ?>" value="<?= $row['student_name'] ?>"/>
                                </div>
                                <div class="col-3 mb-3">
                                    <label for="text<?= $input_count ?>" class="form-label">Akademinė grupė</label>
                                    <input type="text" class="form-control" id="text<?= $input_count++ ?>" value="<?= $row['student_group'] ?>"/>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="row">
                                        <div class="col d-flex">
                                            <label class="form-label" for="date_start<?= $date_input_count1 ?>">Pradžios data</label>
                                        </div>
                                        <div class="col-1 d-flex">
                                        </div>
                                        <div class="col d-flex">
                                            <label class="form-label" for="date_end<?= $date_input_count2 ?>">Pabaigos data</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <input type="date" class="form-control" id="date_start<?= $date_input_count1++ ?>" min="2025-01-01" value="<?= $row['start_date'] ?>"/>
                                        </div>
                                        <div class="col-1 d-flex justify-content-center align-items-center">
                                            <i class="bi bi-dash"></i>
                                        </div>
                                        <div class="col">
                                            <input type="date" class="form-control" id="date_end<?= $date_input_count2++ ?>" min="2025-01-01" value="<?= $row['end_date'] ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <label for="text<?= $input_count ?>" class="form-label">Inventoriaus vienetas</label>
                                    <input type="text" class="form-control" id="text<?= $input_count++ ?>" value="<?= $row['inventory_name'] ?>"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <label for="textArea<?= $input_count ?>" class="form-label">Papildomi komentarai</label>
                                    <textarea class="form-control" id="textArea<?= $input_count++ ?>" rows="3" 
                                    ><?= $row['additional_comments'] ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <label for="textArea<?= $input_count ?>" class="form-label">Pastabos</label>
                                    <textarea class="form-control" id="textArea<?= $input_count++ ?>" style="color: #776F6F;" rows="3" 
                                    disabled><?= $row['feedback'] ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col d-flex justify-content-end">
                                    <button type="button" class="btn btn-warning mx-1">ATNAUJINTI</button>
                                    <button type="button" class="btn btn-danger mx-1">ATMESTI</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                $expanded_check = false;
            }
            ?>
            </div>
        </div>
    </div>
</body>
</html>

<?php include '../../includes/footer_student.php'; ?>