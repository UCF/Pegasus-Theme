// Kill responsive styles for this page
$('link#bootstrap-responsive-css').remove();
$('meta[name="viewport"]').attr('content', 'width=980');

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
		positionYMax = 900;
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
		console.log(randPlax);
		
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
	
	/* Add animations to on-screen elements */
	$('.line').bind('inview', function (event, visible) {
		if (visible == true) {
			// animate the box somehow, then stop listening
			$(this)
				.addClass('active')
				.unbind('inview');
		}
	});
	
	
});