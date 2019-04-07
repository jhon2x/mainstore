define(function () {
    'use strict';

    return function (target) { // target == Result that 'Magento_Checkout/js/model/step-navigator' returns.
        

        var next = target.next;
        target.next = function() {
            var activeIndex = 0,
                code;

            steps.sort(this.sortItems).forEach(function (element, index) {
                if (element.isVisible()) {
                    //element.isVisible(false);
                    activeIndex = index;
                }
            });

            if (steps().length > activeIndex + 1) {
                code = steps()[activeIndex + 1].code;
                steps()[activeIndex + 1].isVisible(true);
                this.setHash(code);
                document.body.scrollTop = document.documentElement.scrollTop = 0;
            }
        };
        return target;
    };
});

