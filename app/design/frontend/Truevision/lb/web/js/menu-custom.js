define([
    'jquery',
    'jquery/ui',
    'mage/menu' ],
    function($) {
        $.widget('acidgreen.menu', $.mage.menu, {

            catNavDesktopContainerEl: undefined,
            catNavMobileContainerEl: undefined,
            categoryNavEl: undefined,

            /**
             * @private
             */
            _init: function () {
                this.catNavDesktopContainerEl = $(".inline-item.text-center");
                this.catNavMobileContainerEl = $(".section-item-content.nav-sections-item-content");
                //this.categoryNavEl = $(".navigation").detach();
                this._super();
            },
            toggle: function() {
                // close
                if ($('html').hasClass('nav-open')) {
                    $('html').removeClass('nav-open');
                    setTimeout(function () {
                        $('html').removeClass('nav-before-open');
                    }, 300);
                } else {
                // open
                    $('html').addClass('nav-before-open');
                    setTimeout(function () {
                        $('html').addClass('nav-open');
                    }, 42);
                }

                //console.log(this);
            },

            /**
             * @private
             */
            _toggleMobileMode: function () {
                this._super();
                this.catNavMobileContainerEl.append(this.categoryNavEl);
            },

            /**
             * @private
             */
            _toggleDesktopMode: function () {
                this._super();
                this.catNavDesktopContainerEl.append(this.categoryNavEl);
            }

        });

        return $.acidgreen.menu;
    }
);