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
            var background = $('#background')[0];
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
    function matchHeight(element) {
        $(element).height($(element).width() / 16 * 9);
    }
    KnightsInSpace.matchHeight = matchHeight;
})(KnightsInSpace || (KnightsInSpace = {}));
/// <reference path="app.d.ts" />
var KnightsInSpace;
(function (KnightsInSpace) {
    var App = (function () {
        function App(element) {
            var _this = this;
            this.element = $('#' + element);
            this.background = $('#background').get(0);
            KnightsInSpace.matchHeight(this.background);
            this.space = new KnightsInSpace.Space($(this.background).height(), $(this.background).width(), $(this.background).position());
            $(window).resize(function () {
                KnightsInSpace.matchHeight(_this.background);
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