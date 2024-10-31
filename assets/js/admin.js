jQuery(function ($) {
    'use strict';
    var netsmax_gateway_for_woocommerce_admin_settings = {
        init: function () {
            /**Since the payment extension configuration form information of woocommerce is prefixed with woocommerce, I can only follow the variable naming rules with woocommerce as the prefix.**/
            $(document.body).on('change', '#woocommerce_netsmax-gateway-for-woocommerce_testmode', function () {
                var test_merchant_key = $('#woocommerce_netsmax-gateway-for-woocommerce_test_merchant_key').parents('tr').eq(0),
                    test_merchant_no = $('#woocommerce_netsmax-gateway-for-woocommerce_test_merchant_no').parents('tr').eq(0),
                    live_merchant_key = $('#woocommerce_netsmax-gateway-for-woocommerce_live_merchant_key').parents('tr').eq(0),
                    live_merchant_no = $('#woocommerce_netsmax-gateway-for-woocommerce_live_merchant_no').parents('tr').eq(0),
                    enable_logging = $('#woocommerce_netsmax-gateway-for-woocommerce_enable_logging').parents('tr').eq(0);

                if ($(this).is(':checked')) {
                    enable_logging.show();
                    test_merchant_key.show();
                    test_merchant_no.show();
                    live_merchant_key.hide();
                    live_merchant_no.hide();
                } else {
                    enable_logging.hide();
                    test_merchant_key.hide();
                    test_merchant_no.hide();
                    live_merchant_key.show();
                    live_merchant_no.show();
                }
            });
            $(document.body).on('change', '#woocommerce_netsmax-gateway-for-woocommerce_payment_page,#woocommerce_netsmax-gateway-for-woocommerce_live_merchant_no,#woocommerce_netsmax-gateway-for-woocommerce_test_merchant_no', function () {
                // Verify payment method...
            });
            $('#woocommerce_netsmax-gateway-for-woocommerce_testmode').change();
            $('#woocommerce_netsmax-gateway-for-woocommerce_test_merchant_key, #woocommerce_netsmax-gateway-for-woocommerce_live_merchant_key').after(
                '<button class="netsmax-gateway-for-woocommerce-toggle-secret" style="height: 30px; margin-left: 2px; cursor: pointer"><span class="dashicons dashicons-visibility"></span></button>'
            );
            $('.netsmax-gateway-for-woocommerce-toggle-secret').on('click', function (event) {
                event.preventDefault();
                let $dashicon = $(this).closest('button').find('.dashicons');
                let $input = $(this).closest('tr').find('.input-text');
                let inputType = $input.attr('type');
                if ('text' == inputType) {
                    $input.attr('type', 'password');
                    $dashicon.removeClass('dashicons-hidden');
                    $dashicon.addClass('dashicons-visibility');
                } else {
                    $input.attr('type', 'text');
                    $dashicon.removeClass('dashicons-visibility');
                    $dashicon.addClass('dashicons-hidden');
                }
            });
        }
    };
    netsmax_gateway_for_woocommerce_admin_settings.init();
});
