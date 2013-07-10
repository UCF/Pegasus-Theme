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
	register_nav_menu('header-menu', __('Header Menu'));
	register_nav_menu('footer-menu', __('Footer Menu'));
	register_sidebar(array(
		'name'          => __('Sidebar'),
		'id'            => 'sidebar',
		'description'   => 'Sidebar found on two column page templates and search pages',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
	));
	register_sidebar(array(
		'name' => __('Footer - Column One'),
		'id' => 'bottom-one',
		'description' => 'Far left column in footer on the bottom of pages.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
	));
	register_sidebar(array(
		'name' => __('Footer - Column Two'),
		'id' => 'bottom-two',
		'description' => 'Second column from the left in footer, on the bottom of pages.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
	));
	register_sidebar(array(
		'name' => __('Footer - Column Three'),
		'id' => 'bottom-three',
		'description' => 'Third column from the left in footer, on the bottom of pages.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
	));
	register_sidebar(array(
		'name' => __('Footer - Column Four'),
		'id' => 'bottom-four',
		'description' => 'Far right in footer on the bottom of pages.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
	));
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
define('THEME_URL', get_bloginfo('stylesheet_directory'));
define('THEME_ADMIN_URL', get_admin_url());
define('THEME_DIR', get_stylesheet_directory());
define('THEME_INCLUDES_DIR', THEME_DIR.'/includes');
define('THEME_STATIC_URL', THEME_URL.'/static');
define('THEME_IMG_URL', THEME_STATIC_URL.'/img');
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

define('DEV_MODE', false); # Never leave this activated in a production environment!

/**
 * List of available fonts. Structure array as key = font name, val = path to the font. 
 * e.g. 'Font Name' => 'path/to/font-name/font-name.css'
 *
 * All fonts in this list should have a single reference file, which points to all 
 * available font file formats.
 * (i.e., from FontSquirrel, use the included stylesheet.css as the reference file.)
 **/
define('THEME_AVAILABLE_FONTS', serialize(array(
	'Theano Modern' => THEME_FONT_URL . '/theano-modern/stylesheet.css',
	'League Gothic' => THEME_FONT_URL . '/league-gothic/stylesheet.css',
	'Aleo'			=> THEME_FONT_URL . '/aleo/stylesheet.css',
	'Visitor'		=> THEME_FONT_URL . '/visitor/stylesheet.css',
))); 


/**
 * Set config values including meta tags, registered custom post types, styles,
 * scripts, and any other statically defined assets that belong in the Config
 * object.
 **/
Config::$custom_post_types = array(
	'Page',
	'AlumniNote',
	'Story',
	'Issue'
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
	'Styles' => array(
		new RadioField(array(
			'name'        => 'Enable Responsiveness',
			'id'          => THEME_OPTIONS_NAME.'[bootstrap_enable_responsive]',
			'description' => 'Turn on responsive styles provided by the Twitter Bootstrap framework.  This setting should be decided upon before building out subpages, etc. to ensure content is designed to shrink down appropriately.  Turning this off will enable the single 940px-wide Bootstrap layout.',
			'default'     => 1,
			'choices'     => array(
				'On'  => 1,
				'Off' => 0,
			),
			'value'       => $theme_options['bootstrap_enable_responsive'],
	    )),
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
);

Config::$links = array(
	array('rel' => 'shortcut icon', 'href' => THEME_IMG_URL.'/favicon.ico',),
	array('rel' => 'alternate', 'type' => 'application/rss+xml', 'href' => get_bloginfo('rss_url'),),
);


Config::$styles = array(
	array('admin' => True, 'src' => THEME_CSS_URL.'/admin.css',),
	//'http://universityheader.ucf.edu/bar/css/bar.css',
	THEME_STATIC_URL.'/bootstrap/css/bootstrap.css',
);

if ($theme_options['bootstrap_enable_responsive'] == 1) {
	array_push(Config::$styles, 
		THEME_STATIC_URL.'/bootstrap/css/bootstrap-responsive.css'
	);		
}

array_push(Config::$styles,	
	plugins_url( 'gravityforms/css/forms.css' ),
	//THEME_CSS_URL.'/webcom-base.css', 
	get_bloginfo('stylesheet_url')
);

if ($theme_options['bootstrap_enable_responsive'] == 1) {
	array_push(Config::$styles, 
		THEME_URL.'/style-responsive.css'
	);	
}

Config::$scripts = array(
	array('admin' => True, 'src' => THEME_JS_URL.'/admin.js',),
	THEME_STATIC_URL.'/bootstrap/js/bootstrap.js',
	THEME_STATIC_URL.'/js/jquery.cookie.js',
	array('name' => 'base-script',  'src' => THEME_JS_URL.'/webcom-base.js',),
	array('name' => 'theme-script', 'src' => THEME_JS_URL.'/script.js',),
	array('name' => 'inview', 'src' => THEME_JS_URL.'/inview.js',),
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
    wp_register_script( 'jquery', 'http://code.jquery.com/jquery-1.7.1.min.js');
    wp_enqueue_script( 'jquery' );
}    
 
add_action('wp_enqueue_scripts', 'jquery_in_header');