$('.header_stories .bottom ul.thumbnails li, #footer .active.item .footer_stories.bottom ul.thumbnails li').each(function() {
	$(this)
		.addClass('span3')
		.removeClass('span2')
		.css({'overflow' : 'visible', 'height' : 'auto'});
});