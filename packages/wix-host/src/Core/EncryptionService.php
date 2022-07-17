<?php

namespace WixCloneHost\Core;

class EncryptionService
{
    public function generate_key_pair(): array
    {
        $key_resource = openssl_pkey_new([
            "digest_alg" => 'sha512',
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA
        ]);
        $public_key = openssl_pkey_get_details($key_resource)['key'];
        openssl_pkey_export($key_resource, $private_key);

        return [
            'public_key' => $public_key,
            'private_key' => $private_key
        ];
    }

    public function encrypt($private_key, $data)
    {
        openssl_private_encrypt($data, $encrypted, $private_key);

        return $encrypted;
    }
}
