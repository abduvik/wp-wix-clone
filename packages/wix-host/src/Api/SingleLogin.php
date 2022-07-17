<?php

namespace WixCloneHost\Api;

use WixCloneHost\Core\EncryptionService;
use WixCloneHost\Core\WPCSTenant;
use WP_REST_Request;

class SingleLogin
{
    private static string $NAMESPACE = 'wpcs/v1';

    private EncryptionService $encryptionService;

    public function __construct(EncryptionService $encryptionService)
    {
        $this->encryptionService = $encryptionService;

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
        $subscription_id = $request->get_param('subscription_id');
        $email = $request->get_param('email');

        $domain = get_post_meta($subscription_id, WPCSTenant::WPCS_DOMAIN_NAME_META, true);
        $private_key = get_post_meta($subscription_id, WPCSTenant::WPCS_TENANT_PRIVATE_KEY_META, true);

        $login_data = [
            'email' => $email
        ];

        $token = $this->encryptionService->encrypt($private_key, $login_data);


        $loginLink = 'https://' . $domain . "/wp-json/wpcs/v1/single_login/verify?token=" . $token;

        wp_redirect($loginLink);

        exit();
    }
}