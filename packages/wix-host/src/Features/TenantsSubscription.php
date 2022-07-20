<?php

namespace WixCloneHost\Features;

use Exception;
use WC_Order;
use WixCloneHost\Core\EncryptionService;
use WixCloneHost\Core\WPCSService;
use WixCloneHost\Core\WPCSTenant;

class TenantsSubscription
{
    private WPCSService $wpcsService;
    private EncryptionService $encryptionService;

    public function __construct(WPCSService $wpcsService, EncryptionService $encryptionService)
    {
        $this->wpcsService = $wpcsService;
        $this->encryptionService = $encryptionService;

        add_action('wps_sfw_after_created_subscription', [$this, 'create_tenant_when_subscription_created'], 10, 2);
//        add_action('wps_sfw_subscription_cancel', [$this, 'remove_tenant_when_subscription_expired']);
        add_action('wps_sfw_expire_subscription_scheduler', [$this, 'remove_tenant_when_subscription_expired']);
    }

    public function create_tenant_when_subscription_created($subscription_id, $order_id)
    {
        $order = new WC_Order($order_id);
        $order_items = $order->get_items();
        $product = reset($order_items);
        $wpcs_version_id = get_post_meta($product->get_product_id(), WPCSTenant::WPCS_PRODUCT_VERSION_META, true);
        $website_name = get_post_meta($order_id, WPCSTenant::WPCS_WEBSITE_NAME_META, true);
        $domain_name = get_post_meta($order_id, WPCSTenant::WPCS_DOMAIN_NAME_META, true);
        $password = wp_generate_password();

        try {
            $args = [
                'version_id' => $wpcs_version_id,
                'name' => sanitize_text_field($website_name),
                'tenant_name' => $order->get_formatted_billing_full_name(),
                'tenant_email' => $order->get_billing_email(),
                'tenant_password' => $password,
                'tenant_user_role' => 'administrator',
            ];

            if ($domain_name) {
                $args['custom_domain_name'] = sanitize_text_field($domain_name);
            }

            $new_tenant = $this->wpcsService->create_tenant($args);
            $keys = $this->encryptionService->generate_key_pair();

            update_post_meta($subscription_id, WPCSTenant::WPCS_TENANT_EXTERNAL_ID_META, $new_tenant->externalId);
            update_post_meta($subscription_id, WPCSTenant::WPCS_TENANT_PUBLIC_KEY_META, $keys['public_key']);
            update_post_meta($subscription_id, WPCSTenant::WPCS_DOMAIN_NAME_META, sanitize_text_field($domain_name));
            update_post_meta($subscription_id, WPCSTenant::WPCS_BASE_DOMAIN_NAME_META, $new_tenant->baseDomain);
            update_post_meta($subscription_id, WPCSTenant::WPCS_TENANT_PRIVATE_KEY_META, $keys['private_key']);

            $this->send_created_email([
                'email' => $order->get_billing_email(),
                'password' => $password,
                'domain' => $new_tenant->baseDomain
            ]);

        } catch (Exception $e) {
            print_r($e);
        }
    }

    public function send_created_email($args)
    {
        wp_mail($args['email'], 'Your website is being created', "
        <!doctype html>
        <html lang='en'>
        <body>
            <p>Hello,</p>
            <p>You can now login here to your new website</p>
            <p><strong>Admin Url</strong>: <a href='https://{$args['domain']}/wp-admin'>https://{$args['domain']}/wp-admin</a></p>
            <p><strong>Email</strong> : {$args['email']}</p>
            <p><strong>Password</strong> : {$args['password']}</p>
        </body>
        </html>
        ", ['Content-Type: text/html; charset=UTF-8']);
    }

    public function remove_tenant_when_subscription_expired($subscription_id)
    {
        $tenant_external_id = get_post_meta($subscription_id, WPCSTenant::WPCS_TENANT_EXTERNAL_ID_META, true);
        $this->wpcsService->delete_tenant([
            'external_id' => $tenant_external_id
        ]);
    }
}