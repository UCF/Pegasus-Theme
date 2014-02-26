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
$('.story h1').fitText(0.8, { minFontSize: '33px' });


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
function parallax(){
    var scrolled = $(window).scrollTop();
    $('.story h1').css({
        'top': (scrolled * 0.3) + 'px',
    });
}
$(window).scroll(function(e){
    parallax();
});


// Lazyload all the photos
//$('.story .game img.lazy').lazyload({'threshold': 300});


// Set a fixed height for #game-nav so affixing behaves
var setGameNavHeight = function() {
    $('#game-nav-placeholder').css('height', $('#game-nav').height());
}
setGameNavHeight();
$(window).on('resize', function() {
    setGameNavHeight();
});


// Handle nav btn clicks, anchor jumps
var scrollToAnchor = function(elem) {
    $('body').animate({
        scrollTop: elem.offset().top - ($('#game-nav-placeholder').height() + 40)
    }, 500);
}
$('#game-nav .logo a').on('click', function(e) {
    e.preventDefault();
    scrollToAnchor($($(this).attr('href')));
});
$(window).on('load', function(){
    var anchor = window.location.hash.substring(1),
        section = $('#' + anchor);
    if (anchor !== null && section.length > 0) {
        scrollToAnchor(section);
    }
});


// Handle affixing/active nav btns
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
        if ($(window).scrollTop() >= (game.offset().top - $('#game-nav-placeholder').height() - 40)) { // -40 to accomodate for extra pad on .logo>a click
            $('#game-nav .logo.active').removeClass('active');
            $('#game-nav .logo-' + game.attr('id')).addClass('active');
        }
    });
});