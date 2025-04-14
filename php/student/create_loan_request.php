<?php

session_save_path("/tmp");
session_start();
if(!isset($_SESSION['email'])) {
    header("Location: ../../index.php");
    exit();
}

if($_SESSION['role'] != 'student'){
    header("Location: ../../index.php");
    exit();
}

?>

<?php include '../../includes/header_student.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sukurti Paskolos Prašymą</title>
    <link rel="stylesheet" href="../../css/mdb.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <script defer src="../../js/bootstrap.bundle.min.js"></script>
    <script defer src="../../js/mdb.umd.min.js"></script>
    <script defer src="../../js/header.js"></script>
</head>
<body>
    <div class="container-md min-vh-100">
        <div class="row mt-5">
            <div class="col-3 mb-3">
                <label for="full_name" class="form-label">Vardas Pavardė</label>
                <input type="text" class="form-control" id="full_name" placeholder="Vardenis Pavardenis">
            </div>
            <div class="col-3 mb-3">
                <label for="academic_group" class="form-label">Akademinė grupė</label>
                <input type="text" class="form-control" id="academic_group" placeholder="IFB-1">
            </div>
            <div class="col-6 mb-3">
                <div class="row">
                    <div class="col d-flex">
                        <label class="form-label" for="start_date">Pradžios data</label>
                    </div>
                    <div class="col-1 d-flex">
                    </div>
                    <div class="col d-flex">
                        <label class="form-label" for="end_date">Pabaigos data</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <input type="date" id="start_date" class="form-control" name="trip-start" min="2025-01-01"/>
                    </div>
                    <div class="col-1 d-flex justify-content-center align-items-center">
                        <i class="bi bi-dash"></i>
                    </div>
                    <div class="col">
                        <input type="date" id="end_date" class="form-control" name="trip-start" min="2025-01-01"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="mb-3">
                <label for="textArea" class="form-label">Inventoriaus vienetas</label>
                <input type="text" class="form-control" id="date_end" placeholder="Arduino UNO R3">
            </div>
        </div>
        <div class="row">
            <div class="mb-3">
                <label for="textArea" class="form-label">Papildomi komentarai</label>
                <textarea class="form-control" id="textArea" rows="3"></textarea>
            </div>
        </div>
    </div>
</body>
</html>

<?php include '../../includes/footer_student.php'; ?>