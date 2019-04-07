require([
        'jquery',
        'jquery/ui',
        'mage/menu' ],
    function($) {
        window.onresize = function(event) {
            moveSorter();

        };
        $(".items.pages-items").on("a.page","click",function(){
            console.log("clicked");
        });

        $(".item .page").click(function(){
            console.log("single clicked");
        });


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
                    console.log("ajax called");
                        moveSorter()
                }
            }
        });
        setTimeout(moveSorter,300);
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

    }
);