<?php

/**
 * Responsible for running code that needs to be executed as wordpress is
 * initializing.  Good place to register scripts, stylesheets, theme elements,
 * etc.
 *
 * @return void
 * @author Jared Lang
 **/
function __init__(){
	add_theme_support('menus');
	add_theme_support('post-thumbnails');
	add_image_size('homepage', 620);
	add_image_size('single-post-thumbnail', 220, 230, true);
	add_image_size('issue-thumbnail', 190, 248);
	register_nav_menu('footer-menu', __('Footer Menu'));

	foreach(Config::$styles as $style){Config::add_css($style);}
	foreach(Config::$scripts as $script){Config::add_script($script);}

	global $timer;
	$timer = Timer::start();

	wp_deregister_script('l10n');
	set_defaults_for_options();
}
add_action('after_setup_theme', '__init__');



# Set theme constants
#define('DEBUG', True);                  # Always on
#define('DEBUG', False);                 # Always off
define('DEBUG', isset($_GET['debug'])); # Enable via get parameter
define('THEME_URL', get_stylesheet_directory_uri());
define('THEME_ADMIN_URL', get_admin_url());
define('THEME_DIR', get_stylesheet_directory());
define('THEME_INCLUDES_DIR', THEME_DIR.'/includes');
define('THEME_STATIC_URL', THEME_URL.'/static');
define('THEME_IMG_URL', THEME_STATIC_URL.'/img');
define('THEME_HELP_IMG_URL', THEME_IMG_URL.'/theme-help');
define('THEME_JS_URL', THEME_STATIC_URL.'/js');
define('THEME_CSS_URL', THEME_STATIC_URL.'/css');
define('THEME_FONT_URL', THEME_STATIC_URL.'/fonts');
define('THEME_DEV_URL', THEME_URL.'/dev');
define('THEME_OPTIONS_GROUP', 'settings');
define('THEME_OPTIONS_NAME', 'theme');
define('THEME_OPTIONS_PAGE_TITLE', 'Theme Options');

$theme_options = get_option(THEME_OPTIONS_NAME);
define('GA_ACCOUNT', $theme_options['ga_account']);
define('CB_UID', $theme_options['cb_uid']);
define('CB_DOMAIN', $theme_options['cb_domain']);

define('DEV_MODE', true); # Never leave this activated in a production environment!


/**
 * List of issue slugs that are from Fall 2013 or prior.
 **/
define('FALL_2013_OR_OLDER', serialize(array(
	'fall-2013',
	'summer-2013',
	'spring-2013',
	'fall-2012',
	'summer-2012'
)));


/**
 * Lists of available fonts for custom stories.
 * Structure array as key = font name, val = path to the font.
 * e.g. 'Font Name' => 'path/to/font-name/font-name.css'
 *
 * All fonts in these lists should have a single reference file, which points to all
 * available font file formats.
 * (i.e., from FontSquirrel, use the included stylesheet.css as the reference file.)
 **/
$custom_available_fonts_array = array(
	'Theano Modern' 		=> THEME_FONT_URL . '/theano-modern/stylesheet.css',
	'League Gothic' 		=> THEME_FONT_URL . '/league-gothic/stylesheet.css',
	'Aleo'					=> THEME_FONT_URL . '/aleo/stylesheet.css',
	'Visitor'				=> THEME_FONT_URL . '/visitor/stylesheet.css',
	'Montserrat'			=> THEME_FONT_URL . '/montserrat/stylesheet.css',
	'Open Sans Condensed' 	=> THEME_FONT_URL . '/open-sans-condensed/stylesheet.css',
);
define('CUSTOM_AVAILABLE_FONTS', serialize($custom_available_fonts_array));


/**
 * A base fallback set of font styles for default template titles.
 *
 * These options will be overridden per-font, as defined in
 * $template_font_styles_array, then by per-post meta values, if available.
 *
 * See output_header_markup() in functions.php for usage.
 *
 * Options:
 * - url:			Reference to @font-face import css file for the font family.
 * - family:		'font-family' property for default Story template h1-h6 tags and dropcaps, and
 *					default Issue cover's h2-h3 tags.
 * - color:			'color' property for default Story template h1-h6, blockquote, and dropcaps, and
 *					default Issue cover's h2 tag.
 * - weight:		'font-weight' property for default Story template h1-h6 tags and default Issue
 *					cover's h2-h3 tags.
 * - size-desktop:	'font-size' property of default Story template h1 and default Issue cover h2 at
 *					980px+ screen width.
 * - size-tablet:	'font-size' property of default Story template h1 and default Issue cover h2 at
 *					979-768px screen width.
 * - size-mobile:	'font-size' property of default Story template h1 and default Issue cover h2 at
 *					<768px screen width.
 * - textalign:		'text-align' property of Issue cover h2.
 * - texttransform:	'text-transform' property of default Story template h1-h6 tags and default Issue
 *					cover's h2-h3 tags.
 *
 **/
$template_font_styles_base_array = array(
	'url' => null,
	'family' => '"Helvetica Neue", "Helvetica-Neue", Helvetica, sans-serif',
	'color' => '#222',
	'weight' => 'bold',
	'size-desktop' => '65px',
	'size-tablet' => '65px',
	'size-mobile' => '40px',
	'textalign' => 'left',
	'texttransform' => 'none',
);
define('TEMPLATE_FONT_STYLES_BASE', serialize($template_font_styles_base_array));


/**
 * Lists of available fonts for default templates and their styles.
 * $template_fonts_array should be structured the same as $custom_available_fonts_array.
 * Structure $template_font_styles_array as as key = font name, val = array of options.
 *
 * Font reference files listed in $template_fonts_array are registered with WordPress
 * as admin stylesheets.
 *
 * If a font in $template_font_styles_array is web-safe and has no reference file,
 * leave the 'url' option as null.
 **/
$template_fonts_array = array(
	'Aleo' => THEME_FONT_URL . '/aleo/stylesheet.css',
	'Montserrat' => THEME_FONT_URL . '/montserrat/stylesheet.css',
	'Open Sans Condensed' => THEME_FONT_URL . '/open-sans-condensed/stylesheet.css',
);
$template_font_styles_array = array(
	'Aleo Light' => array(
		'url' => $template_fonts_array['Aleo'],
		'family' => '"AleoLight", serif',
		'weight' => 'normal',
		'size-desktop' => '72px',
		'size-tablet' => '72px',
	),
	'Aleo Regular' => array(
		'url' => $template_fonts_array['Aleo'],
		'family' => '"AleoRegular", serif',
		'weight' => 'normal',
		'size-desktop' => '72px',
		'size-tablet' => '72px',
	),
	'Aleo Bold' => array(
		'url' => $template_fonts_array['Aleo'],
		'family' => '"AleoBold", serif',
		'weight' => 'normal',
		'size-desktop' => '72px',
		'size-tablet' => '72px',
	),
	'Georgia Regular' => array(
		'url' => null,
		'family' => 'Georgia, serif',
		'weight' => 'normal',
		'size-desktop' => '70px',
		'size-tablet' => '70px',
	),
	'Montserrat Regular' => array(
		'url' => $template_fonts_array['Montserrat'],
		'family' => '"MontserratRegular", serif',
		'weight' => 'normal',
		'size-desktop' => '72px',
		'size-tablet' => '72px',
		'size-mobile' => '36px',
	),
	'Montserrat Bold' => array(
		'url' => $template_fonts_array['Montserrat'],
		'family' => '"MontserratBold", serif',
		'weight' => 'normal',
		'size-desktop' => '72px',
		'size-tablet' => '72px',
		'size-mobile' => '36px',
	),
	'Arial Black' => array(
		'url' => null,
		'family' => '"Arial Black", serif',
		'weight' => 'normal',
		'size-desktop' => '70px',
		'size-tablet' => '70px',
		'size-mobile' => '34px',
	),
	'Open Sans Condensed Bold' => array(
		'url' => $template_fonts_array['Open Sans Condensed'],
		'family' => '"OpenSansCondensedBold", serif',
		'weight' => 'normal',
		'size-desktop' => '75px',
		'size-tablet' => '75px',
		'texttransform' => 'uppercase',
	),
);
define('TEMPLATE_FONT_STYLES', serialize($template_font_styles_array));


/**
 * Set config values including meta tags, registered custom post types, styles,
 * scripts, and any other statically defined assets that belong in the Config
 * object.
 **/
Config::$custom_post_types = array(
	'Page',
	'AlumniNote',
	'Story',
	'Issue',
	'PhotoEssay'
);

Config::$custom_taxonomies = array(
	'Issues'
);

Config::$body_classes = array();


/**
 * Grab array of Issue posts for Config::$theme_settings:
 **/
$issue_covers 		= get_posts(array('post_type' => 'issue'));
$issue_cover_array 	= array();
foreach ($issue_covers as $cover) {
	$issue_cover_array[$cover->post_title] = $cover->post_name;
}


/**
 * Configure theme settings, see abstract class Field's descendants for
 * available fields. -- functions/base.php
 **/
Config::$theme_settings = array(
	'Analytics' => array(
		new TextField(array(
			'name'        => 'Google WebMaster Verification',
			'id'          => THEME_OPTIONS_NAME.'[gw_verify]',
			'description' => 'Example: <em>9Wsa3fspoaoRE8zx8COo48-GCMdi5Kd-1qFpQTTXSIw</em>',
			'default'     => null,
			'value'       => $theme_options['gw_verify'],
		)),
		new TextField(array(
			'name'        => 'Yahoo! Site Explorer',
			'id'          => THEME_OPTIONS_NAME.'[yw_verify]',
			'description' => 'Example: <em>3236dee82aabe064</em>',
			'default'     => null,
			'value'       => $theme_options['yw_verify'],
		)),
		new TextField(array(
			'name'        => 'Bing Webmaster Center',
			'id'          => THEME_OPTIONS_NAME.'[bw_verify]',
			'description' => 'Example: <em>12C1203B5086AECE94EB3A3D9830B2E</em>',
			'default'     => null,
			'value'       => $theme_options['bw_verify'],
		)),
		new TextField(array(
			'name'        => 'Google Analytics Account',
			'id'          => THEME_OPTIONS_NAME.'[ga_account]',
			'description' => 'Example: <em>UA-9876543-21</em>. Leave blank for development.',
			'default'     => null,
			'value'       => $theme_options['ga_account'],
		)),
		new TextField(array(
			'name'        => 'Chartbeat UID',
			'id'          => THEME_OPTIONS_NAME.'[cb_uid]',
			'description' => 'Example: <em>1842</em>',
			'default'     => null,
			'value'       => $theme_options['cb_uid'],
		)),
		new TextField(array(
			'name'        => 'Chartbeat Domain',
			'id'          => THEME_OPTIONS_NAME.'[cb_domain]',
			'description' => 'Example: <em>some.domain.com</em>',
			'default'     => null,
			'value'       => $theme_options['cb_domain'],
		)),
	),

	'Search' => array(
		new RadioField(array(
			'name'        => 'Enable Google Search',
			'id'          => THEME_OPTIONS_NAME.'[enable_google]',
			'description' => 'Enable to use the google search appliance to power the search functionality.',
			'default'     => 1,
			'choices'     => array(
				'On'  => 1,
				'Off' => 0,
			),
			'value'       => $theme_options['enable_google'],
	    )),
		new TextField(array(
			'name'        => 'Search Domain',
			'id'          => THEME_OPTIONS_NAME.'[search_domain]',
			'description' => 'Domain to use for the built-in google search.  Useful for development or if the site needs to search a domain other than the one it occupies. Example: <em>some.domain.com</em>',
			'default'     => null,
			'value'       => $theme_options['search_domain'],
		)),
		new TextField(array(
			'name'        => 'Search Results Per Page',
			'id'          => THEME_OPTIONS_NAME.'[search_per_page]',
			'description' => 'Number of search results to show per page of results',
			'default'     => 10,
			'value'       => $theme_options['search_per_page'],
		)),
	),
	'Social' => array(
		new RadioField(array(
			'name'        => 'Enable OpenGraph',
			'id'          => THEME_OPTIONS_NAME.'[enable_og]',
			'description' => 'Turn on the opengraph meta information used by Facebook.',
			'default'     => 1,
			'choices'     => array(
				'On'  => 1,
				'Off' => 0,
			),
			'value'       => $theme_options['enable_og'],
	    )),
		new TextField(array(
			'name'        => 'Facebook Admins',
			'id'          => THEME_OPTIONS_NAME.'[fb_admins]',
			'description' => 'Comma seperated facebook usernames or user ids of those responsible for administrating any facebook pages created from pages on this site. Example: <em>592952074, abe.lincoln</em>',
			'default'     => null,
			'value'       => $theme_options['fb_admins'],
		)),
		new TextField(array(
			'name'        => 'Facebook URL',
			'id'          => THEME_OPTIONS_NAME.'[fb_url]',
			'description' => 'URL of the Facebook page related to this site. If this field is left empty, this social media link will not appear in the footer.',
			'default'     => 'http://www.facebook.com/UCF',
			'value'       => $theme_options['fb_url'],
		)),
		new TextField(array(
			'name'        => 'Twitter URL',
			'id'          => THEME_OPTIONS_NAME.'[twitter_url]',
			'description' => 'URL of the Twitter page related to this site. If this field is left empty, this social media link will not appear in the footer.',
			'default'     => 'http://twitter.com/UCF',
			'value'       => $theme_options['twitter_url'],
		)),
		new TextField(array(
			'name'        => 'Flickr URL',
			'id'          => THEME_OPTIONS_NAME.'[flickr_url]',
			'description' => 'URL of the Flickr page related to this site. If this field is left empty, this social media link will not appear in the footer.',
			'default'     => 'http://www.flickr.com/groups/ucf/',
			'value'       => $theme_options['flickr_url'],
		)),
		new TextField(array(
			'name'        => 'YouTube URL',
			'id'          => THEME_OPTIONS_NAME.'[youtube_url]',
			'description' => 'URL of the YouTube page related to this site. If this field is left empty, this social media link will not appear in the footer.',
			'default'     => 'http://www.youtube.com/user/UCF',
			'value'       => $theme_options['youtube_url'],
		)),
		new TextField(array(
			'name'        => 'Google+ URL',
			'id'          => THEME_OPTIONS_NAME.'[googleplus_url]',
			'description' => 'URL of the Google+ page related to this site. If this field is left empty, this social media link will not appear in the footer.',
			'default'     => 'https://plus.google.com/+UCF',
			'value'       => $theme_options['googleplus_url'],
		)),
	),
	'Devices' => array(
		new TextField(array(
			'name'        => 'iTunes Store iPad App URL',
			'id'          => THEME_OPTIONS_NAME.'[ipad_app_url]',
			'description' => 'URL of the Pegasus Magazine iPad app in the iTunes store. Used for the iPad modal. The modal and footer link will not be displayed if this field is blank.',
			'default'     => '',
			'value'       => $theme_options['ipad_app_url'],
		))
	),
	'Issues' => array(
		new SelectField(array(
			'name'        => 'Current Issue Cover',
			'id'          => THEME_OPTIONS_NAME.'[current_issue_cover]',
			'description' => 'Specify the current active issue\'s front cover to display on the home page.  This should match up with the Issue Term specified above.',
			'choices'     => $issue_cover_array,
			'default'     => $issue_cover_array[0],
			'value'       => $theme_options['current_issue_cover'],
		)),
	),
	'Contact Information' => array(
		new TextField(array(
			'name' => 'Organization Name',
			'id' => THEME_OPTIONS_NAME.'[org_name]',
			'description' => 'The name for your organization, used when displaying your organization\'s address.',
			'value' => $theme_options['org_name'],
			'default'	=> 'University of Central Florida',
		)),
		new TextareaField(array(
			'name' => 'Organization Address',
			'id' => THEME_OPTIONS_NAME.'[org_address]',
			'description' => 'The address for your organization.',
			'value' => $theme_options['org_address'],
			'default'	=> '4000 Central Florida Blvd.
Orlando, FL 32816',
		)),
	),
);

Config::$links = array(
	array('rel' => 'shortcut icon', 'href' => THEME_IMG_URL.'/favicon.ico',),
	array('rel' => 'alternate', 'type' => 'application/rss+xml', 'href' => get_bloginfo('rss_url'),),
);


Config::$styles = array(
	array('name' => 'admin-css', 'src' => THEME_CSS_URL.'/admin.css', 'admin' => True),
	array('name' => 'font-icomoon', 'src' => THEME_FONT_URL.'/icomoon/style.css'),
	array('name' => 'font-montserrat', 'src' => $custom_available_fonts_array['Montserrat']),
	array('name' => 'font-aleo', 'src' => $custom_available_fonts_array['Aleo']),
	array('name' => 'bootstrap-css', 'src' => THEME_STATIC_URL.'/bootstrap/css/bootstrap.css'),
	array('name' => 'bootstrap-responsive-css', 'src' => THEME_STATIC_URL.'/bootstrap/css/bootstrap-responsive.css'),
	array('name' => 'gf-css', 'src' => plugins_url('gravityforms/css/forms.css')),
	array('name' => 'style-css', 'src' => get_bloginfo('stylesheet_url')),
	array('name' => 'style-responsive-css', 'src' => THEME_URL.'/style-responsive.css')
);
foreach ($template_fonts_array as $key => $val) {
	$name = 'admin-font-'.sanitize_title($key);
	array_push(Config::$styles, array('name' => $name, 'admin' => True, 'src' => $val));
}


Config::$scripts = array(
	array('admin' => True, 'src' => THEME_JS_URL.'/admin.js',),
	THEME_STATIC_URL.'/bootstrap/js/bootstrap.js',
	THEME_STATIC_URL.'/js/jquery.cookie.js',
	array('name' => 'placeholders', 'src' => THEME_JS_URL.'/placeholders.js',),
	array('name' => 'base-script',  'src' => THEME_JS_URL.'/webcom-base.js',),
	array('name' => 'theme-script', 'src' => THEME_JS_URL.'/script.js',),
	array('name' => 'inview', 'src' => THEME_JS_URL.'/inview.js',),
	array('name' => 'kinetic', 'src' => THEME_JS_URL.'/jquery.kinetic.min.js',),
	array('name' => 'lazyload', 'src' => THEME_JS_URL.'/jquery.lazyload.min.js',),
);

Config::$metas = array(
	array('charset' => 'utf-8',),
);
if ($theme_options['gw_verify']){
	Config::$metas[] = array(
		'name'    => 'google-site-verification',
		'content' => htmlentities($theme_options['gw_verify']),
	);
}



function jquery_in_header() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', '//code.jquery.com/jquery-1.7.1.min.js');
    wp_enqueue_script( 'jquery' );
}

add_action('wp_enqueue_scripts', 'jquery_in_header');
