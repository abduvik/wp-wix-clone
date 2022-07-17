<?php

namespace WixCloneClient\Api;


use WixCloneClient\Core\DecryptionService;
use WP_REST_Request;

class SingleLogin
{
    private static string $NAMESPACE = 'wpcs/v1';

    private DecryptionService $decryptionService;

    public function __construct(DecryptionService $decryptionService)
    {
        $this->decryptionService = $decryptionService;

        add_action('rest_api_init', [$this, 'register_rest_routes']);
    }

    public function register_rest_routes()
    {
        register_rest_route(static::$NAMESPACE, '/single_login/verify', array(
            'methods' => 'GET',
            'callback' => [$this, 'verify_single_login'],
        ));
    }

    public function verify_single_login(WP_REST_Request $request)
    {

        return [
            'test' => false
        ];
    }
}