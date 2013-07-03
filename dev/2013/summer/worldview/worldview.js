// Add gray Pegasus logo to header, footer
$('#header .title, h2#footer_logo').addClass('black');

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
		$('.boxrow.matchheight').each(function() {
			var row = $(this);			
			// clear any existing height adjustment
			row.children('.bluebox').css('height', '');
			rowHeight = row.height() - 5; // account for margin-bottom
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
