/**************************
 * document.ready
 **************************/
var Generic = {};

Generic.PostTypeSearch = function($) {
	$('.post-type-search')
		.each(function(post_type_search_index, post_type_search) {
			var post_type_search = $(post_type_search),
				form             = post_type_search.find('.post-type-search-form'),
				field            = form.find('input[type="text"]'),
				working          = form.find('.working'),
				results          = post_type_search.find('.post-type-search-results'),
				by_term          = post_type_search.find('.post-type-search-term'),
				by_alpha         = post_type_search.find('.post-type-search-alpha'),
				sorting          = post_type_search.find('.post-type-search-sorting'),
				sorting_by_term  = sorting.find('button:eq(0)'),
				sorting_by_alpha = sorting.find('button:eq(1)'),

				post_type_search_data  = null,
				search_data_set        = null,
				column_count           = null,
				column_width           = null,

				typing_timer = null,
				typing_delay = 300, // milliseconds

				prev_post_id_sum = null, // Sum of result post IDs. Used to cache results

				MINIMUM_SEARCH_MATCH_LENGTH = 2;

			// Get the post data for this search
			post_type_search_data = PostTypeSearchDataManager.searches[post_type_search_index];
			if(typeof post_type_search_data == 'undefined') { // Search data missing
				return false;
			}

			search_data_set = post_type_search_data.data;
			column_count    = post_type_search_data.column_count;
			column_width    = post_type_search_data.column_width;

			if(column_count == 0 || column_width == '') { // Invalid dimensions
				return false;
			}

			// Sorting toggle
			sorting_by_term.click(function() {
				by_alpha.fadeOut('fast', function() {
					by_term.fadeIn();
					sorting_by_alpha.removeClass('active');
					sorting_by_term.addClass('active');
				});
			});
			sorting_by_alpha.click(function() {
				by_term.fadeOut('fast', function() {
					by_alpha.fadeIn();
					sorting_by_term.removeClass('active');
					sorting_by_alpha.addClass('active');
				});
			});

			// Search form
			form
				.submit(function(event) {
					// Don't allow the form to be submitted
					event.preventDefault();
					perform_search(field.val());
				})
			field
				.keyup(function() {
					// Use a timer to determine when the user is done typing
					if(typing_timer != null)  clearTimeout(typing_timer);
					typing_timer = setTimeout(function() {form.trigger('submit');}, typing_delay);
				});

			function display_search_message(message) {
				results.empty();
				results.append($('<p class="post-type-search-message"><big>' + message + '</big></p>'));
				results.show();
			}

			function perform_search(search_term) {
				var matches             = [],
					elements            = [],
					elements_per_column = null,
					columns             = [],
					post_id_sum         = 0;

				if(search_term.length < MINIMUM_SEARCH_MATCH_LENGTH) {
					results.empty();
					results.hide();
					return;
				}
				// Find the search matches
				$.each(search_data_set, function(post_id, search_data) {
					$.each(search_data, function(search_data_index, term) {
						if(term.toLowerCase().indexOf(search_term.toLowerCase()) != -1) {
							matches.push(post_id);
							return false;
						}
					});
				});
				if(matches.length == 0) {
					display_search_message('No results were found.');
				} else {

					// Copy the associated elements
					$.each(matches, function(match_index, post_id) {

						var element     = by_term.find('li[data-post-id="' + post_id + '"]:eq(0)'),
							post_id_int = parseInt(post_id, 10);
						post_id_sum += post_id_int;
						if(element.length == 1) {
							elements.push(element.clone());
						}
					});

					if(elements.length == 0) {
						display_search_message('No results were found.');
					} else {

						// Are the results the same as last time?
						if(post_id_sum != prev_post_id_sum) {
							results.empty();
							prev_post_id_sum = post_id_sum;


							// Slice the elements into their respective columns
							elements_per_column = Math.ceil(elements.length / column_count);
							for(var i = 0; i < column_count; i++) {
								var start = i * elements_per_column,
									end   = start + elements_per_column;
								if(elements.length > start) {
									columns[i] = elements.slice(start, end);
								}
							}

							// Setup results HTML
							results.append($('<div class="row"></div>'));
							$.each(columns, function(column_index, column_elements) {
								var column_wrap = $('<div class="' + column_width + '"><ul></ul></div>'),
									column_list = column_wrap.find('ul');

								$.each(column_elements, function(element_index, element) {
									column_list.append($(element));
								});
								results.find('div[class="row"]').append(column_wrap);
							});
							results.show();
						}
					}
				}
			}
		});
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

iosRotateAdjust = function($) {
	// Adjust iOS devices on rotate
    if (navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i)) {
        var viewportmeta = document.querySelector('meta[name="viewport"]');
        if (viewportmeta) {
            viewportmeta.content = 'width=device-width, minimum-scale=1.0, maximum-scale=1.0, initial-scale=1.0';
            document.body.addEventListener('gesturestart', function () {
                viewportmeta.content = 'width=device-width, minimum-scale=0.25, maximum-scale=1.6';
            }, false);
        }
    }
}

addBodyClasses = function($) {
	// Assign browser-specific body classes on page load
    var bodyClass = '';
    // Old IE:
    if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) { //test for MSIE x.x;
            var ieversion = new Number(RegExp.$1) // capture x.x portion and store as a number

            if (ieversion >= 10)     { bodyClass = 'ie ie10'; }
            else if (ieversion >= 9) { bodyClass = 'ie ie9'; }
            else if (ieversion >= 8) { bodyClass = 'ie ie8'; }
            else if (ieversion >= 7) { bodyClass = 'ie ie7'; }
    }
     // IE11+:
    else if (navigator.appName === 'Netscape' && !!navigator.userAgent.match(/Trident\/7.0/)) { bodyClass = 'ie ie11'; }
    // iOS:
    else if (navigator.userAgent.match(/iPhone/i)) { bodyClass = 'iphone'; }
    else if (navigator.userAgent.match(/iPad/i))   { bodyClass = 'ipad'; }
    else if (navigator.userAgent.match(/iPod/i))   { bodyClass = 'ipod'; }
    // Android:
    else if (navigator.userAgent.match(/Android/i)) { bodyClass = 'android'; }

    $('body').addClass(bodyClass);
}

var togglePulldown = function($) {
	// Unset tabbability on links inside a hidden pulldown.
	$('#pulldown a').attr('tabindex', '-1');

	$('.pulldown-toggle').on('click', function(e) {
		e.preventDefault();

		var toggle = $(this),
			pulldownContainer = $(toggle.attr('data-pulldown-container')), // The pulldown container to put content in
			pulldownWrap = $('#pulldown');

		// Trigger lazyload if it hasn't been triggered
		pulldownContainer
			.find('img.lazy')
				.trigger('triggerLazy');

		// Make sure that any previously set tabindex values are reset to -1.
		pulldownWrap
			.find('a')
				.attr('tabindex', '-1');

		// If another pulldown is active while a different pulldown is activated,
		// deactivate any existing active pulldowns and activate the new toggle
		// and pulldown.
		if ($('#pulldown.active').length > 0 && !pulldownContainer.hasClass('active')) {
			$('.pulldown-container.active, .pulldown-toggle.active')
				.removeClass('active');
			pulldownContainer
				.addClass('active')
				.find('a')
					.attr('tabindex', '0');
			toggle.addClass('active');
		}
		// If the activated pulldown is not active, activate it and its toggle.
		// Else, deactivate it.
		// When mobile navigation is active, disable this functionality.
		else if (!$('#nav-mobile a').hasClass('active')) {
			pulldownWrap.toggleClass('active');
			pulldownContainer.toggleClass('active');

			if (pulldownContainer.hasClass('active')) {
				pulldownContainer.find('a').attr('tabindex', '0');
			}
			else {
				pulldownContainer.find('a').attr('tabindex', '-1');
			}

			toggle.toggleClass('active');
		}

		// If toggle is a close button, always remove .active classes and tabbability.
		if (toggle.hasClass('close')) {
			$('#pulldown.active, .pulldown-container.active, .pulldown-toggle.active')
				.andSelf()
				.removeClass('active');
			pulldownWrap
				.find('a')
					.attr('tabindex', '-1');
		}

		// Check newly-assigned .active classes on #pulldown.
		// Set a fixed height for #pulldown so that transitions work properly
		// if #pulldown has been assigned an active class
		if (pulldownWrap.hasClass('active')) {
			var newHeight = pulldownContainer.height() - 20; // subtract 20 to hide scrollbars
			pulldownWrap.css('height', newHeight);
			pulldownContainer.find('.controls').css('height', newHeight);
		}
		else {
			pulldownWrap.css('height', 0);
		}
	});
}

var loadPulldownMenus = function($) {
	$('.pulldown-toggle').each(function() {
		var toggle = $(this),
			pulldownContainer = $(toggle.attr('data-pulldown-container')),
			storyList = pulldownContainer.find('.story-list');

		pulldownContainer
			.find('img.lazy')
				.lazyload({
					effect: 'fadeIn',
					container: storyList,
					event: 'triggerLazy'
				})
				.end();
	});
}

var pulldownMenuScroll = function($) {
	// Handle left/right nav arrow btn click in story list controls
	$('.story-list + .controls a').on('click', function(e) {
		e.preventDefault();

		var controlBtn = $(this),
			parentContainer = controlBtn.parents('.controls').parent(),
			itemList = parentContainer.find('.story-list'),
			controlWrap = parentContainer.find('.controls');

		// x-overflowing div width only calculates apparent window width.
		// Need to calculate the combined widths of all child items
		// to get the value that we need.
		var itemListWidth = controlWrap.outerWidth();
		itemList.children('article').each(function() {
			itemListWidth += $(this).outerWidth();
			itemListWidth += parseInt($(this).css('margin-left'));
		});

		var newScrollVal = 0;
		var curScrollVal = itemList.scrollLeft();

		// Get the number of pixels to scroll the itemList
		if (controlBtn.hasClass('forward')) {
			newScrollVal = curScrollVal + parentContainer.width();
			newScrollVal = (newScrollVal > itemListWidth - parentContainer.width()) ? controlWrap.outerWidth() + itemListWidth - parentContainer.width() : newScrollVal;
		}
		else if (controlBtn.hasClass('backward')) {
			newScrollVal = curScrollVal - parentContainer.width();
			newScrollVal = (newScrollVal < 0) ? 0 : newScrollVal;
		}

		// Animate scrolling
		if (curScrollVal != newScrollVal) {
			itemList.animate({
				scrollLeft: newScrollVal
			}, 400);
		}
	});
}

var mobileNavToggle = function($) {
	// Handle window resizing with mobile navigation active.
	// Unset any active pulldown containers, toggles and logo/nav mods.
	$(window).on('resize', function() {
		if (
			(
				$(this).width() > 767 &&
				$('#header-navigation ul, #header-navigation .header-logo').hasClass('mobile-nav-visible')
			) ||
			(
				$(this).width() < 768 &&
				!$('#header-navigation ul, #header-navigation .header-logo').hasClass('mobile-nav-visible')
			)
		) {
			$('#header-navigation ul, #header-navigation .header-logo')
				.removeClass('mobile-nav-visible');
			$('#pulldown.active, #pulldown .active, #nav-mobile .active')
				.removeClass('active');
			$('#nav-mobile .close')
				.removeClass('close');
			$('#pulldown')
				.css('height', 0);
		}
	});

	// Handle link click (this assumes the mobile toggle link has
	// a default data-pulldown-container attribute value set)
	$('#nav-mobile a').on('click', function(e) {
		e.preventDefault();

		var toggle = $(this),
			navList = toggle.parents('ul'),
			activeContainerToggle = navList.find('li a[data-pulldown-container="'+ toggle.attr('data-pulldown-container') +'"]');

		// Toggle the menu/close btn icons and the
		// primary pulldown toggle link's .active class
		if (toggle.hasClass('active')) {
			toggle.addClass('close');
			activeContainerToggle.addClass('active');
		}
		else {
			toggle.removeClass('close');
			activeContainerToggle.removeClass('active');
		}

		// Show Issue, Archive nav links; hide Pegasus logo
		$('#header-navigation ul, #header-navigation .header-logo')
			.toggleClass('mobile-nav-visible');
	});
}

var handleIpad = function($) {
	// Is this the user's first visit to the site?
	var initial_visit = $.cookie('initial-visit') == null ? true : false,
		ipad          = navigator.userAgent.match(/iPad/i) == null ? false : true;

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


	/* iPad Model */
	var ipad_hide = $.cookie('ipad-hide');
	if((ipad_hide == null || !ipad_hide) && ipad && IPAD_DEPLOYED) {
		$('#ipad')
			.modal()
			.on('hidden', function() {
				$.cookie('ipad-hide', true);
			});
	}

	$.cookie('initial-visit', true);
}

var gformSublabels = function($) {
	// Move Gravity Forms Address sublabels above the fields
	$('.ginput_container label').each(function(i,e){
		var field_desc = $('<div>').append($(e).clone()).remove().html();
		$(e).siblings('.ginput_container input').before(field_desc); //moves sub label above input fields
		$(e).siblings('.ginput_container select').before(field_desc); //moves sub label above select fields (e.g. country drop-down)
		$(e).remove();
	});
}

var videoCarousels = function($) {
	// Prevent video sliders from automatically advancing
	$('#videoslides,#recipe-carousel').carousel({
		interval: 0
	})

	// Remove, then re-add video iframes on prev/next button click to prevent multiple videos from playing at a time:
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
}

var popovers = function($) {
	$('.popover-parent').popover({});
}

var SlideShow = (function() {
    var $win = $(window),
        $slidesContents = $('.ss-content');

    function _init() {
        $slidesContents.each(function(index) {
            var slidesContent = $(this);

            // Make main tag 100% height width
            if ($('article.ss-photo-essay').length > 0) {
                $('main, section.ss-content').addClass('ss-photo-essay');
                slidesContent.find('.ss-play').click({ slidesContent: slidesContent }, _playSlide);
                _resizeSlidesWrapper(slidesContent, 0);
            } else {
                $('section.ss-content').addClass('ss-embed');
                // Safari 6.0.5 needs a delay for the writing of the slideshow images
                setTimeout(function() {
                    _resizeSlidesWrapper(slidesContent, 0);
                }, 1000);
            }

            $win.resize({ slidesContent: slidesContent, slideIndex: index }, _resizeSlidesContent);
            slidesContent.find('.ss-arrow-next').click({ slidesContent: slidesContent }, _nextSlide);
            slidesContent.find('.ss-arrow-prev').click({ slidesContent: slidesContent }, _prevSlide);
            slidesContent.find('.ss-restart').click({ slidesContent: slidesContent }, _restartSlide);
        });
    }

    function _min(a, b) {
        return a < b ? a : b;
    }

    function _getCurrentSlide(slidesContent) {
        return slidesContent.find('.ss-slide.ss-current');
    }

    function _getCurrentCaption(slidesContent) {
        return slidesContent.find('.ss-caption.ss-current');
    }

    function _resizeSlidesContent(e) {
        var dynamicTimeout = 'timeout' + e.data.slideIndex.toString();
        if (typeof window[dynamicTimeout] !== 'undefined') {
            clearTimeout(window[dynamicTimeout]);
        }
        var slidesContent = e.data.slidesContent;

        window[dynamicTimeout] = setTimeout(function() {
            _resizeSlidesWrapper(slidesContent, 1000);
        }, 100);
    }

    function _resizeSlidesWrapper(slidesContent, duration) {
        _resizeSlides(slidesContent);
        _resizeCaptions(slidesContent);

        var currentSlide = _getCurrentSlide(slidesContent);
        var left = (slidesContent.width() - currentSlide.outerWidth()) / 2;

        left -= currentSlide.parent().position().left;
        slidesContent.find('.ss-slides-wrapper').animate({
            left: left
        }, {
            duration: duration,
            complete: function() {
                _updateButtonWidths(slidesContent.find('.ss-current'), slidesContent);
            }
        });
    }

    function _resizeSlides(slidesContent) {
        slidesContent.find('.ss-slide').each(function() {
            var slide = $(this),
                data = slide.data();

            if (data) {
                var ratio = _getRatio(data.width, data.height);

                var height = _min(slidesContent.find('.ss-slides-wrapper').height(), data.height);
                var width = Math.round(height * ratio);

                if (width > slidesContent.width() - 100) {
                    width = slidesContent.width() - 100;
                    height = Math.round(width / ratio);
                }

                slide.parent().css({
                    width: width
                });
                slide.css({
                    height: height
                });
            }
        });
    }

    function _getRatio(width, height) {
        return width/height;
    }

    function _resizeCaptions(slidesContent) {
        var captionWrapper = slidesContent.find('.ss-captions-wrapper'),
            captions = slidesContent.find('.ss-caption'),
            captionHeight = 50;

        if (!$('.ss-photo-essay').length) {
            captions.each(function ()  {
                var caption = $(this);

                if (captionHeight < caption.height()) {
                    captionHeight = caption.height();
                }
            });

            captionWrapper.css({
                height: captionHeight
            });
        }
    }

    function _nextSlide(e) {
        e.preventDefault();

        var slidesContent = e.data.slidesContent,
            nextDataId = slidesContent.find('.ss-arrow-next').attr('href');

        if (nextDataId) {
            var nextDataId = nextDataId.replace('#', '');
            _transitionSlide(slidesContent, nextDataId, true);
            _trackSlideShow(slidesContent, 'next');

            if (nextDataId == '2') {
                _trackSlideShow(slidesContent, 'start-next');
            }

            if (nextDataId == 'restart-slide') {
                _trackSlideShow(slidesContent, 'end-next');
            }
        }
    }

    function _prevSlide(e) {
        e.preventDefault();

        var slidesContent = e.data.slidesContent,
            prevDataId = slidesContent.find('.ss-arrow-prev').attr('href');

        if (prevDataId) {
            _transitionSlide(slidesContent, prevDataId.replace('#', ''), false);
            _trackSlideShow(slidesContent, 'previous');
        }
    }

    function _playSlide(e) {
        e.preventDefault();

        var slidesContent = e.data.slidesContent,
            nextDataId = slidesContent.find('.ss-play').attr('href');

        if (nextDataId) {
            _transitionSlide(slidesContent, nextDataId.replace('#', ''), true);
            _trackSlideShow(slidesContent, 'start-play');
        }
    }

    function _restartSlide(e) {
        e.preventDefault();

        var slidesContent = e.data.slidesContent,
            nextDataId = slidesContent.find('.ss-restart').attr('href');

        if (nextDataId) {
            _transitionSlide(slidesContent, nextDataId.replace('#', ''), true);
            _trackSlideShow(slidesContent, 'restart');
        }
    }

    function _trackSlideShow(slidesContent, action, value) {
        var isPhotoEssay = slidesContent.hasClass('ss-photo-essay'),
            category = isPhotoEssay ? 'photo-essay' : 'photo-slideshow',
            slug = slidesContent.attr('id');

            if (value) {
                _gaq.push(['_trackEvent', category, action, slug, value]);
            } else {
                _gaq.push(['_trackEvent', category, action, slug]);
            }
    }

    function _transitionSlide(slidesContent, nextDataId, isForward) {
        var currentSlide = _getCurrentSlide(slidesContent),
            nextSlide = slidesContent.find('div[data-id="' + nextDataId + '"].ss-slide'),
            nextCaption = slidesContent.find('div[data-id="' + nextDataId + '"].ss-caption'),
            restartSlide = slidesContent.find('.ss-closing-overlay');

        if (nextSlide.length) {
            if (nextDataId == 'restart-slide') {
                restartSlide.fadeIn(300, function() {
                    _updateButtons(currentSlide, slidesContent);
                });
            } else {

                // if restart slide is visible the hide it otherwise rotate slides
                if (restartSlide.is(':visible')) {
                    restartSlide.fadeOut(300, function() {
                        // only rotate slides if advancing back to the beginning
                        if (isForward) {
                            _rotateSlides(slidesContent, isForward);
                        }

                        currentSlide.removeClass('ss-current');
                        nextSlide.addClass('ss-current');
                        _resizeSlidesWrapper(slidesContent);
                        _updateButtons(nextSlide, slidesContent);
                    });
                } else {
                    _rotateSlides(slidesContent, isForward);
                    currentSlide.removeClass('ss-current');
                    nextSlide.addClass('ss-current');
                    _resizeSlidesWrapper(slidesContent);
                    _updateButtons(nextSlide, slidesContent);
                }
            }
        }

        if (nextCaption.length) {
            slidesContent.find('.ss-caption').removeClass('ss-current');
            nextCaption.addClass('ss-current');
        }
    }

    function _rotateSlides(slidesContent, isForward) {
        var slidesWrapper = slidesContent.find('.ss-slides-wrapper'),
            slides = slidesContent.find('.ss-slide-wrapper'),
            first = slides.first(),
            last = slides.last();

        if (isForward) {
            first.insertAfter(last);
        } else {
            last.insertBefore(first);
        }

        // change the slides wrapper left to handle the slide rotation
        var currentSlide = _getCurrentSlide(slidesContent);
        var left = (slidesContent.width() - currentSlide.outerWidth()) / 2;

        left -= currentSlide.parent().position().left;
        slidesContent.find('.ss-slides-wrapper').css({
            left: left
        });
    }

    function _updateButtons(currentSlide, slidesContent) {
        var prevButton = slidesContent.find('.ss-arrow-prev'),
            nextButton = slidesContent.find('.ss-arrow-next'),
            prevSlide = currentSlide.parent().prev().find('.ss-slide'),
            nextSlide = currentSlide.parent().next().find('.ss-slide'),
            restartSlide = slidesContent.find('.ss-closing-overlay');

        _updateButtonWidths(currentSlide, slidesContent, prevButton, nextButton);

        if (prevSlide.length && !currentSlide.hasClass('ss-first-slide')) {
            // Set prev href to last slide if on restart slide is visible
            if (restartSlide.is(':visible')) {
                prevButton.attr('href', '#' + currentSlide.data('id'));
            } else {
                prevButton.attr('href', '#' + prevSlide.data('id'));
            }

            prevButton.removeClass('ss-last');
        } else {
            prevButton.removeAttr('href');
            prevButton.addClass('ss-last');
        }

        if (nextSlide.length && !currentSlide.hasClass('ss-last-slide')) {
            nextButton.attr('href', '#' + nextSlide.data('id'));
            nextButton.removeClass('ss-last');
        } else {
            // Disable next button if restart slide is visible otherwise
            // set next href to restart slide
            if (restartSlide.is(':visible')) {
                nextButton.removeAttr('href');
                nextButton.addClass('ss-last');
            } else {
                nextButton.attr('href', '#' + restartSlide.find('.ss-slide').data('id'));
                nextButton.removeClass('ss-last');
            }
        }
    }

    function _updateButtonWidths(currentSlide, slidesContent, prevButton, nextButton) {
        var arrowWidth = (slidesContent.outerWidth() - currentSlide.outerWidth()) / 2;

        if (prevButton === undefined) {
            prevButton = slidesContent.find('.ss-arrow-prev');
        }

        if (nextButton === undefined) {
            nextButton = slidesContent.find('.ss-arrow-next');
        }

        prevButton.parent().width(arrowWidth);
        nextButton.parent().width(arrowWidth);
    }

    return {
        init: _init
    }
});


var lazyLoadAssets = function($) {
	$('#more-stories .story-list-grid img')
		.lazyload({ effect: 'fadeIn' });
	$('#more-stories .story-list img')
		.lazyload({
			effect: 'fadeIn',
			container: $('#more-stories .story-list')
		});
}


var socialButtonTracking = function($) {
    // Track social media button clicks, using GA's integrated
    // _trackSocial method.
    $('aside.social a').click(function() {
        var link = $(this),
            target = link.attr('data-button-target'),
            network = '',
            socialAction = '';

        if (link.hasClass('share-facebook')) {
            network = 'Facebook';
            socialAction = 'Like';
        }
        else if (link.hasClass('share-twitter')) {
            network = 'Twitter';
            socialAction = 'Tweet';
        }
        else if (link.hasClass('share-googleplus')) {
            network = 'Google+';
            socialAction = 'Share';
        }

        _gaq.push(['_trackSocial', network, socialAction, target]);
    });
}


if (typeof jQuery != 'undefined'){
	(function(){
		$(document).ready(function() {
			/* Theme Specific Code Here */
			Webcom.analytics($);
			Webcom.handleExternalLinks($);
			Webcom.loadMoreSearchResults($);

			Generic.PostTypeSearch($);

			defaultMenuSeparators($);
			removeExtraGformStyles($);
			legacySupport($);

			iosRotateAdjust($);
			addBodyClasses($);

			togglePulldown($);
			loadPulldownMenus($);
			pulldownMenuScroll($);
			mobileNavToggle($);
			handleIpad($);
			gformSublabels($);
			videoCarousels($);
			popovers($);
		    var slideshow = new SlideShow();
		    slideshow.init();
		    lazyLoadAssets($);
            socialButtonTracking($);
		});
		/*
		$(window).load(function() {
		});
		*/
	})(jQuery);
}
else {
	console.log('jQuery dependency failed to load');
}
