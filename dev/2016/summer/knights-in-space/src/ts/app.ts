/// <reference path="app.d.ts" />
module KnightsInSpace {
    export class App {
        element: any;
        background: any;
        justloaded: boolean;

        backgroundWidth: number;
        backgroundHeight: number;

        constructor(element: string) {
            var $intro = $('#intro-screen').modal('hide');

            $intro.on('hide.bs.modal', function(e) {
                $('.title-instructions').removeClass('invisible');
            })
            .on('show.bs.modal', function(e) {
                $('.title-instructions').addClass('invisible');
            });

            $('.detail').on('show.bs.modal', function(e) {
                var target = $(e.target).find('.modal-body');
                if ( target.find('.detail-inner').length == 0) {
                    var markup = $('div[data-related="' + e.target.id + '"]').get(0);
                    $(markup).prepend('<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>');
                    target.append(markup);
                }
            });

            if ($(window).width() > 767) {
                $intro.modal('show');
            }

            $(window).resize(this.scaleImageMap);

            $('.expand-toggle').click(this.expand);

            setTimeout(this.scaleImageMap, 100); // Wait 100ms for scale to make sure image is loaded.
        }

        scaleImageMap() {
            // Make sure background width and height are set.
            this.backgroundWidth = this.backgroundWidth ? this.backgroundWidth : 2000;
            this.backgroundHeight = this.backgroundHeight ? this.backgroundHeight : 1175;

            // Cache background as we will be referring to it quite a bit.
            var $background = $('.background');

            // Calculate scale based on new width-height divided by old width/height.
            var scale = {
                x: $background.width() / this.backgroundWidth,
                y: $background.height() / this.backgroundHeight
            };

            // Set old width/height to the new values.
            this.backgroundWidth = $background.width();
            this.backgroundHeight = $background.height();

            var $map = $('#planetmap');
            
            $map.find('area').each((i, a) => {
                var coords = $(a).attr('coords');
                var cSplit = coords.split(',');
                var scaledCoords = new Array<number>();

                for(var c in cSplit) {
                    var index = parseInt(c),
                        origVal = parseInt(cSplit[c]),
                        newVal = 0;

                    if (index === 1 || index === 3 ) {
                        newVal = Math.floor(origVal * scale.y);
                    } else {
                        newVal = Math.floor(origVal * scale.x);
                    }
                    scaledCoords.push(newVal);
                }
                $(a).attr('coords', scaledCoords.join(','));
            });
        }

        expand(e: JQueryEventObject) {
            e.preventDefault();
            var $toggle = $(e.target).closest('.expand-toggle');
            var $detail = $(e.target).closest('.detail-inner');
            var $expand = $detail.find('.expand');

            if ( ! $toggle.hasClass('active') ) {
                $('.expand-toggle, .detail-inner, .expand').removeClass('active');
            }

            $toggle.toggleClass('active');
            $detail.toggleClass('active');
            $expand.toggleClass('active');
        }
    }
}

$(document).ready( () => {
    new KnightsInSpace.App('knights-in-space');
});
