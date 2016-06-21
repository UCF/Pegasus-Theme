/// <reference path="app.d.ts" />
var KnightsInSpace;
(function (KnightsInSpace) {
    var Space = (function () {
        function Space(height, width, position) {
            this.height = height;
            this.width = width;
            this.x = position.left;
            this.y = position.top;
            this.fullHeight = new Array();
            this.positionPlanets();
        }
        Space.prototype.positionPlanets = function () {
            var planets = $('.planets').css({ top: this.y, left: this.x, width: this.width, height: this.height });
            this.sun = $('.sun')[0];
            this.flare = $('.flare')[0];
            this.astroidBelt = $('.astroid-belt')[0];
            this.kuiperBelt = $('.kuiper-belt img')[0];
            this.fullHeight.push(this.sun, this.flare, this.astroidBelt, this.kuiperBelt);
            this.relativeResize();
        };
        Space.prototype.resize = function () {
            var background = $('.background')[0];
            var boundingBox = background.getBoundingClientRect();
            this.height = boundingBox.height;
            this.width = boundingBox.width;
            this.x = boundingBox.left;
            this.y = boundingBox.top;
            this.positionPlanets();
        };
        Space.prototype.relativeResize = function () {
            for (var i in this.fullHeight) {
                var planet = this.fullHeight[i];
                var ratio = planet.naturalWidth / planet.naturalHeight;
                $(planet).height(this.height);
                $(planet).width(this.height * ratio);
            }
        };
        Space.prototype.resizePlanet = function () {
        };
        return Space;
    }());
    KnightsInSpace.Space = Space;
})(KnightsInSpace || (KnightsInSpace = {}));
/// <reference path="app.d.ts" />
var KnightsInSpace;
(function (KnightsInSpace) {
    var App = (function () {
        function App(element) {
            var _this = this;
            var $intro = $('#intro-screen').modal('hide');
            $intro.on('hide.bs.modal', function (e) {
                $('.title-instructions').removeClass('invisible');
            })
                .on('show.bs.modal', function (e) {
                $('.title-instructions').addClass('invisible');
            });
            if ($(window).width() > 767) {
                $intro.modal('show');
            }
            this.element = $('#' + element);
            this.background = $('.background').get(0);
            this.space = new KnightsInSpace.Space($(this.background).height(), $(this.background).width(), $(this.background).position());
            $(window).resize(function () {
                _this.space.resize();
            });
        }
        return App;
    }());
    KnightsInSpace.App = App;
})(KnightsInSpace || (KnightsInSpace = {}));
$(document).ready(function () {
    new KnightsInSpace.App('knights-in-space');
});
//# sourceMappingURL=knights-in-space.js.map