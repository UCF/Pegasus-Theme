if (typeof jQuery != 'undefined'){
	(function(){
		$(document).ready(function() {
			var defaultMenuSeparators = function() {
				// Because IE sucks, we're removing the last stray separator
				// on default navigation menus for browsers that don't 
				// support the :last-child CSS property
				$('.menu.horizontal li:last-child').addClass('last');
			};
			
			var removeExtraGformStyles = function() {
				// Since we're re-registering the Gravity Form stylesheet
				// manually and we can't dequeue the stylesheet GF adds
				// by default, we're removing the reference to the script if
				// it exists on the page (if CSS hasn't been turned off in GF settings.)
				$('link#gforms_css-css').remove();
			}
			
			var legacySupport = function() {
				// To prevent screwy things from happening with bootstrap-responsive.css
				// and the first issue of Pegasus, we need to un-set bootstrap-responsive
				// and style-responsive on any story that is from the first edition
				if (document.title == '58,587' || document.title == 'Nano' || document.title == 'Harris Rosen' || document.title == 'The Big Picture' || document.title == 'Big Screen, Tiny Budget') {
					$('link#bootstrap-responsive-css').remove();
					$('link#style-responsive-css').remove();
				}
			}
			
			var togglePulldown = function() {
				$('.pulldown-toggle').on('click', function(e) {
					e.preventDefault();

					var toggle = $(this),
						pulldownContainer = toggle.attr('data-pulldown-container'), // The pulldown container to put content in
						contentSrc = toggle.attr('data-pulldown-src') || toggle.attr('href'), // Where to grab new pulldown contents from
						dataType = toggle.attr('data-type'); // Type of content to expect from contentSrc (see dataType values: http://api.jquery.com/jQuery.ajax/#data-types)
					
					// If another pulldown is active while a different pulldown is activated,
					// deactivate any existing active pulldowns and activate the new toggle
					// and pulldown.
					if ($('#pulldown.active').length > 0 && !$(pulldownContainer).hasClass('active')) {
						$('.pulldown-container.active').removeClass('active');
						$(pulldownContainer).addClass('active');
						toggle.addClass('active');
					}
					// If the activated pulldown is not active, activate it and its toggle.
					// Else, deactivate it.
					// When mobile navigation is active, disable this functionality.
					else if (!$('#nav-mobile a').hasClass('active')) {
						$('#pulldown').toggleClass('active');
						$(pulldownContainer).toggleClass('active');
						toggle.toggleClass('active');
					}
				});
			}

			var mobileNavToggle = function() {
				$('#nav-mobile a').on('click', function(e) {
					e.preventDefault();

					// Toggle the menu/close btn icons
					$(this).toggleClass('active');

					// Show Issue, Archive nav links; hide Pegasus logo
					$('li#nav-issue, li#nav-archives, #header-navigation .header-logo')
						.toggleClass('mobile-nav-visible');

					// Activate first nav toggle if nothing's active
					if ($('.pulldown-container.active').length < 1) {
						$('.pulldown-container:first-child').addClass('active');
					}

					$('#pulldown').toggleClass('active');
				});
			}


			/* Theme Specific Code Here */
			//Generic.homeDimensions($);
			//Generic.resizeSearch($);
			Webcom.slideshow($);
			Webcom.analytics($);
			Webcom.handleExternalLinks($);
			Webcom.loadMoreSearchResults($);
			defaultMenuSeparators();
			removeExtraGformStyles();
			legacySupport();
			togglePulldown();
			mobileNavToggle();


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
						story_nav.slideToggle().toggleClass('active');
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
					$('#issue-carousel .carousel-control').fadeOut().removeClass('both');
				})
				.bind('slid', function() {
					var index = $('#issue-carousel .item.active').index(),
						left  = false,
						right = false;
					if(index == 0) { // first
						right = true;
					} else if(index == ($('#issue-carousel .item').length - 1)) { // last
						left = true;
					} else {
						left  = true;
						right = true;
					}

					if(left) {
						$('#issue-carousel .carousel-control.left .issue-title').text($('#issue-carousel .item:eq(' + (index - 1) + ') .issue-title').text());
						if(left && right) {
							$('#issue-carousel .carousel-control.left').addClass('both');
						}
						$('#issue-carousel .carousel-control.left').fadeIn();
					}
					if(right) {
						$('#issue-carousel .carousel-control.right .issue-title').text($('#issue-carousel .item:eq(' + (index + 1) + ') .issue-title').text());
						$('#issue-carousel .carousel-control.right').fadeIn();
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
			$('#videoslides,#recipe-carousel').carousel({
				interval: 0
			})

			/* Remove, then re-add video iframes on prev/next button click to prevent multiple videos from playing at a time: */
			$('#videoslides,#recipe-carousel').bind('slide', function() {
				$('.active').addClass('last');
				var videoSrc = $('.last').children('iframe').attr('src');
				$('.last').children('iframe').attr('switchsrc', videoSrc);
			});
			$('#videoslides,#recipe-carousel').bind('slid', function() {
				$('.last').children('iframe').attr('src', 'none');
				var videoSwitchSrc = $('.last').children('iframe').attr('switchsrc');
				$('.last').children('iframe').attr('src', videoSwitchSrc);
				$('.last').removeClass('last');
			});

			/* Popovers */
			$('.popover-parent').popover({});
		});


		$(window).load(function() {
			var loadPulldownMenus = function() {
				$('.pulldown-toggle').each(function() {

					var toggle = $(this),
						pulldownContainer = $(toggle.attr('data-pulldown-container')), // The pulldown container to put content in
						contentSrc = toggle.attr('data-pulldown-src') || toggle.attr('href'), // Where to grab new pulldown contents from
						dataType = toggle.attr('data-type'); // Type of content to expect from contentSrc (see dataType values: http://api.jquery.com/jQuery.ajax/#data-types)

					$.get(contentSrc, function(data) {
						// If this is an HTML document, assume data contains [post_type-list] output.
						// Otherwise, assume it contains WordPress RSS feed content.
						if (dataType == 'html') {
							html = $(data);
							items = html.find('ul[class*="-list"]');

							pulldownContainer
								.find('.items')
									.append(items);
						}
						else {
							xml = $(data);
							items = $(data).find('item');

							var html = '<ul>';

							$.each(items, function(key, val) {
								var item = $(this),
									title = item.find('title').text(),
									subtitle = item.find('description').text(),
									thumb = item.find('enclosure').attr('url'),
									permalink = item.find('link').text();

								html += '<li><a href="'+ permalink +'">';
								html += '<img src="'+ thumb +'" alt="'+ title +'" title="'+ title +'" />';
								html += '<span class="story-title">'+ title +'</span>';
								html += '<span class="subtitle">'+ subtitle +'</span>';
								html += '</a></li>';
							})

							html += '</ul>';

							pulldownContainer
								.find('.items')
									.append(html);
						}
					}, dataType)
						// If something goes wrong, display an error message
						 .fail(function() {
							pulldownContainer.find('.error').removeClass('hidden');
						});

				});
			}

			loadPulldownMenus();
		});
	})(jQuery);
}
else {
	console.log('jQuery dependency failed to load');
}