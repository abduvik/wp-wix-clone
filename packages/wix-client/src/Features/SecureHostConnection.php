<?php

namespace WixCloneClient\Features;

use WixCloneClient\Core\HttpService;

class SecureHostConnection
{
    public static string $TENANT_PUBLIC_KEY = 'TENANT_PUBLIC_KEY';

    public HttpService $httpService;

    public function __construct(HttpService $httpService)
    {
        $this->httpService = $httpService;
        add_action('wpcs_tenant_created', [$this, 'get_tenant_public_id']);
    }

    public function get_tenant_public_id($external_id)
    {
        $response = $this->httpService->get('/v1/tenant/public_keys?external_id=' . $external_id);

        $public_key = $response->public_key;

        update_option('tenant_public_key', $public_key);
    }
}