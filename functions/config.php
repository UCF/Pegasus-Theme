<?php
/****************************************************************************
 *
 * START theme constants here
 *
 ****************************************************************************/

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
define('THEME_COMPONENTS_URL', THEME_STATIC_URL.'/components');
define('THEME_OPTIONS_GROUP', 'settings');
define('THEME_OPTIONS_PAGE_TITLE', 'Theme Options');

$theme_options = get_option(THEME_OPTIONS_NAME);
define('GTM_ID', isset( $theme_options['gtm_id'] ) ? $theme_options['gtm_id'] : null );
define('GA_ACCOUNT', isset( $theme_options['ga_account'] ) ? $theme_options['ga_account'] : null );
define('GA4_ACCOUNT', isset( $theme_options['ga4_account'] ) ? $theme_options['ga4_account'] : null );
define('CB_UID', isset( $theme_options['cb_uid'] ) ? $theme_options['cb_uid'] : null );
define('CB_DOMAIN', isset( $theme_options['cb_domain'] ) ? $theme_options['cb_domain'] : null );

// Timeout for data grabbed from feeds
define( 'FEED_FETCH_TIMEOUT', 10 ); // seconds

define('DEV_MODE', isset( $theme_options['dev_mode'] ) ? intval($theme_options['dev_mode'] ) : null ); # Never leave this activated in a production environment!


/**
 * Version definitions.  Versions should always be whole numbers (no decimals).
 **/
define( 'LATEST_VERSION', 6 ); // The most up-to-date major version of the theme
define( 'EARLIEST_VERSION', 1 ); // the very first version
define( 'VERSIONS', serialize( range( EARLIEST_VERSION, LATEST_VERSION ) ) );
define( 'VERSIONS_PATH', 'versions/' );


/**
 * Group all issues by version.  Intended as a replacement for (now-removed)
 * FALL_2013_OR_OLDER constant and is_fall_2013_or_older(), and should exist
 * solely for running backward compatibility-related functions.
 *
 * Don't create new constants for versions above v2; they are not necessary.
 **/
define( 'V1_ISSUES', serialize( array(
	'fall-2013',
	'summer-2013',
	'spring-2013',
	'fall-2012',
	'summer-2012'
) ) );
define( 'V2_ISSUES', serialize( array(
	'spring-2014',
	'summer-2014',
	'fall-2014',
	'spring-2015'
) ) );


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
	'League Spartan' 		=> THEME_FONT_URL . '/league-spartan/stylesheet.css',
	'Poppins'		 		=> THEME_FONT_URL . '/poppins/stylesheet.css',
);
define('CUSTOM_AVAILABLE_FONTS', serialize($custom_available_fonts_array));


/**
 * A base fallback set of font styles for default Story/Issue template headings and
 * custom styling in the WYSIWYG editor.
 *
 * These options will be overridden per-font, as defined in
 * $template_font_styles_array, then by per-post meta values (for headings), if
 * available.
 *
 * See output_header_markup() and get_font_class() in functions.php for
 * usage.
 *
 * Options:
 * - url:            Reference to @font-face import css file for the font family.
 * - font-family:    Used on all Story template headings, Story template dropcaps, Issue template
 *                     h2-h3's, and all tags with class '.font-name'.
 * - font-weight:    Used on all Story template headings, Story template dropcaps, Issue template
 *                     h2-h3's, and all tags with class '.font-name'.
 * - text-align:     Used on Issue template h2's.
 * - font-style:     Used on all Story template headings, Story template dropcaps, Issue template
 *                     h2-h3's, and all tags with class '.font-name'.
 * - letter-spacing: 'letter-spacing' property of default Story template h1-h6 tags and dropcaps,
 *                     default Issue cover's h2-h3 tags, and tags with class '.font-name'.
 * - size-desktop:   'font-size' property of default Story template h1 and default Issue cover h2 at
 *                     980px+ screen width.
 * - size-tablet:    'font-size' property of default Story template h1 and default Issue cover h2 at
 *                     979-768px screen width.
 * - size-mobile:    'font-size' property of default Story template h1 and default Issue cover h2 at
 *                     <768px screen width.
 *
 **/
$template_font_styles_base_array = array(
	'url' => null,
	'font-family' => '"Gotham SSm 7r", "Gotham SSm A", "Gotham SSm B", sans-serif',
	'font-weight' => '700',
	'text-align' => 'left',
	'font-style' => 'normal',
	'letter-spacing' => '-0.012em',
	'size-desktop' => '3.75rem',
	'size-tablet' => '3.75rem',
	'size-mobile' => '2.5rem',
	'text-transform' => 'none'
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
 * or if the font is loaded in via the Cloud.Typography stylesheet, leave the 'url'
 * option as null.
 **/
$template_fonts_array = array(
	'Aleo' => THEME_FONT_URL . '/aleo/stylesheet.css',
	'Montserrat' => THEME_FONT_URL . '/montserrat/stylesheet.css',
	'Open Sans Condensed' => THEME_FONT_URL . '/open-sans-condensed/stylesheet.css',
	'Poppins' => THEME_FONT_URL . '/poppins/stylesheet.css',
);
define( 'TEMPLATE_FONT_URLS', serialize( $template_fonts_array ) );
$template_font_styles_array = array(
	'Archer Medium' => array(
		'url' => null,
		'font-family' => '"Archer 6r", "Archer A", "Archer B", serif',
		'font-weight' => '600',
		'letter-spacing' => 'normal',
		'-moz-osx-font-smoothing' => 'auto',
		'-webkit-font-smoothing' => 'auto',
	),
	'Archer Medium Italic' => array(
		'url' => null,
		'font-family' => '"Archer 6i", "Archer A", "Archer B", serif',
		'font-weight' => '600',
		'font-style' => 'italic',
		'letter-spacing' => 'normal',
		'-moz-osx-font-smoothing' => 'auto',
		'-webkit-font-smoothing' => 'auto',
	),
	'Archer Bold' => array(
		'url' => null,
		'font-family' => '"Archer 8r", "Archer A", "Archer B", serif',
		'font-weight' => '800',
		'letter-spacing' => 'normal',
		'-moz-osx-font-smoothing' => 'auto',
		'-webkit-font-smoothing' => 'auto',
	),
	'Archer Bold Italic' => array(
		'url' => null,
		'font-family' => '"Archer 8i", "Archer A", "Archer B", serif',
		'font-weight' => '800',
		'font-style' => 'italic',
		'letter-spacing' => 'normal',
		'-moz-osx-font-smoothing' => 'auto',
		'-webkit-font-smoothing' => 'auto',
	),
	'Chronicle Display Roman' => array(
		'url' => null,
		'font-family' => '"Chronicle Display 4r", "Chronicle Display A", "Chronicle Display B", serif',
		'font-weight' => '400',
		'letter-spacing' => 'normal',
	),
	'Chronicle Display Bold' => array(
		'url' => null,
		'font-family' => '"Chronicle Display 7r","Chronicle Display A", "Chronicle Display B", serif',
		'font-weight' => '700',
		'letter-spacing' => 'normal',
	),
	'Georgia Regular' => array(
		'url' => null,
		'font-family' => 'Georgia, serif',
		'font-weight' => 'normal',
		'letter-spacing' => 'normal',
		'size-desktop' => '56px',
		'size-tablet' => '56px',
	),
	'Gotham Light' => array(
		'url' => null,
		'font-family' => '"Gotham SSm 3r", "Gotham SSm A", "Gotham SSm B", sans-serif',
		'font-weight' => '300',
	),
	'Gotham Book' => array(
		'url' => null,
		'font-family' => '"Gotham SSm 4r", "Gotham SSm A", "Gotham SSm B", sans-serif',
		'font-weight' => '400',
	),
	'Gotham Bold' => array(
		'url' => null,
		'font-family' => '"Gotham SSm 7r", "Gotham SSm A", "Gotham SSm B", sans-serif',
		'font-weight' => '700',
	),
	'Gotham Black' => array(
		'url' => null,
		'font-family' => '"Gotham SSm 8r", "Gotham SSm A", "Gotham SSm B", sans-serif',
		'font-weight' => '800',
	),
	'Helvetica Bold' => array(
		'url' => null,
		'font-family' => '"Helvetica Neue", "Helvetica-Neue", Helvetica, sans-serif',
		'font-weight' => 'bold',
		'letter-spacing' => 'normal',
	),
	/* For backward compatibility with Spring 2014 stories: */
	'Aleo Light' => array(
		'url' => $template_fonts_array['Aleo'],
		'font-family' => '"AleoLight", serif',
		'font-weight' => 'normal',
		'letter-spacing' => 'normal',
		'size-desktop' => '58px',
		'size-tablet' => '58px',
	),
	'Aleo Regular' => array(
		'url' => $template_fonts_array['Aleo'],
		'font-family' => '"AleoRegular", serif',
		'font-weight' => 'normal',
		'letter-spacing' => 'normal',
		'size-desktop' => '58px',
		'size-tablet' => '58px',
	),
	'Aleo Bold' => array(
		'url' => $template_fonts_array['Aleo'],
		'font-family' => '"AleoBold", serif',
		'font-weight' => 'normal',
		'letter-spacing' => 'normal',
		'size-desktop' => '58px',
		'size-tablet' => '58px',
	),
	'Montserrat Regular' => array(
		'url' => $template_fonts_array['Montserrat'],
		'font-family' => '"MontserratRegular", sans-serif',
		'font-weight' => 'normal',
		'letter-spacing' => 'normal',
		'size-desktop' => '58px',
		'size-tablet' => '58px',
		'size-mobile' => '36px',
	),
	'Montserrat Bold' => array(
		'url' => $template_fonts_array['Montserrat'],
		'font-family' => '"MontserratBold", sans-serif',
		'font-weight' => 'normal',
		'letter-spacing' => 'normal',
		'size-desktop' => '58px',
		'size-tablet' => '58px',
		'size-mobile' => '36px',
	),
	'Arial Black' => array(
		'url' => null,
		'font-family' => '"Arial Black", "Arial Bold", Gadget, sans-serif',
		'font-weight' => '900',
		'letter-spacing' => 'normal',
		'size-desktop' => '58px',
		'size-tablet' => '58px',
		'size-mobile' => '34px',
	),
	'Open Sans Condensed Bold' => array(
		'url' => $template_fonts_array['Open Sans Condensed'],
		'font-family' => '"OpenSansCondensedBold", sans-serif',
		'font-weight' => 'normal',
		'letter-spacing' => 'normal',
		'size-desktop' => '60px',
		'size-tablet' => '60px',
	),
);
define('TEMPLATE_FONT_STYLES', serialize($template_font_styles_array));



/****************************************************************************
 *
 * START site-wide Config settings here
 *
 ****************************************************************************/

/**
 * Set config values including meta tags, registered custom post types, styles,
 * scripts, and any other statically defined assets that belong in the Config
 * object.
 **/
Config::$custom_post_types = array(
	'Page',
	'Story',
	'Issue',
	'PhotoEssay'
);

Config::$custom_taxonomies = array(
	'Issues'
);


/**
 * Grab array of Issue and Story posts for Config::$theme_settings:
 **/
$issue_covers = get_posts( array(
	'post_type' => 'issue',
	'numberposts' => -1
) );
$issue_cover_array = array();
$issue_cover_first = null;
foreach ( $issue_covers as $cover ) {
	$issue_cover_array[$cover->post_title] = $cover->post_name;
}
$issue_cover_keys = array_keys( $issue_cover_array );
$issue_cover_first = $issue_cover_keys[0];

$story_obj = new Story();
$story_options = $story_obj->get_objects_as_options( array(
	'orderby' => 'date',
	'order' => 'DESC'
) );


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
			'value'       => isset( $theme_options['gw_verify'] ) ? $theme_options['gw_verify'] : null,
		)),
		new TextField(array(
			'name'        => 'Yahoo! Site Explorer',
			'id'          => THEME_OPTIONS_NAME.'[yw_verify]',
			'description' => 'Example: <em>3236dee82aabe064</em>',
			'default'     => null,
			'value'       => isset( $theme_options['yw_verify'] ) ? $theme_options['yw_verify'] : null,
		)),
		new TextField(array(
			'name'        => 'Bing Webmaster Center',
			'id'          => THEME_OPTIONS_NAME.'[bw_verify]',
			'description' => 'Example: <em>12C1203B5086AECE94EB3A3D9830B2E</em>',
			'default'     => null,
			'value'       => isset( $theme_options['bw_verify'] ) ? $theme_options['bw_verify'] : null,
		)),
		new TextField(array(
			'name'        => 'Google Analytics 4 Account',
			'id'          => THEME_OPTIONS_NAME.'[ga4_account]',
			'description' => 'Example: <em>G-123EFG456K. Leave blank for development. Takes precedence over Google Analytics Account (UA).',
			'default'     => null,
			'value'       => isset( $theme_options['ga4_account'] ) ? $theme_options['ga4_account'] : null,
		)),
		new TextField(array(
			'name'        => 'Google Analytics Account',
			'id'          => THEME_OPTIONS_NAME.'[ga_account]',
			'description' => 'Example: <em>UA-9876543-21</em>. Leave blank for development.',
			'default'     => null,
			'value'       => isset( $theme_options['ga_account'] ) ? $theme_options['ga_account'] : null,
		)),
		new TextField(array(
			'name'        => 'Chartbeat UID',
			'id'          => THEME_OPTIONS_NAME.'[cb_uid]',
			'description' => 'Example: <em>1842</em>',
			'default'     => null,
			'value'       => isset( $theme_options['cb_uid'] ) ? $theme_options['cb_uid'] : null,
		)),
		new TextField(array(
			'name'        => 'Chartbeat Domain',
			'id'          => THEME_OPTIONS_NAME.'[cb_domain]',
			'description' => 'Example: <em>some.domain.com</em>',
			'default'     => null,
			'value'       => isset( $theme_options['cb_domain'] ) ? $theme_options['cb_domain'] : null,
		)),
		new TextField(array(
			'name'        => 'Google Tag Manager ID',
			'id'          => THEME_OPTIONS_NAME.'[gtm_id]',
			'description' => 'Example: <em>GTM-XXXXXX</em>',
			'default'     => null,
			'value'       => isset( $theme_options['gtm_id'] ) ? $theme_options['gtm_id'] : null,
		)),
	),
	'Front Page' => array(
		new SelectField(array(
			'name'        => 'Featured Story #1',
			'id'          => THEME_OPTIONS_NAME.'[front_page_featured_story_1]',
			'description' => 'The top featured story to display on the front page.',
			'choices'     => $story_options,
			'default'     => '',
			'value'       => isset( $theme_options['front_page_featured_story_1'] ) ? $theme_options['front_page_featured_story_1'] : null,
		)),
		new SelectField(array(
			'name'        => 'Featured Story #2',
			'id'          => THEME_OPTIONS_NAME.'[front_page_featured_story_2]',
			'description' => 'First of four featured stories underneath the top story on the front page.',
			'choices'     => $story_options,
			'default'     => '',
			'value'       => isset( $theme_options['front_page_featured_story_2'] ) ? $theme_options['front_page_featured_story_2'] : null,
		)),
		new SelectField(array(
			'name'        => 'Featured Story #3',
			'id'          => THEME_OPTIONS_NAME.'[front_page_featured_story_3]',
			'description' => 'Second of four featured stories underneath the top story on the front page.',
			'choices'     => $story_options,
			'default'     => '',
			'value'       => isset( $theme_options['front_page_featured_story_3'] ) ? $theme_options['front_page_featured_story_3'] : null,
		)),
		new SelectField(array(
			'name'        => 'Featured Story #4',
			'id'          => THEME_OPTIONS_NAME.'[front_page_featured_story_4]',
			'description' => 'Third of four featured stories underneath the top story on the front page.',
			'choices'     => $story_options,
			'default'     => '',
			'value'       => isset( $theme_options['front_page_featured_story_4'] ) ? $theme_options['front_page_featured_story_4'] : null,
		)),
		new SelectField(array(
			'name'        => 'Featured Story #5',
			'id'          => THEME_OPTIONS_NAME.'[front_page_featured_story_5]',
			'description' => 'Last of four featured stories underneath the top story on the front page.',
			'choices'     => $story_options,
			'default'     => '',
			'value'       => isset( $theme_options['front_page_featured_story_5'] ) ? $theme_options['front_page_featured_story_5'] : null,
		)),
		new SelectField(array(
			'name'        => 'Featured Gallery',
			'id'          => THEME_OPTIONS_NAME.'[front_page_featured_gallery_1]',
			'description' => 'Featured gallery displayed next to events on the front page.',
			'choices'     => $story_options,
			'default'     => '',
			'value'       => isset( $theme_options['front_page_featured_gallery_1'] ) ? $theme_options['front_page_featured_gallery_1'] : null,
		)),
		new TextareaField(array(
			'name'        => 'Banner Ad Contents',
			'id'          => THEME_OPTIONS_NAME.'[front_page_ad_contents]',
			'description' => 'HTML for banner ad content to be displayed underneath list of issue stories on the front page. Accepts shortcode content.',
			'value'       => isset( $theme_options['front_page_ad_contents'] ) ? $theme_options['front_page_ad_contents'] : null,
			'default'     => '',
		)),
		new TextField(array(
			'name'        => 'UCF Today Story Feed URL',
			'id'          => THEME_OPTIONS_NAME.'[front_page_today_feed_url]',
			'description' => 'URL to the RSS feed for stories to display in "The Feed" section of the front page.',
			'default'     => 'http://today.ucf.edu/feed/',
			'value'       => isset( $theme_options['front_page_today_feed_url'] ) ? $theme_options['front_page_today_feed_url'] : null,
		)),
		new TextField(array(
			'name'        => 'UCF Events JSON Feed URL',
			'id'          => THEME_OPTIONS_NAME.'[front_page_events_feed_url]',
			'description' => 'URL to the JSON feed for events to display in the Events section of the front page.',
			'default'     => 'http://events.ucf.edu/upcoming/feed.json',
			'value'       => isset( $theme_options['front_page_events_feed_url'] ) ? $theme_options['front_page_events_feed_url'] : null,
		))
	),
	'Search' => array(
		new RadioField(array(
			'name'        => 'Enable Google Search',
			'id'          => THEME_OPTIONS_NAME.'[enable_google]',
			'description' => 'Enable to use the google search appliance to power the search functionality.',
			'default'     => 'On',
			'choices'     => array(
				'On'  => 1,
				'Off' => 0,
			),
			'value'       => isset( $theme_options['enable_google'] ) ? $theme_options['enable_google'] : null,
	    )),
		new TextField(array(
			'name'        => 'Search Domain',
			'id'          => THEME_OPTIONS_NAME.'[search_domain]',
			'description' => 'Domain to use for the built-in google search.  Useful for development or if the site needs to search a domain other than the one it occupies. Example: <em>some.domain.com</em>',
			'default'     => null,
			'value'       => isset( $theme_options['search_domain'] ) ? $theme_options['search_domain'] : null,
		)),
		new TextField(array(
			'name'        => 'Search Results Per Page',
			'id'          => THEME_OPTIONS_NAME.'[search_per_page]',
			'description' => 'Number of search results to show per page of results',
			'default'     => 10,
			'value'       => isset( $theme_options['search_per_page'] ) ? $theme_options['search_per_page'] : null,
		)),
	),
	'Issues' => array(
		new SelectField(array(
			'name'        => 'Current Issue',
			'id'          => THEME_OPTIONS_NAME.'[current_issue_cover]',
			'description' => 'Specify the current active issue. If a custom front page layout is enabled, this issue\'s stories will be used on the front page where the list of 12 stories in the issue is displayed.<br><br>The issue cover will be used as the front page if a custom front page is disabled (Settings > Reading > Front page displays is set to "Your latest posts").',
			'choices'     => $issue_cover_array,
			'default'     => $issue_cover_first,
			'value'       => isset( $theme_options['current_issue_cover'] ) ? $theme_options['current_issue_cover'] : null,
		)),
	),
	'Contact Information' => array(
		new TextField(array(
			'name' => 'Organization Name',
			'id' => THEME_OPTIONS_NAME.'[org_name]',
			'description' => 'The name for your organization, used when displaying your organization\'s address.',
			'value' => isset( $theme_options['org_name'] ) ? $theme_options['org_name'] : null,
			'default'	=> 'University of Central Florida',
		)),
		new TextareaField(array(
			'name' => 'Organization Address',
			'id' => THEME_OPTIONS_NAME.'[org_address]',
			'description' => 'The address for your organization.',
			'value' => isset( $theme_options['org_address'] ) ? $theme_options['org_address'] : null,
			'default'	=> '4000 Central Florida Blvd.
Orlando, FL 32816',
		)),
	),
	'Web Fonts' => array(
		new TextField(array(
			'name'        => 'Cloud.Typography CSS Key URL',
			'id'          => THEME_OPTIONS_NAME.'[cloud_font_key]',
			'description' => 'The CSS Key provided by Cloud.Typography for this project.  <strong>Only include the value in the "href" portion of the link
								tag provided; e.g. "//cloud.typography.com/000000/000000/css/fonts.css".</strong><br/><br/>NOTE: Make sure the Cloud.Typography
								project has been configured to deliver fonts to this site\'s domain.<br/>
								See the <a target="_blank" href="http://www.typography.com/cloud/user-guide/managing-domains">Cloud.Typography docs on managing domains</a> for more info.',
			'default'     => '//cloud.typography.com/730568/702404/css/fonts.css', /* CSS Key relative to PROD project */
			'value'       => isset( $theme_options['cloud_font_key'] ) ? $theme_options['cloud_font_key'] : null,
		))
	),
	'News Feeds' => array(
		new TextField(array(
			'name'        => 'UCF Today API Base URL',
			'id'          => THEME_OPTIONS_NAME.'[news_api_base_url]',
			'description' => 'The base URL for the UCF Today wp-json feed.',
			'default'     => 'https://www.ucf.edu/news/wp-json/wp/v2/posts/',
			'value'       => isset( $theme_options['news_api_base_url'] ) ? $theme_options['news_api_base_url'] : null,
		)),
		new TextField(array(
			'name'        => 'Default Related Story Count',
			'id'          => THEME_OPTIONS_NAME.'[related_stories_count]',
			'description' => 'The max number of stories to list in the related stories section.',
			'default'     => 3,
			'value'       => isset( $theme_options['related_stories_count'] ) ? $theme_options['related_stories_count'] : null
		)),
	),
	'Developers' => array(
		new RadioField(array(
			'name'        => 'Enable Developer Mode',
			'id'          => THEME_OPTIONS_NAME.'[dev_mode]',
			'description' => 'Turn on Developer Mode, which enables direct editing from the theme\'s dev/ directory. <strong>Never enable this
								setting in a production environment.</strong>',
			'default'     => 'Off',
			'choices'     => array(
				'On'  => 1,
				'Off' => 0,
			),
			'value'       => isset( $theme_options['dev_mode'] ) ? $theme_options['dev_mode'] : null,
	    )),
	),
);

Config::$links = array(
	// NOTE: canonical is handled in functions/base.php
	array( 'rel' => 'alternate', 'type' => 'application/rss+xml', 'href' => get_bloginfo('rss_url'), )
);
if ( !has_site_icon() ) {
	array_push( Config::$links, array( 'rel' => 'shortcut icon', 'href' => THEME_IMG_URL.'/favicon.ico' ) );
}

Config::$styles = array(
	array('name' => 'admin-css', 'src' => THEME_CSS_URL.'/admin.css', 'admin' => True),
	array('name' => 'font-icomoon', 'src' => THEME_FONT_URL.'/icomoon/style.css'),
	array('name' => 'font-montserrat', 'src' => $custom_available_fonts_array['Montserrat']),
	array('name' => 'font-aleo', 'src' => $custom_available_fonts_array['Aleo']),
	array('name' => 'font-poppins', 'src' => $custom_available_fonts_array['Poppins']),
);

foreach ($template_fonts_array as $key => $val) {
	$name = 'admin-font-'.sanitize_title($key);
	array_push(Config::$styles, array('name' => $name, 'admin' => True, 'src' => $val));
}

if (!empty($theme_options['cloud_font_key'])) {
	array_push(Config::$styles, array('name' => 'font-cloudtypography', 'src' => $theme_options['cloud_font_key']));
	array_push(Config::$styles, array('name' => 'font-cloudtypography-admin', 'admin' => True, 'src' => $theme_options['cloud_font_key']));
}

Config::$scripts = array(
    array('admin' => True, 'src' => THEME_COMPONENTS_URL.'/wysihtml5-0.3.0.min.js',),
	array('admin' => True, 'src' => THEME_JS_URL.'/admin.js', 'deps' => array( 'jquery', 'iris' )),
	THEME_COMPONENTS_URL.'/jquery.cookie.js',
	array( 'name' => 'ucfhb-script', 'src' => '//universityheader.ucf.edu/bar/js/university-header.js?use-1200-breakpoint=1', ),
	array('name' => 'placeholders', 'src' => THEME_COMPONENTS_URL.'/placeholders.js',),
);

Config::$metas = array(
	array( 'charset' => 'utf-8', ),
	array( 'http-equiv' => 'X-UA-Compatible', 'content' => 'IE=Edge' ),
	array( 'name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0' ),
);
if ( isset( $theme_options['gw_verify'] ) && $theme_options['gw_verify'] ) {
	Config::$metas[] = array(
		'name'    => 'google-site-verification',
		'content' => htmlentities( $theme_options['gw_verify'] ),
	);
}


/**
 * Responsible for running code that needs to be executed as wordpress is
 * initializing. Good place to register widgets, image sizes, and menus.
 *
 * @return void
 * @author Jared Lang
 **/
function __init__(){
	add_theme_support( 'menus' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_image_size( 'homepage', 620 );
	add_image_size( 'frontpage-story-thumbnail', 263, 175, true );  // 3x2
	add_image_size( 'frontpage-featured-gallery-thumbnail', 515, 390, true ); // almost 4x3
	add_image_size( 'frontpage-featured-gallery-thumbnail-3x2', 515, 343, true ); // 3x2
	add_image_size( 'single-post-thumbnail', 220, 230, true ); // almost 1x1
	add_image_size( 'single-post-thumbnail-3x2', 220, 147, true ); // 3x2
	add_image_size( 'single-post-thumbnail-300x200', 300, 200, true ); // 3x2
	add_image_size( 'issue-thumbnail', 190, 248 ); // almost 10x13 (but does not crop)
	add_image_size( 'issue-cover-feature', 768, 432, true ); // 16x9
	add_image_size( 'issue-cover-feature-3x2', 768, 512, true ); // 3x2
	add_image_size( 'story-featured-image', 1200, 800, true ); // 3x2
	register_nav_menu( 'footer-menu', __( 'Footer Menu' ) );
	register_nav_menu( 'footer-middle-menu', __( 'Footer Middle Menu' ) );
}
add_action( 'after_setup_theme', '__init__' );
add_action( 'init', 'set_defaults_for_options', 4 );


/**
 * Register frontend scripts and stylesheets.
 **/
function enqueue_frontend_theme_assets() {
	wp_deregister_script( 'l10n' );

	// Register Config css, js
	foreach( Config::$styles as $style ) {
		if ( !isset( $style['admin'] ) || ( isset( $style['admin'] ) && $style['admin'] !== true ) ) {
			Config::add_css( $style );
		}
	}
	foreach( Config::$scripts as $script ) {
		if ( !isset( $script['admin'] ) || ( isset( $script['admin'] ) && $script['admin'] !== true ) ) {
			Config::add_script( $script );
		}
	}

	// NOTE: jquery re-registering in the document head and other post-specific
	// asset registration is done in version-specific /functions/config.php files.
}
add_action( 'wp_enqueue_scripts', 'enqueue_frontend_theme_assets' );


/**
 * Register backend scripts and stylesheets.
 **/
function enqueue_backend_theme_assets() {
	// Register Config css, js
	foreach( Config::$styles as $style ) {
		if ( isset( $style['admin'] ) && $style['admin'] == true ) {
			Config::add_css( $style );
		}
	}
	foreach( Config::$scripts as $script ) {
		if ( isset( $script['admin'] ) && $script['admin'] == true ) {
			Config::add_script( $script );
		}
	}
}
add_action( 'admin_enqueue_scripts', 'enqueue_backend_theme_assets' );


/**
 * Add support for WordPress site icons.
 */
function site_icon_support() {
	wp_site_icon();
}
add_action( 'wp_head', 'site_icon_support' );


/**
 * Remove paragraph tag from excerpts
 * */
remove_filter( 'the_excerpt', 'wpautop' );


/**
 * Adds a custom ACF WYSIWYG toolbar called 'Inline Text' that only includes
 * simple inline text formatting tools and link insertion/deletion.
 * Ported over from Today-Child-Theme (with the adjustment of
 * removing 'link' and 'unlink').
 *
 * @since 6.0.0
 * @author Jo Dickson
 * @param array $toolbars Array of toolbar information from ACF
 * @return array
 */
function pegasus_acf_inline_text_toolbar( $toolbars ) {
	$toolbars['Inline Text'] = array();
	$toolbars['Inline Text'][1] = array( 'bold', 'italic', 'undo', 'redo' );

	return $toolbars;
}

add_filter( 'acf/fields/wysiwyg/toolbars', 'pegasus_acf_inline_text_toolbar' );
