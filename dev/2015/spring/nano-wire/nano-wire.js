
// Custom built fade in tool tip plugin

(function($) {

  $.fadeInToolTip = function(element) {

    var plugin = this,
        $triggerElement = $(element),
        $toolTipElement = $triggerElement.parent().next(),
        $toolTipClass = $toolTipElement.attr('class');

    function tootlTipCloseAnime($element) {
      $element.animate({
        top: "-=15",
        opacity: 0
      }, 500, function() {
        $element.hide();
      });
    }

    function toggleToolTip(e) {
      e.preventDefault();

      if($toolTipElement.is(':visible')) {
        tootlTipCloseAnime($toolTipElement);
      } else {
        tootlTipCloseAnime($('.' + $toolTipClass + ':visible'));
        $toolTipElement.show().animate({
          top: "+=15",
          opacity: 0.85
        });
      }
    }

    function closeToolTip() {
      $triggerElement.trigger('click');
    }

    function setupEventHandlers() {
      $triggerElement.on('click', toggleToolTip);
      $toolTipElement.on('click', '.close', closeToolTip);
    }

    function positionToolTip() {
      $toolTipElement.offset({ top: $triggerElement.offset().top - 250 });
    }

    plugin.init = function() {
      setupEventHandlers();
      positionToolTip();
    };

    plugin.init();

  };

  $.fn.fadeInToolTip = function() {

    return this.each(function() {
      if (undefined === $(this).data('fadeInToolTip')) {
        var plugin = new $.fadeInToolTip(this);

        $(this).data('fadeInToolTip', plugin);
      }
    });

  };

})(jQuery);

// Sonar Animation

function animateSonar($sonar) {
  if($sonar.hasClass('sonar-anime')) {
    // hide element because the animation reverses when the sonar-anime class is removed
    $sonar.hide();
    setTimeout(function() { $sonar.show(); }, 2000);
  }
  $sonar.toggleClass('sonar-anime');
}

// Init

function wireArticleInit() {

  // add waypoint trigger to animate in dots
  var waypoint = new Waypoint({
    element: $('.nano-wire-img'),
    handler: function(direction) {
      if(direction === "down") {
        $('.sonar').show();
        setInterval(animateSonar, 3000, $('.sonar'));
        // Tool tips must have a common class otherwise multiple tool tips will be visible
        $('.fade-in-tool-tip-trigger').addClass('show').fadeInToolTip();
        this.destroy();
      }
    },
    offset: 10
  });
}

$(wireArticleInit);
