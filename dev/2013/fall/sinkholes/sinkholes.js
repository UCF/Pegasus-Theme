$(window).load(function() {
    $('link#bootstrap-responsive-css').remove();

    /* Delete annoying empty p tags */
    $("p:empty").remove();

    $('#depression-click').click(function() {
        $('#collapse-wrapper .more-details-wrapper').fadeToggle();
    });

    $('#collapse-click').click(function() {
        $('#depression-wrapper .more-details-wrapper').fadeToggle();
    });

    $('#us-icon').click(function() {
        $('#us-wrapper').fadeToggle();
    });

    $('#warning-icon').click(function() {
        $('#warning-wrapper').fadeToggle();
    });

    $('#caution-icon').click(function() {
        $('#causes').fadeToggle();
    });

    $('#warning-icon').click(function() {
        $('#warning').fadeToggle();
    })

    // Intial annimation of pins
    $('.img-icon').each(function(index, element) {
        var iconHeight = $(this).css('height').replace('px', '');
        var iconWidth = $(this).css('width').replace('px', '');
        var newIconHeight = iconHeight * 1.50;
        var newIconWidth = iconWidth * 1.50;

        var delay = 500;
        if (index == 0) {
            delay = 1000;
        }

        $(this).delay(delay*index).animate({ height: newIconHeight, width: newIconWidth}, 300).delay(800).animate({ height: iconHeight, width: iconWidth}, 300);
        window.setTimeout(function() { $('this').removeAttr('style') }, 2400);
    });
});
