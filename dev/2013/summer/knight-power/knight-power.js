$(document).ready(function($) {
    // Delete annoying empty p tags
    $("p:empty").remove();

    // Display old browser alert for old browsers (note that <=IE8 are taken care of)
    if($.browser.webkit && !$.browser.safari && parseFloat($.browser.version) < 14 // Chrome
       || $.browser.mozilla && parseFloat($.browser.version) < 5 // Firefox
       || $.browser.webkit && $.browser.safari && parseFloat($.browser.version) < 5 // Safari
       ) {
        $('#error_old_browser').attr('style', 'display: block !important;');
    }

    // Intial annimation of pins
    var pinHeight = $('.pin .img').css('height');
    var pinWidth = $('.pin .img').css('width');
    $('.pin .img').delay(1000).animate({ height: 50, width: 31, 'margin-top': 0 }, 300).delay(300).animate({ height: pinHeight, width: pinWidth, 'margin-top': 25 }, 300)
    window.setTimeout(function() { $('.pin .img').removeAttr('style') }, 2000);

    // Set default map values
    var map_w = 1024,
    map_h = 580;
    if ($(window).width() < 768) {
        $('meta[name="viewport"]').attr('content', 'width=1024px');
    }
    if ($.browser.safari && $(window).width() == 768) {
        $('#error_browser_size').attr('style', 'display: none !important;');
        $('#alert_ipad_map').show();
    }
    if ($.browser.safari && $(window).width() <= 979) {
        map_w = 768;
        map_h = 430;

        // tooltip links
        $('.link a, .pin a').click(function() {
            // Reset map and tooltips
            if ($('.power-tooltip-wrapper.active').length > 0) {
                $('.power-tooltip-wrapper.active').fadeOut().removeClass('active');
            }

            var tooltipId  = '#power-' + $(this).attr('href').substring(1, $(this).attr('href').length),
            zoomToTop  = $(tooltipId).attr('data-top-small'),
            zoomToLeft = $(tooltipId).attr('data-left-small'),
            zoomRatio = 1.60;

            $('.pin').fadeOut(400);
            $('#power-map').delay(400).animate({ height: map_h * zoomRatio, width: map_w * zoomRatio, top: zoomToTop, left: zoomToLeft })
            $(tooltipId).delay(800).fadeIn().addClass('active');
        });
    } else {

        // tooltip links
        $('.link a, .pin a').click(function() {
            // Reset map and tooltips
            if ($('.power-tooltip-wrapper.active').length > 0) {
                $('.power-tooltip-wrapper.active').fadeOut().removeClass('active');
            }

            var tooltipId  = '#power-' + $(this).attr('href').substring(1, $(this).attr('href').length),
            zoomToTop  = $(tooltipId).attr('data-top'),
            zoomToLeft = $(tooltipId).attr('data-left'),
            zoomRatio = 1.75;

            $('.pin').fadeOut(400);
            $('#power-map').delay(400).animate({ height: map_h * zoomRatio, width: map_w * zoomRatio, top: zoomToTop, left: zoomToLeft })
            $(tooltipId).delay(800).fadeIn().addClass('active');
        });
    }

    // Close buttons for infoboxes onclick
    $('#power-map, .power-arrow').click(function() {
        // Reset map and tooltips
        if ($('.power-tooltip-wrapper.active').length > 0) {
            $('.power-tooltip-wrapper.active').fadeOut().removeClass('active');
            // Zooming feature is not working in IE 7 or 8 so don't place the pins on the screen
            if ($('.ie7 .pin, .ie8 .pin').length == 0) {
                $('.pin').delay(800).fadeIn();
            }
            $('#power-map').animate({ height: map_h, width: map_w, top: 0, left: 0 });
        }
    });

    // Kill the volume a bit for the generator audio
    $('#audio-generator')[0].volume = 0.2;
});