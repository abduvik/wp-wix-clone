<?php

namespace WixCloneHost\Core;

class HttpService
{
    private string $auth_keys;
    private string $base_uri;

    public function __construct($base_uri, $auth_keys)
    {
        $this->auth_keys = $auth_keys;
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

    public function post($uri, $data)
    {
        $response = wp_remote_post($this->base_uri . $uri, [
            'method' => 'POST',
            'headers' => [
                'Authorization' => "Basic " . base64_encode($this->auth_keys),
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($data)
        ]);

        return json_decode($response['body']);
    }

    public function delete($uri)
    {
        $response = wp_remote_get($this->base_uri . $uri, [
            'method' => 'DELETE',
            'headers' => [
                'Authorization' => "Basic " . base64_encode($this->auth_keys),
            ],
        ]);

        return json_decode($response['body']);
    }
}