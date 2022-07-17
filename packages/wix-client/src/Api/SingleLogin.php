<?php

namespace WixCloneClient\Api;


use WP_REST_Request;

class SingleLogin
{
    private static string $NAMESPACE = 'wpcs/v1';

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_rest_routes']);
    }

    public function register_rest_routes()
    {
        register_rest_route(static::$NAMESPACE, '/single_login/verify', array(
            'methods' => 'GET',
            'callback' => [$this, 'get_tenant_public_key'],
        ));
    }

    public function verify_single_login(WP_REST_Request $request)
    {

    }
}