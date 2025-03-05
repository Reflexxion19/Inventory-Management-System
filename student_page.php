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
    
    <nav>
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fa-solid fa-bars"></i>
        </label>

        <label class="logo">KTU IVS</label>
        
        <ul>
            <li><a class="active" href="admin_page.php">PAGRINDINIS</a></li>
            <li><a href="#">PASKOLOS</a></li>
            <li><a href="#">PILDYTI PRAŠYMĄ</a></li>
            <li><a href="logout.php">ATSIJUNGTI</a></li>
        </ul>
    </nav>

    <div class="box">
        <h1>Sveiki, <span><?= $_SESSION['name']; ?></span></h1>
        <p>Tai yra <span>studento</span> puslapis</p>
        <button onclick="window.location.href='logout.php'">Atsijungti</button>
    </div>

</body>
</html>