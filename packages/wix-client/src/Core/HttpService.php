<?php

namespace WixCloneClient\Core;

class HttpService
{
    private string $base_uri;

    public function __construct($base_uri)
    {
        $this->base_uri = $base_uri;
    }

    public function get($uri)
    {
        $response = wp_remote_get($this->base_uri . $uri);

        return json_decode($response['body']);
    }
}