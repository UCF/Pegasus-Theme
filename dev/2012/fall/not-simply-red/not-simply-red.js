jQuery(document).ready(function($) {
	$(window).load(function() {
		$(window).resize(function() {
			$('.highlight').each(function() {
				$(this).find('.bar').height($(this).find('.blurb').outerHeight());
			});
		});
		$(window).trigger('resize');
	});
});