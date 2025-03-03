<?php

session_start();
if(!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

if($_SESSION['role'] != 'admin'){
    header("Location: user_page.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administratoriaus Puslapis</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background: #fff;">

    <div class="box">
        <h1>Sveiki, <span><?= $_SESSION['name']; ?></span></h1>
        <p>Tai yra <span>administratoriaus</span> puslapis</p>
        <button onclick="window.location.href='logout.php'">Atsijungti</button>
    </div>

</body>
</html>