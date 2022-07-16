<?php

class HttpService
{
    private string $auth_keys;
    private string $base_uri;

    public function __construct($auth_keys, $base_uri)
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
            ],
            'body' => $data
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