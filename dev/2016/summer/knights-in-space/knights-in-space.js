/// <reference path="app.d.ts" />
var KnightsInSpace;
(function (KnightsInSpace) {
    var App = (function () {
        function App(element) {
            var $intro = $('#intro-screen').modal('hide');
            $intro.on('hide.bs.modal', function (e) {
                $('.title-instructions').removeClass('invisible');
            })
                .on('show.bs.modal', function (e) {
                $('.title-instructions').addClass('invisible');
            });
            $('.detail').on('show.bs.modal', function (e) {
                var target = $(e.target).find('.modal-body');
                if (target.find('.detail-inner').length == 0) {
                    var markup = $('div[data-related="' + e.target.id + '"]').get(0);
                    $(markup).prepend('<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>');
                    target.append(markup);
                }
            });
            if ($(window).width() > 767) {
                $intro.modal('show');
            }
            this.scaleImageMap();
        }
        App.prototype.scaleImageMap = function () {
            var scale = $('.background').width() / 2000;
            var $map = $('#planetmap');
            $map.find('area').each(function (i, a) {
                var coords = $(a).attr('coords');
                var cSplit = coords.split(',');
                var scaledCoords = new Array();
                for (var c in cSplit) {
                    var index = parseInt(c), value = Math.floor(parseInt(cSplit[c]) * scale);
                    if (index === 1 || index === 3) {
                        value -= 20;
                    }
                    scaledCoords.push(value);
                }
                $(a).attr('coords', scaledCoords.join(','));
            });
        };
        return App;
    }());
    KnightsInSpace.App = App;
})(KnightsInSpace || (KnightsInSpace = {}));
$(document).ready(function () {
    new KnightsInSpace.App('knights-in-space');
});
//# sourceMappingURL=knights-in-space.js.map