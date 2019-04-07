var config = {
    'config': {
        'mixins': {
            'Magento_Checkout/js/view/payment': {
                'Acidgreen_Checkout/js/payment-custom': true
            },
            'Magento_Checkout/js/view/summary/abstract-total': {
                'Acidgreen_Checkout/js/summary/abstract-total-custom': true
            },
            'Magento_Checkout/js/view/summary/shipping': {
                'Acidgreen_Checkout/js/summary/shipping-custom': true
            },
             'Magento_Checkout/js/model/step-navigator': {
                'Acidgreen_Checkout/js/step-navigator-custom': true
            },
             'Magento_Checkout/js/action/set-shipping-information': {
                'Acidgreen_Checkout/js/action/set-shipping-information-mixin': true
            }
        }
    }
};
