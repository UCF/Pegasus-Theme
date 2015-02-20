
// Sonar Animation

function animateSonar($sonar) {
  if($sonar && $sonar.hasClass('sonar-anime')) {
    // hide element because the animation reverses when the sonar-anime class is removed
    $sonar.hide();
    setTimeout(function() { $sonar.show(); }, 2000);
  }
  $sonar.toggleClass('sonar-anime');
}

function setupWayPoint($fadeInToolTipTrigger) {
  // add waypoint trigger to animate in dots
  var waypoint = new Waypoint({
    element: $('.nano-wire-img'),
    handler: function(direction) {
      if(direction === "down") {
        $('.sonar').show();
        setInterval(animateSonar, 3000, $('.sonar'));
        // Tool tips must have a common class otherwise multiple tool tips will be visible
        $fadeInToolTipTrigger.addClass('show');
        this.destroy();
      }
    },
    offset: 100
  });
}

// Init

function wireArticleInit() {

  var $fadeInToolTipTrigger = $('.fade-in-tool-tip-trigger');

  setupWayPoint($fadeInToolTipTrigger);
  $fadeInToolTipTrigger.popover();

}

$(wireArticleInit);
