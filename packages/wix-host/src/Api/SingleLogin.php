<?php

namespace WixCloneHost\Api;

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
        register_rest_route(static::$NAMESPACE, '/tenant/single_login', array(
            'methods' => 'GET',
            'callback' => [$this, 'generate_single_login_link'],
        ));
    }

    public function generate_single_login_link(WP_REST_Request $request)
    {

    }
}