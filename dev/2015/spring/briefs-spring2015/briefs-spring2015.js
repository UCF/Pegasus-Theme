(function($) {
    $.fn.countTo = function(options) {
        // merge the default plugin settings with the custom options
        options = $.extend({}, $.fn.countTo.defaults, options || {});

        // how many times to update the value, and how much to increment the value on each update
        var loops = Math.ceil(options.speed / options.refreshInterval),
            increment = (options.to - options.from) / loops;

        return $(this).each(function() {
            var _this = this,
                loopCount = 0,
                value = options.from,
                interval = setInterval(updateTimer, options.refreshInterval);

            function updateTimer() {
                value += increment;
                loopCount++;
                $(_this).html(value.toFixed(options.decimals));

                if (typeof(options.onUpdate) == 'function') {
                    options.onUpdate.call(_this, value);
                }

                if (loopCount >= loops) {
                    clearInterval(interval);
                    value = options.to;

                    if (typeof(options.onComplete) == 'function') {
                        options.onComplete.call(_this, value);
                    }
                }
            }
        });
    };

    $.fn.countTo.defaults = {
        from: 0,  // the number the element should start at
        to: 100,  // the number the element should end at
        speed: 1000,  // how long it should take to count between the target numbers
        refreshInterval: 100,  // how often the element should be updated
        decimals: 0,  // the number of decimal places to show
        onUpdate: null,  // callback method for every time the element is updated,
        onComplete: null  // callback method for when the element finishes updating
    };
})(jQuery);

/* ==========================================================
 * bootstrap-affix.js v2.3.2
 * http://twitter.github.com/bootstrap/javascript.html#affix
 * ==========================================================
 * Copyright 2012 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================== */

!function ($) {

  "use strict"; // jshint ;_;


 /* AFFIX CLASS DEFINITION
  * ====================== */

  var Affix = function (element, options) {
    this.options = $.extend({}, $.fn.affix.defaults, options)
    this.$window = $(window)
      .on('scroll.affix.data-api', $.proxy(this.checkPosition, this))
      .on('click.affix.data-api',  $.proxy(function () { setTimeout($.proxy(this.checkPosition, this), 1) }, this))
    this.$element = $(element)
    this.checkPosition()
  }

  Affix.prototype.checkPosition = function () {
    if (!this.$element.is(':visible')) return

    var scrollHeight = $(document).height()
      , scrollTop = this.$window.scrollTop()
      , position = this.$element.offset()
      , offset = this.options.offset
      , offsetBottom = offset.bottom
      , offsetTop = offset.top
      , reset = 'affix affix-top affix-bottom'
      , affix

    if (typeof offset != 'object') offsetBottom = offsetTop = offset
    if (typeof offsetTop == 'function') offsetTop = offset.top()
    if (typeof offsetBottom == 'function') offsetBottom = offset.bottom()

    affix = this.unpin != null && (scrollTop + this.unpin <= position.top) ?
      false    : offsetBottom != null && (position.top + this.$element.height() >= scrollHeight - offsetBottom) ?
      'bottom' : offsetTop != null && scrollTop <= offsetTop ?
      'top'    : false

    if (this.affixed === affix) return

    this.affixed = affix
    this.unpin = affix == 'bottom' ? position.top - scrollTop : null

    this.$element.removeClass(reset).addClass('affix' + (affix ? '-' + affix : ''))
  }


 /* AFFIX PLUGIN DEFINITION
  * ======================= */

  var old = $.fn.affix

  $.fn.affix = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('affix')
        , options = typeof option == 'object' && option
      if (!data) $this.data('affix', (data = new Affix(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  $.fn.affix.Constructor = Affix

  $.fn.affix.defaults = {
    offset: 0
  }


 /* AFFIX NO CONFLICT
  * ================= */

  $.fn.affix.noConflict = function () {
    $.fn.affix = old
    return this
  }


 /* AFFIX DATA-API
  * ============== */

  $(window).on('load', function () {
    $('[data-spy="affix"]').each(function () {
      var $spy = $(this)
        , data = $spy.data()

      data.offset = data.offset || {}

      data.offsetBottom && (data.offset.bottom = data.offsetBottom)
      data.offsetTop && (data.offset.top = data.offsetTop)

      $spy.affix(data)
    })
  })


}(window.jQuery);

$(document).ready(function($) {

    $.getScript(THEME_JS_URL + '/waypoints.min.js').done(function(script, textStatus) {
        $("#num-reviewed").waypoint(function() {
            var data = [60, 40];

            // Create the Pie chart. The arguments are the canvas ID and the data to be shown.
            var pie = new RGraph.Pie('patents-granted-donut', data);

            // Configure the chart to look as you want.
            pie.Set('chart.linewidth', 5);
            pie.Set('chart.strokestyle', 'transparent');
            pie.Set('chart.variant', 'donut');
            pie.Set('chart.colors', ['#00A75A', '#ABD037']);

            RGraph.Effects.Pie.RoundRobin(pie, {'radius': false,'frames':60});

            var countTo = $(this).find('.donut-num').attr('year-data');
            $(this).find('.count').countTo({
                from: 0,
                to: countTo,
                speed: 1000,
                refreshInterval: 50
            });
        }, { offset: '90%', triggerOnce: true });

        $.when(
            $.getScript(THEME_JS_URL + '/rgraph14_4/RGraph.common.effects.js'),
            $.getScript(THEME_JS_URL + '/rgraph14_4/RGraph.common.core.js'),
            $.getScript(THEME_JS_URL + '/rgraph14_4/RGraph.common.tooltips.js'),
            $.getScript(THEME_JS_URL + '/rgraph14_4/RGraph.common.key.js'),
            $.getScript(THEME_JS_URL + '/rgraph14_4/RGraph.common.dynamic.js'),
            $.getScript(THEME_JS_URL + '/rgraph14_4/RGraph.bar.js')
        ).done(function(){
            // Air Time Height
            // $("#patents-granted").waypoint(function() {

                var data = [1776, 2722, 29029, 30000, 37000, 102800, 128100, 135890, 400000];
                var labels = ['Trade Center', 'Burj Khalifa', 'Mount Everest', 'Jet', 'Bird', '1960', '2012', '2014', 'ISS'];

                var bar = new RGraph.Bar('airtime-graph', data);

                // Configure the chart to look as you want.
                bar.Set('chart.labels', labels);
                bar.Set('chart.noaxes', true);
                bar.Set('chart.background.grid', false);
                bar.Set('chart.ylabels', false);
                bar.Set('chart.hmargin', 18);
                bar.Set('text.color', 'white');
                bar.Set('text.size', 12);
                bar.Set('chart.labels.above', true);
                bar.Set('chart.labels.above.specific', ['1,776', '2,722', '29,029', '30,000', '37,000', '102,800', '128,100', '135,890', '1,415,040']);
                // bar.Set('chart.linewidth', 5);
                // bar.Set('chart.strokestyle', 'transparent');
                // bar.Set('chart.variant', 'donut');
                // bar.Set('background.color', '#dde6ff');
                bar.Set('chart.colors', ['#ffffff']);

                bar.Draw();

                // RGraph.Effects.Bar.RoundRobin(bar, {'radius': false,'frames':60});

                // var countTo = $(this).find('.donut-num').attr('year-data');
                // $(this).find('.count').countTo({
                //     from: 0,
                //     to: countTo,
                //     speed: 1000,
                //     refreshInterval: 50
                // });
            // }, {offset: '90%', triggerOnce: 'true'});
        });
    });
});
