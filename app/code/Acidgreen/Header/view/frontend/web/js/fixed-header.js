define(['jquery'], function($) {
    'use strict';

    var body = $('body');
    var checkBuffer = 20;
    var windowEl = $(window);
    var toggleClass = 'fixed-header';
    
    windowEl.on("scroll", function() {
        var headerEl = getHeaderEl();
        var headerNavOffset = headerEl.offset();
        var isVisible = (headerNavOffset.top + headerEl.height() + checkBuffer) > windowEl.scrollTop();
        body.toggleClass(toggleClass, !isVisible);
    });

    var getHeaderEl = function() {
        return $('.page-header');
    }
});