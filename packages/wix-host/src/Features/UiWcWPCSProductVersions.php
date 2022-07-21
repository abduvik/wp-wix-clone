<?php

namespace WixCloneHost\Features;

use WixCloneHost\Core\WPCSService;
use WixCloneHost\Core\WPCSTenant;

class UiWcWPCSProductVersions
{
    private WPCSService $wpcsService;

    public function __construct(WPCSService $wpcsService)
    {
        $this->wpcsService = $wpcsService;

        add_action('add_meta_boxes', [$this, 'create_woocommerce_wpcs_versions_selector']);
        add_action('save_post', [$this, 'save_woocommerce_wpcs_versions_selector']);
    }

    public function create_woocommerce_wpcs_versions_selector()
    {
        add_meta_box(
            'wpcs_product_version_selector',
            'WPCS Version Selector',
            [$this, 'render_woocommerce_wpcs_versions_selector'],
            'product',
            'side',
            'high'
        );
    }

    public function render_woocommerce_wpcs_versions_selector($post)
    {
        $versions = $this->wpcsService->get_available_product_versions();

        $available_versions = array_filter($versions, function ($version) {
            return $version->statusName === 'Done';
        });

        $current_version = get_post_meta($post->ID, WPCSTenant::WPCS_PRODUCT_VERSION_META, true);

        echo '<label for="wporg_field">WPCS Version</label>';
        echo "<select name=" . WPCSTenant::WPCS_PRODUCT_VERSION_META . " class='postbox'>";
        echo '<option value="">-- Select version --</option>';
        foreach ($available_versions as $version) {
            echo selected($version->name, $current_version);
            echo "<option " . selected($version->id, $current_version) . "value='$version->id'>$version->name</option>";
        }
        echo '</select>';
    }

    public function save_woocommerce_wpcs_versions_selector($post_id)
    {
        if (array_key_exists(WPCSTenant::WPCS_PRODUCT_VERSION_META, $_POST) && $_POST['post_type'] === 'product') {
            update_post_meta(
                $post_id,
                WPCSTenant::WPCS_PRODUCT_VERSION_META,
                $_POST[WPCSTenant::WPCS_PRODUCT_VERSION_META]
            );
        }
    }
}