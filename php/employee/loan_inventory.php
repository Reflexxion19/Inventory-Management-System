<?php

session_save_path("/tmp");
session_start();

if(!isset($_SESSION['email'])) {
    header("Location: ../../index.php");
    exit();
}

if($_SESSION['role'] != 'employee'){
    header("Location: ../../index.php");
    exit();
}

$tab1 = "storage-tab";
$tab2 = "loan-tab";
$reader1 = "reader-storage";
$reader2 = "reader-loan";
$input1 = "identification_code_storage";
$input2 = "identification_code_loan";

?>

<?php include '../../includes/header_employee.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasiskolinti Inventorių</title>
    <link rel="stylesheet" href="../../css/mdb.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <script defer src="../../js/bootstrap.bundle.min.js"></script>
    <script defer src="../../js/header.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
    <script defer src="../../js/qr_barcode_reader.js"></script>
</head>
<body>
    <div class="container-md min-vh-100">
        <div class="row my-5">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active border-primary border-2" id="storage-tab" data-bs-toggle="tab" data-bs-target="#storage-tab-pane" type="button" role="tab" aria-controls="storage-tab-pane" aria-selected="true">Atrakinti Talpyklą</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link border-primary border-2" id="loan-tab" data-bs-toggle="tab" data-bs-target="#loan-tab-pane" type="button" role="tab" aria-controls="loan-tab-pane" aria-selected="false">Pasiskolinti Inventorių</button>
                </li>
            </ul>
            <div class="tab-content border border-2 rounded-bottom border-primary" id="myTabContent">
                <div class="tab-pane fade show active" id="storage-tab-pane" role="tabpanel" aria-labelledby="storage-tab" tabindex="0">
                    <div class="row my-5 d-flex justify-content-center">
                        <div class="col-12 mb-3">
                            <h3 class="d-flex justify-content-center">Atrakinti Talpyklą</h3>

                            <div class="my-3" id="reader-storage"></div>

                            <label for="identification_code_storage" class="form-label">Identifikacinis kodas</label>
                            <input type="text" class="form-control mb-3" id="identification_code_storage" placeholder="Pvz.: 321654898798756654">

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Atrakinti</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="loan-tab-pane" role="tabpanel" aria-labelledby="loan-tab" tabindex="0">
                    <div class="row my-5 d-flex justify-content-center">
                        <div class="col-12 mb-3">
                            <h3 class="d-flex justify-content-center">Pasiskolinti Inventorių</h3>

                            <div class="my-3" id="reader-loan"></div>

                            <label for="identification_code_loan" class="form-label">Identifikacinis kodas</label>
                            <input type="text" class="form-control mb-3" id="identification_code_loan" placeholder="Pvz.: 321654898798756654">

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Pasiskolinti</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const tab1 = <?php echo json_encode($tab1); ?>;
        const tab2 = <?php echo json_encode($tab2); ?>;
        const reader1 = <?php echo json_encode($reader1); ?>;
        const reader2 = <?php echo json_encode($reader2); ?>;
        const input1 = <?php echo json_encode($input1); ?>;
        const input2 = <?php echo json_encode($input2); ?>;
    </script>
</body>
</html>

<?php include '../../includes/footer_employee.php'; ?>