
$(function () {

    var $carouselItem = $('#carousel-ucf-scs').find('.item'),
        $header = $('header'),
        audio = new Audio(),
        audioUrl,
        $currentAudio;

    /**
     * Debouce method to pause logic until resize is complete
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
     * Method to adjust the carousel height, insert image, copy and audio
     */
    var updateCarousel = debounce(function () {
        var carouselHeight = $(window).height() - ($header.offset().top + $header.height()),
            isMobile = ($(window).width() < 992) ? true : false;
        $.each($carouselItem, function (i) {
            var $that = $(this),
                img = (isMobile) ? carouselObjects[i].img.replace('.jpg','-xs.jpg') : carouselObjects[i].img;
            $that.attr('style', 'min-height:' + carouselHeight + 'px;background-image: url(' + img + ")");
            $that.find('.copy').text(carouselObjects[i].copy);
            if (carouselObjects[i].audio) {
                $that.find('.audio-link').attr('data-audio-url', carouselObjects[i].audio);
            }
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

    /**
     * method to play/pause audio
     */
    function playPauseAudio(e) {
        e.preventDefault();

        var $that = $(this);
        $currentAudio = $that;

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
     * audio ended
     */
    function onAudioEnded() {
        $currentAudio.addClass('play').removeClass('pause');
    }

    /**
     * Init for Page
     */
    function initPage() {
        // Event handler to resize carousel on load and on resize
        $(window).on('load resize', updateCarousel);
        // Event handler to scroll page to story copy
        $('.read-story').on('click', smoothScroll);
        $('.carousel-inner').on('click', '.audio-link', playPauseAudio);
        audio.addEventListener('ended', onAudioEnded);
    }

    initPage();

});
