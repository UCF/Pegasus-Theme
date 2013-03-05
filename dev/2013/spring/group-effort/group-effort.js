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
	}).each(function() {
		// If the .stackable-img col is wider than its child image,
		// resize the image so that it is as wide/tall as its parent
		// while maintaining its aspect ratio:
		var stackableImg = $(this),
			img = stackableImg.children('img'),
			oldImgWidth = img.width(),
			oldImgHeight = img.height(),
			newImgWidth = stackableImg.width();
			newImgHeight = stackableImg.height();
		
		if (img.width() < stackableImg.width()) {
			img.width(newImgWidth);
			img.height( Math.round((newImgWidth * oldImgHeight) / oldImgWidth) ); 
		}
		// Default
		else {
			img.width(stackableImg.width());
			img.css('height', 'auto');
		}
		
		// Vertical alignment adjustment
		if (img.height() < stackableImg.height()) {
			img.css( 'padding-top', Math.round((stackableImg.height() - img.height()) / 2) );
		}
		else { 
			img.css('padding-top', '');
		}
	});
	
}
var setColHeights = function() {
	// Force .stackable-col's to always be of equal or greater height of their
	// sibling .text-col:
	
	// Reset forced CSS values from any previous screen resize:
	$('.stackable-col, .text-col').css('height', '');
	
	$('.stackable-col').each(function() {
		var stackableCol 			= $(this),
			stackableColHeight 		= stackableCol.height(),
			siblingTextCol 			= stackableCol.next('.text-col'),
			siblingTextColHeight 	= siblingTextCol.height(),
			textColContent 			= siblingTextCol.children('.text-col-content'),
			textColContentHeight 	= textColContent.height();
		
		// Determine the actual height of siblingTextCol based
		// on its contents:
		if (siblingTextColHeight < textColContentHeight) {
			siblingTextColHeight = textColContentHeight;
		}
		
		// Set col heights:	
		if (siblingTextColHeight > stackableColHeight) {
			stackableCol.height(siblingTextColHeight);
		}
		else  {
			siblingTextCol.height(stackableColHeight);
		}
	});
}
var resetColAdjustments = function() {
	// If the window has been resized to below 768px, reset column
	// widths/heights and any additional padding to images:
	$('.stackable-col, .stackable-img, .stackable-img img, .text-col').css({
		'height' : '',
		'width' : ''
	});
	$('.stackable-img img').css('padding-top','');
	$('.fixedPos').removeClass('fixedPos');
	$('.bottomPos').removeClass('bottomPos');
}

$(document).ready(function() {	
	if ($(window).width() > 767) {
		var endPos = getEndPos();
		setImgDimensions();
		setColHeights();
	}

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
			
			// If we've reached the end of the main story container,
			// unset the current stackable fixed image so it sits
			// at the bottom of its parent column:
			if ($('.fixedPos').length > 0) {
				if (endPos <= currentPos) {
					$('.fixedPos').removeClass('fixedPos').addClass('bottomPos');
				}
			}
		}
	});
	
	$(window).resize(function() {
		if ($(window).width() > 767) {
			endPos = getEndPos();
			setImgDimensions();	
			setColHeights();
		}
		else {
			resetColAdjustments();
		}
	});

});