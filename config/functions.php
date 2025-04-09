<?php

require_once 'config.php';

function display_inventory(){
    global $conn;

    $stmt = mysqli_prepare($conn, "SELECT * 
                                    FROM inventory");
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}

function check_inventory_availability($inventory_id){
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

function display_loans(){
    global $conn;
    $user_id = $_SESSION['user_id'];

    // $stmt = mysqli_prepare($conn, "SELECT * FROM inventory_loan WHERE fk_user_id = ?");
    $stmt = mysqli_prepare($conn, "SELECT *, inventory.name
                                    FROM inventory_loans
                                    INNER JOIN inventory ON inventory_loans.fk_inventory_id = inventory.id
                                    WHERE fk_user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}

function display_users(){
    global $conn;

    $stmt = mysqli_prepare($conn, "SELECT * 
                                    FROM users");
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}

function display_loan_requests(){
    global $conn;

    $stmt = mysqli_prepare($conn, "SELECT *, users.name AS student_name, users.academic_group AS student_group, inventory.name AS inventory_name
                                    FROM loan_applications
                                    INNER JOIN users ON loan_applications.fk_user_id = users.id
                                    INNER JOIN inventory ON loan_applications.fk_inventory_id = inventory.id");
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}

function display_student_loan_requests(){
    global $conn;
    $user_id = $_SESSION['user_id'];

    $stmt = mysqli_prepare($conn, "SELECT *, users.name AS student_name, users.academic_group AS student_group, inventory.name AS inventory_name
                                    FROM loan_applications
                                    INNER JOIN users ON loan_applications.fk_user_id = users.id
                                    INNER JOIN inventory ON loan_applications.fk_inventory_id = inventory.id
                                    WHERE fk_user_id = ? ");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}

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

?>