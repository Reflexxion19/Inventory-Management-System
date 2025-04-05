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
    <title>Paskolos Prašymai</title>
    <link rel="stylesheet" href="../../css/mdb.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <script defer src="../../js/bootstrap.bundle.min.js"></script>
    <script defer src="../../js/header.js"></script>
</head>
<body>
    <div class="container-md min-vh-100">
        <div class="row my-5">
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Prašymas 1
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-3 mb-3">
                                    <label for="email" class="form-label">Vardas Pavardė</label>
                                    <input type="email" class="form-control" id="email" placeholder="Antanas Antanauskas" disabled>
                                </div>
                                <div class="col-3 mb-3">
                                    <label for="email" class="form-label">Akademinė grupė</label>
                                    <input type="email" class="form-control" id="email" placeholder="IFB-1" disabled>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="row">
                                        <div class="col d-flex justify-content-center">
                                            <label class="form-label">Laikotarpis</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <input type="text" class="form-control" id="date_start" placeholder="Pvz.: 2025/01/01" disabled>
                                        </div>
                                        <div class="col-1 d-flex justify-content-center align-items-center">
                                            <i class="bi bi-dash"></i>
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" id="date_end" placeholder="Pvz.: 2025/12/12" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <label for="textArea" class="form-label">Inventoriaus vienetas</label>
                                    <input type="text" class="form-control" id="date_end" placeholder="Arduino UNO R3" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <label for="textArea" class="form-label">Papildomi komentarai</label>
                                    <textarea class="form-control text-secondary" id="textArea" rows="3" disabled>Būtų gerai geltonos spalvos</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col d-flex justify-content-end">
                                    <button type="button" class="btn btn-success mx-1"><i class="bi bi-check" style="font-size: 20px;"></i></button>
                                    <button type="button" class="btn btn-danger mx-1"><i class="bi bi-x" style="font-size: 20px;"></i></button>
                                    <button type="button" class="btn btn-warning mx-1">REDAGUOTI</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Prašymas 2
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                        <div class="row">
                                <div class="col-3 mb-3">
                                    <label for="email" class="form-label">Vardas Pavardė</label>
                                    <input type="email" class="form-control" id="email" placeholder="Jonas Jonauskas" disabled>
                                </div>
                                <div class="col-3 mb-3">
                                    <label for="email" class="form-label">Akademinė grupė</label>
                                    <input type="email" class="form-control" id="email" placeholder="IFIN-1/3" disabled>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="row">
                                        <div class="col d-flex justify-content-center">
                                            <label class="form-label">Laikotarpis</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <input type="text" class="form-control" id="date_start" placeholder="Pvz.: 2025/03/15" disabled>
                                        </div>
                                        <div class="col-1 d-flex justify-content-center align-items-center">
                                            <i class="bi bi-dash"></i>
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" id="date_end" placeholder="Pvz.: 2025/06/13" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <label for="textArea" class="form-label">Inventoriaus vienetas</label>
                                    <input type="text" class="form-control" id="date_end" placeholder="ESP32-S3" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <label for="textArea" class="form-label">Papildomi komentarai</label>
                                    <textarea class="form-control text-secondary" id="textArea" rows="3" disabled>Norėčiau naujesnio</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col d-flex justify-content-end">
                                    <button type="button" class="btn btn-success mx-1"><i class="bi bi-check" style="font-size: 20px;"></i></button>
                                    <button type="button" class="btn btn-danger mx-1"><i class="bi bi-x" style="font-size: 20px;"></i></button>
                                    <button type="button" class="btn btn-warning mx-1">REDAGUOTI</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Prašymas 3
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                        <div class="row">
                                <div class="col-3 mb-3">
                                    <label for="email" class="form-label">Vardas Pavardė</label>
                                    <input type="email" class="form-control" id="email" placeholder="Petras Petrauskas" disabled>
                                </div>
                                <div class="col-3 mb-3">
                                    <label for="email" class="form-label">Akademinė grupė</label>
                                    <input type="email" class="form-control" id="email" placeholder="IFIN-1/2" disabled>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="row">
                                        <div class="col d-flex justify-content-center">
                                            <label class="form-label">Laikotarpis</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <input type="text" class="form-control" id="date_start" placeholder="Pvz.: 2023/04/15" disabled>
                                        </div>
                                        <div class="col-1 d-flex justify-content-center align-items-center">
                                            <i class="bi bi-dash"></i>
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" id="date_end" placeholder="Pvz.: 2025/05/06" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <label for="textArea" class="form-label">Inventoriaus vienetas</label>
                                    <input type="text" class="form-control" id="date_end" placeholder="NFC/RFID Reader" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <label for="textArea" class="form-label">Papildomi komentarai</label>
                                    <textarea class="form-control text-secondary" id="textArea" rows="3" disabled>Norėčiau antros versijos</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col d-flex justify-content-end">
                                    <button type="button" class="btn btn-success mx-1"><i class="bi bi-check" style="font-size: 20px;"></i></button>
                                    <button type="button" class="btn btn-danger mx-1"><i class="bi bi-x" style="font-size: 20px;"></i></button>
                                    <button type="button" class="btn btn-warning mx-1">REDAGUOTI</button>
                                </div>
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