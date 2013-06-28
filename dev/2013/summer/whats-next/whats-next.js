jQuery(document).ready(function($) {
	$.getScript(THEME_JS_URL + '/waypoints.min.js').done( function(script, textStatus) {
		var $bottommenu = jQuery('#bubble-menu');

		jQuery('#next-news').waypoint(function(direction) {
			$bottommenu.toggleClass('slidein');
		}, { offset: '100%'});

		jQuery('#next-virtual').waypoint(function(direction) {
			$bottommenu.toggleClass('slidein');

		}, { offset: 'bottom-in-view'});
	});
});