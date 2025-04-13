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

if (isset($_POST['request_approve'])) {
    approveRequest($_POST['request_id']);
}

if (isset($_POST['request_reject'])) {
    rejectRequest($_POST['request_id']);
}

if (isset($_POST['request_feedback'])) {
    addFeedback($_POST['request_id']);
}

$result = display_loan_requests();
$collapse_count = 0;
$input_count = 0;
$date_input_count1 = 0;
$date_input_count2 = 0;
$expanded_check = true;

?>

<?php include '../../includes/header_admin.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paskolos Prašymai</title>
    <link rel="stylesheet" href="../../css/mdb.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <script defer src="../../js/bootstrap.bundle.min.js"></script>
    <script defer src="../../js/mdb.umd.min.js"></script>
    <script defer src="../../js/header.js"></script>
    <script defer src="../../js/search_accordion.js"></script>
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
            <div class="col-12">
                <div class="input-group">
                    <div class="form-outline" data-mdb-input-init>
                        <input type="search" id="search-box" class="form-control" onkeyup="myFunction()"/>
                        <label class="form-label" for="search-box">Ieškoti</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="accordion" id="accordion">
            <?php
            while($row = mysqli_fetch_assoc($result)){
            ?>
                <div class="accordion-item" data-id="<?= $row['id'] ?>">
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
                                    <input type="text" class="form-control" id="text<?= $input_count++ ?>" 
                                    placeholder="<?= $row['student_name'] ?>" disabled>
                                </div>
                                <div class="col-3 mb-3">
                                    <label for="text<?= $input_count ?>" class="form-label">Akademinė grupė</label>
                                    <input type="text" class="form-control" id="text<?= $input_count++ ?>" 
                                    placeholder="<?= $row['student_group'] ?>" disabled>
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
                                            <input type="text" class="form-control" id="date_start<?= $date_input_count1++ ?>" 
                                            placeholder="<?= $row['start_date'] ?>" disabled>
                                        </div>
                                        <div class="col-1 d-flex justify-content-center align-items-center">
                                            <i class="bi bi-dash"></i>
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" id="date_end<?= $date_input_count2++ ?>" 
                                            placeholder="<?= $row['end_date'] ?>" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <label for="text<?= $input_count ?>" class="form-label">Inventoriaus vienetas</label>
                                    <input type="text" class="form-control" id="text<?= $input_count++ ?>" 
                                    placeholder="<?= $row['inventory_name'] ?>" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <label for="textArea<?= $input_count ?>" class="form-label">Papildomi komentarai</label>
                                    <textarea class="form-control" id="textArea<?= $input_count++ ?>" style="color: #776F6F;" rows="3" 
                                    disabled><?= $row['additional_comments'] ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <label for="textArea<?= $input_count ?>" class="form-label">Pastabos</label>
                                    <textarea class="form-control" id="textArea<?= $input_count++ ?>" 
                                    rows="3"><?= $row['feedback'] ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col d-flex justify-content-end">
                                    <button type="button" class="btn btn-success mx-1" onclick="approve()">PATVIRTINTI</button>
                                    <button type="button" class="btn btn-danger mx-1" onclick="reject()">ATMESTI</button>
                                    <button type="button" class="btn btn-warning ms-1" onclick="addFeedback()">PATEIKTI PASTABĄ</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                $expanded_check = false;
            }
            $_SESSION['success_message'] = "";
            $_SESSION['error_message'] = "";
            ?>
            </div>
        </div>
    </div>
    <script>
        function approve() {
            const accordion_item = event.target.closest(".accordion-item");

            form = document.createElement("form");
            form.method = "POST";
            form.action = "loan_requests.php";

            input_approve = document.createElement("input");
            input_approve.type = "hidden";
            input_approve.name = "request_approve";
            input_approve.value = "";

            input_id = document.createElement("input");
            input_id.type = "hidden";
            input_id.name = "request_id";
            input_id.value = accordion_item.dataset.id;

            form.appendChild(input_approve);
            form.appendChild(input_id);

            document.body.appendChild(form);
            form.submit();
        }

        function reject() {
            const accordion_item = event.target.closest(".accordion-item");

            form = document.createElement("form");
            form.method = "POST";
            form.action = "loan_requests.php";

            input_reject = document.createElement("input");
            input_reject.type = "hidden";
            input_reject.name = "request_reject";
            input_reject.value = "";

            input_id = document.createElement("input");
            input_id.type = "hidden";
            input_id.name = "request_id";
            input_id.value = accordion_item.dataset.id;

            form.appendChild(input_reject);
            form.appendChild(input_id);

            document.body.appendChild(form);
            form.submit();
        }

        function addFeedback() {
            const accordion_item = event.target.closest(".accordion-item");
            const inputs = accordion_item.getElementsByTagName("input");
            const textAreas = accordion_item.getElementsByTagName("textarea");

            form = document.createElement("form");
            form.method = "POST";
            form.action = "loan_requests.php";

            input_feedback = document.createElement("input");
            input_feedback.type = "hidden";
            input_feedback.name = "request_feedback";
            input_feedback.value = "";

            input_id = document.createElement("input");
            input_id.type = "hidden";
            input_id.name = "request_id";
            input_id.value = accordion_item.dataset.id;

            form.appendChild(input_feedback);
            form.appendChild(input_id);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>

<?php include '../../includes/footer_admin.php'; ?>