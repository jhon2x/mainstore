define([], function(){
    'use strict';    
    

    return function (Payment) {
        return Payment.extend({
             initialize: function() {
             	
                this._super();
                this.navigate();
                //this.setDefaultMethod(); test

                return this;
            }
        });
    }
});