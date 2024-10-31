<?php
/**
 * Created by netsmax_gateway_for_woocommerce_logs.php
 * User: Coder.yee
 * Date: 2024/5/10
 * Time: 16:20
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * @name netsmax_gateway_for_woocommerce_Logs
 * The logging service is only enabled in test mode.
 */
final class netsmax_gateway_for_woocommerce_Logs
{
    private static \WC_Logger $_log;
    public static bool $enable   = false; // Log saving: true:enable, false:disable;
    public static string $handle = '';

    public function __construct(string $handle = '', bool $enable = false)
    {
        self::$enable = $enable;
        self::$handle = $handle;
    }

    private static function log(string $message, $level = \WC_Log_Levels::NOTICE): bool
    {
        if (class_exists('WC_Logger') && self::$enable) {
            if (empty(self::$_log)) {
                self::$_log = new \WC_Logger();
            }
            return self::$_log->add(self::$handle, $message, $level);
        }
        return false;
    }

    private static function append(string $level, string $message, array $context = []): bool
    {
        if( self::$enable !== true) {
            return false;
        }
        $logs = @wp_json_encode(
            [
                'message' => trim($message),
                'context' => wp_unslash($context),
            ],
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
        return self::log($logs, $level);
    }

    public static function debug(string $message, array $context = []): bool
    {
        return self::append('DEBUG', $message, $context);
    }
    public static function error(string $message, array $context = []): bool
    {
        return self::append('ERROR', $message, $context);
    }
    public static function warning(string $message, array $context = []): bool
    {
        return self::append('WARNING', $message, $context);
    }
    public static function info(string $message, array $context = []): bool
    {
        return self::append('INFO', $message, $context);
    }
}