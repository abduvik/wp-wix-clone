<?php

namespace WixCloneClient\Features;

use WixCloneClient\Core\HttpService;

class SecureHostConnection
{
    public const TENANT_PUBLIC_KEY = 'TENANT_PUBLIC_KEY';

    public HttpService $httpService;

    public function __construct(HttpService $httpService)
    {
        $this->httpService = $httpService;
        add_action('wpcs_tenant_created', [$this, 'get_tenant_public_id']);
        add_action('wp_head', [$this, 'get_tenant_public_id']);
    }

    public function get_tenant_public_id($external_id)
    {
        echo get_option(static::TENANT_PUBLIC_KEY);
        $external_id = 'd5c931d4-06e9-445a-94bc-6269cf3c809c';
        
        $response = $this->httpService->get('/v1/tenant/public_keys?external_id=' . $external_id);

        $public_key = $response->public_key;

        update_option(static::TENANT_PUBLIC_KEY, $public_key);
    }
}
