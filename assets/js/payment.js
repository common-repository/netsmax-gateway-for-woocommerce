jQuery(function($){
    let netsmax_gateway_for_woocommerce_submit = false;
    function netsmax_gateway_for_woocommerce_submit_form_handler() {
        jQuery('#netsmax-gateway-for-woocommerce-form').hide();
        if (netsmax_gateway_for_woocommerce_submit) {
            netsmax_gateway_for_woocommerce_submit = false;
            return true;
        }
        let netsmax_gateway_for_woocommerce_callback = function (transaction) {

        };

        let netsmax_gateway_for_woocommerce_payment_data = {
            id: netsmax_gateway_for_woocommerce_payment_params.id || '',
            title: netsmax_gateway_for_woocommerce_payment_params.title || '',
            payment_page: netsmax_gateway_for_woocommerce_payment_params.payment_page || '',
            merchant_no: netsmax_gateway_for_woocommerce_payment_params.merchant_no || '',
            result: netsmax_gateway_for_woocommerce_payment_params.result || '',
            redirect: netsmax_gateway_for_woocommerce_payment_params.redirect || '',
            popup_url: netsmax_gateway_for_woocommerce_payment_params.popup_url || '',
            metadata: {
                request_id: netsmax_gateway_for_woocommerce_payment_params.request_id,
            },
            onSuccess: netsmax_gateway_for_woocommerce_callback,
            onCancel: () => {
                jQuery('#netsmax-gateway-for-woocommerce-form').show();
                jQuery(this.el).unblock();
            }
        };

        $("#netsmax-gateway-for-woocommerce-form #netsmax-gateway-for-woocommerce-payment-redirect").attr("href", netsmax_gateway_for_woocommerce_payment_data.redirect);
        jQuery("#" + netsmax_gateway_for_woocommerce_payment_data.id).empty();
        var url = netsmax_gateway_for_woocommerce_payment_data.redirect
        if (netsmax_gateway_for_woocommerce_payment_data.popup_url !== "") {
            url = netsmax_gateway_for_woocommerce_payment_data.popup_url;
        }
        var iframe = jQuery('<iframe/>', {
            src: url,
            id: 'netsmax-gateway-for-woocommerce-dialog-iframe',
            frameborder: 0,
            scrolling: 'no',
            style: 'width: 100%; height: 100%;'
        });
        jQuery('#netsmax-gateway-for-woocommerce-dialog').empty();
        jQuery('#netsmax-gateway-for-woocommerce-dialog').append(iframe).dialog({
            height: 520,
            width: 600,
            modal: true,
          /*  buttons: {
                "Cancel": function() {
                    jQuery(this).dialog("close");
                    netsmax_gateway_for_woocommerce_payment_data.onCancel();
                }
            },*/
            close: function() {
                jQuery(this).dialog("close");
                netsmax_gateway_for_woocommerce_payment_data.onCancel();
            }
        });
    }
    jQuery('#netsmax-gateway-for-woocommerce-payment-button').click(function () {
        return netsmax_gateway_for_woocommerce_submit_form_handler();
    });
    jQuery('#netsmax-gateway-for-woocommerce_form form#order_review').submit(function () {
        /*return netsmax_gateway_for_woocommerce_submit_form_handler();*/
    });
    netsmax_gateway_for_woocommerce_submit_form_handler();
});
