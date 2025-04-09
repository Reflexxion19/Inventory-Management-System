<?php

$host = "localhost";
$user = "ktuivs";
$password = "KtuIVS";
$database = "bakalauras";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>