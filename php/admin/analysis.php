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

$current_date_parsed = explode("-", date("Y-m-d"));
$current_year = (int)$current_date_parsed[0];

$year = $current_year;
if(isset($_GET['year'])){ $year = (int)$_GET['year']; }
$monthlyLoans = calculate_year_loans_by_month($year);

$loanYears = loan_years();

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
    <script defer src="../../js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="../../js/header.js"></script>
</head>
<body>
    
    <div class="container-md min-vh-100">
        <div class="row mt-5 mb-1">
            <div class="col-2">
                <select class="form-select" id="year_select" aria-label="Year select" onchange="year_select()">
                <?php 
                    for($i = $loanYears[0]; $i <= $loanYears[1]; $i++){
                ?>
                    <option <?php if($i === $year){echo 'selected';} ?> value="<?= $i ?>"><?= $i ?></option>
                <?php
                    }
                ?>
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-center w-100" style="height:75vh">
            <canvas id="acquisitions"></canvas>
        </div>
    </div>

    <script>
        function year_select(){
            const select = document.querySelector('select');
            const year = select.value;
            window.location.href = `analysis.php?year=${year}`;
        }
    </script>
    <script>
        const year = <?php echo json_encode($year); ?>;
        const monthlyLoans = <?php echo json_encode($monthlyLoans); ?>;
    </script>
    <script type="module" src="charts/monthly_loans.js"></script>
</body>
</html>

<?php include '../../includes/footer_admin.php'; ?>