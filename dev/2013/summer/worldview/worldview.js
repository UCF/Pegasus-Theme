// Add gray Pegasus logo to header, footer
$('#header .title, h2#footer_logo').addClass('black');

// Remove .fade class from video modal for IE10 users
// https://github.com/twitter/bootstrap/issues/3672
if ($.browser.msie && $.browser.version > 9) {
    $('.modal').removeClass('fade');
}

// IE9 SHOULD play mp4s...but doesn't. And won't fall back gracefully.
if ($('body').hasClass('ie9')) {
	var link = $('video a');
	$('video').remove();
	$('#video-modal .modal-body').append(link);
}

// Apply .flash class at random intervals to .box1 img's w/ .animated class
var minInterval = 2000,
	maxInterval = 200,
	interval = 1000;
setInterval(function(){ 
	interval = Math.floor(Math.random() * (maxInterval - minInterval + 1)) + minInterval;
}, 1000);
setInterval(function(){
	var array = [];
	$('.animated').not($('.behind')).each(function() {
		array.push($(this));
	});
	var item = array[Math.floor(Math.random()*array.length)];
	//console.log(item.attr('src'));
	item
		.siblings($('img'))
			.removeClass('behind')
			.addClass('flash')
			.end()
		.addClass('behind');
	window.setTimeout(function() { item.siblings($('img')).removeClass('flash'); }, 1000);
    //console.log('flash added to element');
    //console.log(interval);
}, interval);

// Adjust heights of .bluebox's within a .boxrow to be the same
var forceEqualHeights = function() {
	if ($(window).width() > 767) {
		var marginBottom = 5;
		if ($('body').hasClass('ie7') || $('body').hasClass('ie8') ) { marginBottom = 10; }
		$('.boxrow.matchheight').each(function() {
			var row = $(this);			
			// clear any existing height adjustment
			row.children('.bluebox').css('height', '');
			rowHeight = row.height() - marginBottom; // account for margin-bottom
			row.children('.bluebox').css('height', rowHeight);
		});
	}
	else {
		$('.boxrow.matchheight .bluebox').css('height', '');
	}
};

forceEqualHeights();
$(window).resize(function() {
	forceEqualHeights();
});
