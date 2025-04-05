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
    <title>Analizė</title>
    <link rel="stylesheet" href="../../css/mdb.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <script defer src="../../js/bootstrap.bundle.min.js"></script>
    <script defer src="../../js/header.js"></script>
</head>
<body>
    <div class="container-md min-vh-100">
        <div class="row my-5">
            <form>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="email" class="form-label">Pavadinimas</label>
                        <input type="email" class="form-control" id="email" placeholder="Pvz.: Arduino UNO R3">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="select" class="form-label">Vieta</label>
                        <select class="form-select" id="select" aria-label="Location select">
                            <option selected>--Pasirinkti vietą--</option>
                            <option value="1">205-1</option>
                            <option value="2">205-2</option>
                            <option value="3">215-1</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3">
                        <label for="textArea" class="form-label"></label>
                        <textarea class="form-control" id="textArea" rows="5"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php include '../../includes/footer_admin.php'; ?>