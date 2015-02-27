/* Map Functions */

  var stopData = [
  {
    "name": "losAngeles",
    "path": {
      "lat": 34.049803,
      "lng": -118.244019
    }
  },
  {
    "name": "sanGabriel",
    "path": {
      "lat": 34.288126,
      "lng": -117.646754
    }
  },
  {
    "name": "flagstaff",
    "path": {
      "lat": 35.189305,
      "lng": -111.617568
    }
  },
  {
    "name": "dallas",
    "path": {
      "lat": 32.774201,
      "lng": -96.799146
    }
  },
  {
    "name": "newOrleans",
    "path": {
      "lat": 29.941955,
      "lng": -90.082897
    }
  },
  {
    "name": "seattle",
    "path": {
      "lat": 47.600265,
      "lng": -122.329726
    }
  },
  {
    "name": "ocoee",
    "path": {
      "lat": 35.065191,
      "lng": -84.462200
    }
  },
  {
    "name": "washington",
    "path": {
      "lat": 38.894405,
      "lng": -77.036539
    }
  },
  {
    "name": "newyork",
    "path": {
      "lat": 40.706175,
      "lng": -74.009207
    }
  }
];

var stops = [];
var currentIndex = 0;
var infoWindows = [];
var markers = [];
var map;

// Map Functions

// Load Google maps on document ready.
$(document).ready(function() {
  var mapScript = document.createElement('script');
  mapScript.type = "text/javascript"; 
  mapScript.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=falsev=3.exp&callback=initialize";

  document.body.appendChild(mapScript);

  $.getScript(THEME_JS_URL + '/jquery.touchSwipe.min.js').done(function() {
    $('.carousel-inner').swipe( {
      swipeLeft:function(event, direction, distance, duration, fingerCount) {
        nextStop();
      },
      swipeRight: function() {
        previousStop();
      }
    });
  });

  $('#stop-carousel').carousel('pause');
});

function initialize() {
  $.each(stopData, function(index, item) {
    stops[index] = {
      name: item.name,
      path: new google.maps.LatLng(item.path.lat, item.path.lng)
    };
  });

  $.each($('.stop-data'), function(index, item) {
    var $title = $($(item).children('.stop-header').get()).html();
    var $subtitle = $($(item).children('.stop-subtitle').get()).text();
    var $image = $($(item).children().children('.stop-image').get()).attr('src');
    var $content = $($(item).children('.stop-content').get()).html();
    stops[index].title = $title;
    stops[index].subtitle = $subtitle;
    stops[index].image = $image;
    stops[index].content = $content;
  });

  $('#btn-previous').click(function(event){
    event.preventDefault();
    previousStop();
  });

  $('#btn-next').click(function(event){
    event.preventDefault();
    nextStop();
  });

  $(window).resize(function() {
    setMapHeight();
  });

  setupMap();
  setupDesktopContent();
}

function setupMap(){
  var center = new google.maps.LatLng(37.6, -96.665);

  var styles = [
    {
      "featureType": "administrative.country",
      "stylers": [
        { "visibility": "off" }
      ]
    },{
      "featureType": "administrative.locality",
      "stylers": [
        { "visibility": "off" }
      ]
    },{
      "featureType": "administrative.neighborhood",
      "stylers": [
        { "visibility": "off" }
      ]
    },{
      "featureType": "administrative.land_parcel",
      "stylers": [
        { "visibility": "off" }
      ]
    }
  ];

  var mapOptions = {
    mapTypeId: google.maps.MapTypeId.TERRAIN,
    scrollwheel: false,
    navigationControl: false,
    mapTypeControl: false,
    scaleControl: false,
    draggable: true,
    zoom: 3,
    center: center,
    disableDefaultUI: true,
    styles: styles
  };

  map = new google.maps.Map(document.getElementById('map-canvas'),
          mapOptions);

  for (var i = 0; i < stops.length; i++) {
      addMarker(i);
  }

  setMapHeight();
  updateNavigation();
  openinfoWindow(0);
}

function addMarker(i) {
  var content = '<span class="map-stop-content">' + stops[i].title + '</span>';

  var infoWindow = new google.maps.InfoWindow({
    content: content,
    maxWidth: 250
  });

  infoWindows.push(infoWindow);

  var marker = new google.maps.Marker({
    map: map,
    animation: google.maps.Animation.DROP,
    position: stops[i].path,
    icon: {
      path: google.maps.SymbolPath.CIRCLE,
      strokeColor: '#CC9900',
      strokeWeight: 2,
      fillColor: '#000',
      fillOpacity: 0.75,
      scale: 6
    }
  });

  markers.push(marker);

  if (i > 0) {
    var line = new google.maps.Polyline({
      path : [ stops[i - 1].path, stops[i].path ],
      geodesic : true,
      strokeColor: '#CC9900',
      strokeOpacity: 1.0,
      strokeWeight: 2
    });
    line.setMap(map);
  }

  var itemId = i;

  google.maps.event.addListener(marker, 'click', function() {
      currentIndex = itemId;
      openinfoWindow(itemId);
  });
}

function setMapHeight(){
  var $mapWidth = $('#map-canvas').width();
  var $mapHeight = ($mapWidth / 4) * 3;
  $('#map-canvas').height($mapHeight);
}

function updateNavigation() {
  if (currentIndex == 0) {
    $('#btn-previous').hide();
    $('#btn-next').show();
  } else if (currentIndex == stops.length - 1) {
    $('#btn-next').hide();
    $('#btn-previous').show();
  } else {
    
    if ($('#btn-next').is(':hidden')) {
      $('#btn-next').show();
    }
    if ($('#btn-previous').is(':hidden')) {
      $('#btn-previous').show();
    }
  }
}

function nextStop(){
  if (currentIndex !== stops.length - 1) {
    currentIndex++;
  } else {
    currentIndex = 0;
  }

  openinfoWindow(currentIndex);
}

function previousStop(){
  if (currentIndex !== 0) {
    currentIndex--;
  } else {
    currentIndex = stops.length - 1;
  }
  openinfoWindow(currentIndex);
}

function openinfoWindow(index){
  map.setZoom(3);
  if ( ! infoWindows[index].visible ) {
    closeAllinfoWindows();
    updateNavigation();
    infoWindows[index].open(map, markers[index]);
    $('#stop-carousel').carousel(index);
    setDescriptionText(index);
  }
}

function closeAllinfoWindows(){
  for(var i = 0; i < infoWindows.length; i++) {
    infoWindows[i].close();
  }
}

// Non Map Functions

function setupDesktopContent() {
  for ( var i = 0; i < stops.length; i++ ) {

    var content = '<div class="item';
    if (i == 0) {
      content += ' active';
    }
    content += '"><div class="carousel-caption"><h2 class="stop-title">' + stops[i].title + '</h2></div>' +
      '<img src="' + stops[i].image + '" class="stop-image" /></div>';

      $('#stop-carousel-inner').append(content);
  }
}

function setDescriptionText(index) {
  $('#stop-subtitle, #stop-content').fadeTo(300, 0, function() {
    $('#stop-subtitle').text(stops[index].subtitle);
    $('#stop-content').html(stops[index].content);
    $('#stop-subtitle').fadeTo(275, 1);
    $('#stop-content').fadeTo(275, 1);
  });
}

