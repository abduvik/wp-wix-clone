<?php

namespace WixCloneClient\Features;

class UiWPCSAdminTenantSettings
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_wpcs_admin_page'], 11);
        add_action('admin_init', [$this, 'add_wpcs_admin_settings']);
    }

    public function add_wpcs_admin_page()
    {
        add_menu_page(
            'Tenant Settings',
            'Tenant Settings',
            'manage_options',
            'wpcs-admin-tenant',
            [$this, 'render_wpcs_admin_tenant_page'],
            'dashicons-networking',
            10);
    }

    public function render_wpcs_admin_tenant_page()
    {
        echo '<h1>WPCS.io Admin</h1><form method="POST" action="options.php">';
        settings_fields('wpcs-admin-tenant');
        do_settings_sections('wpcs-admin-tenant');
        submit_button();
        echo '</form>';
    }

    public function add_wpcs_admin_settings()
    {
        add_settings_section(
            'wpcs_admin_tenant_settings',
            'Host Website Settings',
            fn() => "<p>Intro text for our settings section</p>",
            'wpcs-admin-tenant'
        );

        register_setting('wpcs-admin-tenant', 'wix_host_website_url');
        add_settings_field(
            'wix_host_website_url',
            'Host URL',
            [$this, 'render_settings_field'],
            'wpcs-admin-tenant',
            'wpcs_admin_tenant_settings',
            [
                "id" => "wix_host_website_url",
                "title" => "Host URL",
                "type" => "text"
            ]
        );
    }

    function render_settings_field($args)
    {
        echo "<input type='{$args["type"]}' id'{$args["id"]}' name='{$args["id"]}' value=" . get_option($args["id"]) . ">";
    }
}



