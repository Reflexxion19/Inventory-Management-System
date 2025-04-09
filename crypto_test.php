<?php

$msg = 'Random text';

// Generating an encryption key and a nonce
$key   = "PASAKAE)H@McQr4u7w!z%C*F-JaNdRgU"; // 256 bit
$nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES); // 24 bytes

// Encrypt
$ciphertext = sodium_crypto_secretbox($msg, $nonce, $key);
// Decrypt
$plaintext = sodium_crypto_secretbox_open($ciphertext, $nonce, $key);

echo $plaintext === $msg ? $msg : 'Error';

?>