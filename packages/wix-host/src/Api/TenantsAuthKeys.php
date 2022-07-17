<?php

namespace WixCloneHost\Api;

use Exception;
use WixCloneHost\Core\WPCSTenant;
use WP_Error;
use WP_REST_Request;

class TenantsAuthKeys
{
    private static string $NAMESPACE = 'wpcs/v1';

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_rest_routes']);
    }

    public function register_rest_routes()
    {
        register_rest_route(static::$NAMESPACE, '/tenant/public_keys', array(
            'methods' => 'GET',
            'callback' => [$this, 'get_tenant_public_key'],
        ));
    }

    public function get_tenant_public_key(WP_REST_Request $request)
    {
        $external_id = $request->get_param('external_id');

        try {
            $tenant = WPCSTenant::from_wpcs_external_id($external_id);
            $tenant_auth_keys = $tenant->get_auth_keys();

            return [
                'public_key' => $tenant_auth_keys['public_key']
            ];

        } catch (Exception $e) {
            return new WP_Error('not_found');
        }
    }
}
