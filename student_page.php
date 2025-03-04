<?php

session_start();
if(!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

if($_SESSION['role'] != 'student'){
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Naudotojo Puslapis</title>
    <link rel="stylesheet" href="css/employee_page.css">
</head>
<body style="background: #fff;">
    
    <div class="nav-bar">
        <div class="nav-logo">
            <a class="logo" href="admin_page.php">
                <img src="images/logo.png" alt="Logo">
            </a>
        </div>

        <div class="nav-items">
            <a class="nav-btn" href="admin_page.php">PAGRINDINIS</a>
            <a class="nav-btn" href="#">PASKOLOS</a>
            <a class="nav-btn" href="#">PILDYTI PRAŠYMĄ</a>
            <a class="nav-btn" href="logout.php">ATSIJUNGTI</a>
        </div>
    </div>

    <div class="box">
        <h1>Sveiki, <span><?= $_SESSION['name']; ?></span></h1>
        <p>Tai yra <span>studento</span> puslapis</p>
        <button onclick="window.location.href='logout.php'">Atsijungti</button>
    </div>

</body>
</html>