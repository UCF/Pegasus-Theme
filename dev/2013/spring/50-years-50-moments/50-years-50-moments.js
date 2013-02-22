/* Kill responsive styles for this page */
$('link#bootstrap-responsive-css').remove();
$('meta[name="viewport"]').attr('content', 'width=980');

/* Delete annoying empty p tags */
$("p:empty").remove();

/* Use PIE.js if this is an old browser */
if ($('body').hasClass('ie7') || $('body').hasClass('ie8')) {
	$.getScript(THEME_JS_URL + '/PIE.js', function() {
		if (window.PIE) {
			$('.title-circle-medium, .title-circle-large').each(function() {
				PIE.attach(this);
			});
		}
	});
}

/* Fly-in stuff (uses static/js/inview.js) */

// Basic check for CSS animation support
// http://stackoverflow.com/questions/10888211/detect-support-for-transition-with-javascript
function detectCSSFeature(featurename){
    var feature = false,
    domPrefixes = 'Webkit Moz ms O'.split(' '),
    elm = document.createElement('div'),
    featurenameCapital = null;

    featurename = featurename.toLowerCase();

    if( elm.style[featurename] ) { feature = true; } 

    if( feature === false ) {
        featurenameCapital = featurename.charAt(0).toUpperCase() + featurename.substr(1);
        for( var i = 0; i < domPrefixes.length; i++ ) {
            if( elm.style[domPrefixes[i] + featurenameCapital ] !== undefined ) {
              feature = true;
              break;
            }
        }
    }
    return feature; 
}

var hasAnimationSupport = detectCSSFeature('animation');

if (hasAnimationSupport == true) {
	// List of all the available css3 animations (defined in 50-moments-50-years.css)
	var animations = [
		'fadeIn',
		'fadeInUp',
		'fadeInDown',
		'fadeInLeft',
		'fadeInRight',
		'fadeInUpBig',
		'fadeInDownBig',
		'fadeInLeftBig',
		'fadeInRightBig',
		'bounceIn',
		'bounceInUp',
		'bounceInDown',
		'bounceInLeft',
		'bounceInRight',
	];
	
	$('.box').each(function() {
		if ($(this).hasClass('noanimation') == false) {
			$(this).css('visibility', 'hidden').bind('inview', function (event, visible) {
				if (visible == true) {
					// grab a random animation
					var randAnimation = animations[Math.floor(Math.random() * animations.length)];
					// animate the box somehow, then stop listening
					$(this)
						.css('visibility', 'visible')
						.addClass(randAnimation)
						.unbind('inview');
				}
			});
		}
	});
}