/// <reference path="app.d.ts" />
module KnightsInSpace {
    export class Space {
        height: number;
        width: number;
        x: number;
        y: number;

        sun: any;
        flare: any;
        astroidBelt: any;
        kuiperBelt: any;

        fullHeight: Array<HTMLImageElement>;


        constructor(height: number, width: number, position: any) {
            this.height = height;
            this.width = width;
            this.x = position.left;
            this.y = position.top;

            this.fullHeight = new Array<HTMLImageElement>();

            this.positionPlanets();
        }

        positionPlanets() {
            var planets = $('.planets').css({ top: this.y, left: this.x, width: this.width, height: this.height });

            this.sun = $('.sun')[0];
            this.flare = $('.flare')[0];
            this.astroidBelt = $('.astroid-belt')[0];
            this.kuiperBelt = $('.kuiper-belt img')[0];
            this.fullHeight.push(this.sun, this.flare, this.astroidBelt, this.kuiperBelt);

            this.relativeResize();
        }

        resize() {
            var background: HTMLElement = $('.background')[0];
            var boundingBox = background.getBoundingClientRect();
            this.height = boundingBox.height;
            this.width = boundingBox.width;
            this.x = boundingBox.left;
            this.y = boundingBox.top;

            this.positionPlanets();
        }

        private relativeResize() : void {
            for(var i in this.fullHeight) {
                var planet = this.fullHeight[i];
                var ratio =  planet.naturalWidth / planet.naturalHeight;
                $(planet).height(this.height);
                $(planet).width(this.height * ratio);
            }
        }

        private resizePlanet() {
            
        }
    }
}
