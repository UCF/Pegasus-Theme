$(function() {	
	// Initiate Modal window with custom styles for larger window
	$('#voices-modal').on('show', function() {
		$(this).css({
			width: '859px',
			'margin-left': function () {
				return -($(this).width() / 2);
			}
		});
	});
});