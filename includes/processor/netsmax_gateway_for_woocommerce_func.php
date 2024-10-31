<?php
/**
 * Created by netsmax_gateway_for_woocommerce_func.php
 * User: Coder.yee
 * Date: 2024/5/10
 * Time: 16:37
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
class netsmax_gateway_for_woocommerce_Func{

    /**
     * get name
     * @return string
     */
    public static function get_name()
    {
        return NETSMAX_GATEWAY_FOR_WOOCOMMERCE_NAME;
    }
    /**
     * get wordpress version
     * @return string
     */
    public static function get_wp_version()
    {
        global $wp_version;
        return !empty($wp_version) ? $wp_version : 'null';
    }
    /**
     * get woocommerce version
     * @return string
     */
    public static function get_wc_version()
    {
        try{
            if ( defined('WC_VERSION') ) {
                return WC_VERSION;
            }
            return WC()->version;
        }catch (\Error $e) {

        }
        return '';
    }

    /**
     * Verify and resolve the API server domain name
     * @param string $server
     * @param string $serverId
     * @return string
     */
    public static function parse_url(string $server, string $serverId) :string
    {
        $serverUrl = $server;
        if(!empty($serverId)) {
            $MainDomainParse = wp_parse_url($server);
            $serverId        = esc_html( substr( trim( $serverId ), 0, 64) );
            if (preg_match('/^[a-zA-Z0-9\-.]+$/', $serverId)) {
                if (is_array($MainDomainParse) && !empty($MainDomainParse['scheme']) && !empty($MainDomainParse['host'])) {
                    $serverUrl = $MainDomainParse['scheme'] . '://' . $serverId . '.' . $MainDomainParse['host'];
                    if (!empty($MainDomainParse['port'])) {
                        $serverUrl .= ':' . $MainDomainParse['port'];
                    }
                }
            }elseif(filter_var($serverId, FILTER_VALIDATE_URL)){
                $serverId       = esc_url( substr( trim( $serverId ), 0, 64) );
                $SubDomainParse = wp_parse_url($serverId);
                // Only allow our domain name
                if (is_array($SubDomainParse)
                    && !empty($SubDomainParse['scheme'])
                    && !empty($SubDomainParse['host'])
                    && stripos($SubDomainParse['host'], $MainDomainParse['host']) !== false
                    // The root domain name of the verification domain name must be the default domain name to avoid non-legal domain names.
                    && substr($SubDomainParse['host'], '-' . strlen($MainDomainParse['host'])) === $MainDomainParse['host']
                ) {
                    $serverUrl = $SubDomainParse['scheme'] . '://' . $SubDomainParse['host'];
                    if (!empty($SubDomainParse['port'])) {
                        $serverUrl .= ':' . $SubDomainParse['port'];
                    }
                }
            }
        }
        return $serverUrl;
    }

}