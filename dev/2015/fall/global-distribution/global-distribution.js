var map,
		incTopTen,
		outTopTen,
		incWorldData,
		outWorldData,
		incUSData,
		outUSData,
		markerPath,
		container,
		activeLayer,
		minColor = {r: 255, g: 255, b: 255},
		maxColor = {r: 255, g: 204, b: 0};

// Global data set toggles
var incoming = true,
		outgoing = false,
		topten = true,
		world = false,
		us = false;

// Variable for storing map styles.
var styles = [];

var main = function() {

	var scriptTag = document.createElement('script');
	scriptTag.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=falsev=3.exp&callback=initialize';
	scriptTag.type = 'text/javascript';

	document.body.appendChild(scriptTag);

  container = document.getElementById('map');
  setMapSize();

  $(window).on('resize', function() {
  	setMapSize();
  });
};

var setMapSize = function() {
	var width = $(window).width();
	if (width > 768) {
		container.style.height = container.offsetWidth / 16 * 9 + 'px';
	} else {
		container.style.height = container.offsetWidth / 4 * 3 + 'px';
	}
};

var initialize = function() {

	var $container = $('#map'),
			incWorldPath = $container.attr('data-inc-world'),
			outWorldPath = $container.attr('data-out-world'),
			incUSPath = $container.attr('data-inc-us'),
			outUSPath = $container.attr('data-out-us');
	
	markerPath = $container.attr('data-marker-img');

	setupMap();

	$.when(
		$.getJSON(incWorldPath, function(data) {
			incWorldData = data;
			incWorldData = preparePolys(incWorldData, 'incoming-world');
		}),
		$.getJSON(outWorldPath, function(data) {
			outWorldData = data;
			outWorldData = preparePolys(outWorldData, 'outgoing-world');
		}),
		$.getJSON(incUSPath, function(data) {
			incUSData = data;
			incUSData = preparePolys(incUSData, 'incoming-us');
		}),
		$.getJSON(outUSPath, function(data) {
			outUSData = data;
			outUSData = preparePolys(outUSData, 'outgoing-us');
		})
	).then( function() {

		// Prepare top ten data sets
		incTopTen = getTopTen($.extend(true, {}, incWorldData));
		outTopTen = getTopTen($.extend(true, {}, outWorldData));

		setupControls();
		activeLayerDraw();
	});
};

var preparePolys = function(data, dataset) {
	data.focus = new google.maps.LatLng(data.focus.lat, data.focus.lng);

	// Sort by count and reverse
	data.data.sort(function(a,b) {
		return a.count - b.count;
	});

	data.data.reverse();

	for(var r in data.data) {
		var record = data.data[r];

		// Get the color of the record's polyshape.
		var color = getFillColor(0, data.maxValue, record.count);

		// Create marker and info window.
		var marker = new google.maps.Marker({
			position: record.position,
			icon: markerPath,
			map: map,
		});

		var infoWindow = new google.maps.InfoWindow({
			content: '<div class="name">' + record.name + '</div><div class="count">' + record.count + '</div>'
		});

		record.marker = marker;
		record.infoWindow = infoWindow;

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
			    fillColor: color.hex,
			    fillOpacity: 1,
			    marker: marker,
			    infoWindow: infoWindow
				});

				google.maps.event.addListener(poly, 'mouseover', function(e) {
					for(var r in activeLayer.data) {
						activeLayer.data[r].infoWindow.close();
					}
					this.infoWindow.open(map, this.marker);
				});

				google.maps.event.addListener(poly, 'click', function(e) {
					for(var r in activeLayer.data) {
						activeLayer.data[r].infoWindow.close();
					}
					this.infoWindow.open(map, this.marker);
				});

				record.shapes.push(poly);
			}
		}
	}

	return data;
};

var getTopTen = function(data) {
	var retval = [];
	for(var i = 0; i < 10; i++) {
		retval.push(data.data[i]);
	}

	data.data = retval;

	return data;
};

// Thanks jfriend00 and AngryHacker
// http://stackoverflow.com/questions/8732401/how-to-figure-out-all-colors-in-a-gradient
var getFillColor = function(minValue, maxValue, value) {
	if (value < 0) {
		value = 0;
	} else if (value > maxValue) {
		value = maxValue;
	}

	var percent = value / maxValue * 100;

	var newColor = {};

	function makeChannel(a, b) {
		return (a + Math.round((b-a)*(percent/100)));
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

	styles = [
		{
	    featureType: 'water',
	    stylers: [
	      { color: '#5f7a88' }
	    ]
	  },
	  {
	    featureType: 'landscape',
	    stylers: [
	      { color: '#acacac' }
	    ]
	  },
	  {
	    featureType: 'all',
	    elementType: 'labels',
	    stylers: [
	      { visibility: 'off' }
	    ]
	  },
	  {
	  	featureType: 'administrative',
	  	elementType: 'geometry.fill',
	  	stylers: [
	  		{ visibility: 'off' }
	  	]
	  }
	];

  var mapOptions = {
    mapTypeId: google.maps.MapTypeId.ROADMAP,
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

};

var setupControls = function() {
 	$('.map-control').click(toggleClickHandler);
};

var toggleClickHandler = function(e) {
	e.preventDefault();
	activeLayerDispose();

	var toggle = $(e.target).attr('data-toggle');

	var $bucketControls = $('#bucket-controls-container').find('.map-control');
	var $dataSetControls = $('#data-set-control-container').find('.map-control');

	switch(toggle) {
		case 'outgoing':
			$bucketControls.removeClass('active');
			$(e.target).addClass('active');
			outgoing = true;
			incoming = false;
			activeLayer = outWorldData;
			break;
		case 'incoming':
			$bucketControls.removeClass('active');
			$(e.target).addClass('active');
			outgoing = false;
			incoming = true;
			activeLayer = outUSData;
			break;
		case 'topten':
			$dataSetControls.removeClass('active');
			$(e.target).addClass('active');
			topten = true;
			world = false;
			us = false;
			activeLayer = incWorldData;
			break;
		case 'world':
			$dataSetControls.removeClass('active');
			$(e.target).addClass('active');
			topten = false;
			world = true;
			us = false;
			activeLayer = incUSData;
			break;
		case 'us':
			$dataSetControls.removeClass('active');
			$(e.target).addClass('active');
			topten = false;
			world = false;
			us = true;
			break;
	}

	activeLayerDraw();

};

var activeLayerDraw = function() {
	// Determine active layer.
	if (incoming) {
		if (topten) {
			activeLayer = incTopTen;
		} else if (world) {
			activeLayer = incWorldData;
		} else if (us) {
			activeLayer = incUSData;
		} else {
			activeLayer = incTopTen;
		}
	} else if (outgoing) {
		if (topten) {
			activeLayer = outTopTen;
		} else if (world) {
			activeLayer = outWorldData;
		} else if (us) {
			activeLayer = outUSData;
		} else {
			activeLayer = outTopTen;
		}
	} else {
		activeLayer = incTopTen;
	}

	if (incoming) {
		$('#map-container').addClass('incoming').removeClass('outgoing');
		styles[0].stylers[0].color = '#496e7e';
		styles[1].stylers[0].color = '#ffffff';
	} else {
		$('#map-container').removeClass('incoming').addClass('outgoing');
		styles[0].stylers[0].color = '#fef5ec';
		styles[1].stylers[0].color = '#1e4b68';
	}

	map.setOptions({styles:styles});

	for(var r in activeLayer.data) {
		if (topten) {
			var marker = activeLayer.data[r].marker;
			var infoWindow = activeLayer.data[r].infoWindow;

			var time = (r + 1) * 15;
			setTimeout(draw, time, marker, infoWindow);
		}

		for (var s in activeLayer.data[r].shapes) {
			activeLayer.data[r].shapes[s].setMap(map);
		}
	}

	map.setCenter(activeLayer.focus);
	map.setZoom(activeLayer.zoom);
};

var activeLayerDispose = function() {
	for(var r in activeLayer.data) {
		activeLayer.data[r].infoWindow.close();
		for (var s in activeLayer.data[r].shapes) {
			activeLayer.data[r].shapes[s].setMap(null);
		}
	}
};

var draw = function(marker, infoWindow) {
	infoWindow.open(map, marker);
};

var modalEvents = function() {
	var $modalTransparent = $('.modal-transparent'),
			$modalFullscreen = $('.modal-fullscreen');

	$modalTransparent.on('show.bs.modal', function() {
		setTimeout(function() {
			$(".modal-backdrop").addClass("modal-backdrop-transparent");
		}, 0);
	}).on('hidden.bs.modal', function() {
		$('.modal-backdrop').addClass("modal-backdrop-transparent");
	});

	$modalFullscreen.on('show.bs.modal', function() {
		setTimeout( function() {
	    $('.modal-backdrop').addClass('modal-backdrop-fullscreen');
	  }, 0);
	}).on('hidden.bs.modal', function() {
		$('.modal-backdrop').addClass('modal-backdrop-fullscreen');
	});
};

if (typeof jQuery !== 'undefined') {
	$(document).ready(function($) {
		main();
		modalEvents();
	});
} else {
	console.log('No jQuery!');
}