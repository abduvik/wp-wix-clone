<?php

require_once 'vendor/autoload.php';

/*
Plugin Name: Wix-Clone Client
Plugin URI: https://github.com/abduvik/wp-wix-clone
Description: This plugin is used to create tenants on WPCS.io with support of WordPress, WooCommerce and YITH Subscriptions
Author: Abdu Tawfik
Version: 1.0.0
Author URI: https://www.abdu.dev
*/

use WixCloneClient\Api\SingleLogin;
use WixCloneClient\Core\DecryptionService;
use WixCloneClient\Core\HttpService;
use WixCloneClient\Features\SecureHostConnection;
use WixCloneClient\Features\UiWPCSAdminTenantSettings;

define('WIX_MAIN_HOST_URL', get_option('wix_host_website_url'));
define('WIX_HOST_PUBLIC_KEYS', get_option('tenant_public_key'));


$httpService = new HttpService(WIX_MAIN_HOST_URL . '/wp-json/wpcs');
new SecureHostConnection($httpService);

$decryptionService = new DecryptionService();
new SingleLogin($decryptionService);
new UiWPCSAdminTenantSettings();