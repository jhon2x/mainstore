define([], function(){
    'use strict';    
    

    return function (AbstractTotal) {
        return AbstractTotal.extend({
            isFullMode: function () {
                if (!this.getTotals()) {
                    return false;
                }

                return true;//stepNavigator.isProcessed('shipping');
            }
        });
    }
});