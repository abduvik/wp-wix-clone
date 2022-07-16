<?php

namespace WixCloneClient\Core;

class DecryptionService
{
    public static function decrypt($public_key, $data)
    {
        openssl_private_decrypt($data, $decrypted, $public_key);

        return $decrypted;
    }
}
