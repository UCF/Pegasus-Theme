var map,
		incWorldData,
		outWorldData,
		incUSData,
		outUSData,
		container;

var activeLayer;

var minColor = {r: 255, g: 255, b: 255};
var maxColor = {r: 255, g: 204, b: 0};

var orlando;

function InformationControl(controlDiv, map) {
	var infoWindow = document.createElement('div');
	infoWindow.id = 'info-window';

	var name = document.createElement('div');
	name.id = 'name';

	var count = document.createElement('div');
	count.id = 'count';

	infoWindow.appendChild(name);
	infoWindow.appendChild(count);
	controlDiv.appendChild(infoWindow);
}

var main = function() {
	var mapScript = document.createElement('script');
  mapScript.type = "text/javascript"; 
  mapScript.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=falsev=3.exp&callback=initialize";

  document.body.appendChild(mapScript);
  container = document.getElementById('map');
  setMapSize();
};

var setMapSize = function() {
	container.style.height = container.offsetWidth / 16 * 9 + "px";
};

var initialize = function() {
	orlando = { lat: 28.601919, lng: -81.200103 };

	var $container = $('#map');
	var incWorldPath = $container.attr('data-inc-world');
	var outWorldPath = $container.attr('data-out-world');
	var incUSPath = $container.attr('data-inc-us');
	var outUSPath = $container.attr('data-out-us');

	$.when(
		$.getJSON(incWorldPath, function(data) {
			incWorldData = data;
			incWorldData = preparePolys(incWorldData);
		}),
		$.getJSON(outWorldPath, function(data) {
			outWorldData = data;
			outWorldData = preparePolys(outWorldData);
		}),
		$.getJSON(incUSPath, function(data) {
			incUSData = data;
			incUSData = preparePolys(incUSData);
		}),
		$.getJSON(outUSPath, function(data) {
			outUSData = data;
			outUSData = preparePolys(outUSData);
		})
	).then( function() {
		activeLayer = outWorldData;
		setupMap();
		setupControls();
	});
};

var preparePolys = function(data) {
	var lineSymbol = {
    path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
  };

  var maxValue = data.maxValue;

  var focus = data.focus;
	console.log(focus);
	data.focus = new google.maps.LatLng(focus.lat, focus.lng);

	for(var r in data.data) {
		var record = data.data[r];

		var count = 0;
		if (record.count < 0) {
			count = 0;
		} else if (record.count > maxValue) {
			count = maxValue;
		} else {
			count = record.count;
		}

		var percent = count / maxValue * 100;
		var colorTable = getFillColor(minColor, maxColor, percent);

		record.shapes = [];
		var coords = record.geometry.coordinates;
		for(var x in coords) {
			for (var y in coords[x]) {
				var polyArray = [];
				for (var z in coords[x][y]) {
					var p = new google.maps.LatLng(coords[x][y][z][1], coords[x][y][z][0]);
					polyArray.push(p);
				}
				
				var poly = new google.maps.Polygon({
					paths: polyArray,
					strokeColor: '#c90',
			    strokeOpacity: 0.8,
			    strokeWeight: 1,
			    fillColor: colorTable.hex,
			    fillOpacity: 1,
			    name: record.name,
			    count: record.count
				});

				google.maps.event.addListener(poly, 'mouseover', function(e) {
					$('#name').text(this.name);
					$('#count').text(this.count);
					$('#info-window').addClass('active');
				});

				google.maps.event.addListener(poly, 'mouseout', function(e) {
					$('#name').text('');
					$('#count').text('');
					$('#info-window').removeClass('active');
				});

				record.shapes.push(poly);
			}
		}
	}

	return data;
};

var polyMouseOver = function(data) {
	console.log(data);
};

// Thanks jfriend00 and AngryHacker
// http://stackoverflow.com/questions/8732401/how-to-figure-out-all-colors-in-a-gradient
var getFillColor = function(minColor, maxColor, value) {

	var newColor = {};

	function makeChannel(a, b) {
		return (a + Math.round((b-a)*(value/100)));
	}

	function makeColorPiece(num) {
		num = Math.min(num, 255);
		num = Math.max(num, 0);
		var str = num.toString(16);
		if (str.length < 2) {
			str = "0" + str;
		}
		return str;
	}

	newColor.r = makeChannel(minColor.r, maxColor.r);
	newColor.g = makeChannel(minColor.g, maxColor.g);
	newColor.b = makeChannel(minColor.b, maxColor.b);
	newColor.hex = "#" + makeColorPiece(newColor.r) + makeColorPiece(newColor.g) + makeColorPiece(newColor.b);

	return (newColor);

};

var setupMap = function() {
	var center = new google.maps.LatLng(0,0);

	var styles = [
	  {
	    featureType: "all",
	    elementType: "labels",
	    stylers: [
	      { visibility: "off" }
	    ]
	  },
	  {
	    "featureType": "landscape",
	    "stylers": [
	      { "color": "#efefef" }
	    ]
	  },{
	    "featureType": "water",
	    "stylers": [
	      { "color": "#ffffff" }
	    ]
	  }
	];

  var mapOptions = {
    mapTypeId: google.maps.MapTypeId.TERRAIN,
    scrollwheel: true,
    navigationControl: false,
    mapTypeControl: false,
    scaleControl: false,
    draggable: true,
    zoom: 2,
    center: center,
    disableDefaultUI: true,
    styles: styles
  };

  map = new google.maps.Map(container,
          mapOptions);

 	var infoControlDiv = document.createElement('div');
 	var infoControl = new InformationControl(infoControlDiv, map);

 	infoControlDiv.index = 1;
 	map.controls[google.maps.ControlPosition.TOP_RIGHT].push(infoControlDiv);

  activeLayerDraw();

};

var setupControls = function() {
	$('#map-navigation li').click(function(e) {
		$('#map-navigation li').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
		var $nav = $(e.target);
		var newMap = $nav.attr('data-map');

		activeLayerDispose();
		switch(newMap) {
			case 'incoming-world':
				activeLayer = incWorldData;
				break;
			case 'outgoing-world':
				activeLayer = outWorldData;
				break;
			case 'incoming-us':
				activeLayer = incUSData;
				break;
			case 'outgoing-us':
				activeLayer = outUSData;
				break;
			default:
				activeLayer = incWorldData;
				break;
		}
		activeLayerDraw();
	});
};

var activeLayerDraw = function() {
	for(var r in activeLayer.data) {
		for (var s in activeLayer.data[r].shapes) {
			activeLayer.data[r].shapes[s].setMap(map);
		}
	}

	map.setCenter(activeLayer.focus);
	map.setZoom(activeLayer.zoom);
};

var activeLayerDispose = function() {
	for(var r in activeLayer.data) {
		for (var s in activeLayer.data[r].shapes) {
			activeLayer.data[r].shapes[s].setMap(null);
		}
	}
};

if (typeof jQuery !== 'undefined') {
	$(document).ready(function($) {
		main();
	});
} else {
	console.log("No jQuery!");
}