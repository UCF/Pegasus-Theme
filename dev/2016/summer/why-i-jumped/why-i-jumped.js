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

    function pauseJumperScoll() {
        var $jumper = $('.jumper');
        $(window).scroll(function (event) {
            var scroll = $(window).scrollTop();
            if(scroll < sideBarHeight) {
                $jumper.addClass('jumper-fixed');
                $jumper.removeClass('jumper-absolute');
                $jumper.css('top', '50%');
            } else {
                $jumper.addClass('jumper-absolute');
                $jumper.removeClass('jumper-fixed');
                $jumper.css('top', sideBarHeight + 100);
            }
        });
    }

    $('.side-bar').height($('.content').height());
    var sideBarHeight = $('.side-bar').height() - 420;

    startNumberAnime();
    pauseJumperScoll();

});