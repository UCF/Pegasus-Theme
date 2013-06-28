/**
 * Function that applies a 'disabled' class to all video panels to
 * prevent hover effects during CSS transitions.
 * Timeout value should be close to the defined CSS transition length for
 * panel width transition (0.75s).
 **/
var disableTransitioningPanels = function() {
	$('.video-panel').addClass('disabled');
	window.setTimeout(function() {
		$('.video-panel').removeClass('disabled');
	}, 750);
};

/**
 * Function that enables/disables the video for the currently active panel
 **/
var toggleVideoUrl = function(panelClasses, customAttr) {
	var iframe = $(panelClasses + ' .video iframe');
	iframe.each(function() {
		attrVal = customAttr === null ? '' : iframe.attr(customAttr);
		iframe.attr('src', attrVal);
	});
};

/**
 * Function for handling mobile panels
 **/
var handleMobilePanels = function() {
	if ($(window).width() < 768) {
		// Reshow the title, if it's hidden
		$('#story-title').show();
		// Make sure that all videos are accessible if we're at mobile
		$('.video-panel').each(function() {
			if (!$(this).attr('src')) {
				toggleVideoUrl('#' + $(this).attr('id'), 'data-url-noauto');
			}
		});
	}
	else {
		// If we're on the default view, make sure all videos are still
		// disabled. Else, just disable the inactive videos.
		if ($('.video-panel.active').length > 0) {
			toggleVideoUrl('.inactive', null);
			$('#story-title').hide();
		}
		else {
			toggleVideoUrl('.video-panel', null);
			// Reshow the title, if it's hidden and we're on the default view
			$('#story-title').show();
		}
	}
};


/**
 * Expand one video panel, hide the others 
 **/
$('.video-panel .panel-expand-link').click(function(e) {
	e.preventDefault();
	$('#story-title').hide();
	$(this)
		.parent('.video-panel')
			.addClass('active')
			.siblings('.video-panel')
				.addClass('inactive');
	disableTransitioningPanels();
	toggleVideoUrl('.active', 'data-url');
});

/**
 * Un-expand an active video panel; go back to default view
 **/
$('#closeactive a').click(function(e) {
	e.preventDefault();
	toggleVideoUrl('.active', null);
	$('.video-panel').removeClass('active inactive');
	$('#story-title').show();
	disableTransitioningPanels();
});

/**
 * Handle window resizing
 **/
handleMobilePanels();
$(window).resize(function() {
	handleMobilePanels();
});