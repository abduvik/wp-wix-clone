<?php

namespace WixCloneHost\Core;

use HttpService;

class WPCSService
{
    private HttpService $httpService;

    public function __construct(HttpService $httpService)
    {
        $this->httpService = $httpService;
    }

    public function get_available_product_versions()
    {
        return $this->httpService->get('/versions');
    }

    public function create_tenant()
    {

    }

    public function delete_tenant()
    {

    }

    public function update_tenant_domain()
    {

    }
}
