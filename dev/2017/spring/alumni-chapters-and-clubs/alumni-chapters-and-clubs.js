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
    clubMarker;

var init = function() {
  $chapterText = $('#chapters');
  $clubText = $('#clubs');
  $memberText = $('#members');
  $saMessage = $('#sa-message');
  $saIcon = $('#sa-icon');

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
      console.log(ex);
    });
};

var getData = function() {
  var $container = $('#alumni-map'),
      dataUrl = $container.data('map'),
      chapterMarker = $container.data('chapter-marker');
  $.getJSON(dataUrl, function(data) {
    mapData = data;
    initializeMap();
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
  var $sa_control = $('#sa-control').detach()[0];
  $sa_control.addEventListener('click', toggleMapPosition);
  map.controls[google.maps.ControlPosition.RIGHT_TOP].push($sa_control);

  var $countControl = $('#alumni-info').detach()[0];
  map.controls[google.maps.ControlPosition.TOP_CENTER].push($countControl);
};

var toggleMapPosition = function(e) {
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
    icon: chapterMarker
  });

  marker.addListener('click', function() {
    var infoWindow = createInfoWindow(markerData.name, markerData.count);
    infoWindow.open(map, marker);
  });
  
  marker.setMap(map);
};

var createInfoWindow = function(name, memberCount) {
  var content = '<div class="infoWindow"><h2>' + name + '</h2><p>Members: <span class="member-count">' + memberCount + '</span></p></div>';
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

jQuery(document).ready(function() {
  init();
});
