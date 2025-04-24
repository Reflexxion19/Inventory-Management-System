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

if(isset($_POST['start_date']) && isset($_POST['end_date']) && isset($_POST['inventory']) && isset($_POST['comments'])){
    $application_id = $_POST['application_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $inventory_id = $_POST['inventory'];
    $comments = $_POST['comments'];

    updateRequest($application_id, $start_date, $end_date, $inventory_id, $comments);
} elseif(isset($_POST['application_id'])){
    cancelRequest($_POST['application_id']);
}

$result_inventory = displayInventory();
$inventory_array = [];
$j = 0;
while($row_inventory = mysqli_fetch_assoc($result_inventory)){
    $inventory_array[$j++] = ['id' => $row_inventory['id'], 'name' => $row_inventory['name']];
}
$inventory_count = count($inventory_array);
$result_requests = display_student_loan_requests();
$collapse_count = 0;
$input_count = 0;
$date_input_count1 = 0;
$date_input_count2 = 0;
$select_count = 0;
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../css/dropdown_search.css">
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
                        <input type="search" id="search-box" class="form-control" onkeyup="search()"/>
                        <label class="form-label" for="search-box">Ieškoti</label>
                    </div>
                    <button type="button" class="btn btn-success mx-1" onClick="document.location.href='create_loan_request.php'">SUKURTI PRAŠYMĄ</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="accordion" id="accordion">
            <?php
            while($row = mysqli_fetch_assoc($result_requests)){
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
                                    <input type="text" class="form-control" id="text<?= $input_count++ ?>" value="<?= $row['student_name'] ?>" disabled/>
                                </div>
                                <div class="col-3 mb-3">
                                    <label for="text<?= $input_count ?>" class="form-label">Akademinė grupė</label>
                                    <input type="text" class="form-control" id="text<?= $input_count++ ?>" value="<?= $row['student_group'] ?>" disabled/>
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
                                            <input type="date" class="form-control start-date" id="date_start<?= $date_input_count1++ ?>" min="2025-01-01" value="<?= $row['start_date'] ?>"/>
                                        </div>
                                        <div class="col-1 d-flex justify-content-center align-items-center">
                                            <i class="bi bi-dash"></i>
                                        </div>
                                        <div class="col">
                                            <input type="date" class="form-control end-date" id="date_end<?= $date_input_count2++ ?>" min="2025-01-01" value="<?= $row['end_date'] ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <label for="select<?= $select_count ?>" class="form-label">Inventoriaus vienetas</label>
                                    <select class="form-select inventory" id="select<?= $select_count++ ?>" name="inventory">
                                        <option value="">Pasirinkite inventorių</option>
                                 <?php
                                    $result_inventory = displayInventory();
                                    foreach($inventory_array as $inventory){
                                    ?>
                                        <option <?php echo ($row['fk_inventory_id'] === $inventory['id']) ? "selected" : "" ?> 
                                        value="<?= $inventory['id'] ?>"><?= $inventory['name'] ?></option>
                                    <?php
                                    }
                                    ?>
                                    </select>
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
                                    <button type="button" class="btn btn-warning mx-1" onclick="update()">ATNAUJINTI</button>
                                    <button type="button" class="btn btn-danger mx-1" onclick="remove()">ATŠAUKTI</button>
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
    <script src="../../js/dselect.js"></script>
    <script>
        var select_box_element = document.querySelectorAll('.inventory');

        select_box_element.forEach(element => {
            dselect(element, {
                search: true
            });
        });
    </script>
    <script>
        function update() {
            const accordion_item = event.target.closest(".accordion-item");

            form = document.createElement("form");
            form.method = "POST";
            form.action = "student_loan_requests.php";

            input_application_id = document.createElement("input");
            input_application_id.type = "hidden";
            input_application_id.name = "application_id";
            input_application_id.value = accordion_item.dataset.id;

            input_start_date = document.createElement("input");
            input_start_date.type = "hidden";
            input_start_date.name = "start_date";
            input_start_date.value = accordion_item.querySelector('.start-date').value;

            input_end_date = document.createElement("input");
            input_end_date.type = "hidden";
            input_end_date.name = "end_date";
            input_end_date.value = accordion_item.querySelector('.end-date').value;

            input_inventory = document.createElement("input");
            input_inventory.type = "hidden";
            input_inventory.name = "inventory";
            input_inventory.value = accordion_item.querySelector('.inventory').value;

            input_comments = document.createElement("input");
            input_comments.type = "hidden";
            input_comments.name = "comments";
            input_comments.value = accordion_item.querySelector('textarea').value;

            form.appendChild(input_application_id);
            form.appendChild(input_start_date);
            form.appendChild(input_end_date);
            form.appendChild(input_inventory);
            form.appendChild(input_comments);

            document.body.appendChild(form);
            form.submit();
        }

        function remove() {
            const accordion_item = event.target.closest(".accordion-item");

            form = document.createElement("form");
            form.method = "POST";
            form.action = "student_loan_requests.php";

            input_id = document.createElement("input");
            input_id.type = "hidden";
            input_id.name = "application_id";
            input_id.value = accordion_item.dataset.id;

            form.appendChild(input_id);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>

<?php include '../../includes/footer_student.php'; ?>