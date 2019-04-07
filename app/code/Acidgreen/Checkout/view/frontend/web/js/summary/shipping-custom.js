define([
     'jquery',
     'Magento_Checkout/js/model/quote'
     ], function($,quote){
    'use strict';    
    

    return function (Shipping) {
        return Shipping.extend({
            getValue: function () {
                var price;

                if (!this.isCalculated()) {
                    return this.notCalculatedMessage;
                }
                //price =  this.totals()['shipping_amount'];
                 var shippingMethod = quote.shippingMethod(); //add these both line
                  var price =  shippingMethod.amount;

                return this.getFormattedPrice(price);
            }
        });
    }

    
});