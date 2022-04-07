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
/* global, _gaq, Chart */


const pulldownInit = function ($) {
  const $header = $('#header-navigation');
  const $pulldown = $header.children('.header-pulldown');
  const $storyList = $header.find('.story-list');
  const $pulldownToggle = $header.find('.nav-pulldown-toggle');
  const $closeBtn = $header.find('#pulldown-close');

  // Define lazyload behavior:
  $pulldown
    .find('img.lazy')
    .lazyload({
      effect: 'fadeIn',
      container: $storyList,
      event: 'triggerLazy'
    })
    .end();

  // Trigger lazyload the first time the pulldown is expanded:
  $pulldown.one('show.bs.collapse', () => {
    $pulldown
      .find('img.lazy')
      .trigger('triggerLazy');
  });

  // Handle keyboard behavior on pulldown toggles when the pulldown
  // is expanded:
  $pulldownToggle.on('keydown', function (e) {
    if (e.key === 'Tab' && !e.shiftKey && $(this).attr('aria-expanded') === 'true') {
      e.preventDefault();

      // Prioritize focusing on the close button in
      // the pulldown if it's visible.  Otherwise,
      // fall back to focusing on the pulldown itself.
      if ($closeBtn.is(':visible')) {
        $closeBtn.focus();
      } else {
        $pulldown.focus();
      }
    }
  });

  $storyList.find('.story-callout-link:last-child').on('keydown', (e) => {
    if (e.key === 'Tab' && !e.shiftKey) {
      e.preventDefault();

      // Focus back on the current visible pulldown toggle
      // once all stories in the story list have been tabbed
      // through.  (Assumes only one pulldown toggle is
      // visible at any given time.)
      // This isn't great, since it puts keyboard users in
      // a loop, but we don't always have a subsequent
      // navbar item to focus on next.
      $pulldownToggle.filter(':visible').first().focus();
    }
  });

  // Handle keyboard behavior on the pulldown close button:
  $closeBtn.on('keydown', (e) => {
    if (e.key === 'Tab' && e.shiftKey) {
      e.preventDefault();

      // Focus back on the current visible pulldown toggle
      // when the user reverse tabs from the close button.
      // (Assumes only one pulldown toggle is visible at
      // any given time.)
      $pulldownToggle.filter(':visible').first().focus();
    }
  });

  // Focus back on the current visible pulldown toggle
  // once the pulldown is closed.  (Assumes only one
  // pulldown toggle is visible at any given time.)
  $pulldown.on('hide.bs.collapse', () => {
    $pulldownToggle.filter(':visible').first().focus();
  });
};


const pulldownMenuScroll = function ($) {
  // Handle left/right nav arrow btn click in story list controls
  $('.story-list-control-forward, .story-list-control-backward').on('click', function (e) {
    e.preventDefault();

    const controlBtn = $(this);
    const controlWrap = controlBtn.parents('.story-list-controls');
    const parentContainer = controlWrap.parent();
    const itemList = parentContainer.find('.story-list');

    // x-overflowing div width only calculates apparent window width.
    // Need to calculate the combined widths of all child items
    // to get the value that we need.
    let itemListWidth = controlWrap.outerWidth();
    itemList.children('.story-callout').each(function () {
      itemListWidth += $(this).outerWidth();
    });

    let newScrollVal = 0;
    const curScrollVal = itemList.scrollLeft();

    // Get the number of pixels to scroll the itemList
    if (controlBtn.hasClass('story-list-control-forward')) {
      newScrollVal = curScrollVal + parentContainer.width();
      newScrollVal = newScrollVal > itemListWidth - parentContainer.width() ? controlWrap.outerWidth() + itemListWidth - parentContainer.width() : newScrollVal;
    } else if (controlBtn.hasClass('story-list-control-backward')) {
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
      pulldownInit($);
      pulldownMenuScroll($);
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
