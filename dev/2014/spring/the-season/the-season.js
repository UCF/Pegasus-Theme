/*global jQuery */
/*!
* FitText.js 1.2
*
* Copyright 2011, Dave Rupert http://daverupert.com
* Released under the WTFPL license
* http://sam.zoy.org/wtfpl/
*
* Date: Thu May 05 14:23:00 2011 -0600
*/

(function( $ ){

  $.fn.fitText = function( kompressor, options ) {

    // Setup options
    var compressor = kompressor || 1,
        settings = $.extend({
          'minFontSize' : Number.NEGATIVE_INFINITY,
          'maxFontSize' : Number.POSITIVE_INFINITY
        }, options);

    return this.each(function(){

      // Store the object
      var $this = $(this);

      // Resizer() resizes items based on the object width divided by the compressor * 10
      var resizer = function () {
        $this.css('font-size', Math.max(Math.min($this.width() / (compressor*10), parseFloat(settings.maxFontSize)), parseFloat(settings.minFontSize)));
      };

      // Call once to set.
      resizer();

      // Call on resize. Opera debounces their resize by default.
      $(window).on('resize.fittext orientationchange.fittext', resizer);

    });

  };

})( jQuery );



// Resizing title
$('.story .story-title').fitText(0.8, { minFontSize: '33px' });


// Header img height
function storyHeadHeight() {
    if ($(window).outerWidth() > 767) {
        if ($(window).outerHeight() < $(window).outerWidth()) {
            $('#story-head').height($(window).height());
        }
        else {
            $('#story-head').height($(window).outerWidth());
        }
    }
    else {
        $('#story-head').css('height', 'auto');
    }
}
storyHeadHeight();
$(window).on('resize', function() {
    storyHeadHeight();
});


// Super fun parallax-y things
function parallax(scrolled, direction) {
    var opacity = null;
    $('.story .story-title h1').css({
        'top': (scrolled * 0.3) + 'px',
    });
    opacity = (scrolled / -($('#story-head').height() / 4) ) + 1;
    opacity = opacity > 0 ? opacity : 0;
    $('.story .story-title').css({
        'opacity': opacity,
    });
}
var lastScrollTop = 0;
$(window).on('scroll', function(e) {
    var direction = null;
    var st = $(window).scrollTop();
    if (st < lastScrollTop){
        direction = 'down';
    } else {
        direction = 'up';
    }
    lastScrollTop = st;
    parallax(st, direction);
});


// Lazyload all the photos
//$('.story .game img.lazy').lazyload({'threshold': 300});


// Set a fixed height for #game-nav so affixing behaves
var setGameNavHeight = function() {
    $('#game-nav-placeholder').css('height', $('#game-nav').outerHeight());
}
setGameNavHeight();
$(window).on('resize', function() {
    setGameNavHeight();
});


// Handle nav btn clicks, anchor jumps
var scrollToAnchor = function(elem) {
    $('html, body').animate({
        scrollTop: Math.floor(elem.offset().top - ($('#game-nav-placeholder').outerHeight() + 20 - 1))
    }, 500);
}
$('#game-nav .logo a').on('click', function(e) {
    e.preventDefault();
    var href = $(this).attr('href');
    scrollToAnchor($(href));
    window.location.hash = href;
});
$(window).on('load', function(){
    var anchor = window.location.hash.substring(1),
        section = $('#' + anchor);
    if (anchor !== null && section.length > 0) {
        scrollToAnchor(section);
    }
});


// Handle affixing/active nav btns
$(window).on('load', function() {
    $(window).scroll(-0.1);
});
$(window).bind('scroll', function() {
    // Affix nav if viewport is somewhere between the bottom of #story-head and the top of #team-stats
    if ($(window).scrollTop() > $('#story-head').height() && $(window).scrollTop() < $('#team-stats').offset().top) {
        $('#game-nav').addClass('affixed');
    }
    else {
        $('#game-nav').removeClass('affixed');
    }

    $('.game').each(function() {
        var game = $(this);
        if ($(window).scrollTop() >= (game.offset().top - $('#game-nav-placeholder').height() - 20)) { // -20 to accomodate for extra pad on .logo>a click
            $('#game-nav .logo.active').removeClass('active');
            $('#game-nav .logo-' + game.attr('id')).addClass('active');
        }
    });
});