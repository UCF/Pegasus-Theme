(function () {
    "use strict";

    var $mapContainer,
        $legend,
        $inview;

    function toggleMapLayer(e) {
        var $target = $(e.target);
        if ($target.hasClass('fa')) {
            $target = $target.parent();
        }
        $target.toggleClass('highlight');
        $target.find('span.fa').toggleClass('fa-check');
        var $mapImage = $($mapContainer.find('.map-image').eq($(this).index()));
        $mapImage.fadeToggle(1000);
    }

    function toggleLegend(index) {
        var $this = $(this);
        setTimeout(function () {
            $this.trigger('click');
        }, 1500 * index);
    }

    function startMapAnime() {
        $legend.find('li.toggle').each(toggleLegend);
    }

    // Returns a comma-separated numerical string.
    // http://stackoverflow.com/a/16228123
    function commaSeparateNumber(val) {
        while (/(\d+)(\d{3})/.test(val.toString())) {
            val = val.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        }
        return val;
    }

    // Animates increment of a number (no decimals). Handles commas.
    function animateNumber($num) {
        var numText = $num.text(),
            numTextParsed = parseInt($num.text().replace(/\D/g, ''), 10);

        $num.css({
            "visibility": "visible",
            "width": $num.width() // force fixed width to reduce flicker
        });

        $({number: 0}).animate({number: numTextParsed}, {
            duration: 1500,
            easing: 'swing',
            step: function() {
                $num.text(commaSeparateNumber(Math.ceil(this.number)));
            },
            done: function() {
                $num
                .text(numText) // sometimes the animation doesn't animate the last incremention for whatever reason, so force it
                .css('width', ''); // removed forced width
            }
        });
    }

    function startNumberAnime() {
        $('.state-stats-number').each(function (index, element) {
            var $element = $(element);
            setTimeout(function () {
                animateNumber($element);
            }, 500);
        });
    }

    function inview() {
        var found = [];
        $inview.each(function (index, element) {
            if ($(element).offset().top < $(window).scrollTop() + ($(window).outerHeight() / 2)) {
                found.push(index);
                switch ($(element).data("callback")) {
                    case 'startNumberAnime':
                        startNumberAnime();
                        break;
                    case 'startMapAnime':
                        startMapAnime();
                        break;
                }
            }
        });
        // remove element when in view
        for (var i = 0; i < found.length; i++) {
            $inview.splice(found, 1);
        }
        // turn off event handler when all element in view
        if ($inview.length === 0) {
            $(window).off('load scroll', inview);
        }
    }

    function init() {
        $mapContainer = $('.map-container');
        $legend = $('.legend');
        $inview = $('.inview');

        $legend.on('click', 'li.toggle', toggleMapLayer);
        $(window).on('load scroll', inview);
    }

    $(init);
} ());
