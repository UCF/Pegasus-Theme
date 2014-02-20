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
    var colorInput = $('.shortcode-color,#issue_default_color,#story_default_color');
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
        var enclosingText = selected.attr('data-enclosing') || null;

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
    if ($('#ss_slides_wrapper').length > 0) {

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


        // If only one slide is available on the page, hide the 'Remove slide' button for that slide:
        var hideOnlyRemoveBtn = function() {
            if ($('#ss_slides_all li.custom_repeatable').length < 2) {
                $('#ss_slides_all li.custom_repeatable:first-child a.repeatable-remove').hide();
            }
            else {
                $('#ss_slides_all li.custom_repeatable a.repeatable-remove').show();
            }
        }



        // Admin onload:
        slideCountWidget.hide();
        updateSlideCount();
        updateSliderSortOrder();
        hideOnlyRemoveBtn();


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


        // Add/remove Slide button functionality:
        $('.repeatable-add').on('click', function() {
            var field = $(this).prev('li').clone(true);
            var fieldLocation = $(this).prev('li');
            var widgetNumbers = [];
            var highestNum = 0;

            // Get the highest ID 'widget' number to prevent duplicate IDs after sorting:
            $(keyField).each(function() {
                // get number by trimming the input ID
                var inputID = getInputID($(this).attr('name'));
                widgetNumbers[widgetNumbers.length] = inputID;
            });
            highestNum = Math.max.apply(Math, widgetNumbers);

            // Update 'name' attributes
            $('textarea, input[type="text"], input[type="select"], input[type="file"]', field).val('').attr('name', function(index, name) {
                return name.replace(/(\d+)/, highestNum + 1);
            });
            $('input[type="checkbox"], input[type="radio"]', field).attr('name', function(index, name) {
                return name.replace(/(\d+)/, highestNum + 1);
            });
            // Update 'for' attributes (in <label>)
            $('label', field).val('').attr('for', function(index, forval) {
                return forval.replace(/(\d+)/, highestNum + 1);
            });
            // Update 'id' attributes
            $('textarea, input[type="text"], input[type="select"], input[type="checkbox"], input[type="radio"]', field).attr('id', function(index, idval) {
                return idval.replace(/(\d+)/, highestNum + 1);
            });
            // Remove other existing data from previous slide:
            $('.has-value', field).removeClass('has-value');
            $('input[type="radio"]', field).removeAttr('checked');
            $('label[for^="ss_slide_image["]', field).parent('th').next('td').children('a, br:nth-child(2)').remove();

            field.fadeIn().insertAfter(fieldLocation, $(this).prev('li'));

            $('.slide-handle-header', field).text('');
            WebcomAdmin.sliderHeaderTitleAction($, field);

            hideOnlyRemoveBtn();
            return false;
        });

        $('.repeatable-remove').on('click', function() {
            $(this).parent().remove();
            hideOnlyRemoveBtn();
            updateSlideCount();
            updateSliderSortOrder();
            return false;
        });
    }
};


WebcomAdmin.sliderHeaderTitleAction = function($, field) {
    $('input[type="text"][id^="ss_slide_title"]', field).unbind().keyup({'slideDiv': field}, function(e) {
        var titleFieldValue = $(this).val()
        var slideHeader = $('.slide-handle-header', e.data.slideDiv);
        slideHeader.text(titleFieldValue);
    });
}


WebcomAdmin.storyFieldToggle = function($) {
    var templateField = $('#story_template');

    var toggleFields = function(val) {
        if (val == '') {
            val = 'default';
        }
        var fields = {
            "defaultFields" : ["story_description", "story_default_font", "story_default_color", "story_default_header_img"],
            "photo_essayFields": ["story_description", "story_default_font"],
            "customFields" : ["story_stylesheet", "story_javascript", "story_fonts"],
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
