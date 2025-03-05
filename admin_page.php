<?php

session_start();
if(!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

if($_SESSION['role'] != 'admin'){
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administratoriaus Puslapis</title>
    <link rel="stylesheet" href="css/admin_page.css">
    <script src="https://kit.fontawesome.com/8c063c95d3.js" crossorigin="anonymous"></script>
</head>
<body>

    <nav>
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fa-solid fa-bars"></i>
        </label>

        <label class="logo">KTU IVS</label>
        
        <ul>
            <li><a class="active" href="admin_page.php">PAGRINDINIS</a></li>
            <li><a href="#">PASKOLOS</a></li>
            <li><a href="#">INVENTORIUS</a></li>
            <li><a href="#">NAUDOTOJAI</a></li>
            <li><a href="#">PRAŠYMAI</a></li>
            <li><a href="logout.php">ATSIJUNGTI</a></li>
        </ul>
    </nav>

    <div class="box">
        <h1>Sveiki, <span><?= $_SESSION['name']; ?></span></h1>
        <p>Tai yra <span>administratoriaus</span> puslapis</p>
        <button onclick="window.location.href='logout.php'">Atsijungti</button>
    </div>

</body>
</html>