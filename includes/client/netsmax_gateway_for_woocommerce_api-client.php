<?php
/**
 * Created by netsmax_gateway_for_woocommerce_api-client.php
 * User: Coder.yee
 * Date: 2024/5/10
 * Time: 16:10
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
class netsmax_gateway_for_woocommerce_ApiClient
{

    const API_VERSION = '1.0';

    private bool $test_mode      = false; // 调试模式
    private string $api_server   = ''; // API服务器
    private string $merchant_no  = '';
    private string $merchant_key = '';
    private string $urlApiAppsStatus = '/saas/woocommerce/apps/status'; // 启用、禁用 应用
    private string $urlApiOrderPaymentCashier = '/saas/woocommerce/payment/cashier'; // popup redirect 预支付订单,收银台
    private string $urlApiOrderPrepaymentCashierInline = '/saas/woocommerce/cashier/inline'; // inline 预支付订单,收银台
    private string $urlApiOrderPaymentCashierInline = '/saas/woocommerce/payment/payment-inline'; // inline 预支付订单,付款
    private string $urlApiOrderCancel = '/saas/woocommerce/payment/cancel'; // cancel order
    private string $urlApiOrderQuery = '/saas/woocommerce/payment/query'; // 订单信息查询
    private string $urlApiRefund = '/saas/woocommerce/payment/refund';  // 订单退款
    private string $urlApiRefundQuery = '/saas/woocommerce/payment/refund-query'; // 订单退款信息查询

    /**
     * @var netsmax_gateway_for_woocommerce_Logs
     */
    private static $_logs;

    public function __construct(string $merchant_no, string $merchant_key, string $api_server, bool $test_mode = false)
    {
        $this->merchant_no  = $merchant_no;
        $this->merchant_key = $merchant_key;
        $this->api_server   = $api_server;
        $this->test_mode    = $test_mode;
    }

    /**
     * @param netsmax_gateway_for_woocommerce_Logs $logsInitObj
     * @return netsmax_gateway_for_woocommerce_Logs
     */
    public static function Logs($logsInitObj = '')
    {
        if(!empty($logsInitObj) && is_object($logsInitObj)) {
            self::$_logs = $logsInitObj;
        }
        return self::$_logs;
    }

    /**
     * get value
     * @param int    $user_id
     * @param string $request_id
     * @return string
     */
    public function getInlineUrl(int $user_id, string $request_id): string
    {
        $query = [
            '_t'             => time(),
            '_r'             => $request_id,
            'store-id'       => netsmax_gateway_for_woocommerce_Options::getStoreId(),
            'merchant-id'    => $this->merchant_no,
            'session-id'     => netsmax_gateway_for_woocommerce_Sessions::getUserSessionUniqueId(),
            'user-id'        => $user_id,
            'user-token'     => netsmax_gateway_for_woocommerce_Sessions::getUserToken(),
            'origin-url'     => esc_url(home_url('')),
            'x-api-language' => netsmax_gateway_for_woocommerce_Request::getLanguage(),
        ];

        ksort($query);
        $query['sign'] = $this->sign($query, '');
        $url           = $this->formatUrl($this->urlApiOrderPrepaymentCashierInline, $query);
        return $url;
    }
    /**
     * get value
     * @param string $key
     * @return null
     */
    public function get(string $key)
    {
        if(property_exists($this, $key)) {
            return $this->$key;
        }
        return null;
    }

    /**
     * set value
     * @param string $key
     * @param string $value
     * @return false|string
     */
    public function set(string $key, string $value)
    {
        if(property_exists($this, $key)) {
            return $this->$key = $value;
        }
        return false;
    }
    /**
     * apps status
     * @param array $params
     * @param array $headers
     * @return array|mixed
     */
    public function apiAppStatus(array $params, array $headers = [])
    {
        $result = $this->apiGet($this->urlApiAppsStatus, $params, $headers);
        if(empty($result)) {
            self::wc_add_notice( esc_html__('Unable to toggle gateway Enabled/Disabled try again', 'netsmax-gateway-for-woocommerce'),'error');
        }
        return $result;
    }
    /**
     * order cashier
     * @param array $params
     * @param array $headers
     * @return array|mixed
     */
    public function apiOrderCashier(array $params, array $headers = [])
    {
        $result = $this->apiGet($this->urlApiOrderPaymentCashier, $params, $headers);
        if(empty($result)) {
            self::wc_add_notice( esc_html__('Unable to process payment try again', 'netsmax-gateway-for-woocommerce'),'error');
        }
        return $result;
    }
    /**
     * order cashier inline pay
     * @param array $params
     * @param array $headers
     * @return array|mixed
     */
    public function apiOrderPaymentCashierInline(array $params, array $headers = [])
    {
        $result = $this->apiGet($this->urlApiOrderPaymentCashierInline, $params, $headers);
        if(empty($result)) {
            self::wc_add_notice( esc_html__('Unable to process payment try again', 'netsmax-gateway-for-woocommerce'),'error');
        }
        return $result;
    }

    /**
     * order cancel
     * @param array $params
     * @param array $headers
     * @return array|mixed
     */
    public function apiOrderCancel(array $params, array $headers = [])
    {
        $post = [
            'transaction_id'         => $params['transaction_id'],
            'transaction_no'         => $params['transaction_no'],
            'channel_transaction_no' => $params['channel_transaction_no'],
            'cancel_info'            => $params['cancel_info'] ?? '',
        ];
        $result = $this->apiGet($this->urlApiOrderCancel, $post, $headers);
        if(empty($result)) {
            self::wc_add_notice( esc_html__('Operation failed! Please try again.', 'netsmax-gateway-for-woocommerce'),'error');
        }
        return $result;
    }

    /**
     * order query
     * @param array $params
     * @param array $headers
     * @return array|mixed
     */
    public function apiOrderQuery(array $params, array $headers = [])
    {
        $post = [
            'transaction_id'         => $params['transaction_id'],
            'transaction_no'         => $params['transaction_no'],
            'channel_transaction_no' => $params['channel_transaction_no']
        ];
        $result = $this->apiGet($this->urlApiOrderQuery, $post, $headers);
        if(empty($result)) {
            self::wc_add_notice( esc_html__('Operation failed! Please try again.', 'netsmax-gateway-for-woocommerce'),'error');
        }
        return $result;
    }


    /**
     * order query
     * @param array $params
     * @param array $headers
     * @return array|mixed
     */
    public function apiOrderRefund(array $params, array $headers = [])
    {
        $result = $this->apiGet($this->urlApiRefund, $params, $headers);
        if(empty($result)) {
            self::wc_add_notice( esc_html__('Operation failed! Please try again.', 'netsmax-gateway-for-woocommerce'),'error');
        }
        return $result;
    }
    /**
     * order query
     * @param array $params
     * @param array $headers
     * @return array|mixed
     */
    public function apiOrderRefundQuery(array $params, array $headers = [])
    {
        $result = $this->apiGet($this->urlApiRefundQuery, $params, $headers);
        if(empty($result)) {
            self::wc_add_notice( esc_html__('Operation failed! Please try again.', 'netsmax-gateway-for-woocommerce'),'error');
        }
        return $result;
    }

    private function apiGet(string $url, array $params, array $headers = [])
    {
        $json  = wp_json_encode($params);
        if(empty($json)) {
            return [];
        }
        $query = $this->getQuery();
        $url   = $this->formatUrl($url, $query);
        $headers['x-api-request-id'] = $query['_r'];
        $headers['x-api-signature']   = $this->sign($query, $json);
        $args = [
            'headers' => array_merge($this->getHeaders(), $headers),
            'timeout' => 60,
            'body'    => $json,
        ];
        if($this->test_mode) {
            $wpResponse = wp_remote_post($url, $args);
        }else{
            $wpResponse = wp_safe_remote_post($url, $args);
        }

        if (!is_wp_error($wpResponse) && 200 === wp_remote_retrieve_response_code($wpResponse)) {
            $bodyRaw    = wp_remote_retrieve_body($wpResponse);
            $getHeaders = wp_remote_retrieve_headers($wpResponse);
            try{
                if( $bodyRaw != '' ) {
                    $response   = json_decode($bodyRaw, 1);
                    if(JSON_ERROR_NONE !== json_last_error()) {
                        throw new Exception( 'json last error code: ' . json_last_error());
                    }
                    $response = wc_clean( wp_unslash( $response ) );
                }else{
                    throw new Exception('response content is empty');
                }
            }catch (\Error|\Exception $e) {
                self::Logs()::debug(sprintf(
                    // Translators: 1: the is error message
                    esc_html__('Api response content json_decode is error: %1$s ', 'netsmax-gateway-for-woocommerce'),
                    esc_html($e->getMessage())
                ), wc_clean(wp_unslash([
                    'url'      => $url,
                    'request'  => $args,
                    'response' => [
                        'statusCode'      => wp_remote_retrieve_response_code($wpResponse),
                        'responseHeaders' => $getHeaders->getAll(),
                        'responseContent' => $bodyRaw,
                    ],
                ])));
                return [];
            }

            if (!empty($response) && $this->signVerify([], (string)$bodyRaw, (string)$getHeaders->offsetGet('x-api-signature'))) {
                self::Logs()::debug('Request interface successful.',
                    wc_clean(wp_unslash([
                        'url'      => $url,
                        'request'  => $args,
                        'response' => [
                            'statusCode'      => wp_remote_retrieve_response_code($wpResponse),
                            'responseHeaders' => $getHeaders->getAll(),
                            'responseContent' => $bodyRaw,
                        ],
                    ])));
                return $response;
            }else{
                self::Logs()::debug('Request interface successful, but signature verification failed:',
                    wc_clean(wp_unslash([
                        'url'      => $url,
                        'request'  => $args,
                        'response' => [
                            'statusCode'      => wp_remote_retrieve_response_code($wpResponse),
                            'responseHeaders' => (array)$getHeaders,
                            'responseContent' => $bodyRaw,
                        ],
                    ])));
                self::wc_add_notice( esc_html__('Unable to establish network connection! Please try again.', 'netsmax-gateway-for-woocommerce'),'error');
            }
        }else{
            self::wc_add_notice( esc_html__('Unable to establish network connection! Please try again.', 'netsmax-gateway-for-woocommerce'), 'error');
            self::Logs()::error( 'POST Request Error Response', [
                'url'      => esc_html($url),
                'request'  => wc_clean( wp_unslash( $args ) ),
                'error'    => esc_html(is_wp_error($wpResponse) ? $wpResponse->get_error_message() : ''),
                'statusCode' => esc_html( wp_remote_retrieve_response_code($wpResponse) ),
            ] );
        }

        return [];
    }


    /***
     * Asynchronous notification of successful order payment: receive, verify data legality, and process data.
     * @return array|string
     */
    public function apiGetNotify()
    {
        $getHeaders = [
            'method'         => $this->getClientRequestMethod(),
            'merchant_no'    => $this->getClientMerchantNo(),
            'app_key'        => $this->getClientAppKey(),
            'api_request_id' => $this->getClientApiRequestId(),
            'api_version'    => $this->getClientApiVersion(),
            'api_timestamp'  => $this->getClientApiTimestamp(),
            'api_user_agent' => $this->getClientUserAgent(),
            'api_signature'  => $this->getClientApiSignature(),
        ];

        if (
            ( $getHeaders['method'] !== 'POST' )
            || empty($getHeaders['api_version'])
            || empty($getHeaders['api_request_id'])
            || empty($getHeaders['api_timestamp'])
            || empty($getHeaders['api_user_agent'])
            || empty($getHeaders['api_signature'])
            || empty($getHeaders['merchant_no'])
            || empty($getHeaders['app_key'])
            || ( $getHeaders['app_key'] !== NETSMAX_GATEWAY_FOR_WOOCOMMERCE_APP_KEY)
        ) {
            return __('Request Error.', 'netsmax-gateway-for-woocommerce');
        }

        if($getHeaders['merchant_no'] != $this->merchant_no) {
            return __('Mid Error.', 'netsmax-gateway-for-woocommerce');
        }

        $getRaw  = (string)$this->getClientRaw();

        if(empty($getRaw)) {
            return __('Data Is Null.', 'netsmax-gateway-for-woocommerce');
        }

        // Verify string signature
        if(!$this->signVerify([], $getRaw, $getHeaders['api_signature'])) {
            return __('Sign Error.', 'netsmax-gateway-for-woocommerce');
        }

        $getPost = [];
        try{
            $getMap  = json_decode($getRaw, 1);
            if(JSON_ERROR_NONE !== json_last_error() || empty($getMap) || !is_array($getMap)) {
                throw new Error( __('Parsing failed: data is empty. Or it is not an array.', 'netsmax-gateway-for-woocommerce') );
            }
            $getPost = wc_clean( wp_unslash( $getMap ) );
        }catch (\Error|\Exception $e) {
            return sprintf(
            // Translators: 1: the is error message
                esc_html__('Order Api Notify Data Is Error: %1$s ', 'netsmax-gateway-for-woocommerce'),
                esc_html($e->getMessage())
            );
        }

        if(empty($getPost)) {
            return __('Data Is Null.', 'netsmax-gateway-for-woocommerce');
        }

        $dataMap = [
            'returnCode'             => wc_clean( wp_unslash( $getPost['returnCode'] ?? '' ) ),
            'transaction_id'         => wc_clean( wp_unslash( $getPost['transaction_id'] ?? '' ) ),
            'transaction_no'         => wc_clean( wp_unslash( $getPost['transaction_no'] ?? '' ) ),
            'channel_transaction_no' => wc_clean( wp_unslash( $getPost['channel_transaction_no'] ?? '' ) ),
            'payment_status'         => wc_clean( wp_unslash( $getPost['payment_status'] ?? '' ) ),
        ];
        // Return data for data comparison only
        return $dataMap;
    }

    /***
     * Asynchronous notification of successful order refund: receive, verify data legality, and process data.
     * @return array|string
     */
    public function apiGetRefundNotify()
    {
        $getHeaders = [
            'method'         => $this->getClientRequestMethod(),
            'merchant_no'    => $this->getClientMerchantNo(),
            'app_key'        => $this->getClientAppKey(),
            'api_request_id' => $this->getClientApiRequestId(),
            'api_version'    => $this->getClientApiVersion(),
            'api_timestamp'  => $this->getClientApiTimestamp(),
            'api_user_agent' => $this->getClientUserAgent(),
            'api_signature'  => $this->getClientApiSignature(),
        ];

        if (
            ($getHeaders['method'] !== 'POST')
            || empty($getHeaders['api_signature'])
            || empty($getHeaders['merchant_no'])
            || empty($getHeaders['app_key'])
            || ($getHeaders['app_key'] !== NETSMAX_GATEWAY_FOR_WOOCOMMERCE_APP_KEY)
        ) {
            return __('Request Error.', 'netsmax-gateway-for-woocommerce');
        }

        if ($getHeaders['merchant_no'] != $this->merchant_no) {
            return __('Mid Error.', 'netsmax-gateway-for-woocommerce');
        }

        $getRaw  = (string)$this->getClientRaw();

        self::Logs()::debug('order api Get Refund Notify: ', [
            'request' => [
                'header' => $getHeaders,
                'post'   => esc_html($getRaw),
            ],
        ]);

        if(empty($getRaw)) {
            return __('Data Is Null.', 'netsmax-gateway-for-woocommerce');
        }

        // Verify string signature
        if (!$this->signVerify([], $getRaw, $getHeaders['api_signature'])) {
            return __('Sign Error.', 'netsmax-gateway-for-woocommerce');
        }

        $getPost = [];
        try{
            $getMap  = json_decode($getRaw, 1);
            if(JSON_ERROR_NONE !== json_last_error() || empty($getMap) || !is_array($getMap)) {
                throw new Error( __('Parsing failed: data is empty. Or it is not an array.', 'netsmax-gateway-for-woocommerce') );
            }
            $getPost = wc_clean( wp_unslash( $getMap ) );
        }catch (\Error|\Exception $e) {
            return sprintf(
            // Translators: 1: the is error message
                esc_html__('Order Api Notify Data Is Error: %1$s ', 'netsmax-gateway-for-woocommerce'),
                esc_html($e->getMessage())
            );
        }

        if (empty($getPost)) {
            return __('Data Is Null.', 'netsmax-gateway-for-woocommerce');
        }

        $dataMap = [
            'returnCode'                    => wc_clean(wp_unslash($getPost['returnCode'] ?? '')),
            'transaction_id'                => wc_clean(wp_unslash($getPost['transaction_id'] ?? '')),
            'transaction_no'                => wc_clean(wp_unslash($getPost['transaction_no'] ?? '')),
            'channel_transaction_no'        => wc_clean(wp_unslash($getPost['channel_transaction_no'] ?? '')),
            'refund_transaction_no'         => wc_clean(wp_unslash($getPost['refund_transaction_no'] ?? '')),
            'channel_refund_transaction_no' => wc_clean(wp_unslash($getPost['channel_refund_transaction_no'] ?? '')),
            'refund_status'                 => wc_clean(wp_unslash($getPost['refund_status'] ?? '')),
        ];
        // Return data for data comparison only
        return $dataMap;
    }

    private function getHeaders(): array
    {
        $headers = [
            'Authorization'      => 'Bearer ' . $this->merchant_no,
            'Content-Type'       => 'application/json',
            'x-app-timezone'     => wp_timezone_string(),
            'x-app-handle'       => base64_encode(get_option('blogname', '-')),
            'x-app-store'        => base64_encode(get_option('siteurl', get_site_url())),
            'x-app-store-id'     => netsmax_gateway_for_woocommerce_Options::getStoreId(),
            'x-app-key'          => NETSMAX_GATEWAY_FOR_WOOCOMMERCE_APP_KEY,
            'x-app-woo-version'  => netsmax_gateway_for_woocommerce_Func::get_wc_version(),
            'x-app-wp-version'   => netsmax_gateway_for_woocommerce_Func::get_wp_version(),
            'x-app-client-ip'    => netsmax_gateway_for_woocommerce_Request::getClientIp(),
            'x-app-client-agent' => $this->getClientUserAgent(),
            'x-api-language'     => netsmax_gateway_for_woocommerce_Request::getLanguage(),
            'x-api-timestamp'    => time(),
            'x-api-version'      => self::API_VERSION,
        ];
        return $headers;
    }

    private function getQuery(): array
    {
        $query = [
            '_t' => time(),
            '_r' => netsmax_gateway_for_woocommerce_Request::getRequestId(),
        ];
        return $query;
    }

    private function formatUrl(string $url, array $query = []): string
    {
        return $this->api_server . $url . (strpos($url, '?') === false ? '?' : '&') . http_build_query($query);
    }

    /**
     * 数据签名
     * @return false|string
     */
    private function sign(array $query, string $data)
    {
        if(empty($this->merchant_key) || empty($this->merchant_no)) {
            return '';
        }
        $params     = array_merge($query, ['merchant_no' => $this->merchant_no]);
        $signString = $this->getSignString($params, $data);
        $sign       = hash_hmac( 'sha512', $signString, $this->merchant_key );
        return $sign;
    }

    /**
     * 数据签名验证
     * @param array  $query
     * @param string $data
     * @param string $sign
     * @return bool
     */
    private function signVerify(array $query, string $data, string $sign): bool
    {
        if(empty($this->merchant_no) || empty($sign)) {
            return false;
        }
        $newSign = $this->sign($query, $data);
        return $newSign && $newSign === $sign;
    }

    private function getSignString(array $arrData, string $strData): string
    {
        ksort($arrData);
        $signString    = '';
        $signConnector = '';
        foreach ($arrData as $key => $value) {
            if(is_null($value))
                continue;
            $signString .= $signConnector . $key . '=' . $value;
            $signConnector = '&';
        }
        return $signString .'&_data='. trim($strData);
    }

    // get Authorization token: merchant no
    private function getClientMerchantNo(): string
    {
        $authToken = (string)wc_clean( wp_unslash($_SERVER['HTTP_AUTHORIZATION'] ?? '' ) );
        if(empty($authToken) || strlen($authToken) < 8 || strlen($authToken) > 39) {
            return '';
        }
        preg_match('/^Bearer\s+(.*?)$/', $authToken, $matches);
        if(empty($matches[1])) {
            return '';
        }
        $mid = mb_substr( trim($matches[1] ?? ''), 0, 32);
        if(preg_match('/^[a-zA-Z0-9_\-.]+$/', $mid)) {
            return $mid;
        }
        return '';
    }

    /**
     * request is post
     * @return bool
     */
    public function isPost():bool
    {
        return $this->getClientRequestMethod() === 'POST';
    }
    /**
     * request is post or get
     * @return string
     */
    private function getClientRequestMethod()
    {
        $method = strtoupper( wc_clean( wp_unslash( $_SERVER['REQUEST_METHOD'] ?? 'GET' ) ));
        if($method === 'POST') {
            return 'POST';
        }
        return 'GET';
    }

    /**
     * sign string
     * @return string
     */
    private function getClientApiSignature() {
        return mb_substr( esc_html( wc_clean( wp_unslash( $_SERVER['HTTP_X_API_SIGNATURE'] ?? '' ) ) ), 0, 128);
    }

    /**
     * app key
     * @return string
     */
    private function getClientAppKey() {
        return mb_substr( esc_html( wc_clean( wp_unslash( $_SERVER['HTTP_X_APP_KEY'] ?? '' ) ) ), 0, 32);
    }

    /**
     *
     * @return string
     */
    private function getClientUserAgent() {
        return mb_substr( esc_html( wc_clean( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ?? '' ) ) ) ,0 ,128 );
    }

    /**
     * request id rand
     * @return string
     */
    private function getClientApiRequestId() {
        return mb_substr( esc_html( wc_clean( wp_unslash( $_SERVER['HTTP_X_API_REQUEST_ID'] ?? '' ) ) ), 0, 32);
    }

    /**
     * request time
     * @return string
     */
    private function getClientApiTimestamp() {
        return mb_substr( esc_html( wc_clean( wp_unslash( $_SERVER['HTTP_X_API_TIMESTAMP'] ?? '0' ) ) ), 0, 13);
    }

    /**
     * request version
     * @return string
     */
    private function getClientApiVersion() {
        return mb_substr( esc_html( wc_clean( wp_unslash( $_SERVER['HTTP_X_API_VERSION'] ?? '1.0.0' ) ) ), 0, 10);
    }

    /**
     * get raw
     * @return string
     */
    private function getClientRaw() {
        try{
            // This function will only be triggered to receive post raw when the API payment is successful or the refund is successful.
            // The received data is only verified but not saved. After verifying that the data signature is legal,
            // the plug-in will actively request the API to synchronize the order.
            return wc_clean( wp_unslash( (string)file_get_contents('php://input') ) );
        }catch (\Error|\Exception $e) {}
        return '';
    }

    public static function wc_add_notice($message, $notice_type = 'success', $data = []) {
        if(function_exists('wc_add_notice')) {
            wc_add_notice(esc_html($message), $notice_type, $data);
        }else{
            if(is_admin()) {
                echo wp_kses_post('<div class="' . esc_attr(netsmax_gateway_for_woocommerce_Func::get_name()) . ' ' .($notice_type == 'success' ? 'success' : 'error') .'"><p>' . esc_html($message) . '</p></div>');
            }
        }
    }
}