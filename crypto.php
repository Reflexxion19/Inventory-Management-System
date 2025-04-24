<?php

require_once __DIR__ . '/config/config.php';
require_once __DIR__. '/config/functions.php';

$url = getURL();

$server_base64_private_key = getPrivateKey();
$server_base64_public_key = getPublicKey();
$microcontroller_base64_public_key = getPublicKeyMc();

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
        echo '<p>Public Key (Base64): '. $base64_public_key . '</p>';
        echo '<p>Private Key (Base64): '. $base64_private_key . '</p>';
        echo '</div>';
        exit();
    }

    if(isset($_GET['generate_admin_card_data'])) {
        $data = adminCardData();
        
        echo '<div>';
        echo '<h2>Generated Key Pair</h2>';
        echo '<p>Card data (Base64): '. $data . '</p>';
        echo '</div>';
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

                $base64_encrypted_message = encryptMessage($microcontroller_base64_public_key, $randomString);

                $data_array = [
                    'type' => 'auth_message',
                    'message' => $base64_encrypted_message
                ];
    
                send_data($data_array);
            } elseif ($type === "auth_response") {
                $card_data = $data['card_data']?? null;

                if($card_data){
                    $rand_str = getRandomStringFromDB($device_name);
                    $base64_decrypted_message = decryptMessage($server_base64_private_key, $server_base64_public_key, urldecode($message));

                    if($rand_str === $base64_decrypted_message){
                        //$base64_decrypted_card_data = decryptMessage("", $server_base64_private_key, $card_data);

                        //if($base64_decrypted_card_data === "admin"){
                            $data_array = [
                                'type' => "auth_confirmation",
                                'message' => "unlock"
                            ];
                
                            send_data($data_array);
                        //}
                    } else{
                        echo "Data does not mach!!!";
                    }
                }
            }
        } else {
            header("HTTP/1.1 400 Bad Request");
            exit(); 
        }
    }
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