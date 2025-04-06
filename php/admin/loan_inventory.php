<?php

session_save_path("/tmp");
session_start();
if(!isset($_SESSION['email'])) {
    header("Location: ../../index.php");
    exit();
}

if($_SESSION['role'] != 'admin'){
    header("Location: ../../index.php");
    exit();
}

?>

<?php include '../../includes/header_admin.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasiskolinti Inventorių</title>
    <link rel="stylesheet" href="../../css/mdb.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <script defer src="../../js/bootstrap.bundle.min.js"></script>
    <script defer src="../../js/header.js"></script>
</head>
<body>
    <div class="container-md min-vh-100">
        <div class="row my-5">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active border-primary border-2" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Atrakinti Talpyklą</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link border-primary border-2" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Pasiskolinti Inventorių</button>
                </li>
            </ul>
            <div class="tab-content border border-2 rounded-bottom border-primary" id="myTabContent">
                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                    <div class="row my-5 d-flex justify-content-center">
                        <div class="col-6 mb-3">
                            <h3 class="d-flex justify-content-center">Atrakinti Talpyklą</h3>

                            <label for="identification_code" class="form-label">Identifikacinis kodas</label>
                            <input type="email" class="form-control mb-3" id="identification_code" placeholder="Pvz.: 321654898798756654">

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Atrakinti</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                    <div class="row my-5 d-flex justify-content-center">
                        <div class="col-6 mb-3">
                            <h3 class="d-flex justify-content-center">Pasiskolinti Inventorių</h3>


                            <label for="identification_code" class="form-label">Identifikacinis kodas</label>
                            <input type="email" class="form-control mb-3" id="identification_code" placeholder="Pvz.: 321654898798756654">

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Pasiskolinti</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php include '../../includes/footer_admin.php'; ?>