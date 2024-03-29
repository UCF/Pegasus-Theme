/* global THEME_COMPONENTS_URL */

(function () {
    var $sideElevation,
        $elevationImage,
        $diagramCounter,
        diagramHeadingArray,
        diagramContainerHeight,
        diagramAffixBottom;

    function affixDiagram() {
        var affixTop = $sideElevation.offset().top;
        $sideElevation.affix({
            offset: {
                top: affixTop
            }
        });
    }

    function scrollToDesc(index) {
        var scrollTo = diagramHeadingArray.eq(index).offset().top - (diagramContainerHeight);
        $('html,body').animate({
            scrollTop: scrollTo - 25
        }, 500);
    }

    function setupDiagramEventHandlers() {
        $('map[name=RobotMap] area').on('click', function () {
            var index = $(this).index() + 1;
            scrollToDesc(index);
        });
    }

    function setupResponsiveImageMap() {
        // make the image map responsive
        $.getScript(THEME_COMPONENTS_URL + '/imageMapResizer.min.js').done(function () {
            $('map').imageMapResize();
        });
    }

    function setupWayPoints() {
        $.getScript(THEME_COMPONENTS_URL + '/jquery.waypoints.min.js').done(function () {

            diagramHeadingArray.waypoint({
                handler: function (direction) {
                    var $that = $(this.element),
                        index = diagramHeadingArray.index($that);

                    if (direction === 'up') {
                        index--;
                        $elevationImage.attr('src', $that.prev().prev().prev().attr('data-elevation-image'));
                    } else {
                        $elevationImage.attr('src', $that.attr('data-elevation-image'));
                    }
                    if (index > 0) {
                        $diagramCounter.css('visibility', 'visible').text(index);
                    } else {
                        $diagramCounter.css('visibility', 'hidden');
                    }
                },
                offset: diagramContainerHeight + 30
            });

            diagramAffixBottom = ($('main').outerHeight(true) + $('header').outerHeight(true)) - diagramContainerHeight;

            $('#more-stories').waypoint({
                handler: function (direction) {
                    if (direction === 'down') {
                        $sideElevation.attr('style', 'position: absolute; top: ' + diagramAffixBottom + 'px');
                        $diagramCounter.css('visibility', 'hidden');
                    } else {
                        $sideElevation.attr('style', '');
                        $diagramCounter.css('visibility', 'visible');
                    }
                },
                offset: diagramContainerHeight + 50
            });

        });
    }

    function init() {
        $sideElevation = $sideElevation = $('#side-elevation');
        $elevationImage = $sideElevation.find('img');
        $diagramCounter = $('.diagramCounter');
        diagramHeadingArray = $('.diagram-copy').find('h3');
        diagramContainerHeight = $sideElevation.outerHeight();

        affixDiagram();

        // Wait for image heights to set before setting up way points
        setTimeout(function () {
            setupResponsiveImageMap();
            setupDiagramEventHandlers();
            setupWayPoints();
        }, 500);
    }

    $(init);
})();