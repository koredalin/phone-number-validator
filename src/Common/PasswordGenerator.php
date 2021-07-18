<?php

namespace App\Common;

use App\Common\Interfaces\PasswordGeneratorInterface;

/**
 * https://deliciousbrains.com/php-encryption-methods/
 * @author Hristo
 */
class PasswordGenerator implements PasswordGeneratorInterface
{
    public function encode(string $nonCriptedPassword): string
    {
        $key = file_get_contents(__DIR__.'/sodium_key.txt');
        $nonce = file_get_contents(__DIR__.'/sodium_nonce.txt');
        $ciphertext = sodium_crypto_secretbox($nonCriptedPassword, $nonce, $key);
        $encoded = base64_encode($nonce.$ciphertext);

        return $encoded;
    }
}