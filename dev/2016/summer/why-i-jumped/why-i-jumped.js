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
            "width": $num.width() // force fixed width to reduce flicker
        });

        $({number: 0}).animate({number: numTextParsed}, {
            duration: 5 * 60 * 1000,
            easing: 'linear',
            step: function() {
                $num.text(commaSeparateNumber(numTextParsed - Math.floor(this.number)) + ' ft');
            },
            done: function() {
                $num.text('135,890');
                startNumberAnime();
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
        var $sideBar = $('.side-bar');
        $sideBar.height($('.content-container').height());
        var stopPoint = $sideBar.height() - $jumper.height() - 20;

        $(window).scroll(function (event) {
            var scroll = $(window).scrollTop();
            if (scroll > stopPoint) {
                $jumper.css('top', stopPoint);
            } else {
                $jumper.css('top', scroll);
            }
        });
    }

    function addPlayPauseClickHandler() {
        $('.pause').find('span').on('click', function() {
            $(this).toggleClass('glyphicon-pause glyphicon-play');
            $jumper.find('div:first-child').toggleClass('space-man');
            $jumperContainer.find('.cloud,.cloud-sm,.pegasus').toggle();
            $jumperContainer.find('.counter').toggleClass('counter-hidden counter-visible');
        });
    }

    var $jumperContainer = $('.jumper-container'),
        $jumper = $jumperContainer.find('.jumper');

    startNumberAnime();
    addScrollEvent();
    addPlayPauseClickHandler();

});