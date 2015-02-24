
(function() {

  // Sonar Animation

  function animateSonar($sonar) {
    if($sonar && $sonar.hasClass('sonar-anime')) {
      // hide element because the animation reverses when the sonar-anime class is removed
      $sonar.hide();
      setTimeout(function() { $sonar.show(); }, 2000);
    }
    $sonar.toggleClass('sonar-anime');
  }

  // Setup Way Point

  function setupWayPoint($fadeInToolTipTrigger, $sonar) {
    // add waypoint trigger to animate in dots
    var waypoint = new Waypoint({
      element: $('.nano-wire-img'),
      handler: function(direction) {
        if(direction === "down") {
          $fadeInToolTipTrigger.addClass('show');
          $sonar.show();
          setInterval(animateSonar, 3000, $sonar);
          this.destroy();
        }
      },
      offset: 100
    });
  }

  // Legend Click Handlers

  function setupLegendClickHandlers() {
    $('.nano-wire-img-container').on('click', 'a', function() {
      var $triggerElement = $(this);
      $(this).popover('show');
    });

    $('.legend').on('click', 'a', function() {
      var $triggerElement = $(this);
      $('.fade-in-tool-tip-trigger').each(function() {
        if(!$(this).hasClass($triggerElement.attr('data-trigger-click'))) {
          $(this).popover('hide');
        }
      });
      $('.' + $triggerElement.attr('data-trigger-click')).popover('show');
    });

    $(document).on('click', function(event) {
      if (!$(event.target).closest('.legend').length && !$(event.target).is($(this))) {
        $('.fade-in-tool-tip-trigger').each(function() {
          if(!$(this).hasClass($(event.target).attr('data-trigger-click')) && $(event.target).attr('class') !== $(this).attr('class')) {
            $(this).popover('hide');
          }
        });
      }
    });
  }

  // Init

  function wireArticleInit() {
    var $fadeInToolTipTrigger = $('.fade-in-tool-tip-trigger');
    $fadeInToolTipTrigger.popover();
    $.getScript(THEME_JS_URL + '/jquery.waypoints.min.js').done(function() {
      setupWayPoint($fadeInToolTipTrigger, $('.sonar'));
    });
    setupLegendClickHandlers();
  }

  $(wireArticleInit);

}());
