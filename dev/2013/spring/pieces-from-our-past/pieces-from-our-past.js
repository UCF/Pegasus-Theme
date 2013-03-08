var updateMobileSlider = function() {
	// Change out the img src per each li
	if ($(window).width() < 768) {
		$('#slider .slides li').each(function() {
			var li = $(this),
				img = li.children('img'),
				caption = li.children('.flex-caption');
			img.attr('src', li.attr('data-mobile-img'));
			// Adjust captions. Right floating captions need special handling
			if (caption.hasClass('right-float-wrap')) {
				caption.css('width', '5%');
			}
			else {
				caption.css('width', 'auto');
			}
		});
	}
	// Set the img's back to their normal src if the window has been upsized
	else {
		var firstLi = $('#slider .slides li:first-child');
		// Just check the first li initially
		if (firstLi.attr('data-mobile-img') == firstLi.children('img').attr('src')) {
			$('#slider .slides li').each(function() {
				var li = $(this),
					img = li.children('img');					
				img.attr('src', li.attr('data-normal-img'));
			});
		}
		
		// Update caption widths on every resize above 767px.
		// Caption widths can't be specified in percents so we have to calculate it:
		$('#slider .slides li').each(function() {
			var li = $(this),
				caption = li.children('.flex-caption');
			var captionWidth = Math.round( li.css('width').slice(0, -2) * li.attr('data-caption-width') ) / 100;
			if (caption.hasClass('right-float-wrap')) {
				caption.children('.right-float').css('width', captionWidth);
			}
			else {
				caption.css('width', captionWidth);
			}
		});
	}
}

$(window).load(function() {
	
	// Activate FlexSlider	
	$.getScript(THEME_JS_URL + '/flexslider/jquery.flexslider-min.js', function() {
		// The slider being synced must be initialized first
		$('#slider-nav').flexslider({
			animation: 'slide',
			controlNav: false,
			animationLoop: false,
			slideshow: false,
			itemWidth: 150,
			itemMargin: 30,
			minItems: 5,
			maxitems: 10,
			touch: true,
			asNavFor: '#slider'
		});
		   
		$('#slider').flexslider({
			animation: 'slide',
			controlNav: false,
			animationLoop: false,
			slideshow: false,
			touch: true,
			keyboard: true,
			sync: '#slider-nav',
			useCSS: true,
		});	
	});
	
	
	// Slider resizing
	updateMobileSlider();
	
	var timeout = false;
	$(window).resize(function() {
		if (timeout !== false) {
			clearTimeout(timeout);
		}
		timeout = setTimeout(updateMobileSlider, 200);
	});
	
});