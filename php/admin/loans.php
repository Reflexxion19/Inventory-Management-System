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
    <title>Paskolos</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <script defer src="../../js/bootstrap.bundle.min.js"></script>
    <script defer src="../../js/header.js"></script>
</head>
<body>
    <div class="container-md min-vh-100">
        <div class="row my-5">
            <div class="col">
                <div class="col-1 border border-4 rounded border-primary d-flex justify-content-center">
                    <a href="loan_inventory.php">
                        <i class="bi bi-plus text-primary" style="font-size: 70px;"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Pavadinimas</th>
                            <th scope="col" class="col-1">Veiksmai</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <tr>
                            <td>Name1</td>
                            <td>
                                <button type="button" onClick="document.location.href='return_inventory.php'" class="btn btn-primary">GRĄŽINTI</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Name2</td>
                            <td>
                                <button type="button" onClick="document.location.href='return_inventory.php'" class="btn btn-primary">GRĄŽINTI</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Name3</td>
                            <td>
                                <button type="button" onClick="document.location.href='return_inventory.php'" class="btn btn-primary">GRĄŽINTI</button>
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