if (typeof jQuery != 'undefined'){
	jQuery(document).ready(function($) {
        window.onload = function () {
        	var header_images = $('.header-img');
			var total = header_images.length - 1;
			var current_index = 0;

			setInterval(function() {
				if (current_index == total) {
					current_index = 0;
					$('.header-img:not("#header-img-'+ total +', #header-img-0")').hide(); // hide every visible img between the last and 1st one
					$('#header-img-' + total).fadeOut(1000); // fade out the last one for a smooth transition to the 1st
					
				} else {
					current_index++;
				}

				$.each(header_images, function(index, value) {
					var image = $(value);
					if (image.attr('id') == 'header-img-' + current_index && current_index !== 0) {
						image.fadeIn(1000);
					}
				});
			}, 5000);

			if ($(window).width() > 979) {

	        	$.when(
		        	$.getScript(THEME_JS_URL + '/rgraph/RGraph.common.core.js'),
		        	$.getScript(THEME_JS_URL + '/rgraph/RGraph.common.effects.js'),
		        	$.getScript(THEME_JS_URL + '/rgraph/RGraph.common.tooltips.js'),
		        	$.getScript(THEME_JS_URL + '/rgraph/RGraph.common.key.js'),
		        	$.getScript(THEME_JS_URL + '/rgraph/RGraph.common.dynamic.js'),
		        	$.getScript(THEME_JS_URL + '/rgraph/RGraph.line.js'),
		        	$.Deferred(function( deferred ){
				        $( deferred.resolve );
				    })
		        ).done(function() {
	        		/* 15-year enrollment comparison between state universities chart */
			
					         /* '01   '02   '03   '04   '05   '06   '07   '08   '09   '10   '11   '12  */
					var e_ucf = [63,   75,   89,   82,  100,  101,  118,  119,  122,  133,  107,  129];
		            var graph1 = new RGraph.Line('graph1', e_ucf);
		            graph1.Set('chart.background.grid', false);
		            graph1.Set('chart.linewidth', 3);
		            graph1.Set('chart.gutter.left', 70);
					graph1.Set('chart.gutter.right', 30);
					graph1.Set('chart.gutter.top', 10);
					graph1.Set('chart.gutter.bottom', 20);
		            graph1.Set('chart.hmargin', 5);
		            graph1.Set('chart.units.post', '');
					graph1.Set('chart.ylabels', false);
					graph1.Set('chart.ymin', 50);
					graph1.Set('chart.ymax', 140);
		            graph1.Set('chart.colors', ['#c5c5cc']);
					graph1.Set('chart.background.grid.border', false);
		            graph1.Set('chart.background.grid.autofit', true);
		            graph1.Set('chart.background.grid.autofit.numhlines', 0);
					graph1.Set('chart.background.grid.vlines',false);
		            graph1.Set('chart.curvy', 0);
					graph1.Set('chart.noaxes', true);
					graph1.Set('chart.shadow', true);
					graph1.Set('chart.tickmarks', myTick);
					if (!document.all || RGraph.isIE9up()) {
		                graph1.Set('chart.shadow', false);
		            }

		        	function myTick(obj, data, value, index, x, y, color, prevX, prevY) {
		        		var img = new Image();
		        		img.src = 'data:image/jpeg;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAICAMAAAAcEyWHAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYxIDY0LjE0MDk0OSwgMjAxMC8xMi8wNy0xMDo1NzowMSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNS4xIE1hY2ludG9zaCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDoxRjg4RTlBNjdBMDIxMUUyOTM3OEJBOEJFNjlDMjQxQiIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDoxRjg4RTlBNzdBMDIxMUUyOTM3OEJBOEJFNjlDMjQxQiI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjFGODhFOUE0N0EwMjExRTI5Mzc4QkE4QkU2OUMyNDFCIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjFGODhFOUE1N0EwMjExRTI5Mzc4QkE4QkU2OUMyNDFCIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+5o7uzAAAACRQTFRFrrO9kpqnm6Kupau19vf4trvEd4CQgImY5Obp293hbniJ////dr/hrQAAAAx0Uk5T//////////////8AEt/OzgAAADhJREFUeNpEyjESACAMAkHUqAn8/7/GWEhxswWQ5iJhgibvemqV6IgHBvQ1Cjt/1i48JXlE9ggwAGRhApFdwyg+AAAAAElFTkSuQmCC';

		        		img.onload = function() {
		        			obj.context.drawImage(this, x - (this.width / 2), y - (this.height / 2));
		        		}
		        	}
					
					/* Animate the line generation based on viewport position */
					
					$('canvas#graph1').bind('inview', function (event, visible) {
						if (visible == true) {
							RGraph.Effects.Line.jQuery.Trace(graph1); /* Line animation */
							$('canvas#graph1').unbind('inview');
						} else {
							$('canvas#graph1').unbind('inview');
						}
					});
				});
			}
		}
	});
}