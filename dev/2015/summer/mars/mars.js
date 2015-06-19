/// <reference path="../../../../typings/jquery/jquery.d.ts"/>

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

$(function () {
    function init() {
        $('.sonar').sonar();
    }
    $(init);
});

