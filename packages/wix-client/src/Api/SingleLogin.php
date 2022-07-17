<?php

namespace WixCloneClient\Api;


use WixCloneClient\Core\DecryptionService;
use WP_Error;
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
        $token_encoded = urlencode($_GET['token']);
        $token_decoded = base64_decode(urldecode($token_encoded));
        $public_key = WIX_HOST_PUBLIC_KEYS;
        $data = $this->decryptionService->decrypt($public_key, $token_decoded);
        if (!$data) {
            return new WP_Error('Critical Failure');
        }

        $data = json_decode($data);
        $user_email = $data->email;
        $user = get_user_by_email($user_email);

        wp_clear_auth_cookie();
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID);
        do_action('wp_login', $user->user_login, $user);
        wp_redirect(admin_url());
        exit();
    }
}