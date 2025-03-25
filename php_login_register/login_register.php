<?php

session_save_path("/tmp");
session_start();
require_once '../config.php';

// Tikrinama ar vartotojas paspaudė registracijos mygtuką
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $academic_group = $_POST['academic_group'];
    $email = $_POST['email_register'];
    $password = $_POST['password_register'];
    $repeated_password = $_POST['repeated_password_register'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';

    // Tikrinama ar vardas ir pavardė sudaryti tik iš raidžių
    if (ctype_alpha($name) && ctype_alpha($surname)) {
        // Tikrinama ar slaptažodis sudarytas iš 8 simbolių ir turi bent vieną didžiąjąją raidę, mažąjąją raidę, skaičių ir specialų simbolį
        if (preg_match($pattern, $password)) {
            // Tikinama ar slaptažodžiai sutampa
            if ($password === $repeated_password) {
                // Tikrinama ar el. pašto adresas yra išduotas KTU bei ar tai yra darbuotojo arba studento el. pašto adresas
                if (stristr($email, '@ktu.lt') !== FALSE) {
                    $checkEmail = $conn->query("SELECT email FROM users WHERE email = '$email'");
                    // Tikrinama ar toks el. pašto adresas jau yra
                    if ($checkEmail->num_rows > 0) {
                        $_SESSION['register_error'] = 'Paskyra su tokiu el. paštu jau yra!';
                        $_SESSION['active_form'] ='register';
                    } else {
                        $conn->query("INSERT INTO users (name, email, password, role)
                        VALUES ('$name', '$email', '$hashed_password', 'employee')");
                    }
                } elseif (stristr($email, '@ktu.edu') !== FALSE) {
                    if (strlen($academic_group) !== 0){
                        $checkEmail = $conn->query("SELECT email FROM users WHERE email = '$email'");
                        // Tikrinama ar toks el. pašto adresas jau yra
                        if ($checkEmail->num_rows > 0) {
                            $_SESSION['register_error'] = 'Paskyra su tokiu el. paštu jau yra!';
                            $_SESSION['active_form'] ='register';
                        } else {
                            $conn->query("INSERT INTO users (name, email, password, role, academic_group)
                            VALUES ('$name', '$email', '$hashed_password', 'student', '$academic_group')");
                        }
                    } else {
                        $_SESSION['register_error'] = 'Akademinės grupės kodas negali būti tuščias!';
                        $_SESSION['active_form'] ='register';
                    }
                } else {
                    $_SESSION['register_error'] = 'Priimtini tik KTU išduoti el. pašto adresai!';
                    $_SESSION['active_form'] ='register';
                }
            } else {
                $_SESSION['register_error'] = 'Slaptažodžiai nesutampa!';
                $_SESSION['active_form'] ='register';
            }
        } else {
            $_SESSION['register_error'] = 'Slaptažodis turi būti sudarytas bent iš 8 simbolių ir turėti 
            bent vieną didžiąją raidę, mažąją raidę, skaičių ir specialų simbolį!';
            $_SESSION['active_form'] ='register';
        }
    } else {
        $_SESSION['register_error'] = 'Vardas ir pavardė turi būti sudaryti tik iš raidžių!';
        $_SESSION['active_form'] ='register';
    }

    header("Location: index.php");
    exit();
}

// Tikrinama ar vartotojas paspaudė prisijungimo mygtuką
if (isset($_POST['login'])) {
    $email = $_POST['email_login'];
    $password = $_POST['password_login'];

    $result = $conn->query("SELECT * FROM users WHERE email = '$email'");
    // Tikrinama ar el. pašto adresas yra duomenų bazėje
    if($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Tikrinama ar slaptažodis atitinka duomenų bazėje esantį slaptažodį
        if (password_verify($password, $user['password'])) {
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Tikrinama ar vartotojas turi admin, employee ar student rolę
            if ($user['role'] === 'admin') {
                header("Location: ../php/admin/analysis.php");
            } elseif ($user['role'] === 'employee') {
                header("Location: ../php/employee/loans.php");
            } elseif ($user['role'] === 'student') {
                header("Location: ../php/student/student_loans.php");
            }
            exit();
        }
    }

    $_SESSION['login_error'] = 'Incorrect email or password';
    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}

?>