(function ($) {
    var defaults = {
        callback: function () { },
        runOnLoad: true,
        frequency: 100,
        previousVisibility : null
    };

    var methods = {};
    methods.checkVisibility = function (element, options) {
        if (jQuery.contains(document, element[0])) {
            var previousVisibility = options.previousVisibility;
            var isVisible = element.is(':visible');
            options.previousVisibility = isVisible;
            var initialLoad = previousVisibility == null
            let e = jQuery.Event('visible_change', {'element':element, 'options':options, 'visible': isVisible});

            if (initialLoad) {
                if (options.runOnLoad) {
                    element.trigger(e);
                    options.callback(element, isVisible, initialLoad);
                }
            } else if (previousVisibility !== isVisible) {
                element.trigger(e);
                options.callback(element, isVisible, initialLoad);
            }

            setTimeout(function() {
                methods.checkVisibility(element, options);
            }, options.frequency);
        }
    };

    $.fn.visibilityChanged = function (options) {
        if(typeof options == 'function') options = {'callback': options};
        if(typeof options != 'object') options = {};

        var settings = $.extend({}, defaults, options);
        return this.each(function () {
            methods.checkVisibility($(this), settings);
        });
    };
})(jQuery);
