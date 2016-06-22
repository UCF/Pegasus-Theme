$(function() {
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
            duration: 5 * 60 * 1000,
            easing: 'linear',
            step: function() {
                $num.text(commaSeparateNumber(numTextParsed - Math.floor(this.number)) + ' ft');
            },
            done: function() {
                $num
                .text('') // sometimes the animation doesn't animate the last incremention for whatever reason, so force it
                .css('width', ''); // removed forced width
            }
        });
    }

    function startNumberAnime() {
        $('.counter').each(function (index, element) {
            var $element = $(element);
            setTimeout(function () {
                animateNumber($element);
            }, 1000);
        });
    }

    function addScrollEvent() {
        $('.side-bar').height($('.content-container').height());

        $(window).scroll(function (event) {
            var scroll = $(window).scrollTop();
            $jumper.css('top', scroll);
        });
    }

    function addPlayPauseClickHandler() {
        $('.pause').find('span').on('click', function() {
            $(this).toggleClass('glyphicon-pause glyphicon-play');
            $jumper.find('div:first-child').toggleClass('space-man');
            $jumperContainer.find('.cloud,.cloud-sm,.counter,.pegasus').toggle();
        });
    }

    var $jumperContainer = $('.jumper-container'),
        $jumper = $jumperContainer.find('.jumper');

    startNumberAnime();
    addScrollEvent();
    addPlayPauseClickHandler();

});