/* global THEME_COMPONENTS_URL */
/// <reference path="../../../../typings/jquery/jquery.d.ts"/>

(function () {
    var $sideElevation,
        $elevationImage,
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
            var scrollPosition = window.pageYOffset;

            if (scrollPosition > diagramAffixBottom) {
                $sideElevation.attr('style', 'position: absolute; top: ' + diagramAffixBottom + 'px');
            } else {
                $sideElevation.attr('style', '');
            }

            if (scrollPosition >= diagramArray[6].position) {
                setImage(diagramArray[6].image);
            } else if (scrollPosition >= diagramArray[5].position) {
                setImage(diagramArray[5].image);
            } else if (scrollPosition >= diagramArray[4].position) {
                setImage(diagramArray[4].image);
            } else if (scrollPosition >= diagramArray[3].position) {
                setImage(diagramArray[3].image);
            } else if (scrollPosition >= diagramArray[2].position) {
                setImage(diagramArray[2].image);
            } else if (scrollPosition >= diagramArray[1].position) {
                setImage(diagramArray[1].image);
            } else if (scrollPosition < diagramArray[0].position) {
                setImage(diagramArray[0].image);
            }
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

