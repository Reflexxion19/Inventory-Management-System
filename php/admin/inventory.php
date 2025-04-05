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
    <title>Inventorius</title>
    <link rel="stylesheet" href="../../css/mdb.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <script defer src="../../js/bootstrap.bundle.min.js"></script>
    <script defer src="../../js/mdb.umd.min.js"></script>
    <script defer src="../../js/header.js"></script>
</head>
<body>
    <div class="container-md min-vh-100">
        <div class="row my-5">
            <div class="col">
                <div class="col-1 border border-4 rounded border-primary d-flex justify-content-center">
                    <a href="add_inventory.php">
                        <i class="bi bi-plus text-primary" style="font-size: 70px;"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row my-3 d-flex justify-content-end">
            <div class="col-12">
                <div class="input-group">
                    <div class="form-outline" data-mdb-input-init>
                        <input type="search" id="form1" class="form-control" />
                        <label class="form-label" for="form1">Ieškoti</label>
                    </div>
                    <button type="button" class="btn btn-primary" data-mdb-ripple-init>
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Pavadinimas</th>
                            <th scope="col" class="col-2">Statusas</th>
                            <th scope="col" class="col-3">Veiksmai</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <tr>
                            <td>Name1</td>
                            <td>PASISKOLINTAS</td>
                            <td>
                                <button type="button" class="btn btn-primary">REDAGUOTI</button>
                                <button type="button" class="btn btn-danger"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>Name2</td>
                            <td>LAISVAS</td>
                            <td>
                                <button type="button" class="btn btn-primary">REDAGUOTI</button>
                                <button type="button" class="btn btn-danger"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>Name3</td>
                            <td>PASISKOLINTAS</td>
                            <td>
                                <button type="button" class="btn btn-primary">REDAGUOTI</button>
                                <button type="button" class="btn btn-danger"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<?php include '../../includes/footer_admin.php'; ?>