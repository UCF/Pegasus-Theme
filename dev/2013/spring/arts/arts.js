$(window).load(function() {
	/* Delete annoying empty p tags */
	$("p:empty").remove();
	$('#story-img-wrap, #story-title').height($('#story-title-bg').height());
});

$(window).resize(function() {
	$('#story-img-wrap, #story-title').height($('#story-title-bg').height());
});