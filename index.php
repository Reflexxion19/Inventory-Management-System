<?php

session_start();

$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register_error'] ?? ''
];
$activeForm = $_SESSION['active_form'] ?? 'login';

session_unset();

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
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
    <title>Bakalauras</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <div class="container">
        <div class="form-box <?= isActiveForm('login', $activeForm); ?>" id="login-form">
            <form action="login_register.php" method="post">
                <h2>Prisijungti</h2>
                <?= showError($errors['login']); ?>
                <input type="email" name="email_login" placeholder="El. paštas" required>
                <input type="password" name="password_login" placeholder="Slaptažodis" required>
                <button type="submit" name="login">Prisijungti</button>
                <p>Neturite paskyros? <a href="#" onclick="showForm('register-form')">Prisiregistruokite</a></p>
            </form>
        </div>

        <div class="form-box <?= isActiveForm('register', $activeForm); ?>" id="register-form">
            <form action="login_register.php" method="post">
                <h2>Prisiregistruoti</h2>
                <?= showError($errors['register']); ?>
                <input type="text" name="name" placeholder="Vardas" required>
                <input type="email" name="email_register" placeholder="El. paštas" required>
                <input type="password" name="password_register" placeholder="Slaptažodis" required>
                <button type="submit" name="register">Prisiregistruoti</button>
                <p>Jau turite paskyrą? <a href="#" onclick="showForm('login-form')">Prisijungti</a></p>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>