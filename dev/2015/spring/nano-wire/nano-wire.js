
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

  // Init

  function wireArticleInit() {
    var $fadeInToolTipTrigger = $('.fade-in-tool-tip-trigger');
    $fadeInToolTipTrigger.popover();
    $.getScript(THEME_JS_URL + '/jquery.waypoints.min.js').done(function() {
      setupWayPoint($fadeInToolTipTrigger, $('.sonar'));
    });
  }

  $(wireArticleInit);

}());
