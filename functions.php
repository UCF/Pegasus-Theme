<?php
/**
 * READ ME, PLEASE:
 *
 * Various functions for this theme MUST execute in a specific order and
 * depend on WordPress hooks to be executed with specific priority values.
 * These functions hook into 'init' with priority values set.
 * This order should NOT be modified unless you want bad things to happen.
 *
 * 1) Register Taxonomies
 * 2) Register CPT's
 * 3) Determine the current requested post's relevant version
 * 4) Require version-specific functions/config.php and shortcodes.php files
 * 5) Register Config::$styles, Config::$scripts (MUST occur after version-
 *    specific functions/config.php is loaded so that extra styles, scripts can
 *    be appended per version)
 **/


require_once( 'functions/base.php' );    # Base theme functions
require_once( 'functions/feeds.php' );   # Feed-related functions
require_once( 'functions/admin.php' );   # Admin/login functions
require_once( 'custom-taxonomies.php' ); # Where taxonomies are defined
require_once( 'custom-post-types.php' ); # Where post types are defined
require_once( 'functions/config.php' );  # Where site-level configuration settings are defined


/****************************************************************************
 *
 * START version configuration, backward compatibility functions here
 *
 ****************************************************************************/

/**
 * Function that determines which version should be considered active, based
 * on the passed in post object or what post data is being loaded.
 **/
function get_relevant_version( $the_post=null ) {
	if ( !$the_post ) {
		$request_uri = untrailingslashit( $_SERVER['REQUEST_URI'] );

		// If the home page has been requested, always return the current issue
		if ( $request_uri === get_site_url( get_current_blog_id(), '', 'relative' ) ) {
			$the_post = get_current_issue();
		}
		else {
			global $post;

			// If global $post hasn't been set yet, try fishing the requested url for
			// post information
			if ( !$post ) {
				$url_query_array = array();
				$url_query = parse_url( $request_uri, PHP_URL_QUERY );
				parse_str( $url_query, $url_query_array );

				// Get the relative path of the url, accounting for potential
				// subdirectory WordPress installs
				$url_path = str_replace( get_site_url( get_current_blog_id(), '', 'relative' ), '', $request_uri );
				if ( substr( $url_path, 0, 1 ) === '?' ) {
					$url_path = '/' . $url_path; // If path only contains a query str, make sure it is prepended with /
				}

				// Check if url structure follows WP's default permalink pattern
				// (<root url>/?params).  Post ID is stored in 'p' param.
				// Should catch draft post previews here.
				if ( $url_path === '/?' . $url_query && isset( $url_query_array['p'] ) ) {
					$the_post = get_post( $url_query_array['p'] );
				}
				else {
					// URL follows custom permalink structure.  Try using
					// get_page_by_path() per each post type we want to enforce
					// version-specific assets on.

					// Make sure $url_path has no query params before passing to get_page_by_path()
					$url_path = explode( '/?', $url_path );
					$url_path = $url_path[0];

					// Remove '/story' and '/issue' url prefixes (get_page_by_path() will fail if
					// URLs have these prefixes)
					$url_path = preg_replace( '/^\/story\/|\/issue\/|\/photo\_essay\//', '/', $url_path );

					$post_issue = get_page_by_path( $url_path , OBJECT, array( 'issue' ) );
					$post_story = get_page_by_path( $url_path , OBJECT, array( 'story' ) );

					if ( $post_issue ) {
						$the_post = $post_issue;
					}
					else if ( $post_story ) {
						$the_post = $post_story;
					}
					else {
						// The requested content isn't a story or issue.  Set $the_post to
						// null here so that get_relevant_issue() will return a fallback value
						// (the latest issue).
						$the_post = null;
					}
				}
			}
			else {
				$the_post = $post;
			}
		}
	}

	$relevant_issue = get_relevant_issue( $the_post );
	$relevant_version = get_post_meta( $relevant_issue->ID, 'issue_version', true );
	if ( empty( $relevant_version ) ) {
		$relevant_version = LATEST_VERSION;
	}

	return intval( $relevant_version );
}


/**
 * Returns a relative path to a file by version.
 **/
function get_version_file_path( $filename, $version=null ) {
	if ( !$version ) {
		$version = get_relevant_version();
	}
	return VERSIONS_PATH . 'v' . $version . '/' . $filename;
}

/**
 * Based on the current relevant version, require necessary files.
 **/
function setup_version_files() {
	define( 'THE_POST_VERSION', get_relevant_version() ); // The version for the story/issue loaded in a given request, or the latest version if a story or issue is not available
	define( 'THE_POST_VERSION_DIR', get_stylesheet_directory() . '/' . VERSIONS_PATH . 'v' . THE_POST_VERSION );
	define( 'THE_POST_VERSION_URL', get_stylesheet_directory_uri() . '/' . VERSIONS_PATH . 'v' . THE_POST_VERSION );

	require_once( get_version_file_path( 'functions/config.php' ) );  # Where version-level configuration settings are defined
	require_once( get_version_file_path( 'shortcodes.php' ) );        # Per version shortcodes
}
add_action( 'init', 'setup_version_files', 3 );


/**
 * Loads version-specific CPT templates instead of templates from the theme's
 * root directory.
 *
 * Note: Pages and Posts should always use templates from the root directory
 * (they are not modified per-version).
 **/
function by_version_template( $template ) {
	global $post;

	if ( in_array( $post->post_type, array( 'story', 'issue', 'photo_essay' ) ) ) {
		$new_template = locate_template( array( get_version_file_path( 'single-' . $post->post_type . '.php' ) ) );
	}

	if ( !empty( $new_template ) ) {
		return $new_template;
	}
	return $template;
}
add_filter( 'template_include', 'by_version_template', 99 );


/**
 * Loads version-specific header template.  Should be used in place of
 * get_header() for this theme.
 *
 * Note: this (and get_version_footer()) cannot hook into get_header/get_footer
 * without requiring that a header.php/footer.php file exists in the theme
 * root, so we opt to use a separate function instead to avoid excessive file
 * includes.
 **/
function get_version_header( $template_name='' ) {
	if ( $template_name ) {
		$template_name = '-' . $template_name;
	}

	$new_template = locate_template( array( get_version_file_path( 'header' . $template_name . '.php' ) ) );
	if ( !empty( $new_template ) ) {
		return load_template( $new_template );
	}
}


/**
 * Loads version-specific footer template.  Should be used in place of
 * get_footer() for this theme.
 **/
function get_version_footer( $template_name='' ) {
	if ( $template_name ) {
		$template_name = '-' . $template_name;
	}

	$new_template = locate_template( array( get_version_file_path( 'footer' . $template_name . '.php' ) ) );
	if ( !empty( $new_template ) ) {
		return load_template( $new_template );
	}
}


/**
 * Loads front-page.php or home.php using the relevant version's template.
 * Falls back to loading root index.php if no templates are found.
 **/
function get_version_front_page() {
	$new_template_front = locate_template( array( get_version_file_path( 'front-page.php' ) ) );
	$new_template_home = locate_template( array( get_version_file_path( 'home.php' ) ) );

	if ( !empty( $new_template_front ) ) {
		return load_template( $new_template_front );
	}
	elseif ( !empty( $new_template_home ) ) {
		return load_template( $new_template_home );
	}
	else {
		// something is very wrong--fall back to root index.php
		return load_template( get_stylesheet_directory() . '/index.php' );
	}
}


/**
 * Check/create hidden theme options which store flags that tell us if methods
 * for maintaining backward compatibility have already been performed.
 * Functions with theme option flags below should only run once, assuming they
 * ran successfully the first time.
 **/
function check_backward_compatibility() {
	// Attempts to set versions on Issue posts, based on Issue slugs set
	// in V1_ISSUES and V2_ISSUES.
	if ( get_option( 'theme_bc_versions_set' ) == false ) {
		$success = set_initial_issue_versions();
		if ( $success == true ) {
			add_option( 'theme_bc_versions_set', true );
		}
	}

	// Set a 'story_template' meta field value for stories with an issue slug
	// in the V1_ISSUES constant so that they are 'custom' if they have no
	// value.
	if ( get_option( 'theme_bc_v1_templates_set' ) == false ) {
		$success = set_templates_for_v1();
		if ( $success == true ) {
			add_option( 'theme_bc_v1_templates_set', true );
		}
	}
}
add_action( 'init', 'check_backward_compatibility' );


/**
 * Set version value on Issues in V1_ISSUES and V2_ISSUES.
 * Returns true on success, false on failure.
 **/
function set_initial_issue_versions() {
	$issues = get_posts( array(
		'numberposts' => -1,
		'post_type' => 'issue',
		'orderby' => 'post_date',
		'order' => 'DESC',
	) );

	if ( !$issues || !is_array( $issues ) ) {
		return false;
	}

	foreach ( $issues as $issue ) {
		$success = null;

		if ( in_array( $issue->post_name, unserialize( V1_ISSUES ) ) ) {
			$success = update_post_meta( $issue->ID, 'issue_version', 1 );
			if ( $success !== true ) {
				return false;
			}
			// Trigger a post update to make sure VDP ban is run
			wp_update_post( $issue );
		}
		else if ( in_array( $issue->post_name, unserialize( V2_ISSUES ) ) ) {
			$success = update_post_meta( $issue->ID, 'issue_version', 2 );
			if ( $success !== true ) {
				return false;
			}
			// Trigger a post update to make sure VDP ban is run
			wp_update_post( $issue );
		}
	}

	return true;
}


/**
 * Set a 'story_template' meta field value for stories with an issue
 * slug in the V1_ISSUES constant so that they are 'custom'
 * if they have no value.  Also updates v1 'issue_template' values.
 **/
function set_templates_for_v1() {
	$issue_slugs = unserialize( V1_ISSUES );
	$stories = get_posts( array(
		'numberposts' => -1,
		'post_type' => 'story',
		'tax_query' => array(
			array(
				'taxonomy' => 'issues',
				'field' => 'slug',
				'terms' => $issue_slugs
			)
		)
	) );
	$issues = array();
	foreach ( $issue_slugs as $slug ) {
		$issue = get_posts( array(
			'numberposts' => 1,
			'post_type' => 'issue',
			'name' => $slug
		) );
		if ( $issue[0] ) {
			$issue = $issue[0];
			$issues[] = $issue->ID;
		}
	}

	if ( !$stories || !is_array( $stories ) || !$issues || !is_array( $issues ) ) {
		return false;
	}

	foreach ( $stories as $story ) {
		if ( get_post_meta( $story->ID, 'story_template', true ) !== 'custom' ) {
			$success = update_post_meta( $story->ID, 'story_template', 'custom' );
			if ( $success !== true ) {
				return false;
			}
			// Trigger a post update to make sure VDP ban is run
			wp_update_post( $story );
		}
	}
	foreach ( $issues as $issue_id ) {
		if ( get_post_meta( $issue_id, 'issue_template', true ) !== 'custom' ) {
			$success = update_post_meta( $issue_id, 'issue_template', 'custom' );
			if ( $success !== true ) {
				return false;
			}
			// Trigger a post update to make sure VDP ban is run
			wp_update_post( get_post( $issue_id ) );
		}
	}

	return true;
}



/****************************************************************************
 *
 * START site-level functions here
 *
 ****************************************************************************/

/*
 * Returns current issue post based on the Current Issue Cover
 * value in Theme Options
 */
function no_current_issue_notice() {
	echo '<div class="error"><p><strong>ERROR:</strong> Could not find current issue cover.  <strong>The home page and other portions of the website <u>will not display correctly</u> until this is fixed.</strong>  Make sure the "Current Issue Cover" Theme Options value is set to a <strong>published</strong> Issue post.</p></div>';
}

function get_current_issue() {
	$posts = get_posts( array(
		'post_type' => 'issue',
		'name' => get_theme_option( 'current_issue_cover' )
	) );

	if( count( $posts ) == 0 ) {
		// Couldn't find an issue with name saved in current_issue_cover
		// theme option
		add_action( 'admin_notices', 'no_current_issue_notice' );
		return false;
	} else {
		return $posts[0];
	}
}


/*
 * Retrieve a list of the current issue's stories
 */
function get_current_issue_stories($exclude=array(), $limit=-1) {
	$current_issue = get_current_issue();

	if($current_issue === False) {
		return False;
	} else {
		return get_issue_stories($current_issue, array('exclude'=>$exclude, 'numberposts'=>$limit));
	}
}


/*
 * Returns a relevant issue post object depending on the page being viewed.
 * i.e., if the $post obj passed is the front page or subpage, get the current issue;
 * otherwise, get the current story issue
 */
function get_relevant_issue( $post ) {
	$issue = null;

	if ( $post && $post->post_type == 'story' && !is_404() && !is_search() ) {
		$issue = get_story_issue( $post );
	}
	else if ( $post && $post->post_type == 'issue' && !is_404() && !is_search() ) {
		$issue = $post;
	}
	else if ( $post && $post->post_type == 'photo_essay' && !is_404() && !is_search() ) {
		$issue = get_story_issue( $post );
	}

	// Fallback, or if get_story_issue() returned false
	if ( !$issue ) {
		$issue = get_current_issue();
	}

	return $issue;
}


/*
 * Returns featured image URL of a specified post ID
 */
function get_featured_image_url($id, $size=null) {
	$size = !$size ? 'single-post-thumbnail' : $size;
	$url = '';
	if(has_post_thumbnail($id)
		&& ($thumb_id = get_post_thumbnail_id($id)) !== False
		&& ($image = wp_get_attachment_image_src($thumb_id, $size)) !== False) {
			return $image[0];
	}
	return $url;
}


/*
 * Returns a theme option value or NULL if it doesn't exist
 */
function get_theme_option($key) {
	global $theme_options;
	return isset($theme_options[$key]) ? $theme_options[$key] : NULL;
}


/*
 * Is the iPad app deployed or not
 */
function ipad_deployed() {
	$ipad_app_url = get_theme_option('ipad_app_url');
	return (is_null($ipad_app_url) || $ipad_app_url == '') ? False : True;
}


/*
 * Modify the permalinks for the Issue post type to the following form:
 * http://ucf.edu/pegasus/<issue slug>/
 */
function modify_issue_permalinks($url, $post) {
	if($post->post_type == 'issue') {
		if ($post->post_status == 'publish') {
			return get_bloginfo('url').'/'.$post->post_name.'/';
		}
		else {
			// Handle drafts/previews appropriately
			return get_bloginfo('url').'/?p='.$post->ID.'&post_type=issue';
		}
	}
	return $url;
}
add_filter('post_type_link', 'modify_issue_permalinks', 10, 2);


/*
 * Modify the permalinks for the Story post type to the following form:
 * http://ucf.edu/pegasus/<story slug>/
 */
function modify_story_permalinks($url, $post) {
	if($post->post_type == 'story') {
		if ($post->post_status == 'publish') {
			return get_bloginfo('url').'/'.$post->post_name.'/';
		}
		else {
			// Handle drafts/previews appropriately
			return get_bloginfo('url').'/?p='.$post->ID.'&post_type=story';
		}
	}
	return $url;
}
add_filter('post_type_link', 'modify_story_permalinks', 10, 2);


/*
 * Add a rewrite rule to handle the new Issue post type permalink structure
 */
function cpt_slug_init() {
	$issue_slugs = array_map( function( $i ) {
		return preg_quote($i->post_name);
	},
	get_posts( array(
		'post_type' => 'issue',
		'numberposts' => -1
	)));

	$story_slugs = array_map( function( $i ) {
			return preg_quote($i->post_name);
		},
		get_posts( array(
			'post_type' => 'story',
			'numberposts' => -1
		)));

	add_rewrite_rule('^('.implode('|', $issue_slugs).')$', 'index.php?issue=$matches[1]', 'top');
	add_rewrite_rule('^('.implode('|', $story_slugs).')$', 'index.php?story=$matches[1]', 'top');
	flush_rewrite_rules(false);
}
add_action('init', 'cpt_slug_init');


/*
 * Add featured images to RSS feeds as an <enclosure> node.
 */
function add_post_thumbnail_node() {
	global $post;

	if(has_post_thumbnail($post->ID)) {
		$thumbnail_id = get_post_thumbnail_id($post->ID);
		$url = get_featured_image_url($post->ID);

		echo('<enclosure url="'.$url.'" length="'.filesize(get_attached_file($thumbnail_id)).'" type="'.get_post_mime_type($thumbnail_id).'" />');
	}
}
add_action('rss2_item', 'add_post_thumbnail_node');


/*
 * Remove <content:encoded> node if it is enabled,
 * Update limit on the number of posts displayed at once in a feed,
 * and replace <description> node with the story's story_subtitle
 * meta field content.
 */
update_option('rss_use_excerpt', 1);
update_option('posts_per_rss', 50);

function story_excerpt() {
	global $post;
	if ($post->post_type == 'story') {
		if (get_post_meta($post->ID, 'story_description', TRUE)) {
			return get_post_meta($post->ID, 'story_description', TRUE);
		}
		else {
			return get_post_meta($post->ID, 'story_subtitle', TRUE);
		}
	}
	else { return the_excerpt(); }
}
add_filter('the_excerpt_rss', 'story_excerpt');


/**
 * Get the URL of the feed for an issue.
 * Accepts either an issue post object or an issue taxonomy term object.
 **/
function get_issue_feed_url($object) {
	$slug = null;
	$url = null;

	if ($object->post_type == 'issue') {
		$slug = $object->post_name;
	}
	else if ($object->taxonomy == 'issues') {
		$slug = $object->slug;
	}

	if ($slug !== null) {
		$url = home_url('/issues/'.$slug.'/feed/?post_type=story');
	}

	return $url;
}


/*
 * Enqueue Issue or Story post type specific scripts
 */
function enqueue_issue_story_scripts() {
	global $post;

	if ( !is_404() && !is_search() ) {
		// 1. add issue cover script(s)
		if( $post->post_type == 'issue' || is_home() ) {

			// issue-wide
			$dev_issue_directory = get_post_meta( $post->ID, 'issue_dev_issue_asset_directory', TRUE );
			$issue_javascript_url = Issue::get_issue_javascript_url( $post );

			if ( !empty( $issue_javascript_url ) ) {
				Config::add_script( $issue_javascript_url );
			}
			elseif ( DEV_MODE == 1 && !empty( $dev_issue_directory ) ) {
				$dev_issue_javascript_url = THEME_DEV_URL.'/'.$dev_issue_directory.$post->post_name.'.js';

				if ( curl_exists( $dev_issue_javascript_url ) ) {
					Config::add_script( $dev_issue_javascript_url );
				}
			}

			// issue cover specific
			$dev_issue_home_directory = get_post_meta( $post->ID, 'issue_dev_home_asset_directory', TRUE );
			$home_javascript_url = Issue::get_home_javascript_url( $post );

			if ( !empty( $home_javascript_url ) ) {
				Config::add_script( $home_javascript_url );
			}
			elseif ( DEV_MODE == 1 && !empty( $dev_issue_home_directory ) ) {
				$dev_home_javascript_url = THEME_DEV_URL.'/'.$dev_issue_home_directory.'issue-cover.js';

				if ( curl_exists( $dev_home_javascript_url ) ) {
					Config::add_script( $dev_home_javascript_url );
				}
			}

		// 2. add story script(s)
		} else if( $post->post_type == 'story' ) {
			$story_issue = get_story_issue( $post );

			// issue-wide
			$issue_javascript_url = null;
			$dev_issue_directory = null;
			if ( $story_issue ) {
				$issue_javascript_url = Issue::get_issue_javascript_url( $story_issue );
				$dev_issue_directory = get_post_meta( $story_issue->ID, 'issue_dev_issue_asset_directory', TRUE );
			}

			if( $story_issue !== False && !empty( $issue_javascript_url ) ) {
				Config::add_script( $issue_javascript_url );
			}
			elseif (
				$story_issue !== False &&
				DEV_MODE == 1 &&
				!empty( $dev_issue_directory ) )
				{
					$dev_issue_javascript_url = THEME_DEV_URL.'/'.$dev_issue_directory.$story_issue->post_name.'.js';
					if ( curl_exists( $dev_issue_javascript_url ) ) {
						Config::add_script( $dev_issue_javascript_url );
					}
			}

			// story specific
			$javascript_url = Story::get_javascript_url( $post );
			$dev_story_directory = get_post_meta( $post->ID, 'story_dev_directory', TRUE );

			if ( !empty( $javascript_url ) ) {
				Config::add_script( $javascript_url );
			}
			elseif (
				DEV_MODE == 1 &&
				!empty( $dev_story_directory ) )
				{
					$dev_story_javascript_url = THEME_DEV_URL.'/'.$dev_story_directory.$post->post_name.'.js';
					if ( curl_exists( $dev_story_javascript_url ) ) {
						Config::add_script( $dev_story_javascript_url );
					}
			}

		}
	}
}
add_action('wp_enqueue_scripts', 'enqueue_issue_story_scripts', 10);


/*
 * Get the issue post associated with a story
 */
function get_story_issue( $story ) {
	$issue_terms = wp_get_object_terms( $story->ID, 'issues' );
	// Make sure to grab issues that may not be published, but aren't trash
	$issue_posts = get_posts( array(
		'post_type' => 'issue',
		'numberposts' => -1,
		'post_status' => array(
			'publish',
			'pending',
			'draft',
			'auto-draft',
			'future',
			'private'
		)
	) );

	if ( $issue_terms ) {
		foreach( $issue_terms as $term ) {
			$post_slug = $term->slug;
			if ( $issue_posts ) {
				foreach( $issue_posts as $issue ) {
					if( $post_slug == $issue->post_name ) {
						return $issue;
					}
				}
			}
		}
	}
	return false;
}


/*
 * Get the stories associated with an issue
 */
function get_issue_stories( $issue, $options=array() ) {
	if ( $issue ) {
		$issue_term_slug = $issue->post_name;

		$default_options = array(
			'post_type'   => 'story',
			'exclude'     => array(),
			'numberposts' => -1,
			'orderby'     => 'rand',
			'tax_query'   => array(
				array(
					'taxonomy' => 'issues',
					'field'    => 'slug',
					'terms'    => $issue_term_slug
				)
			),
		);
		$options = array_merge($default_options, $options);

		return get_posts($options);
	}
	return null;
}


/*
 * Check to see if some arbitary file exists (does not return a 404/500)
 * http://stackoverflow.com/questions/14699941/php-curl-check-for-file-existence-before-downloading
 *
 * @return bool
 */
function curl_exists($url) {
	$file_headers = @get_headers($url);
	if($file_headers[0] == 'HTTP/1.1 404 Not Found' || $file_headers[0] == 'HTTP/1.1 500 Internal Server Error') {
		return false;
	}
	return true;
}


/*
 * Get home page/story stylesheet markup for the header
 *
 * @return string
 * @author Jo Greybill
 */
function output_header_markup($post) {
	$output = '';

	// Add font library stylesheets for fonts that are available via the
	// WYSIWYG editor (excluding Cloud.Typography fonts, which are already included)
	if ( $post->post_type == 'page' || ( ( $post->post_type == 'story' || $post->post_type == 'issue' || $post->post_type == 'photo_essay' || is_home() ) && !uses_custom_template( $post ) ) ) {
		$fonts = unserialize( TEMPLATE_FONT_URLS );
		if ( $fonts ) {
			foreach ( $fonts as $name => $url ) {
				$output .= '<link rel="stylesheet" href="'.$url.'" type="text/css" media="all" />';
			}
		}

		$output .= '<style type="text/css">';
		$output .= get_all_font_classes();
		$output .= '</style>';
	}

	// Page stylesheet
	if ( $post->post_type == 'page' ) {
		$page_stylesheet_url = Page::get_stylesheet_url( $post );
		if ( !empty( $page_stylesheet_url ) ) {
			$output .= '<link rel="stylesheet" href="'.$page_stylesheet_url.'" type="text/css" media="all" />';
		}
	}

	if (!is_search() && !is_404()) {
		// 1. Set necessary html, body element width+height for stories, issues
		$output .= '<style type="text/css">';
		$output .= '
			html, body {
				height: 100%;
				width: 100%;
			}
			@media (max-width: 767px) {
				html, body {
					width: auto;
				}
			}
		';
		$output .= '</style>';

		// 2. Story font declarations (default and custom templates)
		if ($post->post_type == 'story') {
			// Custom stories
			if (uses_custom_template($post)) {
				$story_fonts = get_post_meta($post->ID, 'story_fonts', TRUE);
				if (!empty($story_fonts)) {
					$fonts = explode(',', $story_fonts);
					$available_fonts = unserialize(CUSTOM_AVAILABLE_FONTS);
					foreach ($fonts as $font) {
						trim($font);
						if (array_key_exists($font, $available_fonts)) {
							$output .= '<link rel="stylesheet" href="'.$available_fonts[$font].'" type="text/css" media="all" />';
						}
					}
				}
			// Default template stories
			} else {
				$font = get_default_template_font_styles( $post );

				$output .= '<style type="text/css">';
				if ( function_exists( 'get_default_template_font_css' ) ) {
					$output .= get_default_template_font_css( $font ); // override per version
				}
				$output .= '</style>';
			}
		}

		// 3. Custom issue page-specific stylesheet
		if ( (is_home() || $post->post_type == 'issue') && (uses_custom_template($post)) ) {
			$home_stylesheet_url = Issue::get_home_stylesheet_url($post);
			$dev_issue_home_directory = get_post_meta($post->ID, 'issue_dev_home_asset_directory', TRUE);
			if (!empty($home_stylesheet_url)) {
				$output .= '<link rel="stylesheet" href="'.$home_stylesheet_url.'" type="text/css" media="all" />';
			}
			elseif ( DEV_MODE == 1 && !empty($dev_issue_home_directory) ) {
				$dev_home_stylesheet_url = THEME_DEV_URL.'/'.$dev_issue_home_directory.'issue-cover.css';
				if (curl_exists($dev_home_stylesheet_url)) {
					$output .= '<link rel="stylesheet" href="'.$dev_home_stylesheet_url.'" type="text/css" media="all" />';
				}
			}
		}

		// 4. Custom story stylesheet
		if( $post->post_type == 'story' && uses_custom_template($post) ) {
			$story_stylesheet_url = Story::get_stylesheet_url($post);
			$dev_issue_directory = get_post_meta($post->ID, 'story_dev_directory', TRUE);
			if ( !empty($story_stylesheet_url) ) {
				$output .= '<link rel="stylesheet" href="'.$story_stylesheet_url.'" type="text/css" media="all" />';
			}
			elseif ( (DEV_MODE == 1) && !empty($dev_issue_directory) ) {
				$dev_story_stylesheet_url = THEME_DEV_URL.'/'.$dev_issue_directory.$post->post_name.'.css';
				if (curl_exists($dev_story_stylesheet_url)) {
					$output .= '<link rel="stylesheet" href="'.$dev_story_stylesheet_url.'" type="text/css" media="all" />';
				}
			}
		}
	}

	return $output;
}


/*
 * HTTPS-friendly wp_get_attachment_url
 */
function protocol_relative_attachment_url($url) {
	if (is_ssl()) {
		$url = str_replace('http://', 'https://', $url);
	}
	return $url;
}
add_filter('wp_get_attachment_url', 'protocol_relative_attachment_url');


/**
 * Prevent WordPress from wrapping images with captions with a
 * [caption] shortcode.
 **/
add_filter('disable_captions', function( $a ) {
	return true;
});


/**
 * Whether or not the current story or issue requires a custom template.
 **/
function uses_custom_template($post) {
	$meta_field = $post->post_type.'_template';
	$template = get_post_meta($post->ID, $meta_field, TRUE);

	if ( $template && !empty($template) && $template == 'custom') {
		return true;
	}
	return false;
}


/**
 * Displays an issue cover or story contents.  Accounts for whether or
 * not Developer Mode is on/off and what story/issue template is set.
 *
 * Note: files pulled via file_get_contents() here should be requested over http.
 *
 * Markup priority: Uploaded HTML File -> WYSIWYG editor content -> dev directory content
 **/
function display_markup_or_template($post) {
	if ($post->post_type == 'issue') {
		$dev_directory          = get_post_meta($post->ID, 'issue_dev_home_asset_directory', TRUE);
		$dev_directory_html_url = str_replace('https', 'http', THEME_DEV_URL.'/'.$dev_directory.'issue-cover.html');
	}
	else {
		$dev_directory          = get_post_meta($post->ID, $post->post_type.'_dev_directory', TRUE);
		$dev_directory_html_url = str_replace('https', 'http', THEME_DEV_URL.'/'.$dev_directory.$post->post_name.'.html');
	}
	$post_template     = get_post_meta($post->ID, $post->post_type.'_template', TRUE);
	$uploaded_html     = get_post_meta($post->ID, $post->post_type.'_html', TRUE);
	$uploaded_html_url = wp_get_attachment_url($uploaded_html);
	if ($uploaded_html_url) {
		$uploaded_html_url = str_replace('https', 'http', $uploaded_html_url);
	}

	// If developer mode is on and this story/issue is custom,
	// try to use dev directory contents:
	if (
		DEV_MODE == 1 &&
		empty($post->post_content) &&
		$dev_directory !== False &&
		uses_custom_template($post)
	) {
		add_filter('the_content', 'kill_empty_p_tags', 999);

		// Uploaded HTML file should always take priority over dev directory contents
		if (!empty($uploaded_html) && !empty($uploaded_html_url)) {
			print apply_filters( 'the_content', wp_remote_retrieve_body( wp_remote_get( $uploaded_html_url ) ) );
		}
		else {
			if (curl_exists($dev_directory_html_url)) {
				$content = wp_remote_retrieve_body( wp_remote_get( $dev_directory_html_url ) );
				print apply_filters('the_content', $content);
			}
		}
	}
	else {
		// Check the set post template.  Note that if this value is set to 'default'
		// it is saved in the database as an empty value.
		if (!empty($post_template)) {
			switch ($post_template) {
				case 'custom':
					// Kill automatic <p> tag insertion if this isn't an old story.
					// Don't want to accidentally screw up an old story that worked
					// around the <p> tag issue.
					add_filter('the_content', 'kill_empty_p_tags', 999);

					// If an uploaded HTML file is present, use it.  Otherwise, use
					// any content available in the WYSIWYG editor
					if (!empty($uploaded_html) && !empty($uploaded_html_url)) {
						print apply_filters( 'the_content', wp_remote_retrieve_body( wp_remote_get( $uploaded_html_url ) ) );
					}
					else {
						the_content();
					}
					break;
				default:
					$filename = 'templates/' . $post->post_type . '/' . $post_template . '.php';
					$template = get_version_file_path( $filename, get_relevant_version( $post ) );
					require_once( $template );
					break;
			}
		}
		else {
			// Newer stories without a value should assume 'default' template
			add_filter( 'the_content', 'kill_empty_p_tags', 999 );
			$filename = 'templates/' . $post->post_type . '/default.php';
			$template = get_version_file_path( $filename, get_relevant_version( $post ) );
			require_once( $template );
		}
	}
}


/**
 * Returns an array of font style declarations and settings by font name.
 *
 * See TEMPLATE_FONT_STYLES_BASE (functions/config.php) for options.
 **/
function get_font_styles($font_name) {
	$template_fonts = unserialize(TEMPLATE_FONT_STYLES);
	$template_fonts_base = unserialize(TEMPLATE_FONT_STYLES_BASE);
	return array_merge($template_fonts_base, $template_fonts[$font_name]);
}


/**
 * Returns an array of a default story's font styling specs,
 * based on the story's selected title font family and color.
 *
 * See TEMPLATE_FONT_STYLES_BASE (functions/config.php) for options.
 *
 * @return array
 **/
function get_default_template_font_styles($post) {
	$template_fonts = unserialize(TEMPLATE_FONT_STYLES);
	$template_fonts_base = unserialize(TEMPLATE_FONT_STYLES_BASE);

	// Capture any available inputted values
	$post_meta = array(
		'font-family' => get_post_meta($post->ID, $post->post_type.'_default_font', TRUE),
		'color' => get_post_meta($post->ID, $post->post_type.'_default_color', TRUE) ? get_post_meta($post->ID, $post->post_type.'_default_color', TRUE) : '#222',
	);

	// Set base font styles.
	$styles = $template_fonts_base;
	// Override base styles with per-font defaults.
	if (!empty($post_meta['font-family']) && isset($template_fonts[$post_meta['font-family']])) {
		foreach ($template_fonts[$post_meta['font-family']] as $key => $val) {
			$styles[$key] = $val;
		}
	}

	// Override any default values with set post meta values.
	// Don't override 'font-family' option; it does not contain a valid CSS font-family
	// value (this is handled in the base style override loop above.)
	foreach ($post_meta as $key => $val) {
		if (!empty($val) && $key !== 'font-family') {
			$styles[$key] = $val;
		}
	}

	return $styles;
}


/**
 * Returns a string containing a CSS class declaration and rules,
 * using $selector or the font name as the class name.
 *
 * Specify the font name by $font (should match a font name registered
 * in TEMPLATE_FONT_STYLES).
 * Specify a custom selector by $selector (default is .font-name-sanitized).
 * Add extra styles or override existing styles with $extra[].
 * Add !important to all base styles returned by setting $important to true.
 **/
function get_font_class($font, $selector=null, $extra=null, $important=false) {
	if (!$font) { return null; }

	$styles 	= get_font_styles($font);
	$selector 	= $selector !== null ? $selector : '.'.sanitize_title($font);
	$extra 		= is_array($extra) ? $extra : array();
	$important 	= $important == false ? '' : ' !important';

	if (!$styles) { return null; }

	$all_styles = array_merge($styles, $extra);
	$blacklist  = array('url', 'size-desktop', 'size-tablet', 'size-mobile');
	$output 	= '';

	// Set up selector with attributes. Don't include attributes in $blacklist.
	$output .= $selector.' {';
	foreach ($all_styles as $attr=>$val) {
		if (!in_array($attr, $blacklist)) {
			$output .= $attr.': '.$val.$important.';';
		}
	}
	$output .= '}';

	return $output;
}


/**
 * Generate font-specific classes used in default story templates and
 * in formatting.css (static/css/formatting.php) for all registered font
 * families in TEMPLATE_FONT_STYLES (see functions/config.php)
 *
 * Font classes are assigned in this manner, instead of using TinyMCE's
 * default inline font-family selection, to ensure consistency in font
 * formatting across stories.
 *
 * Set $style_tag arg to true to wrap returned CSS in a <style> tag.
 **/
function get_all_font_classes($style_tag=false) {
	$fonts = unserialize(TEMPLATE_FONT_STYLES);

	$output = '';
	if ($style_tag) {
		$output .= '<style type="text/css">';
	}

	foreach ($fonts as $font=>$styles) {
		$ie8_selector = '.ie8 .'.sanitize_title($font);
		$ie8_args = array(
			'font-weight' => 'normal',
			'font-style' => 'normal',
			'line-height' => '1em'
		);
		$output .= get_font_class($font, null, array('line-height'=>'1em'));
		$output .= get_font_class($font, $ie8_selector, $ie8_args, true);
	}

	if ($style_tag) {
		$output .= '</style>';
	}

	return $output;
}


/**
 * Allow SVGs in the Media Library.
 **/
function allow_svgs( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'allow_svgs' );

/**
 * Allow JSON files in the Media Library
 **/
function allow_json( $mimes ) {
	$mimes['json'] = 'application/json';
	return $mimes;
}
add_filter( 'upload_mimes', 'allow_json' );


/**
 * Adds various allowed tags to WP's allowed tags list.
 *
 * Add elements and attributes to this list if WordPress' filters refuse to
 * parse those elems/attributes, or shortcodes within them, as expected.
 *
 * Adding 'source' and its 'src' attr fixes usage of <source src="[media...]">
 * after the WP 4.2.3 Shortcode API change.
 **/
global $allowedposttags;

function add_kses_whitelisted_attributes( $allowedposttags, $context ) {
	if ( $context == 'post' ) {
		$allowedposttags['source'] = array(
			'sizes' => true,
			'src' => true,
			'srcset' => true,
			'type' => true,
			'media' => true
		);
	}
	return $allowedposttags;
}
add_filter( 'wp_kses_allowed_html', 'add_kses_whitelisted_attributes', 10, 2 );


/**
 * Updates REST API responses for Issues to include relevant meta data.
 **/
function register_api_issue_meta() {
	register_rest_field( 'issue',
		'issue_cover_story',
		array(
			'get_callback'    => 'api_issue_get_cover_story',
			'update_callback' => null,
			'schema'          => null,
		)
	);
}

function api_issue_get_cover_story( $object, $field_name, $request ) {
	$meta = get_post_meta( $object['id'], $field_name, true );
	return $meta ? intval( $meta ) : 0;
}

add_action( 'rest_api_init', 'register_api_issue_meta' );


/**
 * Updates REST API responses for Issues to include links to related data
 * for client-side convenience.
 *
 * http://v2.wp-api.org/extending/linking/
 **/
function register_api_issue_links( $response, $post, $request ) {
	$cover_story_id = get_post_meta( $post->ID, 'issue_cover_story', true );
	if ( $cover_story_id ) {
		$response->add_link( 'issue_cover_story', rest_url( '/wp/v2/story/' . $cover_story_id ), array( 'embeddable' => true ) );
	}

	return $response;
}

add_filter( 'rest_prepare_issue', 'register_api_issue_links', 10, 3 );


/**
 * Updates REST API responses for Stories to include relevant meta data.
 **/
function register_api_story_meta() {
	register_rest_field( 'story',
		'story_subtitle',
		array(
			'get_callback'    => 'api_story_get_subtitle',
			'update_callback' => null,
			'schema'          => null,
		)
	);
	register_rest_field( 'story',
		'story_description',
		array(
			'get_callback'    => 'api_story_get_description',
			'update_callback' => null,
			'schema'          => null,
		)
	);
}

function api_story_get_subtitle( $object, $field_name, $request ) {
	return get_post_meta( $object['id'], $field_name, true );
}

function api_story_get_description( $object, $field_name, $request ) {
	return get_post_meta( $object['id'], $field_name, true );
}

add_action( 'rest_api_init', 'register_api_story_meta' );


/**
 * Displays a single story on the front page.
 **/
function display_front_page_story( $story, $css_class='', $show_vertical=false, $thumbnail_size='frontpage-story-thumbnail', $heading='h3' ) {
	if ( !$story ) { return false; }

	$thumbnail = null;
	if ( get_relevant_version( $story ) >= 5 ) {
		// Version 5+: fetch featured image ID
		$thumbnail_id = get_post_thumbnail_id( $story );
	} else {
		// Version 4 and prior: get the ID from the
		// `story_frontpage_thumb` meta field
		$thumbnail_id = get_post_meta( $story->ID, 'story_frontpage_thumb', true );
	}

	if ( $thumbnail_id ) {
		$thumbnail = wp_get_attachment_image(
			$thumbnail_id,
			$thumbnail_size,
			false,
			array(
				'class' => 'fp-feature-img center-block img-responsive',
				'alt' => '' // Intentionally blank to avoid redundant story title announcement
			)
		);
	}

	$title = wptexturize( $story->post_title );

	$description = '';
	if ( $story_description = get_post_meta( $story->ID, 'story_description', true ) ) {
		$description = wptexturize( strip_tags( $story_description, '<b><em><i><u><strong>' ) );
	}
	elseif ( $story_subtitle = get_post_meta( $story->ID, 'story_subtitle', true ) ) {
		$description = wptexturize( strip_tags( $story_subtitle, '<b><em><i><u><strong>' ) );
	}

	$vertical = null;
	if ( $show_vertical ) {
		$vertical = get_the_category( $story->ID );
		if ( $vertical ) {
			$vertical = $vertical[0];
			$vertical = wptexturize( $vertical->name );
		}
	}

	ob_start();
?>
<article class="fp-feature <?php echo $css_class; ?>">
	<a class="fp-feature-link" href="<?php echo get_permalink( $story->ID ); ?>">
		<?php if ( $thumbnail ): ?>
		<div class="fp-feature-img-wrap">
			<?php echo $thumbnail; ?>

			<?php if ( $show_vertical && $vertical ): ?>
			<span class="fp-vertical">
				<?php echo $vertical; ?>
			</span>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</a>
	<div class="fp-feature-text-wrap">
		<<?php echo $heading; ?> class="fp-feature-title">
			<a class="fp-feature-link" href="<?php echo get_permalink( $story->ID ); ?>">
				<?php echo $title; ?>
			</a>
		</<?php echo $heading; ?>>
		<div class="fp-feature-description">
			<?php echo $description; ?>
		</div>
	</div>
</article>
<?php 	return ob_get_clean();
}


/**
 * Displays a single Today article on the front page.
 **/
function display_front_page_today_story( $article ) {
	$url = $article->get_link();
	$title = $article->get_title();
	$publish_date = $article->get_date('m/d');

	ob_start();
?>
<article class="fp-today-feed-item">
	<a class="fp-today-item-link" href="<?php echo $url; ?>">
		<div class="publish-date"><?php echo $publish_date; ?></div>
		<?php echo $title; ?>
	</a>
</article>
<?php 	return ob_get_clean();
}


/**
 * Displays a single event item on the front page.
 **/
function display_front_page_event( $event ) {
	$start = strtotime( $event['starts'] );
	$description = substr( strip_tags( $event['description'] ), 0, 250 );
	if ( strlen( $description ) == 250 ) {
		$description .= '...';
	}

	ob_start();
?>
<div class="fp-event">
	<div class="fp-event-when">
		<span class="fp-event-day"><?php echo date( 'D', $start ); ?></span>
		<span class="fp-event-date"><?php echo date( 'd', $start ); ?></span>
		<span class="fp-event-month"><?php echo date( 'M', $start ); ?></span>
	</div>
	<div class="fp-event-content">
		<span class="fp-vertical"><?php echo $event['category']; ?></span>
		<span class="fp-event-title">
			<a class="fp-event-link" href="<?php echo $event['url']; ?>"><?php echo $event['title']; ?></a>
		</span>
		<div class="fp-event-description">
			<?php echo $description; ?>
		</div>
	</div>
</div>
<?php 	return ob_get_clean();
}


/**
 * Displays a single featured gallery on the front page.
 **/
function display_front_page_gallery( $gallery, $css_class='' ) {
	if ( !$gallery ) { return false; }

	$title = wptexturize( $gallery->post_title );

	$vertical = get_the_category( $gallery->ID );
	if ( $vertical ) {
		$vertical = $vertical[0];
		$vertical = wptexturize( $vertical->name );
	}

	$thumbnail = null;
	if ( get_relevant_version( $gallery ) >= 5 ) {
		// Version 5+: fetch featured image ID
		$thumbnail_id = get_post_thumbnail_id( $gallery );
		$thumbnail_size = 'frontpage-featured-gallery-thumbnail-3x2';
	} else {
		// Version 4 and prior: get the ID from the
		// `story_frontpage_gallery_thumb` meta field
		$thumbnail_id = intval( get_post_meta( $gallery->ID, 'story_frontpage_gallery_thumb', true ) );
		$thumbnail_size = 'frontpage-featured-gallery-thumbnail';
	}

	if ( $thumbnail_id ) {
		$thumbnail = wp_get_attachment_image(
			$thumbnail_id,
			$thumbnail_size,
			false,
			array(
				'class' => 'img-responsive center-block fp-gallery-img',
				'alt' => '' // Intentionally blank to avoid redundant story title announcement
			)
		);
	}

	ob_start();
?>
	<article class="fp-gallery <?php echo $css_class; ?>">
		<a class="fp-gallery-link" href="<?php echo get_permalink( $gallery->ID ); ?>">
			<h2 class="fp-heading fp-gallery-heading"><?php echo $title; ?></h2><?php if ( $vertical ): ?><span class="fp-vertical"><?php echo $vertical; ?></span><?php endif; ?>
			<?php if ( $thumbnail ): ?>
				<?php echo $thumbnail; ?>
			<?php endif; ?>
		</a>
	</article>
<?php 	return ob_get_clean();
}


/**
* Displays social buttons (Facebook, Twitter, G+) for front page header.
*
* @return string
* @author RJ Bruneel
**/
function display_social_header() {
	global $wp;

	$link = home_url( add_query_arg( array(), $wp->request ) );
	$fb_url = 'http://www.facebook.com/sharer.php?u=' . $link;
	$twitter_url = 'https://twitter.com/intent/tweet?text=' . urlencode( 'Pegasus Magazine' ) . '&url=' . $link;

	ob_start();
?>
	<span class="social-icon-list-heading">Share</span>
	<ul class="social-icon-list">
		<li class="social-icon-list-item">
			<a target="_blank" class="sprite facebook" href="<?php echo $fb_url; ?>">Share Pegasus Magazine on Facebook</a>
		</li>
		<li class="social-icon-list-item">
			<a target="_blank" class="sprite twitter" href="<?php echo $twitter_url; ?>">Share Pegasus Magazine on Twitter</a>
		</li>
	</ul>
<?php     return ob_get_clean();
}


/**
 * Displays the current issue's thumbnail and description, for use in the
 * "In This Issue" section of the front page.
 **/
function display_front_page_issue_details() {
	$current_issue = get_current_issue();
	$current_issue_title = wptexturize( $current_issue->post_title );
	$current_issue_thumbnail = get_featured_image_url( $current_issue->ID, 'full' );
	$current_issue_cover_story = get_post_meta( $current_issue->ID, 'issue_cover_story', true );

	ob_start();
?>
	<a class="fp-issue-link" href="<?php echo get_permalink( $current_issue->ID ); ?>">
		<h2 class="h3 fp-subheading fp-issue-title">In This Issue</h2>

		<?php if ( $current_issue_thumbnail ): ?>
		<img class="img-responsive center-block fp-issue-img" src="<?php echo $current_issue_thumbnail; ?>" alt="<?php echo $current_issue_title; ?>" title="<?php echo $current_issue_title; ?>">
		<?php endif; ?>
	</a>

	<?php if ( $current_issue_title ): ?>
	<div class="fp-issue-title">
		<?php echo $current_issue_title; ?>
	</div>
	<?php endif; ?>
<?php 	return ob_get_clean();
}


/**
 * Returns an array of Story objects for use in the "In This Issue" section of
 * the front page.
 **/
function get_front_page_issue_stories() {
	$issue_stories_exclude = array();
	$feature_1 = get_theme_option( 'front_page_featured_story_1' );

	if ( $feature_1 ) {
		$issue_stories_exclude[] = intval( $feature_1 );
	}

	return get_current_issue_stories( $issue_stories_exclude, 12 );
}

/**
 * Add custom endpoint for active issues
 * @author Jim Barnes
 * @since 4.1.3
 **/
function add_active_issue_endpoint() {
	register_rest_route( 'wp/v2', 'issue/active', array(
		'methods'  => 'GET',
		'callback' => 'active_issue_endpoint_callback'
	) );
}

add_action( 'rest_api_init', 'add_active_issue_endpoint' );

/**
 * The function for the active issue endpoint
 * @author Jim Barnes
 * @since 4.1.3
 * @param WP_Rest_Request $request The request object
 * @param WP_Rest_Response The response object
 **/
function active_issue_endpoint_callback( $request ) {
	$current_issue = get_current_issue();

	$args = array(
		'post_type'   => 'issue',
		'numberposts' => -1,
		'orderby'     => 'post_date',
		'order'       => 'desc',
		'post_status' => 'publish',
		'date_query'  => array(
			array(
				'before'    => $current_issue->post_date,
				'inclusive' => true
			)
		)
	);

	if ( isset( $_GET['offset'] ) ) {
		$args['offset'] = $_GET['offset'];
	}

	if ( isset( $_GET['limit'] ) ) {
		$args['posts_per_page'] = $_GET['limit'];
	}

	$issues_all = new WP_Query( $args );

	$retval = array();

	$controller = new WP_REST_Posts_Controller( 'issue' );

	foreach( $issues_all->posts as $issue ) {
		$data = $controller->prepare_item_for_response( $issue, $request );
		$retval[] = $controller->prepare_response_for_collection( $data );
	}

	return new WP_REST_Response( $retval, 200 );
}

/****************************************************************************
 *
 * END site-level functions.  Don't add anything else below this line.
 *
 * START version-level functions here
 *
 ****************************************************************************/

function require_version_functions() {
	require_once( get_version_file_path( 'functions.php' ) );
}
add_action( 'init', 'require_version_functions' );

?>
