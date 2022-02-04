/* eslint-disable sort-vars */
//
// Import vendor assets
//

// =require ucf-athena-framework/dist/js/framework.min.js


//
// Import our assets
//

// =require ./webcom-base.js
// =require ./generic-base.js


// Define globals for JSHint validation:
/* global, IPAD_DEPLOYED, _gaq, Chart */


const togglePulldown = function ($) {
  // Unset tabbability on links inside a hidden pulldown.
  $('#pulldown a').attr('tabindex', '-1');

  $('.pulldown-toggle').on('click', function (e) {
    e.preventDefault();

    const toggle = $(this),
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
    } else if (!$('#nav-mobile a').hasClass('active')) {
      // If the activated pulldown is not active, activate it and its toggle.
      // Else, deactivate it.
      // When mobile navigation is active, disable this functionality.
      pulldownWrap.toggleClass('active');
      pulldownContainer.toggleClass('active');

      if (pulldownContainer.hasClass('active')) {
        pulldownContainer.find('a').attr('tabindex', '0');
      } else {
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
      const newHeight = pulldownContainer.height() - 20; // subtract 20 to hide scrollbars
      pulldownWrap.css('height', newHeight);
      pulldownContainer.find('.controls').css('height', newHeight);
    } else {
      pulldownWrap.css('height', 0);
    }
  });
};

const loadPulldownMenus = function ($) {
  $('.pulldown-toggle').each(function () {
    const toggle = $(this),
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

const pulldownMenuScroll = function ($) {
  // Handle left/right nav arrow btn click in story list controls
  $('.story-list + .controls a').on('click', function (e) {
    e.preventDefault();

    const controlBtn = $(this),
      parentContainer = controlBtn.parents('.controls').parent(),
      itemList = parentContainer.find('.story-list'),
      controlWrap = parentContainer.find('.controls');

    // x-overflowing div width only calculates apparent window width.
    // Need to calculate the combined widths of all child items
    // to get the value that we need.
    let itemListWidth = controlWrap.outerWidth();
    itemList.children('article').each(function () {
      itemListWidth += $(this).outerWidth();
      itemListWidth += parseInt($(this).css('margin-left'), 10);
    });

    let newScrollVal = 0;
    const curScrollVal = itemList.scrollLeft();

    // Get the number of pixels to scroll the itemList
    if (controlBtn.hasClass('forward')) {
      newScrollVal = curScrollVal + parentContainer.width();
      newScrollVal = newScrollVal > itemListWidth - parentContainer.width() ? controlWrap.outerWidth() + itemListWidth - parentContainer.width() : newScrollVal;
    } else if (controlBtn.hasClass('backward')) {
      newScrollVal = curScrollVal - parentContainer.width();
      newScrollVal = newScrollVal < 0 ? 0 : newScrollVal;
    }

    // Animate scrolling
    if (curScrollVal !== newScrollVal) {
      itemList.animate({
        scrollLeft: newScrollVal
      }, 400);
    }
  });
};

const mobileNavToggle = function ($) {
  // Handle window resizing with mobile navigation active.
  // Unset any active pulldown containers, toggles and logo/nav mods.
  $(window).on('resize', function () {
    if (

      $(this).width() > 767 &&
        $('#header-navigation ul, #header-navigation .header-logo').hasClass('mobile-nav-visible')
       ||

        $(this).width() < 768 &&
        !$('#header-navigation ul, #header-navigation .header-logo').hasClass('mobile-nav-visible')

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
  $('#nav-mobile a').on('click', function (e) {
    e.preventDefault();

    const toggle = $(this),
      navList = toggle.parents('ul'),
      activeContainerToggle = navList.find(`li a[data-pulldown-container="${toggle.attr('data-pulldown-container')}"]`);

    // Toggle the menu/close btn icons and the
    // primary pulldown toggle link's .active class
    if (toggle.hasClass('active')) {
      toggle.addClass('close');
      activeContainerToggle.addClass('active');
    } else {
      toggle.removeClass('close');
      activeContainerToggle.removeClass('active');
    }

    // Show Issue, Archive nav links; hide Pegasus logo
    $('#header-navigation ul, #header-navigation .header-logo')
      .toggleClass('mobile-nav-visible');
  });
};

const handleIpad = function ($) {
  // Is this the user's first visit to the site?
  const ipad  = navigator.userAgent.match(/iPad/i) !== null,
    ipadHide = $.cookie('ipad-hide');

  // eslint-disable-next-line no-undef
  if ((ipadHide === null || !ipadHide) && ipad && IPAD_DEPLOYED) {
    $('#ipad')
      .modal()
      .on('hidden', () => {
        $.cookie('ipad-hide', true);
      });
  }
};


const lazyLoadAssets = function ($) {
  $('#more-stories .story-list-grid img')
    .lazyload({
      effect: 'fadeIn'
    });
  $('#more-stories .story-list img')
    .lazyload({
      effect: 'fadeIn',
      container: $('#more-stories .story-list')
    });
};


const socialButtonTracking = function ($) {
  // Track social media button clicks, using GA's integrated
  // _trackSocial method.
  $('.ucf-social-links .btn').on('click', function () {
    const link = $(this);
    const target = window.location;

    let network = '';
    let socialAction = '';

    if (link.hasClass('btn-facebook')) {
      network = 'Facebook';
      socialAction = 'Like';
    } else if (link.hasClass('btn-twitter')) {
      network = 'Twitter';
      socialAction = 'Tweet';
    }

    // eslint-disable-next-line no-undef
    _gaq.push(['_trackSocial', network, socialAction, target]);
  });
};


const removeEmptyPTags = function ($) {
  $('p:empty').remove();
};


/**
 * Google Analytics click event tracking.
 * Do not apply the .ga-event-link class to non-link ('<a></a>') tags!
 * @param {jQuery} $ The query object
 * @returns {void}
 *
 * interaction: Default 'event'. Used to distinguish unique interactions, i.e. social interactions
 * category:    Typically the object that was interacted with (e.g. button); for social interactions, this is the 'socialNetwork' value
 * action:      The type of interaction (e.g. click) or 'like' for social ('socialAction' value)
 * label:       Useful for categorizing events (e.g. nav buttons); for social, this is the 'socialTarget' value
 **/
const gaEventTracking = function ($) {
  $('.ga-event-link').on('click', function (e) {
    e.preventDefault();

    const $link       = $(this);
    const url         = $link.attr('href');
    const interaction = $link.attr('data-ga-interaction') ? $link.attr('data-ga-interaction') : 'event';
    const category    = $link.attr('data-ga-category') ? $link.attr('data-ga-category') : 'Outbound Links';
    const action      = $link.attr('data-ga-action') ? $link.attr('data-ga-action') : 'click';
    const label       = $link.attr('data-ga-label') ? $link.attr('data-ga-label') : $link.text();
    const target      = $link.attr('target');

    if (typeof ga !== 'undefined' && action !== null && label !== null) {
      // eslint-disable-next-line no-undef
      _gaq.push([interaction, category, action, label]);
      if (typeof target !== 'undefined' && target === '_blank') {
        window.open(url, '_blank');
      } else {
        window.setTimeout(() =>  {
          document.location = url;
        }, 200);
      }
    } else {
      document.location = url;
    }
  });
};


/**
 * Similar to removeEmptyPTags, but searches for and removes Bootstrap containers
 * whose only child column is empty.
 * @param {jQuery} $ The jQuery object
 * @returns {void}
 **/
const removeEmptyPageContainers = function ($) {
  const $subpage = $('.subpage');
  if ($subpage.length) {
    $subpage
      .find('div[class^="col-"]:only-child')
      .each(function () {
        const $col = $(this);
        if ($.trim($col.html()) === '') {
          $col.parents('.container').remove();
        }
      });
  }
};


/**
 * Enable the thumbnail nav and jump to top affixing for photo essays.
 * @param {jQuery} $ The jQuery object
 * @returns {void}
 **/
const photoEssayNav = function ($) {
  const $top = $('#photo-essay-top'),
    $bottom = $('#photo-essay-bottom'),
    $navbar = $('#photo-essay-navbar'),
    $jumpTop = $('#photo-essay-jump-top');

  let topOffset = 0,
    // eslint-disable-next-line no-unused-vars
    bottomOffset = 0,
    bottomOffsetInverse = 0,
    prevScrollPos = 0;

  function setOffsets() {
    topOffset = $top.offset().top + $top.outerHeight(true);
    bottomOffset = $bottom.offset().top - $(window).outerHeight();
    bottomOffsetInverse = $(window).outerHeight() - $bottom.offset().top + 100;
  }

  function toggleNavbar() {
    const newOffset = {
      top: topOffset,
      bottom: bottomOffsetInverse
    };

    // If affixing is already applied, just replace the offset value.
    //
    // Else, initialize affixing.
    if ($navbar.is('.affix, .affix-top, .affix-bottom')) {
      $navbar.data('bs.affix').options.offset = newOffset;
    } else {
      $navbar
        .affix({
          offset: newOffset
        })
        .on('affix-top.bs.affix', () => {
          // Unset position top overrides
          $navbar.css('top', '');
        });
    }
  }

  function handleResize() {
    setOffsets();
    toggleNavbar();
  }

  function scrollNavbar() {
    if ($navbar.is('.affix')) {
      const scrollPos = $(document).scrollTop(),
        navbarTop = parseFloat($navbar.css('top'));

      let newNavbarScrollPos,
        scrollAmount;

      if (scrollPos > prevScrollPos) {
        // scroll down
        scrollAmount = scrollPos - prevScrollPos;

        // If scrolling down, $navbar's 'top' value should increase by scrollAmount
        // up to the point that the bottom of $navbar is scrolled to (but no further).
        newNavbarScrollPos = Math.max(navbarTop - scrollAmount, ($navbar.outerHeight(true) - $(window).height()) * -1);
        $navbar.css('top', newNavbarScrollPos);
      } else {
        // scroll up
        scrollAmount = prevScrollPos - scrollPos;

        // If scrolling up, $navbar's 'top' value should decrease by scrollAmount, but
        // reach no less than 0.
        newNavbarScrollPos = Math.min(navbarTop + scrollAmount, 0);
        $navbar.css('top', newNavbarScrollPos);
      }
    }
  }

  function handleScroll() {
    scrollNavbar();
    prevScrollPos = $(document).scrollTop();
  }

  function handleNavClick(e) {
    e.preventDefault();

    const $target = $(this.getAttribute('href'));
    if ($target.length) {
      $('html, body').animate({
        scrollTop: $target.offset().top
      }, 500);
    }

    return false;
  }

  function handleJumpTopClick(e) {
    e.preventDefault();

    $('html, body').animate({
      scrollTop: 0
    }, 300);
  }

  if ($top.length && $bottom.length) {
    setOffsets();
    prevScrollPos = $(document).scrollTop();

    $(window)
      .on({
        'load scroll': handleScroll,
        'load resize': handleResize
      });

    $navbar.on('click', '.photo-essay-nav-link', handleNavClick);
    $jumpTop.on('click', handleJumpTopClick);
  }
};

const twitterWidget = function () {
  window.twttr = (function (d, s, id) {
    let js = d.getElementsByTagName(s)[0];
    const fjs = d.getElementsByTagName(s)[0];
    const t = window.twttr || {};

    if (d.getElementById(id)) {
      return t;
    }
    js = d.createElement(s);
    js.id = id;
    js.src = 'https://platform.twitter.com/widgets.js';
    fjs.parentNode.insertBefore(js, fjs);

    t._e = [];
    t.ready = function (f) {
      t._e.push(f);
    };

    return t;
  }(document, 'script', 'twitter-wjs'));
};

if (typeof jQuery !== 'undefined') {
  (function () {
    $(() => {
      togglePulldown($);
      loadPulldownMenus($);
      pulldownMenuScroll($);
      mobileNavToggle($);
      handleIpad($);
      lazyLoadAssets($);
      socialButtonTracking($);
      removeEmptyPTags($);
      gaEventTracking($);
      removeEmptyPageContainers($);
      photoEssayNav($);
      if ($('.front-page').length) {
        twitterWidget();
      }
    });
  }(jQuery));
} else {
  console.log('jQuery dependency failed to load');
}
