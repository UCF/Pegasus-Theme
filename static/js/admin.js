// Adds filter method to array objects
// https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/Array/filter
if(!Array.prototype.filter){
	Array.prototype.filter=function(a){"use strict";if(this===void 0||this===null)throw new TypeError;var b=Object(this);var c=b.length>>>0;if(typeof a!=="function")throw new TypeError;var d=[];var e=arguments[1];for(var f=0;f<c;f++){if(f in b){var g=b[f];if(a.call(e,g,f,b))d.push(g)}}return d}
}

var WebcomAdmin = {};


WebcomAdmin.__init__ = function($){
	// Allows forms with input fields of type file to upload files
	$('input[type="file"]').parents('form').attr('enctype','multipart/form-data');
	$('input[type="file"]').parents('form').attr('encoding','multipart/form-data');

    // Initialize all the color selectors
    var colorInput = $('.shortcode-color,#issue_default_color,#story_default_color,#story_default_header_img_background_color');
    if (colorInput.length) {
        colorInput.iris();
    }
};


WebcomAdmin.shortcodeInterfaceTool = function($) {
    cls = this;
    cls.shortcodeForm = $('#select-shortcode-form');
    cls.shortcodeButton = cls.shortcodeForm.find('button');
    cls.shortcodeSelect = cls.shortcodeForm.find('#shortcode-select');
    cls.shortcodeEditors = cls.shortcodeForm.find('#shortcode-editors');
    cls.shortcodeDescriptions = cls.shortcodeForm.find('#shortcode-descriptions');

    cls.shortcodeInsert = function(shortcode, parameters, enclosingText) {
        var text = '[' + shortcode;

        if (parameters) {
            for (key in parameters) {
                text += " " + key + "=\"" + parameters[key] + "\"";
            }
        }

        text +=  "]";
        if (enclosingText) {
            text += enclosingText;
        }
        text += "[/" + shortcode + "]";

        send_to_editor(text);
    }

    cls.shortcodeAction = function() {
        var selected = cls.shortcodeSelect.find(':selected');
        if (selected.length < 1 || selected.val() == ''){return;}

        var editor = cls.shortcodeEditors.find('li.shortcode-' + cls.shortcodeSelected);
        var dummyText = selected.attr('data-enclosing') || null;
        var highlightedWysiwygText = tinymce.activeEditor ? tinymce.activeEditor.selection.getContent() : null;
        var enclosingText = null;

        if (dummyText && highlightedWysiwygText) {
            enclosingText = highlightedWysiwygText;
        }
        else {
            enclosingText = dummyText;
        }

        var parameters = {};
        if (editor.length == 1) {
            editor.children().each(function() {
                var formElement = $(this);
                switch(formElement.prop('tagName')) {
                    case 'INPUT':
                    case 'TEXTAREA':
                    case 'SELECT':
                        parameters[formElement.attr('data-parameter')] = formElement.val();
                        break;
                }
            });
        }

        cls.shortcodeInsert(selected.val(), parameters, enclosingText);
    }

    cls.shortcodeSelectAction = function() {
        cls.shortcodeSelected = cls.shortcodeSelect.val();

        cls.shortcodeEditors.find('li').hide();
        cls.shortcodeDescriptions.find('li').hide();
        cls.shortcodeEditors.find('.shortcode-' + cls.shortcodeSelected).show();
        cls.shortcodeDescriptions.find('.shortcode-' + cls.shortcodeSelected).show();
    }

    cls.shortcodeSelectAction();

    // Option change for select, cause action
    cls.shortcodeSelect.change(cls.shortcodeSelectAction);

    // Button to insert shortcode
    cls.shortcodeButton.click(cls.shortcodeAction);
}


WebcomAdmin.themeOptions = function($){
	cls          = this;
	cls.active   = null;
	cls.parent   = $('.i-am-a-fancy-admin');
	cls.sections = $('.i-am-a-fancy-admin .fields .section');
	cls.buttons  = $('.i-am-a-fancy-admin .sections .section a');
    cls.buttonWrap = $('.i-am-a-fancy-admin .sections');
    cls.sectionLinks = $('.i-am-a-fancy-admin .fields .section a[href^="#"]');

	this.showSection = function(e){
		var button  = $(this);
		var href    = button.attr('href');
		var section = $(href);

        if (cls.buttonWrap.find('.section a[href="'+href+'"]') && section.is(cls.sections)) {
            // Switch active styles
            cls.buttons.removeClass('active');
            button.addClass('active');

            cls.active.hide();
            cls.active = section;
            cls.active.show();

            history.pushState({}, "", button.attr('href'));
            var http_referrer = cls.parent.find('input[name="_wp_http_referer"]');
            http_referrer.val(window.location);
            return false;
        }
	}

	this.__init__ = function(){
		cls.active = cls.sections.first();
		cls.sections.not(cls.active).hide();
		cls.buttons.first().addClass('active');
		cls.buttons.click(this.showSection);
        cls.sectionLinks.click(this.showSection);

		if (window.location.hash){
			cls.buttons.filter('[href="' + window.location.hash + '"]').click();
		}

		var fadeTimer = setInterval(function(){
			$('.updated').fadeOut(1000);
			clearInterval(fadeTimer);
		}, 2000);
	};

	if (cls.parent.length > 0){
		cls.__init__();
	}
};


WebcomAdmin.sliderMetaBoxes = function($) {
    // Slider Meta Box Updates:
    // (only run this code if we're on a screen with #slider-slides-settings-basic;
    // i.e. if we're on a slider edit screen:
    if ($('body.post-type-photo_essay').length > 0) {
        var frame;
        var slideCountWidget = $('#slider-slides-settings-count'),
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
        var getAllSlides = function() {
            return $('li.custom_repeatable.postbox');
        }

        // Return all slides that have enough content to be deemed not empty
        var getValidSlides = function() {
            var slides = [];
            var allSlides = getAllSlides();

            $.each(allSlides, function() {
                var slide = $(this),
                    inputID = getInputID(slide.find(keyField).attr('name')),
                    inputs = slide.find('input[name*="['+inputID+']"]'),
                    textareas = slide.find('textarea[name*="['+inputID+']"]'),
                    fields = [inputs, textareas];

                $.each(fields, function() {
                    if (($(this).val() && typeof $(this).val() != 'undefined' && $(this).val !== '') || $(this).hasClass('has-value')) {
                        slides.push(slide);
                        return false;
                    }
                });
            });
            return slides;
        }

        // Parses a string value to extract an input ID.
        // Assumes passed value is an input name/id, structured as "fieldname[id]".
        // Returns an input ID.
        var getInputID = function(string) {
            var inputID = string.split('[')[1];
            inputID = inputID.substr(0, inputID.length - 1);

            return inputID;
        }

        // Function that updates Slide Count value:
        var updateSlideCount = function() {
            var validSlides = getValidSlides();

            if (slideCountWidget.is('hidden')) {
                slideCountWidget.show();
            }

            var slideCount = validSlides.length;
            //alert('slideCount is: '+ slideCount + '; input value is: ' + SlideCountField.attr('value'));
            slideCountField.attr('value', slideCount);

            if (slideCountWidget.is('visible')) {
                slideCountWidget.hide();
            }
        }


        // Update the Slide Sort Order:
        var updateSliderSortOrder = function() {
            var sortOrder = [];
            var orderString = '';
            var validSlides = getValidSlides();

            $.each(validSlides, function() {
                var fieldName = $(this).find('input[name*="["], textarea[name*="["]')[0].getAttribute('name');
                var inputID = getInputID(fieldName);
                sortOrder[sortOrder.length] = inputID;
            });

            if (slideCountWidget.is('hidden')) {
                slideCountWidget.show();
            }

            $.each(sortOrder, function(index, value) {
                // make sure we only have number values (i.e. only slider widgets):
                if (!isNaN(value)) {
                    orderString += value + ",";
                }
            });
            // add each value to Slide Order field value:
            slideOrderField.attr('value', orderString);

            if (slideCountWidget.is('visible')) {
                slideCountWidget.hide();
            }
        }


        // Admin onload:
        slideCountWidget.hide();
        updateSlideCount();
        updateSliderSortOrder();


        // Update slide count when a slide's content changes:
        $(getAllSlides())
            .find('input, textarea')
                .on('change', function() {
                    updateSlideCount();
                    updateSliderSortOrder();
        });

        $(getAllSlides()).each(function() {
            var slideContent = $(this);
            WebcomAdmin.sliderHeaderTitleAction($, slideContent);
        });


        // Sortable slides
        $('#ss_slides_all').sortable({
            handle : 'h3.hndle',
            placeholder : 'sortable-placeholder',
            sort : function( event, ui ) {
                $('.sortable-placeholder').height( $(this).find('.ui-sortable-helper').height() );
            },
            update : function( event, ui ) {
                updateSliderSortOrder();
            },
            tolerance :'pointer'
        });


        // Toggle slide with header click
        $('#slider_slides').delegate('.custom_repeatable .hndle', 'click', function() {
            $(this).siblings('.inside').toggle().end().parent().toggleClass('closed');
        });


        // Create a new slide
        var createSlide = function(attachment) {
            var newSlideSibling = $('#ss_slides_all li.postbox:last-child'),
                slideCloner = $('#ss_slides_all li.cloner'),
                newSlide = slideCloner.clone(true).removeClass('cloner');

            var attachment_id = attachment.attributes.id,
                attachment_filename = attachment.attributes.filename,
                attachment_url = attachment.attributes.url,
                attachment_title = attachment.attributes.title,
                attachment_caption = attachment.attributes.caption;

            // Update 'name' attributes
            $('textarea, input[type="text"], input[type="select"], input[type="file"], input[type="hidden"]', newSlide).val('').attr('name', function(index, name) {
                return name.replace('xxxxxx', attachment_id);
            });
            $('input[type="checkbox"], input[type="radio"]', newSlide).attr('name', function(index, name) {
                return name.replace('xxxxxx', attachment_id);
            });
            // Update 'for' attributes (in <label>)
            $('label', newSlide).val('').attr('for', function(index, forval) {
                return forval.replace('xxxxxx', attachment_id);
            });
            // Update 'id' attributes
            $('textarea, input[type="text"], input[type="select"], input[type="checkbox"], input[type="radio"]', newSlide).attr('id', function(index, idval) {
                return idval.replace('xxxxxx', attachment_id);
            });

            // Remove other existing data from previous slide:
            $('.has-value', newSlide).removeClass('has-value');
            $('input[type="radio"]', newSlide).removeAttr('checked');

            // Update slide header title:
            $('.slide-handle-header', newSlide).text(attachment_title);
            WebcomAdmin.sliderHeaderTitleAction($, newSlide);

            // Update new slide values with anything made available
            // from the attachment object provided
            $('label[for^="ss_slide_image["]', newSlide)
                .parent('th')
                    .next('td')
                        .find('a')
                            .attr('href', attachment_url)
                            .find('img')
                                .attr('src', attachment_url)
                                .siblings('span')
                                    .text(attachment_filename);

            $('input[id^="ss_slide_title"]', newSlide).attr('value', attachment_title);
            $('textarea[id^="ss_slide_caption"]', newSlide).attr('value', attachment_caption);
            $('input[id^="file_img_"]', newSlide).attr('value', attachment_id);
            newSlide.insertAfter(newSlideSibling).show();

            // Update slide count, order
            updateSlideCount();
            updateSliderSortOrder();

            return false;
        }


        // Remove a slide
        $('.repeatable-remove').on('click', function() {
            $(this).parent().remove();
            //hideOnlyRemoveBtn();
            updateSlideCount();
            updateSliderSortOrder();
            return false;
        });


        // Handle Media Library modal toggle
        $('#slide_modal_toggle').on('click', function(event){
            event.preventDefault();

            if (!frame) {
                // Create uploader object
                var frame = wp.media({
                    title: 'Select Slide Images',
                    multiple: true,
                    library: { type: 'image' },
                    button : { text : 'Create New Slides' }
                });
                frame.on('select', function() {
                    var selection = frame.state().get('selection');
                    selection.each(function(attachment) {
                        //console.log(attachment.attributes);
                        createSlide(attachment);
                    });
                });
            }

            // Open the Media Library modal
            frame.open();
        });
    }
};


WebcomAdmin.sliderHeaderTitleAction = function($, field) {
    $('input[type="text"][id^="ss_slide_title"]', field).unbind().keyup({'slideDiv': field}, function(e) {
        var titleFieldValue = $(this).val()
        var slideHeader = $('.slide-handle-header', e.data.slideDiv);
        slideHeader.text(titleFieldValue);
    });
};


WebcomAdmin.storyFieldToggle = function($) {
    var templateField = $('#story_template');

    var toggleFields = function(val) {
        if (val == '') {
            val = 'default';
        }
        var fields = {
            "defaultFields" : ["story_description", "story_default_font", "story_default_color", "story_default_header_img", "story_default_header_img_background_color"],
            "photo_essayFields": ["story_description", "story_default_font"],
            "customFields" : ["story_html", "story_stylesheet", "story_javascript", "story_fonts"],
        };
        var fieldsOnKey = val + 'Fields';
        var fieldsOn = fields[fieldsOnKey];

        delete fields[fieldsOnKey];
        fieldsOff = fields;

        if (fieldsOff) {
            $.each(fieldsOff, function(key, array) {
                $.each(array, function(k, f) {
                    $('label[for="' + f + '"]').parents('tr').hide();
                });
            });
        }
        if (fieldsOn) {
            $.each(fieldsOn, function(key, field) {
                if ($.isArray(field)) {
                    $.each(val, function(k, f) {
                        $('label[for="' + f + '"]').parents('tr').fadeIn();
                    });
                }
                else {
                    $('label[for="' + field + '"]').parents('tr').fadeIn();
                }
            });
        }
    }

    // Toggle on load
    toggleFields(templateField.val());

    // Toggle fields on Story Template field change
    templateField.on('change', function() {
        toggleFields($(this).val());
    });
};


(function($){
	WebcomAdmin.__init__($);
	WebcomAdmin.themeOptions($);
	WebcomAdmin.shortcodeInterfaceTool($);
    WebcomAdmin.sliderMetaBoxes($);
    WebcomAdmin.storyFieldToggle($);
})(jQuery);
