<?php

use WixCloneHost\Api\SingleLogin;
use WixCloneHost\Api\TenantsAuthKeys;
use WixCloneHost\Core\EncryptionService;
use WixCloneHost\Core\HttpService;
use WixCloneHost\Core\WPCSService;
use WixCloneHost\Features\TenantsSubscription;
use WixCloneHost\Features\UiAccountSubscriptionsSettings;
use WixCloneHost\Features\UiWcTenantsCheckout;
use WixCloneHost\Features\UiWcWPCSProductVersions;
use WixCloneHost\Features\UiWPCSAdminSettings;

require_once 'vendor/autoload.php';

/**
 * @package WPCSWooSubscriptions
 * @version 1.0.0
 */
/*
Plugin Name: Wix-Clone Host
Plugin URI: https://github.com/abduvik/wp-wix-clone
Description: This plugin is used to create tenants on WPCS.io with support of WordPress, WooCommerce and Subscriptions for WooCommerce
Author: Abdu Tawfik
Version: 1.0.1
Author URI: https://www.abdu.dev
*/

define('WPCS_API_REGION', get_option('wpcs_credentials_region_setting')); // Or eu1, depending on your region.
define('WPCS_API_KEY', get_option('wpcs_credentials_api_key_setting')); // The API Key you retrieved from the console
define('WPCS_API_SECRET', get_option('wpcs_credentials_api_secret_setting')); // The API Secret you retrieved from the console

$http_service = new HttpService('https://api.' . WPCS_API_REGION . '.wpcs.io', WPCS_API_KEY . ":" . WPCS_API_SECRET);
$wpcsService = new WPCSService($http_service);
$encryptionService = new EncryptionService();

new TenantsAuthKeys();
new SingleLogin($encryptionService);
new TenantsSubscription($wpcsService, $encryptionService);
new UiWPCSAdminSettings();
new UiAccountSubscriptionsSettings($wpcsService);
new UiWcWPCSProductVersions($wpcsService);
new UiWcTenantsCheckout();
