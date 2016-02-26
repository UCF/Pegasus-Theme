(function() {
  var $whiteCol = $('#profile-danny-white'),
      $frostCol = $('#profile-scott-frost'),
      resizeTimer;

  function resizeHandler() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(setEqualHeights, 100);
  }

  function setEqualHeights() {
    // Unset previously set fixed heights
    $whiteCol.css('height', '');
    $frostCol.css('height', '');

    if ($(window).width() > 767) {
      var newWhiteColHeight = $whiteCol.outerHeight(),
          newFrostColHeight = $frostCol.outerHeight(),
          greaterHeight = null;

      greaterHeight = newWhiteColHeight > newFrostColHeight ? newWhiteColHeight : newFrostColHeight;
      $whiteCol.css('height', greaterHeight);
      $frostCol.css('height', greaterHeight);
    }
  }

  $(window).on('load resize', resizeHandler);
})();
