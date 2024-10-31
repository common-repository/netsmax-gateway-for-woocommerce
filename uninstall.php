<?php
if ( ! defined( 'ABSPATH' ) || ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

include_once __DIR__ . '/includes/processor/netsmax_gateway_for_woocommerce_options.php';
netsmax_gateway_for_woocommerce_Options::delSettings();
netsmax_gateway_for_woocommerce_Options::delVersion();
netsmax_gateway_for_woocommerce_Options::delStoreId();

