<?php

/**
 * READ ME, PLEASE:
 *
 * Various functions for this theme MUST execute in a specific order and
 * depend on WordPress hooks to be executed with specific priority values.
 * These functions hook into 'after_setup_theme' (the earliest available
 * hook for themes) with priority values set.
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
 * on the current global $post's post_type.
 **/
function get_relevant_version( $the_post=null ) {
	if ( !$the_post ) {
		$request_uri = untrailingslashit( $_SERVER['REQUEST_URI'] );

		if ( $request_uri === get_site_url( get_current_blog_id(), '', 'relative' ) ) {
			$the_post = get_current_issue();
		}
		else {
			global $post;

			if ( !$post ) {
				$basename = basename( $request_uri );
				$post_issue = get_page_by_path( $basename , OBJECT, 'issue');
				$post_story = get_page_by_path( $basename , OBJECT, 'story');

				if ( $post_issue ) {
					$the_post = $post_issue;
				}
				else if ( $post_story ) {
					$the_post = $post_story;
				}
				else {
					// Shouldn't ever reach this point, but if we do, we really
					// have no clue what it is we're loading :(
					$the_post = null;
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
add_action( 'after_setup_theme', 'setup_version_files', 3 );


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
function get_version_header() {
	$new_template = locate_template( array( get_version_file_path( 'header.php' ) ) );
	if ( !empty( $new_template ) ) {
		return load_template( THE_POST_VERSION_DIR . '/header.php' );
	}
}


/**
 * Loads version-specific footer template.  Should be used in place of
 * get_footer() for this theme.
 **/
function get_version_footer() {
	$new_template = locate_template( array( get_version_file_path( 'footer.php' ) ) );
	if ( !empty( $new_template ) ) {
		return load_template( THE_POST_VERSION_DIR . '/footer.php' );
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
function get_current_issue() {
	$posts = get_posts(array(
		'post_type' => 'issue',
		'name'      => get_theme_option('current_issue_cover')
	));

	if(count($posts) == 0) {
		die('Error: No Issue post matches the post assigned to the "Current Issue Cover" Theme Options setting.');
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
function get_relevant_issue($post) {
	if (is_preview()) {
		$issue = get_current_issue();
	} else if($post && $post->post_type == 'story' && !is_404() && !is_search()) {
		$issue = get_story_issue($post);
	} else if ($post && $post->post_type == 'issue' && !is_404() && !is_search()) {
		$issue = $post;
	} else {
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
	$issue_slugs = array_map(
		create_function('$i', 'return preg_quote($i->post_name);'),
		get_posts(array('post_type' => 'issue', 'numberposts' => -1)
	));
	$story_slugs = array_map(
		create_function('$i', 'return preg_quote($i->post_name);'),
		get_posts(array('post_type' => 'story', 'numberposts' => -1)
	));

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
		// 1. add home page script(s)
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

			// home page specific
			$dev_issue_home_directory = get_post_meta( $post->ID, 'issue_dev_home_asset_directory', TRUE );
			$home_javascript_url = Issue::get_home_javascript_url( $post );

			if ( !empty( $home_javascript_url ) ) {
				Config::add_script( $home_javascript_url );
			}
			elseif ( DEV_MODE == 1 && !empty( $dev_issue_home_directory ) ) {
				$dev_home_javascript_url = THEME_DEV_URL.'/'.$dev_issue_home_directory.'home.js';

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
function get_story_issue($story) {
	$issue_terms = wp_get_object_terms($story->ID, 'issues');
	$issue_posts = get_posts(array('post_type'=>'issue', 'numberposts'=>-1));

	foreach($issue_terms as $term) {
		$post_slug = $term->slug;
		foreach($issue_posts as $issue) {
			if($post_slug == $issue->post_name) {
				return $issue;
			}
		}
	}
	return False;
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

	// Page stylesheet
	if ( $post->post_type == 'page' && !empty( $page_stylesheet_url ) ) {
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

				if ($font['url']) {
					$output .= '<link rel="stylesheet" href="'.$font['url'].'" type="text/css" media="all" />';
				}

				$output .= '<style type="text/css">';
				if ( function_exists( 'get_default_template_font_css' ) ) {
					$output .= get_default_template_font_css( $font ); // override per version
				}
				$output .= get_all_font_classes();
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
				$dev_home_stylesheet_url = THEME_DEV_URL.'/'.$dev_issue_home_directory.'home.css';
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
add_filter('disable_captions', create_function('$a', 'return true;'));


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
		$dev_directory_html_url = str_replace('https', 'http', THEME_DEV_URL.'/'.$dev_directory.'home.html');
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
			print apply_filters('the_content', file_get_contents($uploaded_html_url));
		}
		else {
			if (curl_exists($dev_directory_html_url)) {
				$content = file_get_contents($dev_directory_html_url);
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
						print apply_filters('the_content', file_get_contents($uploaded_html_url));
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
add_action( 'after_setup_theme', 'require_version_functions' );

?>
