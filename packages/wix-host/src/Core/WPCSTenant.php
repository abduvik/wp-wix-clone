<?php

namespace WixCloneHost\Core;

use Exception;

class WPCSTenant
{
    public const WPCS_WEBSITE_NAME_META = 'WPCS_WEBSITE_NAME_META';
    public const WPCS_DOMAIN_NAME_META = 'WPCS_DOMAIN_NAME_META';
    public const WPCS_PRODUCT_VERSION_META = 'WPCS_PRODUCT_VERSION_META';
    public const WPCS_TENANT_EXTERNAL_ID_META = 'WPCS_TENANT_EXTERNAL_ID_META';
    public const WPCS_TENANT_PUBLIC_KEY_META = 'WPCS_TENANT_PUBLIC_KEY_META';
    public const WPCS_TENANT_PRIVATE_KEY_META = 'WPCS_TENANT_PRIVATE_KEY_META';

    private string $subscription_id;

    public function __construct(string $subscription_id)
    {
        $this->subscription_id = $subscription_id;
    }

    /**
     * @throws Exception
     */
    public static function from_wpcs_external_id(string $wpcs_external_id)
    {
        $args = array(
            'meta_query' => array(
                array(
                    'key' => static::WPCS_TENANT_EXTERNAL_ID_META,
                    'value' => $wpcs_external_id
                )
            ),
            'post_type' => 'wps_subscriptions',
            'posts_per_page' => '1'
        );

        $subscription = get_posts($args);
        // check results ##
        if (!$subscription || is_wp_error($subscription)) {
            throw new Exception("not found");
        }

        $subscription = $subscription[0];

        return new WPCSTenant($subscription->ID);
    }

    public function get_auth_keys(): array
    {
        $public_key = get_post_meta($this->subscription_id, static::WPCS_TENANT_PUBLIC_KEY_META, true);
        $private_key = get_post_meta($this->subscription_id, static::WPCS_TENANT_PRIVATE_KEY_META, true);

        return [
            'public_key' => $public_key,
            'private_key' => $private_key,
        ];
    }
}