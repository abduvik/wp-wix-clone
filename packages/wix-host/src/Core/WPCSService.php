<?php

namespace WixCloneHost\Core;

class WPCSService
{
    private HttpService $httpService;

    public function __construct(HttpService $httpService)
    {
        $this->httpService = $httpService;
    }

    public function get_available_product_versions()
    {
        return $this->httpService->get('/v1/versions');
    }

    public function create_tenant($args)
    {
        return $this->httpService->post('/v1/tenants', [
            'versionId' => $args['version_id'],
            'name' => $args['name'],
            'tenantName' => $args['tenant_name'],
            'tenantEmail' => $args['tenant_email'],
            'tenantPassword' => $args['tenant_password'],
            'tenantUserRole' => $args['tenant_user_role'],
        ]);
    }

    public function delete_tenant($args)
    {
        return $this->httpService->delete('/v1/tenants?tenantId=' . $args['external_id']);
    }

    public function update_tenant_domain($args)
    {
        return $this->httpService->post('/v1/tenants?externalId=' . $args['external_id'], [
            'setAsMainDomain' => true,
            'domainName' => $args['domain_name'],
        ]);
    }
}
