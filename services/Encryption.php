<?php
class Encryption {
    private const METHOD = 'aes-256-cbc';
    private const KEY = 'tu_llave_secreta_de_32_caracteres'; 

    //this function is for encrypt the information of the data. 
    public static function encrypt($data) {
        // create a IV random for a encrypted
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::METHOD));
        $encrypted = openssl_encrypt($data, self::METHOD, self::KEY, 0, $iv);
        
        // We combine the IV with the encrypted data so that we can decrypt it later
        // We use base64 to make it easy to save in the database
        return base64_encode($iv . $encrypted);
    }

    //this information is for decrypt the information of a data
    public static function decrypt($data) {
        $data = base64_decode($data);
        $ivSize = openssl_cipher_iv_length(self::METHOD);
        $iv = substr($data, 0, $ivSize);
        $encrypted = substr($data, $ivSize);
        
        return openssl_decrypt($encrypted, self::METHOD, self::KEY, 0, $iv);
    }
}