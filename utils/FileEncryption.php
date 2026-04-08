<?php

class FileEncryption {

    private static $key = 'TU_CLAVE_SUPER_SECRETA_32_BYTES!!'; // usa .env

    public static function encrypt($data) {
        $iv = random_bytes(16);

        $encrypted = openssl_encrypt(
            $data,
            'AES-256-CBC',
            self::$key,
            OPENSSL_RAW_DATA,
            $iv
        );

        return $iv . $encrypted; // guardamos IV + data
    }

    public static function decrypt($data) {
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);

        return openssl_decrypt(
            $encrypted,
            'AES-256-CBC',
            self::$key,
            OPENSSL_RAW_DATA,
            $iv
        );
    }
}