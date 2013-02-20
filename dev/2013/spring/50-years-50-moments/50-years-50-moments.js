/* Delete annoying empty p tags */
$("p:empty").remove();

/* Use PIE.js if this is an old browser */
if ($('body').hasClass('ie7') || $('body').hasClass('ie8')) {
	$.getScript(csspie_path, function() {
		if (window.PIE) {
			$('.title-circle-medium, .title-circle-large').each(function() {
				PIE.attach(this);
			});
		}
	});
}