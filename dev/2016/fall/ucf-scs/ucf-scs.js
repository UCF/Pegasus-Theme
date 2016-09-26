
$(function () {

    var $carouselItem = $('#carousel-ucf-scs').find('.item'),
        $header = $('header'),
        carouselImages = [],
        audio = new Audio(),
        audioUrl;

    /**
     * Debouce method to prvent code to run too quickly on resize
     */
    function debounce(func, wait, immediate) {
        var timeout;
        return function () {
            var context = this,
                args = arguments;

            var later = function () {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    /**
     * Method to adjust the carousel height
     */
    var updateCarouselHeight = debounce(function () {
        var carouselHeight = $(window).height() - ($header.offset().top + $header.height());
        $.each($carouselItem, function (index) {
            $(this).attr('style', 'min-height:' + carouselHeight + 'px;' + carouselImages[index]);
        });
    }, 100);

    /**
     * Scroll for anchor links on the page
     */
    function smoothScroll(e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: $($(this).attr('href')).offset().top - 20
        }, 500);
    }

    function playPauseAudio(e) {
        e.preventDefault();
        var $that = $(this);

        //TODO: When finished playing switch back to play text

        if (audioUrl && audioUrl === $that.attr('data-audio-url')) {
            if (audio.paused === false) {
                $that.addClass('play').removeClass('pause');
                audio.pause();
            } else {
                $that.addClass('pause').removeClass('play');
                audioUrl = $that.attr('data-audio-url');
                audio.play();
            }
        } else {
            audioUrl = $that.attr('data-audio-url');
            audio.src = audioUrl;
            $that.addClass('pause').removeClass('play');
            audio.play();
        }
    }

    /**
     * Init for Page
     */
    function initPage() {
        $audio = $('#audio-player');
        // Store carousel image urls
        $.each($carouselItem, function () {
            carouselImages.push($(this).attr('style'));
        });
        // Event handler to resize carousel on load and on resize
        $(window).on('load resize', updateCarouselHeight);
        // Event handler to scroll page to story copy
        $('.read-story').on('click', smoothScroll);
        $('.carousel-inner').on('click', '.audio-link', playPauseAudio);
    }

    initPage();

});