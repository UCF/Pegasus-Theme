var getEndPos = function() {
	// Get the position of the end of the main story container:
	return $('#stackable-container #end').offset().top - $(window).height(); /* bottom offset */
}
var setImgDimensions = function() {
	// Get the current width of the left-hand #stackable-container column
	// and assign it to every .stackable-img:
	$('.stackable-img').css({
		'width' : $('.stackable-col').width(),
		'height' : $(window).height()
	});
}
var setImgColHeight = function() {
	// Force .stackable-col's to always be the same height as their sibling .text-col:
	$('.stackable-col').each(function() {
		var stackableCol = $(this),
			siblingTextCol = stackableCol.next('.text-col');
			
		if ( ($(window).height() < siblingTextCol.height()) || ($(window).height() < stackableCol.height()) ) {
			if (siblingTextCol.height() > stackableCol.height()) {
				stackableCol.css('height', siblingTextCol.height());
			}
			else {
				siblingTextCol.height(stackableCol.height());
			}
		}
		else {
			stackableCol.css('height', $(window).height());
			siblingTextCol.height($(window).height());
		}
	});
}/*
var setFixedVerticalPos = function() {
	// Adjust the negative margin-top value of .fixedPos to always
	// force it to the vertical center of its .stackable-col
	// See: http://css-tricks.com/quick-css-trick-how-to-center-an-object-exactly-in-the-center/
	if ($('.fixedPos').length > 0) {
		$(this).css('margin-top', ($(this).height() / 2) * -1);
	}
}*/

$(window).load(function() {	
	var endPos = getEndPos();
	setImgDimensions();
	setImgColHeight();
	//setFixedVerticalPos();

	$(window).scroll(function() {
		if ($(window).width() > 767) {
			// Get our current vertical position in the viewport:
			var currentPos = $('html').scrollTop() || $('body').scrollTop();
			
			// For each stackable fixed image, toggle the .fixedPos class
			// when it reaches the top of the page:
			$('.stackable-img').each(function() {
				var targetPos = $(this).parents('.row-fluid').position().top;
				$(this)
					.toggleClass('fixedPos', currentPos >= targetPos)
					.css('margin-top', '')
					.toggleClass('bottomPos', endPos <= currentPos);
			});	
			
			
			if ($('.fixedPos').length > 0) {
				// If we've reached the end of the main story container,
				// unset the current stackable fixed image:
				if (endPos <= currentPos) {
					$('.fixedPos').removeClass('fixedPos').addClass('bottomPos');
				}
				//setFixedVerticalPos();
			}
		}
	});
	
	$(window).resize(function() {
		endPos = getEndPos();
		setImgDimensions();	
		setImgColHeight();
		//setFixedVerticalPos();
	});

});