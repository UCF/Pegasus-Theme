// Add gradient bg to IE
jQuery(document).ready(function($) {
	if ( $('body').hasClass('ie7') || $('body').hasClass('ie8') || $('body').hasClass('ie9') ) {
		var gradientbg = $('#ie-gradient-bg').attr('data-img-url');
		$('body').attr('style', 'background: url("'+gradientbg+'") center center repeat-y');
	}
});