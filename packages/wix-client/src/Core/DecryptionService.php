<?php

namespace WixCloneClient\Core;

class DecryptionService
{
    public function decrypt($public_key, $data)
    {
        openssl_public_decrypt($data, $decrypted, $public_key);

        return $decrypted;
    }
}
