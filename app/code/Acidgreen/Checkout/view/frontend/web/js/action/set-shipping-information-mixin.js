/*jshint browser:true jquery:true*/
/*global alert*/
define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';

    return function (setShippingInformationAction) {

        return wrapper.wrap(setShippingInformationAction, function (originalAction) {
            var shippingAddress = quote.shippingAddress();
            if (shippingAddress['extension_attributes'] === undefined) {
                shippingAddress['extension_attributes'] = {};
            }

            shippingAddress['extension_attributes']['delivery_instruction'] = $("#custom_attributes_delivery_instructions").val();
            if($("#custom_attributes_newsletter_subscribe").prop('checked'))
                shippingAddress['extension_attributes']['newsletter_subscribe'] = 1;
            else
                shippingAddress['extension_attributes']['newsletter_subscribe'] = 0;
            // pass execution to original action ('Magento_Checkout/js/action/set-shipping-information')
            

            return originalAction();
        });
    };
});