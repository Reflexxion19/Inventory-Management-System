#include "headers/crypto.h"

static const char* TAG = "HTTPS";

const char *microcontroller_base64_private_key = "ExmYdMJSIHd1m8TUeJqrdiQANBLrxsCZbUqX2m0hlG8=";
const char *microcontroller_base64_public_key = "oNVejsrLG0P78GeRPs1gBnBHoqt4iVUXACTAAEh0iQU=";

const char *server_base64_public_key = "oNVejsrLG0P78GeRPs1gBnBHoqt4iVUXACTAAEh0iQU=";

size_t base64_decode(const char *b64_input, unsigned char *output) {
    size_t len = strlen(b64_input);
    size_t output_len = 0;
    
    if (sodium_base642bin(output, crypto_box_PUBLICKEYBYTES + crypto_box_SECRETKEYBYTES + crypto_box_MACBYTES,
                          b64_input, len, NULL, &output_len, NULL,
                          sodium_base64_VARIANT_ORIGINAL) != 0) {
        ESP_LOGE(TAG, "Base64 decoding failed"); // For debugging
        return 0;
    }
    
    return output_len;
}

bool decrypt_message(const char *base64_public_key, const char *base64_private_key, 
                    const char *base64_encrypted_message, unsigned char **decrypted, 
                    size_t *decrypted_len) {
    unsigned char private_key[crypto_box_SECRETKEYBYTES];
    unsigned char public_key[crypto_box_PUBLICKEYBYTES];
    unsigned char encrypted_message[crypto_box_NONCEBYTES + 256];

    size_t private_key_len = base64_decode(base64_private_key, private_key);
    size_t public_key_len = base64_decode(base64_public_key, public_key);
    size_t message_len = base64_decode(base64_encrypted_message, encrypted_message);

    if (private_key_len != crypto_box_SECRETKEYBYTES ||
        public_key_len != crypto_box_PUBLICKEYBYTES ||
        message_len == 0) {
        ESP_LOGE(TAG, "Invalid key or message length"); // For debugging
        return false;
    }
    
    if (message_len < crypto_box_NONCEBYTES) {
        ESP_LOGE(TAG, "Message too short"); // For debugging
        return false;
    }

    unsigned char nonce[crypto_box_NONCEBYTES];
    memcpy(nonce, encrypted_message, crypto_box_NONCEBYTES);

    const unsigned char *ciphertext = encrypted_message + crypto_box_NONCEBYTES;
    size_t ciphertext_len = message_len - crypto_box_NONCEBYTES;

     *decrypted = malloc(ciphertext_len - crypto_box_MACBYTES);
     if (*decrypted == NULL) {
         ESP_LOGE(TAG, "Memory allocation failed");  // For debugging
         return false;
     }
     *decrypted_len = ciphertext_len - crypto_box_MACBYTES;

    if (crypto_box_open_easy(*decrypted, ciphertext, ciphertext_len,
                            nonce, public_key, private_key) != 0) {
        ESP_LOGE(TAG, "Decryption failed - message tampered or keys incorrect"); // For debugging
        free(*decrypted);
        *decrypted = NULL;
        return false;
    }

    ESP_LOGI(TAG, "Decrypted message: %.*s", (int)*decrypted_len, *decrypted); // For debugging
    return true;
}

bool encrypt_message(const unsigned char *private_key, const unsigned char *public_key, 
                    const unsigned char *message, size_t message_len,
                    unsigned char **encrypted, size_t *encrypted_len) {
    // Create a keypair from the provided keys
    unsigned char keypair[crypto_box_SECRETKEYBYTES + crypto_box_PUBLICKEYBYTES];
    memcpy(keypair, private_key, crypto_box_SECRETKEYBYTES);
    memcpy(keypair + crypto_box_SECRETKEYBYTES, public_key, crypto_box_PUBLICKEYBYTES);
    
    // Generate a random nonce
    unsigned char nonce[crypto_box_NONCEBYTES];
    randombytes_buf(nonce, sizeof nonce);
    
    size_t ciphertext_len = message_len + crypto_box_MACBYTES;
    unsigned char ciphertext[ciphertext_len];
    
    if (crypto_box_easy(ciphertext, message, message_len, nonce, 
                       public_key, private_key) != 0) {
        ESP_LOGE(TAG, "Encryption failed"); // For debugging
        *encrypted = NULL;
        *encrypted_len = 0;
        return false;
    }
    
    *encrypted_len = crypto_box_NONCEBYTES + ciphertext_len;
    *encrypted = malloc(*encrypted_len);
    if (*encrypted == NULL) {
        ESP_LOGE(TAG, "Memory allocation failed"); // For debugging
        *encrypted_len = 0;
        return false;
    }
    
    memcpy(*encrypted, nonce, crypto_box_NONCEBYTES);
    memcpy(*encrypted + crypto_box_NONCEBYTES, ciphertext, ciphertext_len);
    
    ESP_LOGI(TAG, "Message encrypted successfully"); // For debugging
    return true;
}

char *decrypt_encrypt(const char *base64_encrypted_message){
    unsigned char *decrypted_message = NULL;
    size_t decrypted_len = 0;

    decrypt_message(server_base64_public_key, microcontroller_base64_private_key, base64_encrypted_message, &decrypted_message, &decrypted_len);
    
    if (decrypted_message == NULL) {
        ESP_LOGE(TAG, "Decryption failed"); // For debugging
        free(decrypted_message);
        return NULL;
    }

    // Step 1: Decrypt the message
    if (!decrypt_message(server_base64_public_key, 
            microcontroller_base64_private_key, 
            base64_encrypted_message, 
            &decrypted_message, 
            &decrypted_len) || 
    !decrypted_message) {
        ESP_LOGE(TAG, "Decryption failed");
        free(decrypted_message);
        return NULL;
    }

    // Step 2: Decode keys
    unsigned char private_key[crypto_box_SECRETKEYBYTES];
    unsigned char public_key[crypto_box_PUBLICKEYBYTES];

    size_t private_key_len = base64_decode(microcontroller_base64_private_key, private_key);
    size_t public_key_len = base64_decode(server_base64_public_key, public_key);

    if (private_key_len != crypto_box_SECRETKEYBYTES ||
    public_key_len != crypto_box_PUBLICKEYBYTES) {
        ESP_LOGE(TAG, "Invalid key length");
        free(decrypted_message);
        return NULL;
    }

    // Step 3: Re-encrypt the message
    unsigned char *re_encrypted = NULL;
    size_t re_encrypted_len = 0;

    if (!encrypt_message(private_key, public_key,
        decrypted_message, decrypted_len,
        &re_encrypted, &re_encrypted_len) ||
    !re_encrypted) {
        ESP_LOGE(TAG, "Encryption failed");
        free(decrypted_message);
        return NULL;
    }

    // Step 4: Convert to Base64
    size_t b64_maxlen = sodium_base64_encoded_len(re_encrypted_len, sodium_base64_VARIANT_ORIGINAL);
    char *b64_re_encrypted = malloc(b64_maxlen);
    if (!b64_re_encrypted) {
        ESP_LOGE(TAG, "Memory allocation for base64 failed");
        free(decrypted_message);
        free(re_encrypted);
        return NULL;
    }

    if (!sodium_bin2base64(b64_re_encrypted, b64_maxlen,
            re_encrypted, re_encrypted_len,
            sodium_base64_VARIANT_ORIGINAL)) {
        ESP_LOGE(TAG, "Base64 conversion failed");
        free(b64_re_encrypted);
        free(decrypted_message);
        free(re_encrypted);
        return NULL;
    }

    // Cleanup (do NOT free b64_re_encrypted, it's returned to the caller)
    free(decrypted_message);
    free(re_encrypted);

    ESP_LOGI(TAG, "Re-encrypted message (Base64): %s", b64_re_encrypted);
    return b64_re_encrypted; // Caller must free() this later!
}