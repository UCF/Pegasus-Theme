$(function() {
	// Reset the body content background image on resize
	var defaultBg = $('#wildlife_body_content').attr('style');
	var contentBgReset = function() {
		if ($(window).width() > 767 && $(window).width() < 979) {
			$('#wildlife_body_content').attr('style', 'background: url("'+ $('#wildlife_body_content').attr('data-bg-small') + '") top center no-repeat;');
		}
		else {
			$('#wildlife_body_content').attr('style', defaultBg);
		}
	}
	
	contentBgReset();
	$(window).resize(function() {
		contentBgReset();
	});
	
	
	// Initiate Modal window with custom styles for larger window
	$('#habitat-modal').on('show', function() {
		$(this).css({
			width: 'auto',
			'margin-left': function () {
				return -($(this).width() / 2);
			}
		});
	});
});