/// <reference path="app.d.ts" />
module KnightsInSpace {
    export class App {
        element: any;
        background: any;
        space: KnightsInSpace.Space;

        constructor(element: string) {
            var $intro = $('#intro-screen').modal('hide');

            $intro.on('hide.bs.modal', function(e) {
                $('.title-instructions').removeClass('invisible');
            })
            .on('show.bs.modal', function(e) {
                $('.title-instructions').addClass('invisible');
            });

            if ($(window).width() > 767) {
                $intro.modal('show');
            }

            this.element = $('#' + element);
            this.background = $('.background').get(0);
            
            this.space = new KnightsInSpace.Space($(this.background).height(), $(this.background).width(), $(this.background).position());

            $(window).resize( () => {
                this.space.resize();
            });
        }
    }
}

$(document).ready( () => {
    new KnightsInSpace.App('knights-in-space');
});
