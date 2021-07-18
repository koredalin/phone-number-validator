<?php

namespace App\Common;

use App\Common\Interfaces\PasswordGeneratorInterface;

/**
 *
 * @author Hristo
 */
class PasswordGenerator implements PasswordGeneratorInterface
{
    public function encode(string $nonCriptedPassword): string
    {
        
        $key = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);

        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $ciphertext = sodium_crypto_secretbox($nonCriptedPassword, $nonce, $key);

        $encoded = base64_encode($nonce.$ciphertext);
        var_dump($encoded);

        // string 'v6KhzRACVfUCyJKCGQF4VNoPXYfeFY+/pyRZcixz4x/0jLJOo+RbeGBTiZudMLEO7aRvg44HRecC' (length=76)

        return $encoded;
    }
}
