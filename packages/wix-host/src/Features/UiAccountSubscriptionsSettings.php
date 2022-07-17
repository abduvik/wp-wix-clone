<?php

namespace WixCloneHost\Features;

use Exception;
use WixCloneHost\Core\WPCSService;
use WixCloneHost\Core\WPCSTenant;

class UiAccountSubscriptionsSettings
{
    private WPCSService $wpcsService;

    public function __construct(WPCSService $wpcsService)
    {
        $this->wpcsService = $wpcsService;

        add_action('wps_sfw_subscription_details_html', [$this, 'render_single_login'], 1, 25);
        add_action('wps_sfw_subscription_details_html', [$this, 'render_edit_domain'], 1, 30);
    }

    public function render_single_login()
    {
        echo '<a class="button">Login as: a@s.com <span class="dashicons dashicons-admin-network"></span></a>';
    }

    public function render_edit_domain($subscription_id)
    {
        $this->handle_update_subscription_domain($subscription_id);
        $domain_name = get_post_meta($subscription_id, WPCSTenant::WPCS_DOMAIN_NAME_META, true);

        echo '<h4>Website Details</h4>';
        echo "<form method='post' action=''>
                <p class='woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide'>
                    <label for='account_email'>Domain Name (optional)</label>
                    <input type='text' placeholder='www.example.com' class='woocommerce-Input woocommerce-Input--email input-text' name='domain_name' id='domain_name' value='$domain_name'>
	            </p>
	            <button class='button' type='submit'>Update</button>
	           </form><br /><br />";
        echo '<p>Before verifying a domain, make sure that its DNS contains the following settings.</p>
              <p>For the domain apex add A records with the following IPs as their values:</p>
<pre>
54.74.209.56
54.75.81.37
54.216.187.86
</pre>
              <p>If you are verifying a subdomain, create a CNAME record with the value:</p>
              <pre>public.eu1.wpcs.io</pre>';
        echo '<h4>Website Status</h4>';
    }

    public function handle_update_subscription_domain($subscription_id)
    {
        if (!isset($_POST['domain_name'])) {
            return;
        }

        $domain = sanitize_text_field($_POST['domain_name']);

        $tenant_external_id = get_post_meta($subscription_id, WPCSTenant::WPCS_TENANT_EXTERNAL_ID_META, true);

        try {
            $this->wpcsService->update_tenant_domain([
                'external_id' => $tenant_external_id,
                'domain_name' => $domain,
            ]);

            update_post_meta($subscription_id, WPCSTenant::WPCS_DOMAIN_NAME_META, $domain);
        } catch (Exception $e) {
        }
    }
}