if (typeof jQuery != 'undefined'){
	jQuery(document).ready(function($) {
		Webcom.slideshow($);
		Webcom.chartbeat($);
		Webcom.analytics($);
		Webcom.handleExternalLinks($);
		Webcom.loadMoreSearchResults($);
		
		$('#story_nav').hide();
		$('.toggle_story_nav')
			.click(function(e) {
				e.preventDefault();
				var story_nav = $('#story_nav');
				if(story_nav.is(':visible')) {
					$(this).html('&#9660;');
				} else {
					$(this).html('&#9650;');
				}
				story_nav.slideToggle();
			});

		/* iPad Model */
		(function() {
			var ipad_hide = $.cookie('ipad-hide');
			if((ipad_hide == null || !ipad_hide) && navigator.userAgent.match(/iPad/i) != null) {
				$('#ipad')
					.modal()
					.on('hidden', function() {
						$.cookie('ipad-hide', true);
					});
			}
		})();
		

	});
}else{console.log('jQuery dependancy failed to load');}