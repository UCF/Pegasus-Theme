/// <reference path="../../../../typings/jquery/jquery.d.ts"/>

var $sideElevation,
    diagramCopyHeadingArray,
    $elevationImage,
    robotContainerHeight,
    diagramCopyPositionArray = [];

(function ($) {
    $.fn.sonar = function () {
        function animateSonar($sonar) {
            if ($sonar && $sonar.hasClass('sonar-anime')) {
                // hide element because the animation reverses when the sonar-anime class is removed
                $sonar.hide();
                setTimeout(function () { $sonar.show(); }, 2000);
            }
            $sonar.toggleClass('sonar-anime');
        }
        return this.each(function () {
            $(this).show();
            setInterval(animateSonar, 3000, $(this));
        });
    };    
} (jQuery));

function affixRobot() {
    var affixTop = $sideElevation.offset().top;
    $sideElevation.affix({
        offset: {
            top: affixTop
        }
    });
} 

function scrollToDesc(index) {    
    var scrollTo = diagramCopyHeadingArray.eq(index).offset().top - (robotContainerHeight + 20);
    $('html,body').animate({
        scrollTop: scrollTo
    }, 500);
}

function setImage(index) {
    // TODO: dynamically get the image path
    $elevationImage.attr('src', '/pegasus/wp-content/themes/Pegasus-Theme/dev/2015/summer/mars/img/elevation' + index + '.png');
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
        if (scrollPosition >= diagramCopyPositionArray[6]) {
            setImage(6);
        } else if (scrollPosition >= diagramCopyPositionArray[5]) {
            setImage(5);
        } else if (scrollPosition >= diagramCopyPositionArray[4]) {
            setImage(4);
        } else if (scrollPosition >= diagramCopyPositionArray[3]) {
            setImage(3);
        } else if (scrollPosition >= diagramCopyPositionArray[2]) {
            setImage(2);
        } else if (scrollPosition >= diagramCopyPositionArray[1]) {
            setImage(1);
        } else if (scrollPosition < diagramCopyPositionArray[0]) {
            setImage(0);
        }
    });
}

function populateDiagramCopyArray() {
    var tempArray = [];
    $.each(diagramCopyHeadingArray, function (i) {
        tempArray[i] = diagramCopyHeadingArray.eq(i).offset().top - robotContainerHeight;
    });
    diagramCopyPositionArray = tempArray;
}

$(function () {    
    function init() {
        $sideElevation = $sideElevation = $('#side-elevation');
        diagramCopyHeadingArray = $('.diagram-copy').find('h2');
        $elevationImage = $sideElevation.find('img');
        robotContainerHeight = $elevationImage.outerHeight();
        populateDiagramCopyArray();
        // $('.sonar').sonar();
        affixRobot();
        setupDiagramEventHandlers();
        setupScrollEventHandlers();
        $('map').imageMapResize();
    }
    $(init);
});


