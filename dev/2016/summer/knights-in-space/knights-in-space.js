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
            $('.detail').on('show.bs.modal', this.addDetail);
            if ($(window).width() > 767) {
                $intro.modal('show');
            }
            $('.expand-toggle').click(this.expand);
        }
        App.prototype.addDetail = function (e) {
            var target = $(e.target).find('.modal-body');
            if (target.find('.detail-inner').length == 0) {
                var markup = $('div[data-related="' + e.target.id + '"]').clone().get(0);
                $(markup).prepend('<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>');
                target.append(markup);
            }
        };
        App.prototype.expand = function (e) {
            e.preventDefault();
            var $toggle = $(e.target).closest('.expand-toggle');
            var $detail = $(e.target).closest('.detail-inner');
            var $expand = $detail.find('.expand');
            if (!$toggle.hasClass('active')) {
                $('.expand-toggle, .detail-inner, .expand').removeClass('active');
            }
            $toggle.toggleClass('active');
            $detail.toggleClass('active');
            $expand.toggleClass('active');
        };
        return App;
    }());
    KnightsInSpace.App = App;
})(KnightsInSpace || (KnightsInSpace = {}));
$(document).ready(function () {
    new KnightsInSpace.App('knights-in-space');
});
//# sourceMappingURL=knights-in-space.js.map
