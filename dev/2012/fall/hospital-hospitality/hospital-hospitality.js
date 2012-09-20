jQuery(document)
	.ready(function($) {
		$('body').css('background-image', 'url('+ $('.intro').attr('data-background-url') + ')');
	});