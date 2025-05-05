<?php

$host = "localhost";
$user = "ktuivs";
$password = "KtuIVS";
$database = "bakalauras";

$server_base64_private_key = "A9W3gD35zDgBQ82cjKggeQKhGRMmanNxbxKk4AsTrV0=";
$server_base64_public_key = "PGpRqXf8riutWHtYgQyxxj2FvHTvTioHPbjIxrCevhw=";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>