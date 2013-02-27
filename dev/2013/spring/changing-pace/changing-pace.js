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
	
	$('#circlegraph .circle').each(function() {
		// grab some random absolute positioning coord's:
		var randomXCoord = randomFromRange(positionXMin, positionXMax);
		var randomYCoord = randomFromRange(positionYMin, positionYMax);
		
		// grab a random Plax coord object:
		var randPlax = randomFromArray(plaxRanges);
		
		// assign stuff:
		$(this)
			.css({
				'top' : randomYCoord,
				'left' : randomXCoord
			})
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
		// All lines are absolutely positioned relative to #circlegraph.
		// We assign positions/widths/heights here for the sake of
		// convenience.
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
				'top': 532,
				'left' : 530,
				'width' : 191,
				'height': 79
			})
			.attr('data-linedraw-endpos', 191);
		$('#line-ist')
			.css({
				'top': 602,
				'left' : 489,
				'width' : 232,
				'height': 248
			})
			.attr('data-linedraw-endpos', 241);
		
		$('.line').each(function() {
			var canvasDiv 		= $(this),
				canvasID 		= canvasDiv.attr('id'),
				// .bt lines run from bottom-left to top-right; .tb run top-left to bottom-right:
				canvasStartPos 	= (canvasDiv.hasClass('line-bt')) ? 'M0 ' + canvasDiv.height().toString() : 'M0 0',
				canvasEndPos 	= (canvasDiv.hasClass('line-bt')) 
									? canvasDiv.attr('data-linedraw-endpos') + ' ' + (canvasDiv.height() * -1).toString() 
									: canvasDiv.attr('data-linedraw-endpos') + ' ' + canvasDiv.height().toString(),
				canvas 			= new Raphael(canvasID, canvasDiv.width(), canvasDiv.height());
			
			var line = canvas.path(canvasStartPos);
			line.attr({ stroke: '#999', 'stroke-width' : 1 });
			
			line.animate({ path : canvasStartPos + ' L ' + canvasEndPos }, 1500);
		});				
				
	});
	
	/* Add the growing animation to the Partnerships circles as they're visible: */
	$('#partnerships .circle').bind('inview', function (event, visible) {
		if (visible == true) {
			// animate the circle, then stop listening
			$(this)
				.addClass('grow')
				.unbind('inview');
		}
	});
	
	
});