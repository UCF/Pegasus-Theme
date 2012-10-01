$(function() {
	// Set body background image
	var bodyBg = $('#bodybg').attr('data-body-bg');
	$('body').attr('style', 'background-image: url("'+bodyBg+'"); background-repeat: no-repeat; background-position: top center; background-size: cover;');
	
	// Set Pegasus logos
	$('#header .title, h2#footer_logo').addClass('outline');
});