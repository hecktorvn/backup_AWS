var App = (function() {
    'use strict';

    App.loaders = function() {

        //Show loading class toggle
        function toggleLoader() {
            $('.toggle-loading').on('click', function() {
                var parent = $(this).parents('.widget, .card');

                if (parent.length) {
                    parent.addClass('be-loading-active');

                    setTimeout(function() {
                        parent.removeClass('be-loading-active');
                    }, 3000);
                }
            });
        }


        //Loader show
        toggleLoader();
        $.fn.loading = function(state){
            if(!$(this).has('.be-loading')) return false;
            if(state !== true) $(this).removeClass('be-loading-active');
            else $(this).addClass('be-loading-active');
        };

        $('.be-content .card .main-content, [contentLoading]').appendSVG_load();
        $('.be-content .card .main-content, [contentLoading]').addClass('be-loading');
        $('[contentLoading]').removeAttr('contentLoading');
    };

    $.fn.appendSVG_load = function(){
        let svg = '<div class="be-spinner">';
        svg += '<svg width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">';
        svg += '        <circle fill="none" stroke-width="4" stroke-linecap="round" cx="33" cy="33" r="30" class="circle"></circle>';
        svg += '</svg>';
        svg += '</div>';

        $(this).append(svg);
    };

    return App;
})(App || {});
