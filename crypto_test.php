<?php

function generateRandomKeyPair() {
    $keypair = sodium_crypto_box_keypair();

    $public_key = sodium_crypto_box_publickey($keypair);
    $private_key = sodium_crypto_box_secretkey($keypair);

    return [
        'public_key' => $public_key,
        'private_key' => $private_key
    ];
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }

    echo "Random string=" . $randomString . "|||";

    return $randomString;
}

function encryptMessage($public_key, $private_key, $message) {
    echo "Public Key (Base64):" . base64_encode($public_key) . "|||";
    echo "Private Key (Base64):" . base64_encode($private_key) . "|||";

    $keypair = sodium_crypto_box_keypair_from_secretkey_and_publickey($private_key, $public_key);

    $nonce = random_bytes(SODIUM_CRYPTO_BOX_NONCEBYTES);

    $ciphertext = sodium_crypto_box($message, $nonce, $keypair);
    $encrypted_message = base64_encode($nonce . $ciphertext);

    echo "Encrypted Message (Base64):" . $encrypted_message . "|||";

    return $encrypted_message;
}

function decryptMessage($base64_public_key, $base64_private_key, $base64_encrypted_message) {
    $public_key = base64_decode($base64_public_key);
    $private_key = base64_decode($base64_private_key);
    $encrypted_message = base64_decode($base64_encrypted_message);

    $keypair = sodium_crypto_box_keypair_from_secretkey_and_publickey($private_key, $public_key);

    $nonce = substr($encrypted_message, 0, SODIUM_CRYPTO_BOX_NONCEBYTES);
    $ciphertext = substr($encrypted_message, SODIUM_CRYPTO_BOX_NONCEBYTES);

    $decrypted = sodium_crypto_box_open($ciphertext, $nonce, $keypair);

    if ($decrypted === false) {
        echo "Decryption failed! Possible reasons:\n";
        echo "- Message was tampered with\n";
        echo "- Wrong keys used\n";
        echo "- Corrupted message\n";
    } else {
        echo "Successfully decrypted message:\n";
        echo $decrypted . "\n";
    }
}

// $base64_public_key = "oNVejsrLG0P78GeRPs1gBnBHoqt4iVUXACTAAEh0iQU=";
// $base64_private_key = "ExmYdMJSIHd1m8TUeJqrdiQANBLrxsCZbUqX2m0hlG8=";
// $base64_encrypted_message = "Wii09qiTBG1daAjOXREf1aioy2a32dU1YLqpsH00/I7HXur5fQ8rXOoJ4u9MN54HXzu66XovfFizBy2k7TK+7HZ4+bLDLQw=";

$keypair = generateRandomKeyPair();
$public_key = $keypair['public_key'];
$private_key = $keypair['private_key'];

$base64_public_key = base64_encode($public_key);
$base64_private_key = base64_encode($private_key);

$message = generateRandomString(10);
// $message = "Alio valio ir inter.....NOPE!?.:D;D/D'D:O";

$base64_encrypted_message = encryptMessage($public_key, $private_key, $message);
decryptMessage($base64_public_key, $base64_private_key, $base64_encrypted_message);



if(isset($_POST['message'])){
    $message = $_POST['message'];
    echo $message;
}


$message = "Unlock";

"&message=". $message;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://192.168.137.36/post");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$output = curl_exec($ch);
curl_close($ch);

?>