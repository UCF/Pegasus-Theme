$(document).ready(function() {
    /* Delete annoying empty p tags */
    $("p:empty").remove();
    // Set Pegasus logos
    $('#header .title').addClass('black');

    // Navigation
    $.getScript(THEME_JS_URL + '/waypoints.min.js').done( function(script, textStatus) {
    	var $bottommenu = $('#bubble-menu');

    	$('#article-header, #img-zuvich').waypoint(function(direction) {
    		$bottommenu.toggleClass('slidein');
    	});
    });
});