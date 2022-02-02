/* eslint-disable sort-vars */
// Define globals for JSHint validation:
/* global send_to_editor, tinymce, wysihtml5, wp, THEME_CSS_URL, USE_SC_INTERFACE */


// Adds filter method to array objects
// https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/Array/filter


const WebcomAdmin = {};


WebcomAdmin.__init__ = function ($) {
  // Allows forms with input fields of type file to upload files
  $('input[type="file"]').parents('form').attr('enctype', 'multipart/form-data');
  $('input[type="file"]').parents('form').attr('encoding', 'multipart/form-data');

  // Initialize all the color selectors
  const colorInput = $('.shortcode-color,#issue_default_color,#story_default_color,#story_default_header_img_background_color');
  if (colorInput.length) {
    colorInput.iris();
  }
};


WebcomAdmin.shortcodeInterfaceTool = function ($) {
  this.shortcodeForm = $('#select-shortcode-form');
  this.shortcodeButton = this.shortcodeForm.find('button');
  this.shortcodeSelect = this.shortcodeForm.find('#shortcode-select');
  this.shortcodeEditors = this.shortcodeForm.find('#shortcode-editors');
  this.shortcodeDescriptions = this.shortcodeForm.find('#shortcode-descriptions');

  this.shortcodeInsert = (shortcode, parameters, enclosingText) => {
    let text = `[${shortcode}`;

    if (parameters) {
      // eslint-disable-next-line guard-for-in
      for (const key in parameters) {
        text += ` ${key}="${parameters[key]}"`;
      }
    }

    text +=  ']';
    if (enclosingText) {
      text += enclosingText;
    }
    text += `[/${shortcode}]`;

    send_to_editor(text);
  };

  this.shortcodeAction = () => {
    const selected = this.shortcodeSelect.find(':selected');
    if (selected.length < 1 || selected.val() === '') {
      return;
    }

    const editor = this.shortcodeEditors.find(`li.shortcode-${this.shortcodeSelected}`);
    const dummyText = selected.attr('data-enclosing') || null;
    const highlightedWysiwygText = tinymce.activeEditor ? tinymce.activeEditor.selection.getContent() : null;
    let enclosingText = null;

    if (dummyText && highlightedWysiwygText) {
      enclosingText = highlightedWysiwygText;
    } else {
      enclosingText = dummyText;
    }

    const parameters = {};
    if (editor.length === 1) {
      editor.children().each(function () {
        const formElement = $(this);
        switch (formElement.prop('tagName')) {
          case 'INPUT':
          case 'TEXTAREA':
          case 'SELECT':
            parameters[formElement.attr('data-parameter')] = formElement.val();
            break;
          default:
            break;
        }
      });
    }

    this.shortcodeInsert(selected.val(), parameters, enclosingText);
  };

  this.shortcodeSelectAction = () => {
    this.shortcodeSelected = this.shortcodeSelect.val();

    this.shortcodeEditors.find('li').hide();
    this.shortcodeDescriptions.find('li').hide();
    this.shortcodeEditors.find(`.shortcode-${this.shortcodeSelected}`).show();
    this.shortcodeDescriptions.find(`.shortcode-${this.shortcodeSelected}`).show();
  };

  this.shortcodeSelectAction();

  // Option change for select, cause action
  this.shortcodeSelect.change(this.shortcodeSelectAction);

  // Button to insert shortcode
  this.shortcodeButton.click(this.shortcodeAction);
};


WebcomAdmin.themeOptions = function ($) {
  this.active   = null;
  this.parent   = $('.i-am-a-fancy-admin');
  this.sections = $('.i-am-a-fancy-admin .fields .section');
  this.buttons  = $('.i-am-a-fancy-admin .sections .section a');
  this.buttonWrap = $('.i-am-a-fancy-admin .sections');
  this.sectionLinks = $('.i-am-a-fancy-admin .fields .section a[href^="#"]');

  this.showSection = (e) => {
    const button  = $(e.target);
    const href    = button.attr('href');
    const section = $(href);

    if (this.buttonWrap.find(`.section a[href="${href}"]`) && section.is(this.sections)) {
      // Switch active styles
      this.buttons.removeClass('active');
      button.addClass('active');

      this.active.hide();
      this.active = section;
      this.active.show();

      history.pushState({}, '', button.attr('href'));
      const httpReferrer = this.parent.find('input[name="_wp_http_referer"]');
      httpReferrer.val(window.location);
    }

    return false;
  };

  this.__init__ = () => {
    this.active = this.sections.first();
    this.sections.not(this.active).hide();
    this.buttons.first().addClass('active');
    this.buttons.click(this.showSection);
    this.sectionLinks.click(this.showSection);

    if (window.location.hash) {
      this.buttons.filter(`[href="${window.location.hash}"]`).click();
    }

    const fadeTimer = setInterval(() =>  {
      $('.updated').fadeOut(1000);
      clearInterval(fadeTimer);
    }, 2000);
  };

  if (this.parent.length > 0) {
    this.__init__();
  }
};


WebcomAdmin.wysiwygFields = function ($) {
  const $toolbars = $('.wysihtml5-editor');

  // White list of tags to allow
  const wysihtml5ParserRules = {
    tags: {
      br:     {},
      strong: {},
      b:      {},
      i:      {},
      em:     {},
      u:      {},
      a:      {
        // eslint-disable-next-line camelcase
        set_attributes: {
          target: '_blank'
        },
        // eslint-disable-next-line camelcase
        check_attributes: {
          href:   'url' // important to avoid XSS
        }
      }
    }
  };

  $toolbars.each(function () {
    const $toolbar = $(this),
      toolbarID = $toolbar.attr('id');
    // Theme Option field IDs contain brackets, which are invalid markup;
    // getting the textarea via vanilla js and passing the whole node to
    // wysihtml5 is easier than trying to pass its ID:
    const textarea = document.getElementById($toolbar.attr('data-textarea-id'));

    // Initialize the wysihtml5 editor
    if ($(textarea).length > 0) {
      // eslint-disable-next-line no-new
      new wysihtml5.Editor(textarea, { // id of textarea element or DOM node
        toolbar: toolbarID, // id of toolbar element
        parserRules: wysihtml5ParserRules // defined in parser rules set
      });
    }
  });
};


WebcomAdmin.sliderMetaBoxes = function ($) {
  // Slider Meta Box Updates:
  // (only run this code if we're on a screen with #slider-slides-settings-basic;
  // i.e. if we're on a slider edit screen:
  if ($('body.post-type-photo_essay').length > 0) {
    let frame;
    const slideCountWidget = $('#slider-slides-settings-count'),
      slideCountField = $('input#ss_slider_slidecount'),
      slideOrderField = $('input#ss_slider_slideorder'),
      keyField = 'input[name^="ss_slide_image["]'; // Related to some arbitrary unique field in each slide, from which an ID can be grabbed

    // Admin panel adjustments for Photo Essay previews
    // Autosaving Photo Essays kills serialized data (which we use for slide images/
    // video thumbnails), so autosaving is disabled for this post type.
    // This adds a helpful message for users to avoid confusion.
    if ($('#post-status-display').text().indexOf('Published') < 1) {
      $('#save-action').prepend('<p style="text-align:left;"><strong>NOTE:</strong> To preview an unpublished Photo Essay before publishing it, make sure to save any changes as a Draft, <em>then</em> click "Preview".</p>');
    }

    // Returns all jquery objects that correspond to a single duplicate-able slide.
    // Necessary for determining slide count + order.
    // This should be customized per site.
    const getAllSlides = function () {
      return $('li.custom_repeatable.postbox');
    };

    // Return all slides that have enough content to be deemed not empty
    const getValidSlides = function () {
      const slides = [];
      const allSlides = getAllSlides();

      $.each(allSlides, function () {
        const slide = $(this),
          inputID = getInputID(slide.find(keyField).attr('name')),
          inputs = slide.find(`input[name*="[${inputID}]"]`),
          textareas = slide.find(`textarea[name*="[${inputID}]"]`),
          fields = inputs.add(textareas);

        $.each(fields, function () {
          if ($(this).val() && typeof $(this).val() !== 'undefined' && $(this).val !== '' || $(this).hasClass('has-value')) {
            slides.push(slide);
          }

          return false;
        });
      });
      return slides;
    };

    // Parses a string value to extract an input ID.
    // Assumes passed value is an input name/id, structured as "fieldname[id]".
    // Returns an input ID.
    const getInputID = function (string) {
      let inputID = string.split('[')[1];
      inputID = inputID.substr(0, inputID.length - 1);

      return inputID;
    };

    // Function that updates Slide Count value:
    const updateSlideCount = function () {
      const validSlides = getValidSlides();

      if (slideCountWidget.is('hidden')) {
        slideCountWidget.show();
      }

      const slideCount = validSlides.length;
      // alert('slideCount is: '+ slideCount + '; input value is: ' + SlideCountField.attr('value'));
      slideCountField.attr('value', slideCount);

      if (slideCountWidget.is('visible')) {
        slideCountWidget.hide();
      }
    };


    // Update the Slide Sort Order:
    const updateSliderSortOrder = function () {
      const sortOrder = [];
      let orderString = '';
      const validSlides = getValidSlides();

      $.each(validSlides, function () {
        const fieldName = $(this).find('input[name*="["], textarea[name*="["]')[0].getAttribute('name');
        const inputID = getInputID(fieldName);
        sortOrder[sortOrder.length] = inputID;
      });

      if (slideCountWidget.is('hidden')) {
        slideCountWidget.show();
      }

      $.each(sortOrder, (index, value) => {
        // make sure we only have number values (i.e. only slider widgets):
        if (!isNaN(value)) {
          orderString += `${value},`;
        }
      });
      // add each value to Slide Order field value:
      slideOrderField.attr('value', orderString);

      if (slideCountWidget.is('visible')) {
        slideCountWidget.hide();
      }
    };


    // Admin onload:
    slideCountWidget.hide();
    updateSlideCount();
    updateSliderSortOrder();


    // Update slide count when a slide's content changes:
    $(getAllSlides())
      .find('input, textarea')
      .on('change', () => {
        updateSlideCount();
        updateSliderSortOrder();
      });

    // White list of tags to allow
    const wysihtml5ParserRules = {
      classes: {
        'wysiwyg-text-align-center': {}
      },
      tags: {
        br:     {},
        strong: {},
        b:      {},
        i:      {},
        em:     {},
        u:      {},
        div:    {},
        a:      {
          // eslint-disable-next-line camelcase
          set_attributes: {
            target: '_blank'
          },
          // eslint-disable-next-line camelcase
          check_attributes: {
            href:   'url' // important to avoid XSS
          }
        }
      }
    };

    $(getAllSlides()).each(function () {
      const slideContent = $(this);
      WebcomAdmin.sliderHeaderTitleAction($, slideContent);

      // Initialize the wysihtml5 editor
      // DO NOT initialize the hidden clone object as it is initialize when slide is created
      if (slideContent.find('div[id^="wysihtml5-toolbar["]').attr('id').indexOf('xxxxxx') === -1) {
        const toolbar = slideContent.find('div[id^="wysihtml5-toolbar["]');
        const textarea = slideContent.find('textarea[id^="ss_slide_caption["]');
        // eslint-disable-next-line no-new
        new wysihtml5.Editor(textarea.attr('id'), { // id of textarea element
          toolbar: toolbar.attr('id'), // id of toolbar element
          stylesheets: [`${THEME_CSS_URL}/editor.css`],
          parserRules: wysihtml5ParserRules // defined in parser rules set
        });
      }
    });

    // Sortable slides
    $('#ss_slides_all').sortable({
      handle : 'h3.hndle',
      placeholder : 'sortable-placeholder',
      sort : function () {
        $('.sortable-placeholder').height($(this).find('.ui-sortable-helper').height());
      },
      stop : function (event, ui) {
        const html5iframe = $(ui.item).find('iframe.wysihtml5-sandbox'),
          toolbar = $(ui.item).find('div[id^="wysihtml5-toolbar["]'),
          textarea = $(ui.item).find('textarea[id^="ss_slide_caption["]');

        textarea.show();
        html5iframe.remove();
        toolbar.hide();

        // eslint-disable-next-line no-new
        new wysihtml5.Editor(textarea.attr('id'), { // id of textarea element
          toolbar: toolbar.attr('id'), // id of toolbar element
          stylesheets: [`${THEME_CSS_URL}editor.css`],
          parserRules: wysihtml5ParserRules // defined in parser rules set
        });
      },
      update : function () {
        updateSliderSortOrder();
      },
      tolerance :'pointer'
    });


    // Toggle slide with header click
    $('#slider_slides').on('click', '.custom_repeatable .hndle', function () {
      $(this).siblings('.inside').toggle().end().parent()
        .toggleClass('closed');
    });


    // Create a new slide
    const createSlide = function (attachment) {
      const newSlideSibling = $('#ss_slides_all li.postbox:last-child'),
        slideCloner = $('#ss_slides_all li.cloner'),
        newSlide = slideCloner.clone(true).removeClass('cloner');

      const attachmentId = attachment.attributes.id,
        attachmentFilename = attachment.attributes.filename,
        attachmentUrl = attachment.attributes.url,
        attachmentTitle = attachment.attributes.title,
        attachmentCaption = attachment.attributes.caption;

      // Update 'name' attributes
      $('textarea, input[type="text"], input[type="select"], input[type="file"], input[type="hidden"]', newSlide).val('').attr('name', (index, name) => {
        return name.replace('xxxxxx', attachmentId);
      });
      $('input[type="checkbox"], input[type="radio"]', newSlide).attr('name', (index, name) => {
        return name.replace('xxxxxx', attachmentId);
      });
      // Update 'for' attributes (in <label>)
      $('label', newSlide).val('').attr('for', (index, forval) => {
        if (forval) {
          return forval.replace('xxxxxx', attachmentId);
        }
        return undefined;
      });
      // Update 'id' attributes
      $('textarea, input[type="text"], input[type="select"], input[type="checkbox"], input[type="radio"], div[id^="wysihtml5-toolbar"]', newSlide).attr('id', (index, idval) => {
        return idval.replace('xxxxxx', attachmentId);
      });

      // Remove other existing data from previous slide:
      $('.has-value', newSlide).removeClass('has-value');
      $('input[type="radio"]', newSlide).removeAttr('checked');

      // Update slide header title:
      $('.slide-handle-header', newSlide).text(attachmentTitle);
      WebcomAdmin.sliderHeaderTitleAction($, newSlide);

      // Update new slide values with anything made available
      // from the attachment object provided
      $('label[for^="ss_slide_image["]', newSlide)
        .parent('th')
        .next('td')
        .find('img')
        .attr('src', attachmentUrl)
        .siblings('span')
        .text(attachmentFilename);

      $('textarea[id^="ss_slide_caption"]', newSlide).attr('value', attachmentCaption);
      $('input[id^="file_img_"]', newSlide).attr('value', attachmentId);
      newSlide.insertAfter(newSlideSibling).show();

      // eslint-disable-next-line no-new
      new wysihtml5.Editor(`ss_slide_caption[${attachmentId}]`, { // id of textarea element
        toolbar:      `wysihtml5-toolbar[${attachmentId}]`, // id of toolbar element
        stylesheets:  [`${THEME_CSS_URL}editor.css`],
        parserRules:  wysihtml5ParserRules // defined in parser rules set
      });

      // Update slide count, order
      updateSlideCount();
      updateSliderSortOrder();

      return false;
    };


    // Remove a slide
    $('.repeatable-remove').on('click', function () {
      $(this).parent().remove();
      // hideOnlyRemoveBtn();
      updateSlideCount();
      updateSliderSortOrder();
      return false;
    });


    // Handle Media Library modal toggle
    $('#slide_modal_toggle').on('click', (event) =>  {
      event.preventDefault();

      if (!frame) {
        // Create uploader object
        frame = wp.media({
          title: 'Select Slide Images',
          multiple: true,
          library: {
            type: 'image'
          },
          button : {
            text : 'Create New Slides'
          }
        });
        frame.on('select', () => {
          const selection = frame.state().get('selection');
          selection.each((attachment) => {
            // console.log(attachment.attributes);
            createSlide(attachment);
          });
        });
      }

      // Open the Media Library modal
      frame.open();
    });
  }
};


WebcomAdmin.sliderHeaderTitleAction = function ($, field) {
  $('input[type="text"][id^="ss_slide_title"]', field).unbind().keyup({
    slideDiv: field
  }, function (e) {
    const titleFieldValue = $(this).val();
    const slideHeader = $('.slide-handle-header', e.data.slideDiv);
    slideHeader.text(titleFieldValue);
  });
};


WebcomAdmin.storyFieldToggle = function ($) {
  const templateField = $('#story_template');

  const toggleFields = function (val) {
    if (val === '') {
      val = 'default';
    }
    const fields = {
      defaultFields : ['story_description', 'story_default_font', 'story_default_color', 'story_default_header_img'],
      // eslint-disable-next-line camelcase
      photo_essayFields: ['story_description', 'story_default_font', 'story_default_header_img'],
      customFields : ['story_html', 'story_stylesheet', 'story_javascript', 'story_fonts']
    };
    const fieldsOnKey = `${val}Fields`;
    const fieldsOn = fields[fieldsOnKey];

    delete fields[fieldsOnKey];
    const fieldsOff = fields;

    if (fieldsOff) {
      $.each(fieldsOff, (key, array) => {
        $.each(array, (k, f) => {
          $(`label[for="${f}"]`).parents('tr').hide();
        });
      });
    }
    if (fieldsOn) {
      $.each(fieldsOn, (key, field) => {
        if ($.isArray(field)) {
          $.each(val, (k, f) => {
            $(`label[for="${f}"]`).parents('tr').fadeIn();
          });
        } else {
          $(`label[for="${field}"]`).parents('tr').fadeIn();
        }
      });
    }
  };

  // Toggle on load
  toggleFields(templateField.val());

  // Toggle fields on Story Template field change
  templateField.on('change', function () {
    toggleFields($(this).val());
  });
};


WebcomAdmin.issueFieldToggle = function ($) {
  const templateField = $('#issue_template');

  const toggleFields = function (val) {
    if (val === '') {
      val = 'default';
    }
    const fields = {
      defaultFields : ['issue_story_1', 'issue_story_2', 'issue_story_3'],
      customFields : ['issue_html', 'issue_stylesheet_home', 'issue_javascript_home']
    };
    const fieldsOnKey = `${val}Fields`;
    const fieldsOn = fields[fieldsOnKey];

    delete fields[fieldsOnKey];
    const fieldsOff = fields;

    if (fieldsOff) {
      $.each(fieldsOff, (key, array) => {
        $.each(array, (k, f) => {
          $(`label[for="${f}"]`).parents('tr').hide();
        });
      });
    }
    if (fieldsOn) {
      $.each(fieldsOn, (key, field) => {
        if ($.isArray(field)) {
          $.each(val, (k, f) => {
            $(`label[for="${f}"]`).parents('tr').fadeIn();
          });
        } else {
          $(`label[for="${field}"]`).parents('tr').fadeIn();
        }
      });
    }
  };

  // Toggle on load
  toggleFields(templateField.val());

  // Toggle fields on Story Template field change
  templateField.on('change', function () {
    toggleFields($(this).val());
  });
};


/**
 * Adds file uploader functionality to File fields.
 * Mostly copied from https://codex.wordpress.org/Javascript_Reference/wp.media
 * @param {jQuery} $ The jQuery Object
 * @returns {void}
 **/
WebcomAdmin.fileUploader = function ($) {
  $('.meta-file-wrap').each(function () {
    let frame;
    const $container = $(this),
      $field = $container.find('.meta-file-field'),
      $uploadBtn = $container.find('.meta-file-upload'),
      $deleteBtn = $container.find('.meta-file-delete'),
      $previewContainer = $container.find('.meta-file-preview');

    // Add new btn click
    $uploadBtn.on('click', (e) => {
      e.preventDefault();

      // If the media frame already exists, reopen it.
      if (frame) {
        frame.open();
        return;
      }

      // Create a new media frame
      frame = wp.media({
        title: 'Select or Upload a File',
        button: {
          text: 'Use this file'
        },
        multiple: false // Set to true to allow multiple files to be selected
      });

      // When an image is selected in the media frame...
      frame.on('select', () => {

        // Get media attachment details from the frame state
        const attachment = frame.state().get('selection').first().toJSON();

        // Send the attachment URL to our custom image input field.
        $previewContainer.html(`<img src="${attachment.iconOrThumb}"><br>${attachment.filename}`);

        // Send the attachment id to our hidden input
        $field.val(attachment.id);

        // Hide the add image link
        $uploadBtn.addClass('hidden');

        // Unhide the remove image link
        $deleteBtn.removeClass('hidden');
      });

      // Finally, open the modal on click
      frame.open();
    });

    // Delete selected btn click
    $deleteBtn.on('click', (e) => {
      e.preventDefault();

      // Clear out the preview image
      $previewContainer.html('No file selected.');

      // Un-hide the add image link
      $uploadBtn.removeClass('hidden');

      // Hide the delete image link
      $deleteBtn.addClass('hidden');

      // Delete the image id from the hidden input
      $field.val('');
    });
  });
};


(function ($) {
  WebcomAdmin.__init__($);
  WebcomAdmin.themeOptions($);
  if (USE_SC_INTERFACE) {
    WebcomAdmin.shortcodeInterfaceTool($);
  }
  WebcomAdmin.wysiwygFields($);
  WebcomAdmin.sliderMetaBoxes($);
  WebcomAdmin.storyFieldToggle($);
  WebcomAdmin.issueFieldToggle($);
  WebcomAdmin.fileUploader($);
}(jQuery));
