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

if (!isset($_SESSION['inventory_tab_state'])) {
    $_SESSION['inventory_tab_state'] = "inventory";
}

if (isset($_POST['delete_inventory'])) {
    deleteInventory($_POST['delete_inventory'], $_POST['name'], $_POST['serial_number'], $_POST['inventory_number']);
}

if (isset($_POST['delete_storage'])) {
    deleteStorage($_POST['delete_storage'], $_POST['name']);
}

$result_inventory = displayInventory();
$result_storage = displayStorage();
$path = "../../images/qr_codes/";
    
?>

<?php include '../../includes/header_admin.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventorius</title>
    <link rel="stylesheet" href="../../css/mdb.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <script defer src="../../js/bootstrap.bundle.min.js"></script>
    <script defer src="../../js/mdb.umd.min.js"></script>
    <script defer src="../../js/header.js"></script>
    <script defer src="../../js/search_table_2.js"></script>
    <script defer src="../../js/state.js"></script>
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
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo ($_SESSION['inventory_tab_state'] === "inventory") ? "active" : "" ?> border-primary border-2" 
                    id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory-tab-pane" type="button" role="tab" aria-controls="inventory-tab-pane" 
                    aria-selected="true" onclick="saveState('inventory_tab_state', 'inventory')">Įrenginiai</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo ($_SESSION['inventory_tab_state'] === "storage") ? "active" : "" ?> border-primary border-2" 
                    id="storage-tab" data-bs-toggle="tab" data-bs-target="#storage-tab-pane" type="button" role="tab" aria-controls="storage-tab-pane" 
                    aria-selected="false" onclick="saveState('inventory_tab_state', 'storage')">Talpyklos</button>
                </li>
            </ul>
            <div class="tab-content border border-2 rounded-bottom border-primary" id="myTabContent">
                <div class="tab-pane fade <?php echo ($_SESSION['inventory_tab_state'] === "inventory") ? "show active" : "" ?>" id="inventory-tab-pane" 
                    role="tabpanel" aria-labelledby="inventory-tab" tabindex="0">
                    <div class="row mt-3 d-flex justify-content-center">
                        <div class="col-12 mb-3">
                            <h3 class="d-flex justify-content-center">Įrenginiai</h3>
    
                            <div class="col-12 mt-3">
                                <div class="input-group">
                                    <div class="form-outline" data-mdb-input-init>
                                        <input type="search" id="search-box" class="form-control" onkeyup="search()"/>
                                        <label class="form-label" for="search-box">Ieškoti</label>
                                    </div>
                                    <button type="button" class="btn btn-success ms-1" onClick="document.location.href='add_inventory.php'">PRIDĖTI INVENTORIŲ</button>
                                </div>
                            </div>

                            <div class="col">
                                <table class="table" id="table-inventory">
                                    <thead>
                                        <tr>
                                            <th scope="col">Pavadinimas</th>
                                            <th scope="col">Serijinis Numeris</th>
                                            <th scope="col">Inventoriaus Numeris</th>
                                            <th scope="col">Statusas</th>
                                            <th scope="col" class="col-1">Lipdukas</th>
                                            <th scope="col" class="col-1">Veiksmai</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-group-divider">
                                    <?php
                                    while($row = mysqli_fetch_assoc($result_inventory)){
                                    ?>
                                        <tr style="cursor: pointer;" data-id="<?= $row['id'] ?>" onclick="redirect(event)">
                                            <td><?= $row['name'] ?></td>
                                            <td><?= $row['serial_number'] ?></td>
                                            <td><?= $row['inventory_number'] ?></td>
                                    <?php
                                        if(checkInventoryAvailability($row['id'])){
                                    ?>
                                            <td>LAISVAS</td>
                                    <?php
                                        } else {
                                    ?>
                                            <td>PASISKOLINTAS</td>
                                    <?php
                                        }
                                    ?>
                                            <td><a class="d-flex justify-content-center" id="download" href="<?= $path . $row['sticker_path'] ?>" 
                                            download="<?= $row['sticker_path'] ?>"><?php echo '<img style="width: 50%;" src="' . $path . $row['sticker_path'] . '" />' ?></a></td>
                                            <td><button type="button" class="btn btn-danger" id="btn"><i class="bi bi-trash"></i></button></td>
                                        </tr>
                                    <?php
                                    }
                                    $_SESSION['success_message'] = "";
                                    $_SESSION['error_message'] = "";
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade <?php echo ($_SESSION['inventory_tab_state'] === "storage") ? "show active" : "" ?>" id="storage-tab-pane" 
                    role="tabpanel" aria-labelledby="storage-tab" tabindex="0">
                    <div class="row mt-3 d-flex justify-content-center">
                        <div class="col-12 mb-3">
                            <h3 class="d-flex justify-content-center">Talpyklos</h3>
    
                            <div class="col-12 mt-3">
                                <div class="input-group">
                                    <div class="form-outline" data-mdb-input-init>
                                        <input type="search" id="search-box-storage" class="form-control" onkeyup="searchStorage()"/>
                                        <label class="form-label" for="search-box-storage">Ieškoti</label>
                                    </div>
                                    <button type="button" class="btn btn-success ms-1" onClick="document.location.href='add_storage.php'">PRIDĖTI TALPYKLĄ</button>
                                </div>
                            </div>

                            <div class="col">
                                <table class="table" id="table-storage">
                                    <thead>
                                        <tr>
                                            <th scope="col">Pavadinimas</th>
                                            <th scope="col" class="col-1">Lipdukas</th>
                                            <th scope="col" class="col-1">Veiksmai</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-group-divider">
                                    <?php
                                    while($row = mysqli_fetch_assoc($result_storage)){
                                    ?>
                                        <tr style="cursor: pointer;" data-id="<?= $row['id'] ?>" onclick="redirect(event)">
                                            <td><?= $row['name'] ?></td>
                                            <td><a class="d-flex justify-content-center" id="download_storage" href="<?= $path . $row['sticker_path'] ?>" 
                                            download="<?= $row['sticker_path'] ?>"><?php echo '<img style="width: 50%;" src="' . $path . $row['sticker_path'] . '" />' ?></a></td>
                                            <td><button type="button" class="btn btn-danger" id="btn-storage"><i class="bi bi-trash"></i></button></td>
                                        </tr>
                                    <?php
                                    }
                                    $_SESSION['success_message'] = "";
                                    $_SESSION['error_message'] = "";
                                    ?>
                                    </tbody>
                                </table>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function redirect(event) {
            if (event.target.id === "download" || event.target.id === "download_storage" || 
                event.target.closest("#download") || event.target.closest("#download_storage")) {
                return;
            } else if (event.target.closest("#btn")) {
                if(confirm("Ar tikrai norite ištrinti šį inventoriaus vienetą?")) {
                    const row = event.currentTarget;
                    const cells = row.getElementsByTagName('td');

                    form = document.createElement("form");
                    form.method = "POST";
                    form.action = "inventory.php";

                    input_delete = document.createElement('input');
                    input_delete.type = "hidden";
                    input_delete.name = "delete_inventory";
                    input_delete.value = event.currentTarget.dataset.id;

                    input_name = document.createElement('input');
                    input_name.type = "hidden";
                    input_name.name = "name";
                    input_name.value = cells[0].textContent;

                    input_serial_number = document.createElement('input');
                    input_serial_number.type = "hidden";
                    input_serial_number.name = "serial_number";
                    input_serial_number.value = cells[1].textContent;

                    input_inventory_number = document.createElement('input');
                    input_inventory_number.type = "hidden";
                    input_inventory_number.name = "inventory_number";
                    input_inventory_number.value = cells[2].textContent;

                    form.appendChild(input_delete);
                    form.appendChild(input_name);
                    form.appendChild(input_serial_number);
                    form.appendChild(input_inventory_number);

                    document.body.appendChild(form);
                    form.submit();
                }
                return;
            } else if (event.target.closest("#btn-storage")) {
                if(confirm("Ar tikrai norite ištrinti šį talpyklos įrašą?")) {
                    const row = event.currentTarget;
                    const cells = row.getElementsByTagName('td');

                    form = document.createElement("form");
                    form.method = "POST";
                    form.action = "inventory.php";

                    input_delete = document.createElement('input');
                    input_delete.type = "hidden";
                    input_delete.name = "delete_storage";
                    input_delete.value = event.currentTarget.dataset.id;

                    input_name = document.createElement('input');
                    input_name.type = "hidden";
                    input_name.name = "name";
                    input_name.value = cells[0].textContent;

                    form.appendChild(input_delete);
                    form.appendChild(input_name);

                    document.body.appendChild(form);
                    form.submit();
                }
                return;
            }

            if(event.target.closest("#inventory-tab-pane")){
                window.location.href = 'inventory_information.php?inventory_id=' + event.currentTarget.dataset.id;
            } else if(event.target.closest("#storage-tab-pane")){
                window.location.href = 'storage_information.php?storage_id=' + event.currentTarget.dataset.id;
            }
        }
    </script>
</body>
</html>

<?php include '../../includes/footer_admin.php'; ?>