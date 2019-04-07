/*
 * Copyright (c) 2018 acidgreen. All Rights Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 *
 * Created by Tony Trinh
 * Project: Peppermayo Cloud
 * Date: 10/12/2018
 * Time: 3:4:33
 * Last modified: 10/12/18 12:22 PM
 *
 */

require([
        'jquery',
        'jquery/ui',
        'mage/menu' ],
    function($) {


        /***
         *  Move Sorter
          * @param event
         */

        // Move on window resize
        window.onresize = function(event) {
            moveSorter();
            moveCurrrency();
        };

        // Move when page is loaded
        $(document).ready(function(){
            setTimeout(moveSorter,300);
            setTimeout(moveCurrrency,300);
        });

        // Move when ajax request
        $(document).ajaxComplete(function(e, xhr, settings) {
            if(xhr) {
                let result = {};
                if (xhr.responseJSON) {
                    result = xhr.responseJSON;
                }
                else if(xhr.responseText){
                    result = JSON.parse(xhr.responseText);
                }
                if(result && result.categoryProducts) {
                    moveSorter()
                }
            }
        });

        // Move sorter function
        function moveSorter(){
            if(window.innerWidth < 768 &&
                $(".toolbar-products:first").has(".toolbar-sorter.sorter").length > 0 &&
                $(".filter-options").has(".toolbar-sorter.sorter").length == 0
            ){
                $(".filter-options").prepend($(".toolbar-products:first .toolbar-sorter.sorter"));
            }
            else if(window.innerWidth > 767 &&
                $(".filter-options").has(".toolbar-sorter.sorter").length > 0 &&
                $(".toolbar-products:first").has(".toolbar-sorter.sorter").length == 0
            ){
                $(".toolbar-products:first").append($(".filter-options .toolbar-sorter.sorter"));
            }
        }


        function moveCurrrency(){

            if(window.innerWidth < 768 &&
                $(".header-right").has("#switcher-currency").length > 0 &&
                $(".ub-mega-menu").has("#switcher-currency").length == 0
            ){
                $(".ub-mega-menu").append($("#switcher-currency"));
            }
            else if(window.innerWidth > 767 &&
                $(".ub-mega-menu").has("#switcher-currency").length > 0 &&
                $(".header-right").has("#switcher-currency").length == 0
            ){
                $(".header-right").prepend($("#switcher-currency"));
            }


        }

        // Fix Toggle button on Safari
/*
        $(".nav-toggle").click(function(e){
            $("html").removeClass("active");
        })
        setTimeout(function(){
            $("html").removeClass("active");
        },600)
        */

    }
);