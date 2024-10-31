<?php
/**
 * Created by netsmax_gateway_for_woocommerce_sessions.php
 * User: Coder.yee
 * Date: 2024/5/10
 * Time: 17:45
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
class netsmax_gateway_for_woocommerce_Sessions {

    /**
     * @return WC_Session|WC_Session_Handler
     */
    private static function session()
    {
        if (!isset(WC()->session) || empty(WC()->session)) {
            try{
                WC()->initialize_session();
            }catch (Error $e) {
                // error message
            }

        }
        return WC()->session;
    }

    public static function setRequestId(string $requestId)
    {
        try{
            self::session()->set('netsmax_gateway_for_woocommerce_request_id', $requestId);
            return true;
        }catch (Error $e) {
            // error message
        }
        return false;
    }

    /**
     * @return string
     */
    public static function getRequestId()
    {
        try{
            return (string)self::session()->get('netsmax_gateway_for_woocommerce_request_id');
        }catch (Error $e){
            // error message
        }
        return '';
    }

    public static function setUserToken(string $gateway_id) {
        try{
            if (is_admin()) {
                return;
            }
            if(!self::session()->has_session()) {
                self::session()->set_customer_session_cookie( true );
            }
            $tokenUser = new WC_Payment_Token_CC(self::getUserToken());
            $tokenUser->set_token(self::getUserToken());
            $tokenUser->set_user_id(get_current_user_id());
            $tokenUser->set_gateway_id($gateway_id);
            $tokenUser->save();
        }catch (Error $e) {
            // error message
        }
    }


    public static function getUserToken():string {
        try{
            $userToken = self::session()->get('netsmax_gateway_for_woocommerce_user_token_id');
            if(empty($userToken)) {
                $userToken = netsmax_gateway_for_woocommerce_Request::getUUID();
                self::session()->set('netsmax_gateway_for_woocommerce_user_token_id', $userToken);
            }
            return $userToken;
        }catch (Error $e) {
            // error message
        }
        return '';
    }
    public static function getUserSessionUniqueId():string {
        try{
            return md5(self::session()->get_customer_unique_id());
        }catch (Error $e) {
            // error message
        }
        return '';
    }
}