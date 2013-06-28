$(document).ready(function($) {
    $.getScript(THEME_JS_URL + '/waypoints.min.js').done(function(script, textStatus) {

        // Invention Disclosures Reviewed by OTT
        $("#num-reviewed .year").each(function(){
            var width = $(this).width();
            $(this).data("width", width);
            $(this).width("0");
        });

        // Enable the waypoint & animation
        $("#num-reviewed").waypoint(function() {
            $(this).find(".year").each(function(){
                $(this).animate({
                    width: $(this).data('width')
                }, 1000);

                var countTo = $(this).attr('year-data');
                $(this).find('.count').countTo({
                    from: 0,
                    to: countTo,
                    speed: 1000,
                    refreshInterval: 50
                });
            });
        }, { offset: '70%', triggerOnce: true });

        $.when(
            $.getScript(THEME_JS_URL + '/rgraph/RGraph.common.effects.js'),
            $.getScript(THEME_JS_URL + '/rgraph/RGraph.common.core.js'),
            $.getScript(THEME_JS_URL + '/rgraph/RGraph.common.tooltips.js'),
            $.getScript(THEME_JS_URL + '/rgraph/RGraph.common.key.js'),
            $.getScript(THEME_JS_URL + '/rgraph/RGraph.common.dynamic.js'),
            $.getScript(THEME_JS_URL + '/rgraph/RGraph.pie.js')
        ).done(function(){
            // % of UCF Patents Granted
            $("#patents-granted").waypoint(function() {

                var data = [60, 40];

                // Create the Pie chart. The arguments are the canvas ID and the data to be shown.
                var pie = new RGraph.Pie('patents-granted-donut', data);

                // Configure the chart to look as you want.
                pie.Set('chart.linewidth', 5);
                pie.Set('chart.strokestyle', 'transparent');
                pie.Set('chart.variant', 'donut');
                pie.Set('chart.colors', ['#00A75A', '#ABD037']);

                RGraph.Effects.Pie.RoundRobin(pie, {'radius': false,'frames':60});

                var countTo = $(this).find('.donut-num').attr('year-data');
                $(this).find('.count').countTo({
                    from: 0,
                    to: countTo,
                    speed: 1000,
                    refreshInterval: 50
                });
            }, {offset: '50%', triggerOnce: 'true'});

            // # of Available for Licensing

            // % of Licenses Executed to Spinout
            $("#license-spinout").waypoint(function() {

                var data = [48, 52];

                // Create the Pie chart. The arguments are the canvas ID and the data to be shown.
                var pie = new RGraph.Pie('license-spinout-donut', data);

                // Configure the chart to look as you want.
                pie.Set('chart.linewidth', 5);
                pie.Set('chart.strokestyle', 'transparent');
                pie.Set('chart.variant', 'donut');
                pie.Set('chart.colors', ['#f26c5e', '#fac1b2']);

                RGraph.Effects.Pie.RoundRobin(pie, {'radius': false,'frames':60});

                var countTo = $(this).find('.donut-num').attr('year-data');
                $(this).find('.count').countTo({
                    from: 0,
                    to: countTo,
                    speed: 1000,
                    refreshInterval: 50
                });
            }, {offset: '50%', triggerOnce: 'true'});

            // # of Licenses Executed

            // Royalty Distribution
            $("#royalty-distribution").waypoint(function() {

                var data = [30, 28, 28, 14];

                // Create the Pie chart. The arguments are the canvas ID and the data to be shown.
                var pie = new RGraph.Pie('royalty-distribution-donut', data);

                // Configure the chart to look as you want.
                pie.Set('chart.linewidth', 5);
                pie.Set('chart.strokestyle', 'transparent');
                pie.Set('chart.variant', 'donut');
                pie.Set('chart.colors', ['#f26c5e', '#f79c89', '#fac1b2', '#fcded4']);

                RGraph.Effects.Pie.RoundRobin(pie, {'radius': false,'frames':60});

            }, {offset: '50%', triggerOnce: 'true'});
        });
    });
});

(function($) {
    $.fn.countTo = function(options) {
        // merge the default plugin settings with the custom options
        options = $.extend({}, $.fn.countTo.defaults, options || {});

        // how many times to update the value, and how much to increment the value on each update
        var loops = Math.ceil(options.speed / options.refreshInterval),
            increment = (options.to - options.from) / loops;

        return $(this).each(function() {
            var _this = this,
                loopCount = 0,
                value = options.from,
                interval = setInterval(updateTimer, options.refreshInterval);

            function updateTimer() {
                value += increment;
                loopCount++;
                $(_this).html(value.toFixed(options.decimals));

                if (typeof(options.onUpdate) == 'function') {
                    options.onUpdate.call(_this, value);
                }

                if (loopCount >= loops) {
                    clearInterval(interval);
                    value = options.to;

                    if (typeof(options.onComplete) == 'function') {
                        options.onComplete.call(_this, value);
                    }
                }
            }
        });
    };

    $.fn.countTo.defaults = {
        from: 0,  // the number the element should start at
        to: 100,  // the number the element should end at
        speed: 1000,  // how long it should take to count between the target numbers
        refreshInterval: 100,  // how often the element should be updated
        decimals: 0,  // the number of decimal places to show
        onUpdate: null,  // callback method for every time the element is updated,
        onComplete: null,  // callback method for when the element finishes updating
    };
})(jQuery);