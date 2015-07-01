/* global THEME_COMPONENTS_URL */
/// <reference path="../../../../typings/jquery/jquery.d.ts"/>

(function () {
    var $sideElevation,
        $elevationImage,
        $diagramCounter,
        diagramHeadingArray,
        diagramContainerHeight,
        diagramAffixBottom,
        diagramArray = [];

    function affixDiagram() {
        var affixTop = $sideElevation.offset().top;
        $sideElevation.affix({
            offset: {
                top: affixTop
            }
        });
    }

    function scrollToDesc(index) {
        var scrollTo = diagramHeadingArray.eq(index).offset().top - (diagramContainerHeight + 20);
        $('html,body').animate({
            scrollTop: scrollTo
        }, 500);
    }

    function setImage(imgSrc) {
        $elevationImage.attr('src', imgSrc);
    }

    function setupDiagramEventHandlers() {
        $('map[name=RobotMap] area').on('click', function () {
            var index = $(this).index() + 1;
            scrollToDesc(index);
        });
    }

    function setupScrollEventHandlers() {
        $(window).on('scroll', function () {
            var scrollPosition = window.pageYOffset + 20,
                index = 0;

            if (scrollPosition > diagramAffixBottom) {
                $sideElevation.attr('style', 'position: absolute; top: ' + diagramAffixBottom + 'px');
            } else {
                $sideElevation.attr('style', '');
            }

            if (scrollPosition >= diagramArray[6].position) {
                index = 6;
            } else if (scrollPosition >= diagramArray[5].position) {
                index = 5;
            } else if (scrollPosition >= diagramArray[4].position) {
                index = 4;
            } else if (scrollPosition >= diagramArray[3].position) {
                index = 3;
            } else if (scrollPosition >= diagramArray[2].position) {
                index = 2;
            } else if (scrollPosition >= diagramArray[1].position) {
                index = 1;
            }
            
            setImage(diagramArray[index].image);
            $diagramCounter.text(index);
        });
    }

    function setDiagramCopyArray() {
        $.each(diagramHeadingArray, function (i) {
            var diagramObj = {};
            diagramObj.position = diagramHeadingArray.eq(i).offset().top - diagramContainerHeight;
            diagramObj.image = diagramHeadingArray.eq(i).attr('data-elevation-image');
            diagramArray.push(diagramObj);
        });
    }

    $(function () {
        function init() {
            $sideElevation = $sideElevation = $('#side-elevation');
            $elevationImage = $sideElevation.find('img');
            $diagramCounter = $('.diagramCounter');
            diagramHeadingArray = $('.diagram-copy').find('h2');
            diagramContainerHeight = $sideElevation.outerHeight();
            diagramAffixBottom = ($('main').outerHeight() + $('header').outerHeight()) - diagramContainerHeight;

            setDiagramCopyArray();
            affixDiagram();
            setupDiagramEventHandlers();
            setupScrollEventHandlers();
        
            // make the image map responsive
            $.getScript(THEME_COMPONENTS_URL + '/imageMapResizer.min.js').done(function () {
                $('map').imageMapResize();
            });
        }
        $(init);
    });
})();



