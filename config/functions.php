<?php

require_once 'config.php';
require_once __DIR__ . '/../phpqrcode/qrlib.php';

#region Inventory
    #region Locations
function getLocations(){
    global $conn;

    $stmt = mysqli_prepare($conn, "SELECT *
                                    FROM inventory_locations");
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}
    #endregion

    #region Display Inventory
function displayInventory(){
    global $conn;

    $stmt = mysqli_prepare($conn, "SELECT * 
                                    FROM inventory");
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}

function checkInventoryAvailability($inventory_id){
    global $conn;

    $stmt = mysqli_prepare($conn, "SELECT *
                                    FROM inventory_loans
                                    WHERE fk_inventory_id = ? AND `status` = 'Borrowed'");
    mysqli_stmt_bind_param($stmt, "i", $inventory_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0){
        return false; 
    }

    return true;
}

function displayStorage(){
    global $conn;

    $stmt = mysqli_prepare($conn, "SELECT *
                                    FROM inventory_locations");
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}
    #endregion

    #region Add Inventory
function addInventory($name, $location, $serial_number, $inventory_number, $description){
    global $conn;
    
    $sticker_path = generateSticker($name, $serial_number, $inventory_number);

    $stmt = mysqli_prepare($conn, "INSERT INTO inventory(`name`, fk_inventory_location_id, serial_number, 
                                                        inventory_number, `description`, sticker_path)
                                    VALUES(?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sissss", $name, $location, $serial_number, $inventory_number, $description, $sticker_path);
    mysqli_stmt_execute($stmt);
    $affected_rows = mysqli_stmt_affected_rows($stmt);

    if($affected_rows > 0){
        header("Location: generated_sticker.php");
        exit();
    } else{
        $_SESSION['error_message'] = "Inventoriaus pridėti nepavyko! Bandykite dar kartą!";
        exit();
    }
}

function generateSticker($name, $serial_number, $inventory_number){
    $path = __DIR__. '/../images/qr_codes/';
    $file_path = $path . clean($name) . "_" . clean($serial_number) . "_" . clean($inventory_number) . '.png';
    $file = clean($name) . "_" . clean($serial_number) . "_" . clean($inventory_number) . '.png';
    $_SESSION['generated_sticker'] = $file;

    $data = $name . "__" . $serial_number . "__" . $inventory_number;

    QRcode::png($data, $file_path, QR_ECLEVEL_Q, 256, 4);

    return $file;
}

function clean($string) {
    $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
 
    return preg_replace('/[^A-Za-z0-9\-]/', '-', $string); // Removes special chars.
 }
    #endregion

    #region Edit Inventory
function getInventoryById($inventory_id){
    global $conn;

    $stmt = mysqli_prepare($conn, "SELECT *
                                    FROM inventory
                                    WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $inventory_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    return $row;
}

function deleteSticker($inventory_id){
    global $conn;

    $stmt = mysqli_prepare($conn, "SELECT *
                                FROM inventory
                                WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $inventory_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    $path = __DIR__. '/../images/qr_codes/';
    $sticker_path = $path . clean($row['name']) . "_" . clean($row['serial_number']) . "_" . clean($row['inventory_number']) . '.png';
    if(!unlink($sticker_path)){
        return false;
    }

    return true;
}

function updateInventory($name, $location, $serial_number, $inventory_number, $description, $inventory_id){
    global $conn;

    if(deleteSticker($inventory_id)){
        $sticker_path = generateSticker($name, $serial_number, $inventory_number);

        $stmt = mysqli_prepare($conn, "UPDATE inventory
                                        SET `name`= ?, fk_inventory_location_id = ?, serial_number = ?, inventory_number = ?, `description` = ?, sticker_path = ?
                                        WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sissssi", $name, $location, $serial_number, $inventory_number, $description, $sticker_path, $inventory_id);
        mysqli_stmt_execute($stmt);
        $affected_rows = mysqli_stmt_affected_rows($stmt);

        if($affected_rows > 0){
            $_SESSION['success_message'] = "Inventorius atnaujintas sėkmingai!";
            header("Location: inventory.php");
            exit();
        } else{
            $row = getInventoryById($inventory_id);

            if($row['name'] === $name && $row['fk_inventory_location_id'] === $location && $row['serial_number'] === $serial_number && 
            $row['inventory_number'] === $inventory_number && $row['description'] === $description){
                $_SESSION['error_message'] = "Įrašyti duomenys atitinka jau esamus duomenis!";
                return;
            }

            $_SESSION['error_message'] = "Inventoriaus atnaujinti nepavyko! Bandykite dar kartą!";
            return;
        }
    } else{
        $_SESSION['error_message'] = "Inventoriaus atnaujinti nepavyko! Bandykite dar kartą!";
        return;
    }
}
    #endregion

    #region Delete Inventory
function deleteInventory($inventory_id, $name, $serial_number, $inventory_number){
    global $conn;

    $stmt = mysqli_prepare($conn, "DELETE FROM inventory
                                    WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $inventory_id);
    mysqli_stmt_execute($stmt);
    $affected_rows = mysqli_stmt_affected_rows($stmt);

    if($affected_rows > 0){
        $_SESSION['success_message'] = "Inventorius ištrintas sėkmingai!";

        $path = __DIR__. '/../images/qr_codes/';
        $sticker_path = $path . clean($name) . "_" . clean($serial_number) . "_" . clean($inventory_number) . '.png';

        unlink($sticker_path);
        header("Location: inventory.php");
        exit();
    } else{
        $_SESSION['error_message'] = "Inventoriaus ištrinti nepavyko! Bandykite dar kartą!";
        exit();
    }
}
    #endregion

    #region Add Storage
function generateStorageSticker($name){
    $path = __DIR__. '/../images/qr_codes/';
    $file_path = $path . clean($name) . '.png';
    $file = clean($name) . '.png';
    $_SESSION['generated_sticker'] = $file;

    $data = $name;

    QRcode::png($data, $file_path, QR_ECLEVEL_Q, 256, 4);

    return $file;
}

function addStorage($name, $description){
    global $conn;

    $sticker_path = generateStorageSticker($name);

    $stmt = mysqli_prepare($conn, "INSERT INTO inventory_locations(`name`, `description`, sticker_path)
                                    VALUES(?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $name, $description, $sticker_path);
    mysqli_stmt_execute($stmt);
    $affected_rows = mysqli_stmt_affected_rows($stmt);

    if($affected_rows > 0){
        $_SESSION['success_message'] = "Talpykla pridėta sėkmingai!";
        header("Location: inventory.php");
        exit();
    } else{
        $_SESSION['error_message'] = "Talpyklos pridėti nepavyko! Bandykite dar kartą!";
        exit();
    }
}
    #endregion
    
    #region Edit Storage
function getStorageById($storage_id){
    global $conn;

    $stmt = mysqli_prepare($conn, "SELECT *
                                    FROM inventory_locations
                                    WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $storage_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    return $row;
}

function deleteStorageSticker($storage_id){
    global $conn;

    $stmt = mysqli_prepare($conn, "SELECT *
                                FROM inventory_locations
                                WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $storage_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    $path = __DIR__. '/../images/qr_codes/';
    $sticker_path = $path . clean($row['name']) . '.png';
    if(!unlink($sticker_path)){
        return false;
    }

    return true;
}

function updateStorage($name, $description, $storage_id){
    global $conn;

    if(deleteStorageSticker($storage_id)){
        $sticker_path = generateStorageSticker($name);

        $stmt = mysqli_prepare($conn, "UPDATE inventory_locations
                                        SET `name`= ?, `description` = ?, sticker_path = ?
                                        WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sssi", $name, $description, $sticker_path, $storage_id);
        mysqli_stmt_execute($stmt);
        $affected_rows = mysqli_stmt_affected_rows($stmt);

        if($affected_rows > 0){
            $_SESSION['success_message'] = "Talpykla atnaujinta sėkmingai!";
            header("Location: inventory.php");
            exit();
        } else{
            $row = getStorageById($storage_id);

            if($row['name'] === $name && $row['description'] === $description){
                $_SESSION['error_message'] = "Įrašyti duomenys atitinka jau esamus duomenis!";
                return;
            }

            $_SESSION['error_message'] = "Talpyklos atnaujinti nepavyko! Bandykite dar kartą!";
            return;
        }
    } else{
        $_SESSION['error_message'] = "Talpyklos atnaujinti nepavyko! Bandykite dar kartą!";
        return;
    }
}
    #endregion

    #region Delete Storage
function deleteStorage($storage_id, $name){
    global $conn;

    $stmt = mysqli_prepare($conn, "DELETE FROM inventory_locations
                                    WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $storage_id);
    mysqli_stmt_execute($stmt);
    $affected_rows = mysqli_stmt_affected_rows($stmt);

    if($affected_rows > 0){
        $_SESSION['success_message'] = "Talpykla ištrinta sėkmingai!";

        $path = __DIR__. '/../images/qr_codes/';
        $sticker_path = $path . clean($name) . '.png';

        unlink($sticker_path);
        header("Location: inventory.php");
        exit();
    } else{
        $_SESSION['error_message'] = "Talpyklos ištrinti nepavyko! Bandykite dar kartą!";
        exit();
    }
}
    #endregion
    #endregion

#region Loans
    #region Display Loans
function display_loans(){
    global $conn;
    $user_id = $_SESSION['user_id'];

    $stmt = mysqli_prepare($conn, "SELECT inventory_loans.*, inventory.name
                                    FROM inventory_loans
                                    INNER JOIN inventory ON inventory_loans.fk_inventory_id = inventory.id
                                    WHERE fk_user_id = ? && `status` = 'Borrowed'");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}
    #endregion

    #region Loan Actions
function selectInventoryByIdCodeParams($name, $serial_number, $inventory_number){
    global $conn;

    $stmt = mysqli_prepare($conn, "SELECT *
                                        FROM inventory
                                        WHERE name = ? AND serial_number = ? AND inventory_number = ?");
    mysqli_stmt_bind_param($stmt, "sss", $name, $serial_number, $inventory_number);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    return $row;
}

        #region Unlock Storage
function selectStorageByIdCodeParams($name){
    global $conn;

    $stmt = mysqli_prepare($conn, "SELECT *
                                    FROM inventory_locations
                                    WHERE name = ?");
    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    return $row;
}

function unlockStorage($storage_id_code){
    $parsed = explode("__", $storage_id_code);

    if(count($parsed) === 1){
        $name = $parsed[0];

        $row = selectStorageByIdCodeParams($name);

        if($row){
            $name = $row['name'];
            $message = "Unlock";

            $data = "name=". $name . "&message=". $message;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://8dbb-158-129-21-117.ngrok-free.app/post");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            $output = curl_exec($ch);
            curl_close($ch);

            $_SESSION['error_message'] = $output;

            echo "<script>
                    window.addEventListener('load', (event) => {
                        if(document.getElementById('loan-tab')){
                            document.getElementById('loan-tab').click();
                        } else if (document.getElementById('return-tab')){
                            document.getElementById('return-tab').click();
                        }
                    });
                </script>";
        }
    } else{
       $_SESSION['error_message'] = "Identifikacinis kodas neteisingas!";
       return; 
    }
}
        #endregion

        #region Add Loan
function checkIfInventoryLoaned($inventory_id){
    global $conn;
    
    $stmt = mysqli_prepare($conn, "SELECT *
                                    FROM inventory_loans
                                    WHERE fk_inventory_id = ? AND `status` = 'Borrowed'");
    mysqli_stmt_bind_param($stmt, "i", $inventory_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if($row){
        return true;
    } else{
        return false;
    }
}

function loanInventory($loan_id_code, $user_id){
    global $conn;

    $parsed = explode("__", $loan_id_code);

    if(count($parsed) === 3){
        $name = $parsed[0];
        $serial_number = $parsed[1];
        $inventory_number = $parsed[2];

        $date = date("Y-m-d");
        $return_until_date = date("Y-m-d", strtotime("+1 month"));

        $row = selectInventoryByIdCodeParams($name, $serial_number, $inventory_number);
        
        if($row){
            if(checkIfInventoryLoaned($row['id'])){
                $_SESSION['error_message'] = "Inventorius jau paskolintas!";
                return;
            } else{
                $stmt = mysqli_prepare($conn, "INSERT INTO inventory_loans(fk_user_id, fk_inventory_id, 
                                                        loan_date, return_until_date, `status`)
                                                VALUES(?, ?, ?, ?, 'Borrowed')");
                mysqli_stmt_bind_param($stmt, "iiss", $user_id, $row['id'], $date, $return_until_date);
                mysqli_stmt_execute($stmt);
                $affected_rows = mysqli_stmt_affected_rows($stmt);

                if($affected_rows > 0){
                    $_SESSION['success_message'] = "Inventoriaus paskola užregistruota!";
                    header("Location: loans.php");
                    exit();
                } else{
                    $_SESSION['error_message'] = "Inventoriaus paskolos užregistruoti nepavyko! Bandykite dar kartą!";
                    return;
                }
            }
        } else{
            $_SESSION['error_message'] = "Identifikacinis kodas neteisingas!";
            return;
        }
    } else{
        $_SESSION['error_message'] = "Identifikacinis kodas neteisingas!";
        return;
    }
}
        #endregion

        #region Return Loan
function returnInventory($return_id_code, $user_id){
    global $conn;

    $parsed = explode("__", $return_id_code);

    if(count($parsed) === 3){
        $name = $parsed[0];
        $serial_number = $parsed[1];
        $inventory_number = $parsed[2];

        $row = selectInventoryByIdCodeParams($name, $serial_number, $inventory_number);

        if($row){
            $date = date("Y-m-d");

            $stmt = mysqli_prepare($conn, "UPDATE inventory_loans
                                            SET `status` = 'Returned', return_date = ?
                                            WHERE fk_user_id = ? AND fk_inventory_id = ? AND `status` = 'Borrowed'");
            mysqli_stmt_bind_param($stmt, "sii", $date, $user_id, $row['id']);
            mysqli_stmt_execute($stmt);
            $affected_rows = mysqli_stmt_affected_rows($stmt);

            if($affected_rows > 0){
                $_SESSION['success_message'] = "Inventoriaus grąžinimas užregistruotas!";
                header("Location: loans.php");
                exit();
            } else{
                $_SESSION['error_message'] = "Inventoriaus grąžinimo užregistruoti nepavyko! Bandykite dar kartą!";
                return;
            }
        } else{
            $_SESSION['error_message'] = "Identifikacinis kodas neteisingas!";
            return;
        }
    } else{
        $_SESSION['error_message'] = "Identifikacinis kodas neteisingas!";
        return;
    }
}
        #endregion
    #endregion
#endregion

#region Users
    #region Display Users
function display_users(){
    global $conn;

    $stmt = mysqli_prepare($conn, "SELECT * 
                                    FROM users");
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}
    #endregion

    #region Change Role
function getUserById($user_id){
    global $conn;

    $stmt = mysqli_prepare($conn, "SELECT *
                                    FROM users
                                    WHERE id =?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    return $row;
}

function changeRole($user_id, $role){
    global $conn;

    $stmt = mysqli_prepare($conn, "UPDATE users
                                    SET `role` = ?
                                    WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "si", $role, $user_id);
    mysqli_stmt_execute($stmt);
    $affected_rows = mysqli_stmt_affected_rows($stmt);

    if($affected_rows > 0){
        $_SESSION['success_message'] = "Naudotojo rolė atnaujinta sėkmingai!";
        header("Location: users.php");
        exit();
    } else{
        $row = getUserById($user_id);

        if($row['role'] === $role){
            $_SESSION['error_message'] = "Pasirinkta rolė atitinka jau esamą rolę!";
        }

        $_SESSION['error_message'] = "Rolės atnaujinti nepavyko! Bandykite dar kartą!";
        exit();
    }
}
    #endregion
#endregion

#region Admin Loan Requests
    #region Display Requests
function display_loan_requests(){
    global $conn;

    $stmt = mysqli_prepare($conn, "SELECT loan_applications.*, users.name AS student_name, users.academic_group AS student_group, inventory.name AS inventory_name
                                    FROM loan_applications
                                    INNER JOIN users ON loan_applications.fk_user_id = users.id
                                    INNER JOIN inventory ON loan_applications.fk_inventory_id = inventory.id
                                    WHERE status = 'submitted' OR status = 'corrected'");
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}
    #endregion

    #region Request Actions
function approveRequest($request_id){
    global $conn;

    $stmt = mysqli_prepare($conn, "UPDATE loan_applications
                                    SET `status` = 'approved'
                                    WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $request_id);
    mysqli_stmt_execute($stmt);
    $affected_rows = mysqli_stmt_affected_rows($stmt);

    if($affected_rows > 0){
        $_SESSION['success_message'] = "Prašymas patvirtintas sėkmingai!";
    } else{
        $_SESSION['error_message'] = "Prašymo patvirtinti nepavyko! Bandykite dar kartą!";
    }

    header("Location: loan_requests.php");
    exit();
}

function rejectRequest($request_id){
    global $conn;

    $stmt = mysqli_prepare($conn, "UPDATE loan_applications
                                    SET `status` = 'rejected'
                                    WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $request_id);
    mysqli_stmt_execute($stmt);
    $affected_rows = mysqli_stmt_affected_rows($stmt);

    if($affected_rows > 0){
        $_SESSION['success_message'] = "Prašymas atmestas sėkmingai!";
    } else{
        $_SESSION['error_message'] = "Prašymo atmesti nepavyko! Bandykite dar kartą!";
    }

    header("Location: loan_requests.php");
    exit();
}

function getLoanRequestByID($request_id){
    global $conn;

    $stmt = mysqli_prepare($conn, "SELECT users.email AS user_email, inventory.name AS inventory_name
                                    FROM loan_applications
                                    INNER JOIN users ON loan_applications.fk_user_id = users.id
                                    INNER JOIN inventory ON loan_applications.fk_inventory_id = inventory.id
                                    WHERE loan_applications.id = ?");
    mysqli_stmt_bind_param($stmt, "i", $request_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    return $row;
}

function addFeedback($request_id){
    global $conn;

    $conn->begin_transaction();

    try{
        $stmt = mysqli_prepare($conn, "UPDATE loan_applications
                                    SET `status` = 'needs_correction'
                                    WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $request_id);
        mysqli_stmt_execute($stmt);
        $affected_rows = mysqli_stmt_affected_rows($stmt);

        if($affected_rows > 0){
            $row = getLoanRequestByID($request_id);
            $mail_sent = sendMail($row['user_email'], $row['inventory_name']);

            if($mail_sent){
                $_SESSION['success_message'] = "Atsiliepimas išsiųstas sėkmingai!";
                $conn->commit();
                
                header("Location: loan_requests.php");
                exit();
            }
        }

        $_SESSION['error_message'] = "Atsiliepimo išsiųsti nepavyko! Bandykite dar kartą!";
        $conn->rollback();

        header("Location: loan_requests.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Atsiliepimo išsiųsti nepavyko! Bandykite dar kartą!";
        $conn->rollback();

        header("Location: loan_requests.php");
        exit();
    }
}

function sendMail($recipient, $inventory){
    $headers = "From: KTUIVS reflexxion.usage@gmail.com";
    $to = "tankiuks9@gmail.com"; // PAKEISTI Į $recipient!!!
    $subject = "Pasikeitė jūsų paskolos prašymo statusas sistemoje KTUIVS";

    $message = "Sveiki,\n\n";
    $message .= "Pasikeitė jūsų inventoriaus (" . $inventory . ") paskolos prašymo statusas. Inventoriaus paskolos prašymo statusą galite patikrinti prisijungę prie savo KTUIVS paskyros.\n\n";
    $message .= "Nebandykite atsakyti į šią žinutę. Tai yra automatinis pranešimas.\n\n";
    $message .= "Pagarbiai,\n";
    $message .= "KTUIVS";

    if(mail($to, $subject, $message, $headers)){
        return true;
    } else{
        return false;
    }
}
    #endregion
#endregion

#region Student Loan Requests
function display_student_loan_requests(){
    global $conn;
    $user_id = $_SESSION['user_id'];

    $stmt = mysqli_prepare($conn, "SELECT loan_applications.*, users.name AS student_name, users.academic_group AS student_group, inventory.name AS inventory_name
                                    FROM loan_applications
                                    INNER JOIN users ON loan_applications.fk_user_id = users.id
                                    INNER JOIN inventory ON loan_applications.fk_inventory_id = inventory.id
                                    WHERE fk_user_id = ? ");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}
#endregion

#region Analysis
function loan_years(){
    global $conn;
    $year = array_fill(0, 2, 0); // 12 reikšmių mąsyvas pripildytas nuliais

    $current_date_parsed = explode("-", date("Y-m-d"));
    $current_year = (int)$current_date_parsed[0];
    $year[0] = $current_year;
    $year[1] = $current_year;

    $stmt = mysqli_prepare($conn, "SELECT * 
                                    FROM inventory_loans");
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while($row = mysqli_fetch_assoc($result)){
        $start_date = $row['loan_date'];
        $end_date = $row['return_until_date'];
        $return_date = $row['return_date'];

        $start_date_parsed = explode("-", $start_date);
        $end_date_parsed = explode("-", $end_date);

        $start_year = (int)$start_date_parsed[0];
        $end_year = (int)$end_date_parsed[0];

        if($row['status'] === 'Borrowed'){
            $end_year = $current_year;
        } elseif($row['status'] === 'Returned'){
            $return_date_parsed = explode("-", $return_date);
            $return_year = (int)$return_date_parsed[0];

            $end_year = $return_year;
        }

        if($start_year < $year[0]){
            $year[0] = $start_year;
        } elseif($end_year > $year[1]){
            $year[1] = $end_year;
        }
    }

    return $year;
}

function calculate_year_loans_by_month($year){
    global $conn;
    $month = array_fill(0, 12, 0); // 12 reikšmių mąsyvas pripildytas nuliais

    $stmt = mysqli_prepare($conn, "SELECT * 
                                    FROM inventory_loans");
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while($row = mysqli_fetch_assoc($result)){
        $start_date = $row['loan_date'];
        $end_date = $row['return_until_date'];
        $return_date = $row['return_date'];

        $start_date_parsed = explode("-", $start_date);
        $end_date_parsed = explode("-", $end_date);
        $current_date_parsed = explode("-", date("Y-m-d"));

        $start_year = (int)$start_date_parsed[0];
        $end_year = (int)$end_date_parsed[0];
        $start_month = (int)$start_date_parsed[1];
        $end_month = (int)$end_date_parsed[1];
        
        $current_year = (int)$current_date_parsed[0];
        $current_month = (int)$current_date_parsed[1];

        if($row['status'] === 'Borrowed'){
            $end_year = $current_year;
            $end_month = $current_month;
        } elseif($row['status'] === 'Returned'){
            $return_date_parsed = explode("-", $return_date);
            $return_year = (int)$return_date_parsed[0];
            $return_month = (int)$return_date_parsed[1];

            $end_year = $return_year;
            $end_month = $return_month;
        }

        for($i = 1; $i < 13; $i++){
            if($start_year === $year && $end_year === $year){
                if($start_month === $i || $end_month === $i){
                    $month[$i - 1]++;
                } elseif($i > $start_month && $i < $end_month){
                    $month[$i - 1]++;
                }
            } elseif($start_year === $year && $end_year > $year){
                if($start_month === $i || 12 === $i){
                    $month[$i - 1]++;
                } elseif($i > $start_month && $i < 12){
                    $month[$i - 1]++;
                }
            } elseif($start_year < $year && $end_year === $year){
                if(1 === $i || $end_month === $i){
                    $month[$i - 1]++;
                } elseif($i > 1 && $i < $end_month){
                    $month[$i - 1]++;
                }
            } elseif($start_year < $year && $end_year > $year){
                if(1 === $i || 12 === $i){
                    $month[$i - 1]++;
                } elseif($i > 1 && $i < 12){
                    $month[$i - 1]++;
                }
            }
        }
    }

    return $month;
}
#endregion

?>