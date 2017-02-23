var map,
    mapData,
    center,
    saControl,
    saPosition,
    $saMessage,
    $saIcon,
    isCentered,
    $chapterText,
    $clubText,
    $memberText,
    chapterCount,
    clubCount,
    memberCount,
    chapterMarker,
    clubMarker,
    activeInfoWindows;

var init = function() {
  $chapterText = $('#chapters');
  $clubText = $('#clubs');
  $memberText = $('#members');
  $saMessage = $('#sa-message');
  $saIcon = $('#sa-icon');
  activeInfoWindows = [];
  chapterCount = clubCount = memberCount = 0;

  if(window.google && google.maps) {
    getData();
  } else {
    lazyLoadGoogleMap();
  }
};

var lazyLoadGoogleMap = function() {
  $.getScript('//maps.google.com/maps/api/js?sensor=false&callback=getData&key=AIzaSyBQtVEuBQkAjfKe1HbdO-In1LgIuu1UEXk')
    .fail(function(jqxhr, settings, ex) {
      // do nothing  
    });
};

var getData = function() {
  var $container = $('#alumni-map'),
      dataUrl = $container.data('map');
  $.getJSON(dataUrl, function(data) {
    mapData = data;
    initializeMap();
    initializeTables();
  });
};

var initializeMap = function() {
  center = new google.maps.LatLng(39.905227, -95.419666);
  saPosition = new google.maps.LatLng(23.133774, 45.101088);
  var mapOptions = {
    center: center,
    zoom: 4,
    mapTypeId: google.maps.MapTypeId.SATELLITE,
    scrollwheel: false
  };
  map = new google.maps.Map(document.getElementById('alumni-map'), mapOptions);

  isCentered = true;
  createControls();

  chapterMarker = $('#alumni-map').data('chapter-marker');
  clubMarker = $('#alumni-map').data('club-marker');

  for(var i in mapData) {
    var d = mapData[i];
    if (d.chapter) {
      chapterCount += 1;
    } else {
      clubCount += 1;
    }
    memberCount += d.count;

    setTimeout(addMarker, 100 * i, d);
    setTimeout(updateLabels, 100 * i, chapterCount, clubCount, memberCount);
  }
};

var createControls = function() {
  var $saControl = $('#sa-control').detach()[0];
  $saControl.addEventListener('click', toggleMapPosition);
  map.controls[google.maps.ControlPosition.RIGHT_TOP].push($saControl);

  var $countControl = $('#alumni-info').detach()[0];
  map.controls[google.maps.ControlPosition.TOP_CENTER].push($countControl);
};

var toggleMapPosition = function(e) {
  if (map.getZoom() !== 4) {
    map.setZoom(4);
  }
  if (isCentered) {
    map.panTo(saPosition);
    $saMessage.text('Back to the U.S.A.');
    $saIcon
      .removeClass('fa-arrow-circle-o-right')
      .addClass('fa-arrow-circle-o-left');
    isCentered = false;
  } else {
    map.panTo(center);
    $saMessage.text('View Saudi Arabia Chapter');
    $saIcon
      .removeClass('fa-arrow-circle-o-left')
      .addClass('fa-arrow-circle-o-right');
    isCentered = true;
  }
};

var addMarker = function(markerData) {
  var marker = new google.maps.Marker({
    position: new google.maps.LatLng(markerData.lat, markerData.lng),
    title: markerData.name,
    animation: google.maps.Animation.DROP,
    icon: markerData.chapter ? chapterMarker : clubMarker
  });

  marker.addListener('mouseover', function() {
    var infoWindow = createInfoWindow(markerData);
    activeInfoWindows.push(infoWindow);
    infoWindow.open(map, marker);
  });

  marker.addListener('mouseout', function() {
    for(var i in activeInfoWindows) {
      var infoWindow = activeInfoWindows[i];
      infoWindow.close();
      $(infoWindow).remove();
    }
  });
  
  marker.setMap(map);
};

var createInfoWindow = function(data) {
  var pClass = data.chapter ? 'chapter' : 'club';
  var content = '<div class="infoWindow"><p class="'+ pClass + '">' + data.name + '</p></div>';
  var infoWindow = new google.maps.InfoWindow({
    content: content
  });

  return infoWindow;
};

var updateLabels = function(x, y, z) { 
  $chapterText.text(x);
  $clubText.text(y);
  $memberText.text(z);
};

var initializeTables = function() {
  var _data = mapData.sort(alphaSort),
      $chapterTable = $('#chapter-table'),
      $clubTable    = $('#club-table');

  for(var i in _data) {
    var item = _data[i];
    if ( item.chapter ) {
      $chapterTable.append($('<tr><td>' + item.name + '</td></tr>'));
    } else {
      $clubTable.append($('<tr><td>' + item.name + '</td></tr>'));
    }
  }
};

var alphaSort = function(a, b) {
  a = a.name.toLowerCase();
  b = b.name.toLowerCase();

  if (a < b) {
    return -1;
  }
  if (a > b) {
    return 1;
  }
  return 0;
};

jQuery(document).ready(function() {
  init();
});
