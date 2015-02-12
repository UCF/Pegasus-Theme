/* Map Functions */

  var stopData = [
  {
    "name": "losAngeles",
    "path": {
      "lat": 34.049803,
      "lng": -118.244019
    },
    "title": "Los Angeles, California",
    "subtitle": "Louie Perez, musician",
    "image": "http://localhost/wordpress/pegasus/wp-content/uploads/sites/7/2015/02/on-the-road-edited-2.jpg",
    "imgThunbmail": "http://localhost/wordpress/pegasus/wp-content/uploads/sites/7/2015/02/on-the-road-edited-2-150x150.jpg",
    "content": "<p>\"I had never heard of Los Lobos, but [their] music is really nice. The advice [Perez] gave was life changing: 'Don't let anyone turn you away from your dreams. Be the person you want to be. You've got to find your own road in life to reach your dreams.' Now we're friends on Facebook and Instagram, so we still keep in contact.\"</p>"
  },
  {
    "name": "sanGabriel",
    "path": {
      "lat": 34.288126,
      "lng": -117.646754
    },
    "title": "Bridge to Nowhere, <br /><span class='smaller-title'>San Gabriel Mountains, California</span>",
    "subtitle": "Bungee Jumping",
    "image": "http://localhost/wordpress/pegasus/wp-content/uploads/sites/7/2015/02/on-the-road-edited-2.jpg",
    "imgThunbmail": "http://localhost/wordpress/pegasus/wp-content/uploads/sites/7/2015/02/on-the-road-edited-2-150x150.jpg",
    "content": "<p>\"That was a great symbol of what we were going through on the road trip because it [represented] shedding your old life and jumping into a new one. When I jumped, it was like everything was going in slow motion. I could see the mountains, the crystal-clear water that was beneath me, and I could hear my crew screaming my name.\"</p>"
  },
  {
    "name": "flagstaff",
    "path": {
      "lat": 35.189305,
      "lng": -111.617568
    },
    "title": "Flagstaff, Arizona",
    "subtitle": "Nikki Cooley, Fifth World Discoveries",
    "image": "http://localhost/wordpress/pegasus/wp-content/uploads/sites/7/2015/02/on-the-road-edited-2.jpg",
    "imgThunbmail": "http://localhost/wordpress/pegasus/wp-content/uploads/sites/7/2015/02/on-the-road-edited-2-150x150.jpg",
    "content": "<p>\"Nikki was the first Navajo to get a Colorado River guide license, and she runs a nonprofit that trains other Native Americans to become guides. Her story of overcoming so many problems growing up reminded me of my own experiences. She made me stop and think that there are people out there who have been through a lot worse than me. She broke down on camera, and to me showing that weakness is what makes her the leader she is. She’s not a CEO, but she has the same skillset to be successful in a role like that.\"</p>"
  },
  {
    "name": "dallas",
    "path": {
      "lat": 32.774201,
      "lng": -96.799146
    },
    "title": "Dallas, Texas",
    "subtitle": "Randall Stephenson, AT&T CEO",
    "image": "http://localhost/wordpress/pegasus/wp-content/uploads/sites/7/2015/02/on-the-road-edited-2.jpg",
    "imgThunbmail": "http://localhost/wordpress/pegasus/wp-content/uploads/sites/7/2015/02/on-the-road-edited-2-150x150.jpg",
    "content": "<p>\"Both of the CEOs we met were breakthroughs for me because they have impressive titles but also backgrounds with similarities to ours. Stephenson, for example, told us about going to college and [interacting with] people with different colored skin. It made him realize there was more out there in the world than what he was used to. I think the main thing I learned from him was not to be afraid to go outside of your comfort zone.\"</p>"
  },
  {
    "name": "newOrleans",
    "path": {
      "lat": 29.941955,
      "lng": -90.082897
    },
    "title": "New Orleans, Louisiana",
    "subtitle": "Leah Chase, Dooky Chase's Restaurant owner/chef",
    "image": "http://localhost/wordpress/pegasus/wp-content/uploads/sites/7/2015/02/on-the-road-edited-2.jpg",
    "imgThunbmail": "http://localhost/wordpress/pegasus/wp-content/uploads/sites/7/2015/02/on-the-road-edited-2-150x150.jpg",
    "content": "<p>\"She is living history. She's fed President Obama, Martin Luther King Jr. and lot of civil rights activists in her restaurant, which has been open since 1941. And she was really not afraid to speak her mind. A piece of advice I took from her was that there's no one to blame for your problems, except yourself. Then she fed us. I had gumbo and it was amazing.\"</p>"
  },
  {
    "name": "seattle",
    "path": {
      "lat": 47.600265,
      "lng": -122.329726
    },
    "title": "Seattle, Washington",
    "subtitle": "Howard Schultz, Starbucks CEO",
    "image": "http://localhost/wordpress/pegasus/wp-content/uploads/sites/7/2015/02/on-the-road-edited-2.jpg",
    "imgThunbmail": "http://localhost/wordpress/pegasus/wp-content/uploads/sites/7/2015/02/on-the-road-edited-2-150x150.jpg",
    "content": "<p>\"I expected him to walk in with a bunch of public relations people, but he walked in by himself, [and knew our] names and backstories. We were shocked. He said, 'Take away the title, take away the job, and we’re all the same.' He grew up in the Bronx and it was rough for him. That was an eye-opener because it made me believe that even with my background, I could one day be a CEO. His story honestly changed my life. Then he gave us autographed books and free coffee.\"</p>"
  },
  {
    "name": "ocoee",
    "path": {
      "lat": 35.065191,
      "lng": -84.462200
    },
    "title": "Ocoee River, Tennessee",
    "subtitle": "Whitewater Rafting",
    "image": "http://localhost/wordpress/pegasus/wp-content/uploads/sites/7/2015/02/on-the-road-edited-2.jpg",
    "imgThunbmail": "http://localhost/wordpress/pegasus/wp-content/uploads/sites/7/2015/02/on-the-road-edited-2-150x150.jpg",
    "content": "<p>\"That was definitely a team-building experience because we had to work together. I actually can’t swim, but I had a life vest on so when we got to a resting spot I jumped out of the raft and floated around. Rafting was probably my favorite experience.\"</p>"
  },
  {
    "name": "washington",
    "path": {
      "lat": 38.894405,
      "lng": -77.036539
    },
    "title": "Washington, D.C.",
    "subtitle": "Arne Duncan, U.S. Secretary of Education",
    "image": "http://localhost/wordpress/pegasus/wp-content/uploads/sites/7/2015/02/on-the-road-edited-2.jpg",
    "imgThunbmail": "http://localhost/wordpress/pegasus/wp-content/uploads/sites/7/2015/02/on-the-road-edited-2-150x150.jpg",
    "content": "<p>\"We were actually at the Department of Education interviewing Alejandra Ceja [the executive director of the White House Initiative on Educational Excellence for Hispanics], and after we finished some interns came up to our RV and said, 'Everyone saw your RV and word got all the way up to Arne Duncan, and he wants you to interview him.' [Duncan] told us, 'Try and figure out over time what you would do everyday because you love to do it.'\"</p>"
  },
  {
    "name": "newyork",
    "path": {
      "lat": 40.706175,
      "lng": -74.009207
    },
    "title": "New York City, New York",
    "subtitle": "John Legend, musician",
    "image": "http://localhost/wordpress/pegasus/wp-content/uploads/sites/7/2015/02/on-the-road-edited-2.jpg",
    "imgThunbmail": "http://localhost/wordpress/pegasus/wp-content/uploads/sites/7/2015/02/on-the-road-edited-2-150x150.jpg",
    "content": "<p>\"We went to his studio, and he had just finished recording. He … said, 'Hi, I'm John.' And I [replied], 'Hi, I'm John.' I had to do it; it was kind of funny. He's a smart guy. He went to college at 16, graduated at 20 and actually did music as a gig at night. His advice was to follow your own road in life and don't let others turn you away from what you want to do. He was also one of those people with a big title, big name, who was very human.\"</p>"
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
  var script = document.createElement('script');
  script.type = 'text/javascript';
  script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=falsev=3.exp&' +
      'callback=initialize';
  document.body.appendChild(script);
  $('#stop-carousel').carousel("pause");
});

function initialize() {

      $.each(stopData, function(index, item) {
        stops[index] = {
          name: item.name,
          path: new google.maps.LatLng(item.path.lat, item.path.lng),
          title: item.title,
          subtitle: item.subtitle,
          image: item.image,
          content: item.content
      };
    });

    setupMap();
    setupDesktopContent();
    setupMobileContent();
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
  var content = stops[i].title;

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
  adjustContent();
});

function updateNavigation() {
  if (currentIndex == 0) {
    $('#btn-previous').hide();
  } else if (currentIndex == stops.length - 1) {
    $('#btn-next').hide();
  } else {
    
    if ($('#btn-next').is(':hidden')) {
      $('#btn-next').show();
    }
    $('#previous-location').text(stops[currentIndex - 1].title);
    if ($('#btn-previous').is(':hidden')) {
      $('#btn-previous').show();
    }
  }
  if (stops[currentIndex + 1]) {
    $('#next-location').html(stops[currentIndex + 1].title);
  }
  if (stops[currentIndex - 1]) {
    $('#previous-location').html(stops[currentIndex - 1].title);
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
    console.log(index);
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
    content += '"><div class="carousel-caption"><h4 class="stop-title">' + stops[i].title + '</h4></div>' +
      '<img src="' + stops[i].image + '" class="stop-image img-responsive" /></div>';

      $('#stop-carousel-inner').append(content);
  }
}

function setDescriptionText(index) {
  $('#stop-subtitle').text(stops[index].subtitle);
  $('#stop-content').html(stops[index].content);
}

function setupMobileContent() {
  for ( var i = 0; i < stops.length; i++ ) {
    var content = '<div class="row"><div class="span12">' +
      '<h4 class="stop-header">' + stops[i].title + '</h4>' +
      '<p class="stop-subtitle">' + stops[i].subtitle + '</p>' +
      '<img src="' + stops[i].image + '" class="img-responsive" />' +
      stops[i].content + '</div></div>';

      $("#mobile-content-container").append(content);
  }
}

function adjustContent() {
  var windowWidth = $(window).width();

  if (windowWidth > 992) {
    if ($('#map-content-container').is(':hidden')) {
      $('#map-content-container').show();
      $('#mobile-content').hide();
      goToStop(currentIndex);
    }
  } else {
    if ($('#mobile-content').is(':hidden')) {
      $('#mobile-content').show();
      $('#map-content-container').hide();
    }
  }

}
