<?php

class Encryptor
{
    private $method = 'AES-256-CBC';

    public function encrypt($data, $password)
    {
        $iv = openssl_random_pseudo_bytes(16);
        $key = hash('sha256', $password, true);

        $encrypted = openssl_encrypt(
            $data,
            $this->method,
            $key,
            0,
            $iv
        );

        return base64_encode($iv . $encrypted);
    }

    public function generateKey()
    {
        return bin2hex(random_bytes(16));
    }
}