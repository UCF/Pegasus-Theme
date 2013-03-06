$(document).ready(function() {
	var pieces = $('#myCarousel .item');
	var totalPieces = pieces.size() - 1;
	var currentIndex = 0;

	$('#myCarousel').carousel({
		interval: false
	});

	$('.p-left').click(function() {
		currentIndex--;
		updateNavigation();
		$('#myCarousel').carousel('prev');
	})

	$('.p-right').click(function() {
		currentIndex++;
		updateNavigation();
		$('#myCarousel').carousel('next');
	})

	function updateNavigation() {
		if (currentIndex < 0) {
			currentIndex = totalPieces;
		}
		
		if (currentIndex > totalPieces) {
			currentIndex = 0;
		}

		var prevPieceIndex = currentIndex - 1;
		if (prevPieceIndex < 0) {
			prevPieceIndex = totalPieces;
		}

		var nextPieceIndex = currentIndex + 1;
		if (nextPieceIndex > totalPieces) {
			nextPieceIndex = 0;
		}

		var prevImgSrc = $(pieces.get(prevPieceIndex)).find('img').attr('src');
		var nextImgSrc = $(pieces.get(nextPieceIndex)).find('img').attr('src');
		var prevImg = $('#myCarousel .p-left img');
		var nextImg = $('#myCarousel .p-right img');
		prevImg.fadeOut(500, function() {
			$(this).attr('src', prevImgSrc.replace('.png', '-thumb.jpg'));
		});
		nextImg.fadeOut(500, function() {
			$(this).attr('src', nextImgSrc.replace('.png', '-thumb.jpg'));
		});
		prevImg.fadeIn(500);
		nextImg.fadeIn(500);
	}
});