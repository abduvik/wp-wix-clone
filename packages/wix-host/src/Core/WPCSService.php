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
        $payload = [
            'versionId' => $args['version_id'],
            'name' => $args['name'],
            'tenantName' => $args['tenant_name'],
            'tenantEmail' => $args['tenant_email'],
            'tenantPassword' => $args['tenant_password'],
            'tenantUserRole' => $args['tenant_user_role']
        ];

        if (isset($args['custom_domain_name'])) {
            $payload['customDomainName'] = $args['custom_domain_name'];
        }

        return $this->httpService->post('/v1/tenants', $payload);
    }

    public function delete_tenant($args)
    {
        return $this->httpService->delete('/v1/tenants?tenantId=' . $args['external_id']);
    }

    public function add_tenant_domain($args)
    {
        $this->httpService->post('/v1/tenants/domains?externalId=' . $args['external_id'], [
            'setAsMainDomain' => true,
            'domainName' => $args['domain_name'],
        ]);
    }

    public function delete_tenant_domain($args)
    {
        $url = '/v1/tenants/domains?externalId=' . $args['external_id'] . "&domainName=" . $args['old_domain_name'];
        $this->httpService->delete($url);
    }
}
