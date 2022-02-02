/* eslint-disable camelcase */
/* eslint-disable sort-vars */
// Define globals for JSHint validation:
/* global PostTypeSearchDataManager */

const Generic = {};

Generic.PostTypeSearch = function ($) {
  $('.post-type-search')
    // eslint-disable-next-line consistent-return
    .each((post_type_search_index, post_type_search) => {
      post_type_search = $(post_type_search);

      const form                    = post_type_search.find('.post-type-search-form'),
        field                       = form.find('input[type="text"]'),
        results                     = post_type_search.find('.post-type-search-results'),
        by_term                     = post_type_search.find('.post-type-search-term'),
        by_alpha                    = post_type_search.find('.post-type-search-alpha'),
        sorting                     = post_type_search.find('.post-type-search-sorting'),
        sorting_by_term             = sorting.find('button:eq(0)'),
        sorting_by_alpha            = sorting.find('button:eq(1)'),
        typing_delay                = 300, // milliseconds
        MINIMUM_SEARCH_MATCH_LENGTH = 2;

      let post_type_search_data  = null,
        typing_timer             = null,
        search_data_set          = null,
        column_count             = null,
        column_width             = null,
        prev_post_id_sum         = null; // Sum of result post IDs. Used to cache results

      // Get the post data for this search
      post_type_search_data = PostTypeSearchDataManager.searches[post_type_search_index];
      if (typeof post_type_search_data === 'undefined') { // Search data missing
        return false;
      }

      search_data_set = post_type_search_data.data;
      column_count    = post_type_search_data.column_count;
      column_width    = post_type_search_data.column_width;

      if (column_count === 0 || column_width === '') { // Invalid dimensions
        return false;
      }

      // Sorting toggle
      sorting_by_term.click(() => {
        by_alpha.fadeOut('fast', () => {
          by_term.fadeIn();
          sorting_by_alpha.removeClass('active');
          sorting_by_term.addClass('active');
        });
      });
      sorting_by_alpha.click(() => {
        by_term.fadeOut('fast', () => {
          by_alpha.fadeIn();
          sorting_by_term.removeClass('active');
          sorting_by_alpha.addClass('active');
        });
      });

      // Search form
      form
        .submit((event) => {
          // Don't allow the form to be submitted
          event.preventDefault();
          perform_search(field.val());
        });
      field
        .keyup(() => {
          // Use a timer to determine when the user is done typing
          if (typing_timer !== null) {
            clearTimeout(typing_timer);
          }
          typing_timer = setTimeout(() => {
            form.trigger('submit');
          }, typing_delay);
        });

      function display_search_message(message) {
        results.empty();
        results.append($(`<p class="post-type-search-message"><big>${message}</big></p>`));
        results.show();
      }

      function perform_search(search_term) {
        const matches         = [],
          elements            = [],
          columns             = [];

        let post_id_sum         = 0,
          elements_per_column   = null;

        if (search_term.length < MINIMUM_SEARCH_MATCH_LENGTH) {
          results.empty();
          results.hide();
          return;
        }
        // Find the search matches
        $.each(search_data_set, (post_id, search_data) => {
          $.each(search_data, (_, term) => {
            if (term.toLowerCase().indexOf(search_term.toLowerCase()) !== -1) {
              matches.push(post_id);
              return false;
            }
            return true;
          });
        });
        if (matches.length === 0) {
          display_search_message('No results were found.');
        } else {

          // Copy the associated elements
          $.each(matches, (match_index, post_id) => {

            const element     = by_term.find(`li[data-post-id="${post_id}"]:eq(0)`),
              post_id_int = parseInt(post_id, 10);
            post_id_sum += post_id_int;
            if (element.length === 1) {
              elements.push(element.clone());
            }
          });

          if (elements.length === 0) {
            display_search_message('No results were found.');
          } else if (post_id_sum !== prev_post_id_sum) {
            // Are the results the same as last time?
            results.empty();
            prev_post_id_sum = post_id_sum;


            // Slice the elements into their respective columns
            elements_per_column = Math.ceil(elements.length / column_count);
            for (let i = 0; i < column_count; i++) {
              const start = i * elements_per_column,
                end   = start + elements_per_column;
              if (elements.length > start) {
                columns[i] = elements.slice(start, end);
              }
            }

            // Setup results HTML
            results.append($('<div class="row"></div>'));
            $.each(columns, (column_index, column_elements) => {
              const column_wrap = $(`<div class="${column_width}"><ul></ul></div>`),
                column_list = column_wrap.find('ul');

              $.each(column_elements, (element_index, element) => {
                column_list.append($(element));
              });
              results.find('div[class="row"]').append(column_wrap);
            });
            results.show();
          }
        }
      }
    });
};

Generic.defaultMenuSeparators = function ($) {
  // Because IE sucks, we're removing the last stray separator
  // on default navigation menus for browsers that don't
  // support the :last-child CSS property
  $('.menu.horizontal li:last-child').addClass('last');
};

Generic.removeExtraGformStyles = function ($) {
  // Since we're re-registering the Gravity Form stylesheet
  // manually and we can't dequeue the stylesheet GF adds
  // by default, we're removing the reference to the script if
  // it exists on the page (if CSS hasn't been turned off in GF settings.)
  $('link#gforms_css-css').remove();
};

Generic.addBodyClasses = function ($) {
  // Assign browser-specific body classes on page load
  let bodyClass = '';
  // Old IE:
  if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) { // test for MSIE x.x;
    const ieversion = Number(RegExp.$1); // capture x.x portion and store as a number

    if (ieversion >= 10) {
      bodyClass = 'ie ie10';
    } else if (ieversion >= 9) {
      bodyClass = 'ie ie9';
    } else if (ieversion >= 8) {
      bodyClass = 'ie ie8';
    } else if (ieversion >= 7) {
      bodyClass = 'ie ie7';
    }
  } else if (navigator.appName === 'Netscape' && Boolean(navigator.userAgent.match(/Trident\/7.0/))) {
    // IE11+:
    bodyClass = 'ie ie11';
  } else if (navigator.userAgent.match(/iPhone/i)) {
  // iOS:
    bodyClass = 'iphone';
  } else if (navigator.userAgent.match(/iPad/i)) {
    bodyClass = 'ipad';
  } else if (navigator.userAgent.match(/iPod/i)) {
    bodyClass = 'ipod';
  } else if (navigator.userAgent.match(/Android/i)) {
  // Android:
    bodyClass = 'android';
  }

  $('body').addClass(bodyClass);
};


if (typeof jQuery !== 'undefined') {
  (function () {
    $(() => {
      Generic.PostTypeSearch($);
      Generic.defaultMenuSeparators($);
      Generic.removeExtraGformStyles($);
      Generic.addBodyClasses($);
    });
  }(jQuery));
} else {
  console.log('jQuery dependency failed to load');
}
