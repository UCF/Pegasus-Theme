// Kill responsive styles for this page
$('link#bootstrap-responsive-css').remove();
$('meta[name="viewport"]').attr('content', 'width=980');

/* Delete annoying empty p tags */
$("p:empty").remove();

// Return a random value from a range of numbers.
// Why Javascript doesn't have built-in range calculation
// is beyond me...
var randomFromRange = function(min, max) {
	return Math.round(Math.floor(Math.random() * (max - min + 1)) + min);
}
// Return a random value from an array of values.
var randomFromArray = function(someArray) {
	return someArray[Math.floor(Math.random() * someArray.length)];
}

var plaxifyEverything = function() {
	// Create some random coordinates and Plax ranges for our circles:
	var plaxRanges = [
		{"xRange":40,"yRange":40},
        {"xRange":20,"yRange":20},
        {"xRange":10,"yRange":10},
        {"xRange":40,"yRange":40,"invert":true},
        {"xRange":200,"yRange":200},
        {"xRange":80,"yRange":80},
        {"xRange":50,"yRange":50},
        {"xRange":200,"yRange":200,"invert":true}
	];
	var positionXMin = 40,
		positionXMax = 520,
		positionYMin = 20,
		positionYMax = 950;
	var sizeRange = [
		'circe-small',
		'circle-medium',
		'circle-large',
		'circle-xlarge'
	];
	
	$('#circlegraph .circle').each(function() {
		// grab some random absolute positioning coord's:
		var randomXCoord = randomFromRange(positionXMin, positionXMax);
		var randomYCoord = randomFromRange(positionYMin, positionYMax);
		
		// grab a random size class:
		var randSize = randomFromArray(sizeRange);
		
		// grab a random Plax coord object:
		var randPlax = randomFromArray(plaxRanges);
		
		// assign stuff:
		$(this)
			.css({
				'top' : randomYCoord,
				'left' : randomXCoord
			})
			.addClass(randSize)
			.plaxify(randPlax);
	});
}

$(document).ready(function() {
	
	/* Grab plax.js and do Plax stuff: */
	$.getScript(THEME_JS_URL + '/plax.js', function() {
		plaxifyEverything();
		$.plax.enable({ "activityTarget": $('#circlegraph')});
	});
	
	/* Grab raphael.js and draw some lines: */
	$.getScript(THEME_JS_URL + '/raphael.2.1.0.js', function() {
		// 
		$('#line-college-of-medicine')
			.css({
				'top': 64,
				'left' : 480,
				'width' : 242,
				'height': 311
			})
			.attr('data-linedraw-endpos', 480);
		$('#line-regional-campuses')
			.css({
				'top': 327,
				'left' : 522,
				'width' : 199,
				'height': 105
			})
			.attr('data-linedraw-endpos', 400);
		$('#line-rosen')
			.css({
				'top': 327,
				'left' : 522,
				'width' : 199,
				'height': 105
			})
			.attr('data-linedraw-endpos', 400);
		$('#line-ist')
			.css({
				'top': 327,
				'left' : 522,
				'width' : 199,
				'height': 105
			})
			.attr('data-linedraw-endpos', 400);
		
		$('.line').each(function() {
			var canvasDiv = $(this),
				canvasID = canvasDiv.attr('id'),
				canvasEndPos = canvasDiv.attr('data-linedraw-endpos'),
				canvas = new Raphael(canvasID, canvasDiv.width(), canvasDiv.height()),
				line = canvas.path('M0 ' + canvasDiv.height().toString());
			line.attr({ stroke: '#ccc', 'stroke-width' : 1 });
			line.animate({ path : 'M0 ' + canvasDiv.height().toString() + ' L ' + canvasEndPos + ' ' + (canvasDiv.height() * -1).toString() }, 1500);
		});				
				
	});
	
	
});