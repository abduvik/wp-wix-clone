<?php

namespace WixCloneClient\Core;

class HttpService
{
    private string $auth_keys;
    private string $base_uri;

    public function __construct($base_uri)
    {
        $this->base_uri = $base_uri;
    }

    public function get($uri)
    {
        $response = wp_remote_get($this->base_uri . $uri, [
            'headers' => [
                'Authorization' => "Basic " . base64_encode($this->auth_keys),
            ]
        ]);

        return json_decode($response['body']);
    }
}