<?php
/**
 * Created by netsmax_gateway_for_woocommerce.php
 * User: Coder.yee
 * Date: 2024/5/10
 * Time: 16:10
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class netsmax_gateway_for_woocommerce {

    public static function plugins_loaded()
    {
        self::load_plugin_textdomain();
        if (!class_exists('WC_Payment_Gateway')) {
            add_action('admin_notices', [self::class, 'woo_missing_notice']);
            return;
        }

        $wcVersion = netsmax_gateway_for_woocommerce_Func::get_wc_version();
        if (empty($wcVersion) || version_compare($wcVersion, NETSMAX_GATEWAY_FOR_WOOCOMMERCE_MIN_WC_VERSION, '<')) {
            add_action('admin_notices', [self::class, 'woocommerce_not_supported']);
            return;
        }

        if (version_compare(phpversion(), NETSMAX_GATEWAY_FOR_WOOCOMMERCE_MIN_PHP_VERSION, '<')) {
            add_action('admin_notices', [self::class, 'php_not_supported']);
            return;
        }
        if(function_exists('is_admin') && !is_admin()) {
            add_action('woocommerce_cancelled_order', [self::class, 'cancel_order'], 10, 1);
        }

        add_filter('woocommerce_payment_gateways', [self::class, 'add_gateway'], 99);
        add_filter('plugin_action_links_' . plugin_basename(NETSMAX_GATEWAY_FOR_WOOCOMMERCE_MAIN_FILE), [self::class, 'plugin_action_links']);
    }

    public static function cancel_order($order_id)
    {
        (new netsmax_gateway_for_woocommerce_Gateway())->cancel_order($order_id);
    }

    public static function before_woocommerce_init()
    {
        if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', NETSMAX_GATEWAY_FOR_WOOCOMMERCE_MAIN_FILE, true);
        }
    }


    /**
     * Registers WooCommerce Blocks integration.
     */
    public static function woocommerce_blocks_loaded()
    {
        if (class_exists('\Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType')) {
            add_action(
                'woocommerce_blocks_payment_method_type_registration',
                static function (\Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $registry) {
                    $registry->register(new netsmax_gateway_for_woocommerce_BlocksSupport());
                }
            );
        }
    }

    public static function woo_missing_notice()
    {
        if (!is_admin()) {
            return;
        }
        echo wp_kses_post('<div class="error"><p><strong>'
            . sprintf(
                    // translators: 1: is woocommerce install link
                    __('Netsmax requires WooCommerce to be installed and active. Click <a href="%1$s" class="thickbox open-plugin-details-modal">here</a> to install WooCommerce.', 'netsmax-gateway-for-woocommerce'),
                    esc_url(admin_url('plugin-install.php?tab=plugin-information&plugin=woocommerce&TB_iframe=true&width=772&height=539'))
                )
            . '</strong></p></div>');
    }

    public static function woocommerce_not_supported()
    {
        if (!is_admin()) {
            return;
        }

        echo wp_kses_post('<div class="error"><p><strong>'
            . sprintf(
                // Translators: 1: is netsmax version, 2: is WooCommerce Oldest supported version, 3: is WooCommerce The current version
                esc_html__('Netsmax %1$s requires WooCommerce %2$s or greater to be installed and active. WooCommerce %3$s is no longer supported.', 'netsmax-gateway-for-woocommerce'),
                esc_html(NETSMAX_GATEWAY_FOR_WOOCOMMERCE_VERSION),
                esc_html(NETSMAX_GATEWAY_FOR_WOOCOMMERCE_MIN_WC_VERSION),
                esc_html(defined('WC_VERSION') ? WC_VERSION : '-')
            )
            . '</strong></p></div>');

    }

    public static function php_not_supported()
    {
        if (!is_admin()) {
            return;
        }

        echo wp_kses_post('<div class="error"><p><strong>'
            . sprintf(
                // Translators: 1: is netsmax version, 2: is PHP Oldest supported version, 3: is The PHP version
                esc_html__('Netsmax %1$s The minimum PHP version required for this plugin is %2$s. You are running %3$s.', 'netsmax-gateway-for-woocommerce'),
                esc_html(NETSMAX_GATEWAY_FOR_WOOCOMMERCE_VERSION),
                esc_html(NETSMAX_GATEWAY_FOR_WOOCOMMERCE_MIN_PHP_VERSION),
                esc_html(phpversion())
            )
            . '</strong></p></div>');
    }


    public static function load_plugin_textdomain()
    {
        load_plugin_textdomain('netsmax-gateway-for-woocommerce', false, basename(dirname(NETSMAX_GATEWAY_FOR_WOOCOMMERCE_MAIN_FILE)) . '/languages');
    }

    public static function plugin_action_links($links)
    {
        if (!is_admin()) {
            return $links;
        }
        $settings_link = [
            'settings' => '<a href="'
                . esc_url(admin_url('admin.php?page=wc-settings&tab=checkout&section=netsmax-gateway-for-woocommerce'))
                . '" title="'
                . esc_attr__('View Netsmax Settings', 'netsmax-gateway-for-woocommerce')
                . '">'
                . esc_html__('Settings', 'netsmax-gateway-for-woocommerce')
                . '</a>',
        ];
        return array_merge($settings_link, $links);
    }

    public static function add_gateway($methods)
    {
        $methods[] = 'netsmax_gateway_for_woocommerce_online_payment_gateway';
        return $methods;
    }

    public function __call($method, $args)
    {
        if (!method_exists($this, $method)) {
            throw new \RuntimeException(sprintf(
                'The Main::%s is not exist.',
                esc_html($method)
            ));
        }
        return self::$method(...$args);
    }
}