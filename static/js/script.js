if (typeof jQuery != 'undefined'){
	jQuery(document).ready(function($) {
		if($.browser.msie) {
			var version = $.browser.version;
			if(version >= 7 && version < 8) {
				$('body').addClass('ie7');
			} else if(version >= 8 && version < 9) {
				$('body').addClass('ie8');
			} else if(version >= 9 && version < 10) {
				$('body').addClass('ie9');
			}
		}

		var defaultMenuSeparators = function($) {
			// Because IE sucks, we're removing the last stray separator
			// on default navigation menus for browsers that don't 
			// support the :last-child CSS property
			$('.menu.horizontal li:last-child').addClass('last');
		};
		
		var removeExtraGformStyles = function($) {
			// Since we're re-registering the Gravity Form stylesheet
			// manually and we can't dequeue the stylesheet GF adds
			// by default, we're removing the reference to the script if
			// it exists on the page (if CSS hasn't been turned off in GF settings.)
			$('link#gforms_css-css').remove();
		}
		
		var legacySupport = function($) {
			// To prevent screwy things from happening with bootstrap-responsive.css
			// and the first issue of Pegasus, we need to un-set bootstrap-responsive
			// and style-responsive on any story that is from the first edition
			if (document.title == '58,587' || document.title == 'Nano' || document.title == 'Harris Rosen' || document.title == 'The Big Picture' || document.title == 'Big Screen, Tiny Budget') {
				$('link#bootstrap-responsive-css').remove();
				$('link#style-responsive-css').remove();
			}
		}
		

		/* Theme Specific Code Here */
		//Generic.homeDimensions($);
		//Generic.resizeSearch($);
		Webcom.slideshow($);
		Webcom.analytics($);
		Webcom.handleExternalLinks($);
		Webcom.loadMoreSearchResults($);
		defaultMenuSeparators($);
		removeExtraGformStyles($);
		legacySupport($);


		// Is this the user's first visit to the site?
		var initial_visit = $.cookie('initial-visit') == null ? true : false,
			ipad          = navigator.userAgent.match(/iPad/i) == null ? false : true;

		(function() {
			$('.header_stories').hide();
			
			var toggle_nav         = $('.toggle_story_nav a'),
				toggle_nav_tooltip = null,
				tooltip_options    = {
					placement:'bottom',
					title  :'<strong>Click here <br /> for more stories</strong>'
				};

			if(!ipad) {
				toggle_nav.tooltip(tooltip_options);
			}
			toggle_nav
				.on( (ipad) ? 'touchend' : 'click', function(e) {
					e.preventDefault();
					var story_nav = $('.header_stories');
					if(story_nav.is(':visible')) {
						$(this).html('&#9650;');
						if(!ipad) {
							toggle_nav.tooltip('hide').attr('data-original-title', '<strong>Click here <br /> for more stories</strong>').tooltip('fixTitle').tooltip('show');
						}
					} else {
						$(this).html('&#9660;');
						if(!ipad) {
							toggle_nav.tooltip('hide').attr('data-original-title', '<strong>Close menu</strong>').tooltip('fixTitle').tooltip('show');
						}
					}
					story_nav.slideToggle();
				});
		})();


		/* iPad Model */
		(function() {
			var ipad_hide = $.cookie('ipad-hide');
			if((ipad_hide == null || !ipad_hide) && ipad && IPAD_DEPLOYED) {
				$('#ipad')
					.modal()
					.on('hidden', function() {
						$.cookie('ipad-hide', true);
					});
			}
		})();

		$.cookie('initial-visit', true);


		/* Issue slider controls */
		$('#issue-carousel')
			.carousel({
				interval: 0
			})
			.bind('slide', function() {
				$('#issue-carousel .carousel-control').fadeOut();
			})
			.bind('slid', function() {
				var index = $('#issue-carousel .item.active').index();				
				if(index == 0) { // first
					$('#issue-carousel .carousel-control.right .issue-title').text($('#issue-carousel .item:eq(' + (index + 1) + ') .issue-title').text());
					$('#issue-carousel .carousel-control.right').fadeIn();
				} else if(index == ($('#issue-carousel .item').length - 1)) { // last
					$('#issue-carousel .carousel-control.left .issue-title').text($('#issue-carousel .item:eq(' + (index - 1) + ') .issue-title').text());
					$('#issue-carousel .carousel-control.left').fadeIn();
				} else {

				}
			});


		/* Move Gravity Forms Address sublabels above the fields: */
		$('.ginput_container label').each(function(i,e){
			var field_desc = $('<div>').append($(e).clone()).remove().html();
			$(e).siblings('.ginput_container input').before(field_desc); //moves sub label above input fields
			$(e).siblings('.ginput_container select').before(field_desc); //moves sub label above select fields (e.g. country drop-down)
			$(e).remove();
		});


		/* Prevent video sliders from automatically advancing */
		$('#videoslides').carousel({
			interval: 0
		})

		/* Remove, then re-add video iframes on prev/next button click to prevent multiple videos from playing at a time: */
		$('#videoslides').bind('slide', function() {
			$('.active').addClass('last');
			var videoSrc = $('.last').children('iframe').attr('src');
			$('.last').children('iframe').attr('switchsrc', videoSrc);
		});
		$('#videoslides').bind('slid', function() {
			$('.last').children('iframe').attr('src', 'none');
			var videoSwitchSrc = $('.last').children('iframe').attr('switchsrc');
			$('.last').children('iframe').attr('src', videoSwitchSrc);
			$('.last').removeClass('last');
		});

		/* Popovers */
		$('.popover-parent').popover({});
	});
}else{console.log('jQuery dependancy failed to load');}