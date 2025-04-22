<?php

require_once __DIR__ . '/config/config.php';
require_once __DIR__. '/config/functions.php';

$url = getURL();

$server_base64_private_key = "ExmYdMJSIHd1m8TUeJqrdiQANBLrxsCZbUqX2m0hlG8=";
$server_base64_public_key = "oNVejsrLG0P78GeRPs1gBnBHoqt4iVUXACTAAEh0iQU=";

$microcontroller_base64_public_key = "oNVejsrLG0P78GeRPs1gBnBHoqt4iVUXACTAAEh0iQU=";

$rawData = file_get_contents("php://input");

if ($_SERVER["CONTENT_TYPE"] !== "application/json") {
    if(isset($_GET['generate_key_pair'])) {
        $keypair = generateRandomKeyPair();
        $public_key = $keypair['public_key'];
        $private_key = $keypair['private_key'];
    
        $base64_public_key = base64_encode($public_key);
        $base64_private_key = base64_encode($private_key);
        
        echo '<div>';
        echo '<h2>Generated Key Pair</h2>';
        echo '<p>Public Key (Base64): '. $base64_public_key. '</p>';
        echo '<p>Private Key (Base64): '. $base64_private_key. '</p>';
        echo '</div>';
        exit();
    }

    header("HTTP/1.1 400 Bad Request");
    exit();
} else {
    $data = json_decode($rawData, true);

    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        header("HTTP/1.1 400 Bad Request");
        exit();
    } else {
        $device_name = $data['device_name']?? null;
        $type = $data['type'] ?? null;
        $message = $data['message']?? null;

        if ($device_name && $type && $message) {
            if ($type === "auth") {
                $randomString = generateRandomString();
                storeRandomStringInDB($device_name,  $randomString);

                $base64_encrypted_message = encryptMessage($microcontroller_base64_public_key, $server_base64_private_key, $randomString);

                $data_array = [
                    'type' => 'auth_message',
                    'message' => $base64_encrypted_message
                ];
    
                send_data($data_array);
            } elseif ($type === "auth_response") {
                $data_array = [
                    'type' => "auth_confirmation",
                    'message' => "unlock"
                ];
    
                send_data($data_array);
            }
        } else {
            header("HTTP/1.1 400 Bad Request");
            exit(); 
        }
    }
}

if(isset($_GET['generate_key_pair'])) {
    $keypair = generateRandomKeyPair();
    $public_key = $keypair['public_key'];
    $private_key = $keypair['private_key'];

    $base64_public_key = base64_encode($public_key);
    $base64_private_key = base64_encode($private_key);
    
    echo '<div>';
    echo '<h2>Generated Key Pair</h2>';
    echo '<p>Public Key (Base64): '. $base64_public_key. '</p>';
    echo '<p>Private Key (Base64): '. $base64_private_key. '</p>';
    echo '</div>';
}




// $keypair = generateRandomKeyPair();
// $public_key = $keypair['public_key'];
// $private_key = $keypair['private_key'];

// $base64_public_key = base64_encode($public_key);
// $base64_private_key = base64_encode($private_key);

// $message = generateRandomString(10);

// $base64_encrypted_message = encryptMessage($public_key, $private_key, $message);
// decryptMessage($base64_public_key, $base64_private_key, $base64_encrypted_message);

?>