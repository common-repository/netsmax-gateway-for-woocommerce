<?php
/*
 * Plugin Name: Netsmax Gateway For Woocommerce
 * Plugin URI: https://wordpress.org/plugins/netsmax-gateway-for-woocommerce/
 * Description: Extends WooCommerce with an Netsmax gateway payment. Manage your transactions on wordpress more conveniently.
 * Version: 1.0.4
 * Requires at least: 5.9
 * Requires PHP: 7.4
 * Author: Netsmax
 * Author URI: https://www.netsmax.com/
 * Text Domain: netsmax-gateway-for-woocommerce
 * Domain Path: /languages
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Plugin version number
defined('NETSMAX_GATEWAY_FOR_WOOCOMMERCE_VERSION') or define( 'NETSMAX_GATEWAY_FOR_WOOCOMMERCE_VERSION', '1.0.4' );

// Globally unique code, ID, component ID
defined('NETSMAX_GATEWAY_FOR_WOOCOMMERCE_NAME') or define( 'NETSMAX_GATEWAY_FOR_WOOCOMMERCE_NAME', 'netsmax-gateway-for-woocommerce' );

// API interface processing communication server
// Installation Notes link: https://api.netsmax.com/saas/woocommerce/apps/install-plugin
// Data and Privacy link: https://www.netsmax.com/terms/privacy
// Sign Up Terms link: https://www.netsmax.com/terms/singup
defined('NETSMAX_GATEWAY_FOR_WOOCOMMERCE_API_SERVER') or define( 'NETSMAX_GATEWAY_FOR_WOOCOMMERCE_API_SERVER', 'https://api.netsmax.com' );

// App key for API communication
defined('NETSMAX_GATEWAY_FOR_WOOCOMMERCE_APP_KEY') or define( 'NETSMAX_GATEWAY_FOR_WOOCOMMERCE_APP_KEY', 'D134F56C820A491A9D0DDA1BDB6E945A' );

// The minimum PHP version required by the plugin. If the version is lower than this, the plugin cannot run.
defined('NETSMAX_GATEWAY_FOR_WOOCOMMERCE_MIN_PHP_VERSION') or define( 'NETSMAX_GATEWAY_FOR_WOOCOMMERCE_MIN_PHP_VERSION', '7.4.0' );

// The plugin will not work if the WooCommerce version is lower than the minimum supported version.
defined('NETSMAX_GATEWAY_FOR_WOOCOMMERCE_MIN_WC_VERSION') or define( 'NETSMAX_GATEWAY_FOR_WOOCOMMERCE_MIN_WC_VERSION', '7.4' );

// The main entry file path of the plugin
defined('NETSMAX_GATEWAY_FOR_WOOCOMMERCE_MAIN_FILE') or define( 'NETSMAX_GATEWAY_FOR_WOOCOMMERCE_MAIN_FILE', __FILE__ );

// Plugin full URL
defined('NETSMAX_GATEWAY_FOR_WOOCOMMERCE_PLUGINS_URL') or define( 'NETSMAX_GATEWAY_FOR_WOOCOMMERCE_PLUGINS_URL', untrailingslashit( plugins_url( '/', __FILE__ ) ) );


if(!function_exists('netsmax_gateway_for_woocommerce_plugins_loaded')) {
    function netsmax_gateway_for_woocommerce_plugins_loaded() {
        // Verify that WooCommerce is installed and enabled. If WooCommerce is not enabled, this plugin will not work.
        if (!class_exists('WC_Payment_Gateway')) {
            include_once __DIR__ . '/includes/netsmax_gateway_for_woocommerce.php';
            netsmax_gateway_for_woocommerce::woo_missing_notice();
            return;
        }
        include_once __DIR__ . '/includes/services/netsmax_gateway_for_woocommerce_gateway.php';
        include_once __DIR__ . '/includes/netsmax_gateway_for_woocommerce.php';
        include_once __DIR__ . '/includes/util/netsmax_gateway_for_woocommerce_request.php';
        include_once __DIR__ . '/includes/processor/netsmax_gateway_for_woocommerce_sessions.php';
        include_once __DIR__ . '/includes/processor/netsmax_gateway_for_woocommerce_func.php';
        include_once __DIR__ . '/includes/processor/netsmax_gateway_for_woocommerce_options.php';
        include_once __DIR__ . '/includes/register/netsmax_gateway_for_woocommerce_online_payment_gateway.php';
        include_once __DIR__ . '/includes/register/netsmax_gateway_for_woocommerce_gateway_blocks_support.php';
        include_once __DIR__ . '/includes/logs/netsmax_gateway_for_woocommerce_logs.php';
        include_once __DIR__ . '/includes/client/netsmax_gateway_for_woocommerce_api-client.php';
        netsmax_gateway_for_woocommerce::plugins_loaded();
    }
}

if(!function_exists('netsmax_gateway_for_woocommerce_blocks_loaded')) {
    function netsmax_gateway_for_woocommerce_blocks_loaded()
    {
        // Verify that WooCommerce is installed and enabled. If WooCommerce is not enabled, this plugin will not work.
        if (class_exists('\Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType')) {
            include_once __DIR__ . '/includes/blocks/netsmax_gateway_for_woocommerce_blocks-support.php';
            add_action(
                'woocommerce_blocks_payment_method_type_registration',
                static function (\Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $registry) {
                    $registry->register(new netsmax_gateway_for_woocommerce_gateway_blocks_support());
                }
            );
        }
    }
}

 // load.
add_action( 'plugins_loaded', 'netsmax_gateway_for_woocommerce_plugins_loaded', 99 );

// Declare compatibility with custom order tables for WooCommerce.
add_action( 'before_woocommerce_init', function (){
    if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});

// Registers WooCommerce Blocks integration.
add_action( 'woocommerce_blocks_loaded', 'netsmax_gateway_for_woocommerce_blocks_loaded' );