<?php

session_save_path("/tmp");
session_start();

if(isset($_SESSION['email']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    header("Location: php/admin/inventory.php");
    exit();
}

if(isset($_SESSION['email']) && isset($_SESSION['role']) && $_SESSION['role'] == 'employee') {
    header("Location: php/employee/loans.php");
    exit();
}

if(isset($_SESSION['email']) && isset($_SESSION['role']) && $_SESSION['role'] == 'student') {
    header("Location: php/student/student_loans.php");
    exit();
}

$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register_error'] ?? ''
];
$activeForm = $_SESSION['active_form'] ?? 'login';
$verificationSuccess = $_SESSION['verification_success'] ?? '';

session_unset();

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

function showSuccess($success) {
    return !empty($success) ? "<p class='success-message'>$success</p>" : '';
}

function isActiveForm($formName, $activeForm) {
    return $formName === $activeForm ? 'active' : '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KTU IVS</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    
    <div class="container">
        <div class="form-box <?= isActiveForm('login', $activeForm); ?>" id="login-form">
            <form action="php_login_register/login_register.php" method="post">
                <h2>Prisijungti</h2>
                <?= showError($errors['login']); ?>
                <?= showSuccess($verificationSuccess); ?>
                <input type="email" name="email_login" placeholder="El. paštas" pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" required>
                <input type="password" name="password_login" placeholder="Slaptažodis" required>
                <button type="submit" name="login">Prisijungti</button>
                <p>Neturite paskyros? <a href="#" onclick="showForm('register-form')">Prisiregistruokite</a></p>
            </form>
        </div>

        <div class="form-box <?= isActiveForm('register', $activeForm); ?>" id="register-form">
            <form action="php_login_register/login_register.php" method="post">
                <h2>Prisiregistruoti</h2>
                <?= showError($errors['register']); ?>
                <input type="text" name="name" placeholder="Vardas" pattern=".{2,}$" required>
                <input type="text" name="surname" placeholder="Pavardė" pattern=".{2,}$" required>
                <input type="text" name="academic_group" placeholder="Akademinė grupė" pattern=".{3,}$">
                <input type="email" name="email_register" placeholder="El. paštas" pattern="[a-z]+\.+[a-z]+@ktu+\.(edu|lt)$" required>
                <input type="password" name="password_register" placeholder="Slaptažodis"
                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[#?!@$%^&*\-\[\]]).{8,}" id="password" required>
                <input type="password" name="repeated_password_register" placeholder="Pakartokite slaptažodį" id="repeated_password" required>
                <button type="submit" name="register">Prisiregistruoti</button>
                <p>Jau turite paskyrą? <a href="#" onclick="showForm('login-form')">Prisijungti</a></p>
            </form>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/8c063c95d3.js" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
    <script src="js/validation.js"></script>
</body>
</html>