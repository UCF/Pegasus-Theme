// @codekit-prepend "../../../../static/components/bootstrap-sass-3.3.4/assets/javascripts/bootstrap.js";
// @codekit-prepend "webcom-base.js";
// @codekit-prepend "generic-base.js";


// Define globals for JSHint validation:
/* global console, IPAD_DEPLOYED, _gaq, Chart */


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
};

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
};

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
    if (curScrollVal !== newScrollVal) {
      itemList.animate({
        scrollLeft: newScrollVal
      }, 400);
    }
  });
};

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
};

var handleIpad = function($) {
  // Is this the user's first visit to the site?
  var ipad  = navigator.userAgent.match(/iPad/i) === null ? false : true,
      ipad_hide = $.cookie('ipad-hide');

  if ((ipad_hide === null || !ipad_hide) && ipad && IPAD_DEPLOYED) {
    $('#ipad')
      .modal()
      .on('hidden', function() {
        $.cookie('ipad-hide', true);
      });
  }
};

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
            nextDataId = nextDataId.replace('#', '');
            _transitionSlide(slidesContent, nextDataId, true);
            _trackSlideShow(slidesContent, 'next');

            if (nextDataId === '2') {
                _trackSlideShow(slidesContent, 'start-next');
            }

            if (nextDataId === 'restart-slide') {
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
            if (nextDataId === 'restart-slide') {
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
        var slides = slidesContent.find('.ss-slide-wrapper'),
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
    };
});


var lazyLoadAssets = function($) {
  $('#more-stories .story-list-grid img')
    .lazyload({ effect: 'fadeIn' });
  $('#more-stories .story-list img')
    .lazyload({
      effect: 'fadeIn',
      container: $('#more-stories .story-list')
    });
};


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
};


var removeEmptyPTags = function($) {
  $('p:empty').remove();
};


/**
 * Google Analytics click event tracking.
 * Do not apply the .ga-event-link class to non-link ('<a></a>') tags!
 *
 * interaction: Default 'event'. Used to distinguish unique interactions, i.e. social interactions
 * category:    Typically the object that was interacted with (e.g. button); for social interactions, this is the 'socialNetwork' value
 * action:      The type of interaction (e.g. click) or 'like' for social ('socialAction' value)
 * label:       Useful for categorizing events (e.g. nav buttons); for social, this is the 'socialTarget' value
 **/
var gaEventTracking = function($) {
  $('.ga-event-link').on('click', function(e) {
    e.preventDefault();

    var $link       = $(this);
    var url         = $link.attr('href');
    var interaction = $link.attr('data-ga-interaction') ? $link.attr('data-ga-interaction') : 'event';
    var category    = $link.attr('data-ga-category') ? $link.attr('data-ga-category') : 'Outbound Links';
    var action      = $link.attr('data-ga-action') ? $link.attr('data-ga-action') : 'click';
    var label       = $link.attr('data-ga-label') ? $link.attr('data-ga-label') : $link.text();
    var target      = $link.attr('target');

    if (typeof ga !== 'undefined' && action !== null && label !== null) {
      _gaq.push([interaction, category, action, label]);
      if (typeof target !== 'undefined' && target === '_blank') {
        window.open(url, '_blank');
      }
      else {
        window.setTimeout(function(){ document.location = url; }, 200);
      }
    }
    else {
      document.location = url;
    }
  });
};


/**
 * Similar to removeEmptyPTags, but searches for and removes Bootstrap containers
 * whose only child column is empty.
 **/
var removeEmptyPageContainers = function($) {
  var $subpage = $('.subpage');
  if ($subpage.length) {
    $subpage
      .find('div[class^="col-"]:only-child')
        .each(function() {
          var $col = $(this);
          if ($.trim($col.html()) === '') {
            $col.parents('.container').remove();
          }
        });
  }
};


/**
 * ChartJS
 **/

// jshint unused:false
var customChart = function ($) {
  if (!$('body').hasClass('ie8') && $('.custom-chart').length) {
    $.each($('.custom-chart'), function() {
      var $chart = $(this),
          type = $chart.attr('data-chart-type'),
          jsonPath = $chart.attr('data-chart-data'),
          optionsPath = $chart.attr('data-chart-options'),
          canvas = document.createElement('canvas'),
          ctx = canvas.getContext('2d'),
          data = {};

      // Set default options
      var options = {
        responsive: true,
        scaleShowGridLines: false,
        pointHitDetectionRadius: 5
      };

      $chart.append(canvas);

      $.getJSON(jsonPath, function(json) {
        data = json;
        $.getJSON(optionsPath, function(json) {
          $.extend(options, options, json);
        }).complete(function() {
          switch(type.toLowerCase()) {
            case 'bar':
              var barChart = new Chart(ctx).Bar(data, options);
              break;
            case 'line':
              var lineChart = new Chart(ctx).Line(data, options);
              break;
            case 'radar':
              var radarChart = new Chart(ctx).Radar(data, options);
              break;
            case 'polar-area':
              var polarAreaChart = new Chart(ctx).PolarArea(data, options);
              break;
            case 'pie':
              var pieChart = new Chart(ctx).Pie(data, options);
              break;
            case 'doughnut':
              var doughnutChart = new Chart(ctx).Doughnut(data, options);
              break;
            default:
              break;
          }
        });
      });
    });
  }
};
// jshint unused:true


/**
 * Enable affixing on callouts with .callout-affix class.
 **/
var affixedCallouts = function($) {
  var $callouts = $('.callout-affix');

  function getBottomOffset(i) {
    // If this is the last affixable callout on the page, set the bottom
    // offset to the bottom of the article.  Else, set it to the top offset
    // of the next affixable callout.
    if (i !== $callouts.length - 1) {
      return $('body').outerHeight() - $($callouts[i+1]).parent('.callout-outer').offset().top + 30;
    }
    else {
      var $moreStories = $('#more-stories');
      if ($moreStories.length) {
        // Stories
        return $('body').outerHeight() - $moreStories.offset().top + 30;
      }
      else {
        // Pages
        return $('body').outerHeight() - $('#footer-social').offset().top + 30;
      }
    }
  }

  function doAffix() {
    for (var i = 0; i < $callouts.length; i++) {
      var $callout = $($callouts[i]),
          $calloutPlaceholder = $callout.parent('.callout-outer'),
          calloutHeight = $callout.outerHeight();

      // Set the callout's placeholder height.
      $calloutPlaceholder.css('height', calloutHeight);

      // Only initialize affixing on callouts that don't consume an excessive amount
      // of vertical screen real estate.
      if (
        (calloutHeight < ($(window).outerHeight() / 2) && $(window).width() > 767) ||
        (calloutHeight < ($(window).outerHeight() * 0.3) && $(window).width() <= 767)
      ) {

        var newOffset = {
          top: $callout.offset().top,
          bottom: getBottomOffset(i)
        };

        if ($callout.hasClass('affix-cancel')) {
          $callout.removeClass('affix-cancel');
        }

        // If affixing is already applied, just replace the offset value.
        // Else, initialize affixing
        if ($callout.is('.affix, .affix-top, .affix-bottom')) {
          $callout.data('bs.affix').options.offset = newOffset;
        }
        else {
          $callout.affix({
            offset: newOffset
          });
        }

      }
      else {
        // Use CSS to disable the affix effect.  Attempting to destroy the
        // affix event is more trouble than it's worth.
        $callout.addClass('affix-cancel');
      }

    }
  }

  if ($callouts.length) {
    // Ugly, but need to give slideshows enough time to finish rendering/resizing
    window.setTimeout(doAffix, 1500);
    $(window).on('resize', doAffix);
  }
};


if (typeof jQuery !== 'undefined'){
  (function(){
    $(document).ready(function() {
      togglePulldown($);
      loadPulldownMenus($);
      pulldownMenuScroll($);
      mobileNavToggle($);
      handleIpad($);
      var slideshow = new SlideShow();
      slideshow.init();
      lazyLoadAssets($);
      socialButtonTracking($);
      removeEmptyPTags($);
      gaEventTracking($);
      removeEmptyPageContainers($);
      customChart($);
      affixedCallouts($);
    });
  })(jQuery);
}
else {
  console.log('jQuery dependency failed to load');
}
