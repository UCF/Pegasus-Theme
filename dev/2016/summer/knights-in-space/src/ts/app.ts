/// <reference path="app.d.ts" />
module KnightsInSpace {
    export class App {
        element: any;
        background: any;
        space: KnightsInSpace.Space;

        constructor(element: string) {
            this.element = $('#' + element);
            this.background = $('#background').get(0);
            KnightsInSpace.matchHeight(this.background);
            this.space = new KnightsInSpace.Space($(this.background).height(), $(this.background).width(), $(this.background).position());

            $(window).resize( () => {
                KnightsInSpace.matchHeight(this.background);
                this.space.resize();
            });
        }
    }
}

$(document).ready( () => {
    new KnightsInSpace.App('knights-in-space');
});
