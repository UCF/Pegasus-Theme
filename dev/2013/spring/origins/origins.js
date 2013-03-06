$(document).ready(function() {
	
	/* Delete annoying empty p tags */
	$("p:empty").remove();
	
	var header_images = $('.article-header-image');

	// subtract 2 because of the blank div with no ID
	var total = header_images.length - 2;
	var current_index = -1;
	var $window = $(window);

	setInterval(function() {
		resizeHeaderImage();

		if (current_index == total) {
			current_index = 0;
			$('.article-header-image').hide(); // reset faded in img's to hidden
		} else {
			current_index++;
		}

		$.each(header_images, function(index, value) {
			var image = $(value);
			if (image.attr('id') == 'origin' + current_index) {
				image.fadeIn(900);
				$('#image-year').find('p').text(image.attr('year'));
			}

		});
	}, 1500);

	$window.resize(function() {
		resizeHeaderImage();
	});
});

function resizeHeaderImage() {
	var imageDiv = $('#article-header-images');
	if ($(window).width() < 767) {
		var divHeight = imageDiv.width() * .60;
		$('#article-header-images').height(divHeight);
		imageDiv.find('img').height(divHeight).width(imageDiv.width());
	} else {
		$('#article-header-images').css('height', '');
		imageDiv.find('img').css('height', '').css('width', '');
	}
}