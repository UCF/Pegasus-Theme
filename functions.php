<?php
require_once('functions/base.php');   			# Base theme functions
require_once('functions/feeds.php');			# Where functions related to feed data live
require_once('custom-taxonomies.php');  		# Where per theme taxonomies are defined
require_once('custom-post-types.php');  		# Where per theme post types are defined
require_once('functions/admin.php');  			# Admin/login functions
require_once('functions/config.php');			# Where per theme settings are registered
require_once('shortcodes.php');         		# Per theme shortcodes

//Add theme-specific functions here.


/**
 * Dynamically populate the Alumni Notes 'Class Year' form field with years ranging from 1969 to the current year
 * 
 * Note that the new input select name and id values must match the name and id of the empty dropdown within the
 * form that this function is replacing
 *
 * @author Jo Greybill
 *  
**/
add_action("gform_field_input", "class_year_input", 10, 5);	
function class_year_input($input, $field, $value, $lead_id, $form_id){
    if($field["cssClass"] == "alumninotes_class_year"){
        $input = '<div class="ginput_container"><select multiple="multiple" id="input_2_4" class="small gfield_select" tabindex="5" name="input_4">';
		$current_year = date('Y');
		foreach ( range($current_year, 1968) as $year ) {
			$input .= '<option value='.$year.'>'.$year.'</option>';
		}
		$input .= '</select></div>';
    }
    return $input;
}


/* 
 * Retrieve a list of the current issue's stories
 */
function get_current_issue_stories($exclude=array(), $limit=-1) {
	$current_issue = get_current_issue();

	if($current_issue === False) {
		return False;
	} else {
		return get_issue_stories($current_issue, array('exclude'=>$exclude, 'limit'=>$limit));
	}
}


/*
* Returns featured image URL of a specified post ID
*/
function get_featured_image_url($id) {
	$url = '';
	if(has_post_thumbnail($id)
		&& ($thumb_id = get_post_thumbnail_id($id)) !== False
		&& ($image = wp_get_attachment_image_src($thumb_id, 'single-post-thumbnail')) !== False) {
			return $image[0];
	}
	return $url;
}


/*
 * Returns a relevant issue post object depending on the page being viewed.
 * i.e., if the $post obj passed is the front page or subpage, get the current issue;
 * otherwise, get the current story issue
 */
function get_relevant_issue($post) {
	if($post && $post->post_type == 'story') {
		$issue = get_story_issue($post);
	} else if ($post && $post->post_type == 'issue') {
		$issue = $post;
	} else {
		$issue = get_current_issue();
	}

	return $issue;
}


/*
 * Retrieve a list of stories for navigation. Exclude a story if we are on
 * its page otherwise pick 4 at random.
 */
function get_navigation_stories($issue=null) {
	global $post;

	$exclude = array();

	if(is_null($issue)) {
		$issue = get_relevant_issue($post);
	}

	if(is_front_page() || $post->post_type == 'issue') {
		$current_issue  = (is_front_page()) ? get_current_issue() : $post;
		$cover_story_id = get_post_meta($current_issue->ID, 'issue_cover_story', True);
		if($cover_story_id !== False && $cover_story_id != '') {
			if( ($cover_story = get_post($cover_story_id)) !== False) {
				$exclude[] = $cover_story->ID;
			}
		}
	} if($post->post_type == 'story') {
		$exclude[] = $post->ID;
	}
	
	$top_stories     = get_issue_stories($issue, array('exclude' => $exclude, 'limit' => 4));
	$top_stories_ids = array_merge(array_map(create_function('$p', 'return $p->ID;'), $top_stories), $exclude);
	$bottom_stories  = get_issue_stories($issue, array('exclude' => $top_stories_ids, 'limit' => 6));
	return array('top_stories' => $top_stories, 'bottom_stories' => $bottom_stories);
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
 * Modify the permalinks for the Issue post type to the following form:
 * http://pegasus.ucf.edu/<issue slug>/
 */
function modify_issue_permalinks($url, $post) {
	if($post->post_type == 'issue') {
		return get_bloginfo('url').'/'.$post->post_name.'/';
	}
	return $url;
} 
add_filter('post_type_link', 'modify_issue_permalinks', 10, 2);


/*
 * Add a rewrite rule to handle the new Issue post type permalink structure
 */
function issue_init() {
	$issue_slugs  = array_map(
		create_function('$i', 'return preg_quote($i->post_name);'),
		get_posts(array('post_type' => 'issue')
	));
	add_rewrite_rule('^('.implode('|', $issue_slugs).')$', 'index.php?issue=$matches[1]', 'top');
	flush_rewrite_rules(false);
}
add_action('init', 'issue_init');


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
		return get_post_meta($post->ID, 'story_subtitle', TRUE);
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
	// add home page script(s)
	if($post->post_type == 'issue' || is_home()) {
		// issue-wide
		if (($issue_javascript_url = Issue::get_issue_javascript_url($post)) !== False) {
			Config::add_script($issue_javascript_url);
		}
		elseif (DEV_MODE == true && ($dev_issue_directory = get_post_meta($post->ID, 'issue_dev_issue_asset_directory', TRUE)) !== NULL) {
			$dev_issue_javascript_url = THEME_DEV_URL.'/'.$dev_issue_directory.$post->post_name.'.js';
			if (curl_exists($dev_issue_javascript_url)) {
				Config::add_script($dev_issue_javascript_url);
			}
		}
		// home page specific
		if (($home_javascript_url = Issue::get_home_javascript_url($post)) !== False) {
			Config::add_script($home_javascript_url);
		}
		elseif (DEV_MODE == true && ($dev_issue_home_directory = get_post_meta($post->ID, 'issue_dev_home_asset_directory', TRUE)) !== NULL) {
			$dev_home_javascript_url = THEME_DEV_URL.'/'.$dev_issue_home_directory.'home.js';
			if (curl_exists($dev_home_javascript_url)) {
				Config::add_script($dev_home_javascript_url);
			}
		}
	// add story script(s)
	} else if($post->post_type == 'story') {
		// issue-wide
		if( ($story_issue = get_story_issue($post)) !== False && ($issue_javascript_url = Issue::get_issue_javascript_url($story_issue)) !== False ) {
			Config::add_script($issue_javascript_url);
		}
		elseif (
			($story_issue = get_story_issue($post)) !== False && 
			DEV_MODE == true && 
			($dev_issue_directory = get_post_meta($story_issue->ID, 'issue_dev_issue_asset_directory', TRUE)) !== NULL)
			{
				$dev_issue_javascript_url = THEME_DEV_URL.'/'.$dev_issue_directory.$story_issue->post_name.'.js';
				if (curl_exists($dev_issue_javascript_url)) {
					Config::add_script($dev_issue_javascript_url);
				}
		}
		// story specific
		if ( ($javascript_url = Story::get_javascript_url($post)) !== False) {
			Config::add_script($javascript_url);
		}
		elseif (
			DEV_MODE == true && 
			($dev_story_directory = get_post_meta($post->ID, 'story_dev_directory', TRUE)) !== NULL) 
			{
				$dev_story_javascript_url = THEME_DEV_URL.'/'.$dev_story_directory.$post->post_name.'.js';
				if (curl_exists($dev_story_javascript_url)) {
					Config::add_script($dev_story_javascript_url);
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

	# UPDATED as of Spring 2014 -- issue TERM slugs should be created
	# to MATCH issue POST slugs!
	foreach($issue_terms as $term) {
		# reverse the term slug
		#$post_slug = implode('-', array_reverse(explode('-', $term->slug)));
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
function get_issue_stories($issue, $options=array()) {
	$default_options = array(
		'exclude' => array(),
		'limit'   => -1,
		'orderby' => 'rand'
	);
	$options = array_merge($default_options, $options);

	# UPDATED as of Spring 2014 -- issue TERM slugs should be created
	# to MATCH issue POST slugs!
	
	#$issue_term_slug = implode('-', array_reverse(explode('-', $issue->post_name)));
	$issue_term_slug = $issue->post_name;

	return get_posts(array(
		'post_type'   => 'story',
		'numberposts' => $options['limit'],
		'exclude'     => $options['exclude'],
		'orderby'     => $options['orderby'],
		'tax_query'   => array(
				array(
					'taxonomy' => 'issues',
					'field'    => 'slug',
					'terms'    => $issue_term_slug
				)
			)
	));
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
 * Get home page/story stylesheet and script markup for the header
 *
 * @return string
 * @author Jo Greybill
 */
function output_header_markup($post) {
	$output = '';
	// Issue-wide stylesheet (on home page)
	if( is_home() || $post->post_type == 'issue' ) {		
		if ( ($issue_stylesheet_url = Issue::get_issue_stylesheet_url($post)) !== False ) {
			$output .= '<link rel="stylesheet" href="'.$issue_stylesheet_url.'" type="text/css" media="all" />';
		}
		elseif ( DEV_MODE == true && ($dev_issue_directory = get_post_meta($post->ID, 'issue_dev_issue_asset_directory', TRUE)) !== NULL ) {
			$dev_issue_stylesheet_url = THEME_DEV_URL.'/'.$dev_issue_directory.$post->post_name.'.css';
			if (curl_exists($dev_issue_stylesheet_url)) {
				$output .= '<link rel="stylesheet" href="'.$dev_issue_stylesheet_url.'" type="text/css" media="all" />';
			}
		}
	}
	// Home stylesheet
	if ( is_home() || $post->post_type == 'issue' ) {
		if (( $home_stylesheet_url = Issue::get_home_stylesheet_url($post)) !== False) {
			$output .= '<link rel="stylesheet" href="'.$home_stylesheet_url.'" type="text/css" media="all" />';
		}
		elseif ( DEV_MODE == true && ($dev_issue_home_directory = get_post_meta($post->ID, 'issue_dev_home_asset_directory', TRUE)) !== NULL ) {
			$dev_home_stylesheet_url = THEME_DEV_URL.'/'.$dev_issue_home_directory.'home.css';
			if (curl_exists($dev_home_stylesheet_url)) {
				$output .= '<link rel="stylesheet" href="'.$dev_home_stylesheet_url.'" type="text/css" media="all" />';
			}
		}
	}
	// Story fonts
	if ( 
		$post->post_type == 'story' && 
		get_post_meta($post->ID, 'story_fonts', TRUE) && 
		get_post_meta($post->ID, 'story_fonts', TRUE) !== '' ) 
		{

		$fonts = explode(',', get_post_meta($post->ID, 'story_fonts', TRUE));
		$available_fonts = unserialize(THEME_AVAILABLE_FONTS);
		foreach ($fonts as $font) { 
			trim($font);
			if (array_key_exists($font, $available_fonts)) {
				$output .= '<link rel="stylesheet" href="'.$available_fonts[$font].'" type="text/css" media="all" />';
			}
		} 

	}
	// Issue-wide stylesheet (on story)
	if( $post->post_type == 'story' ) {
		if ( ($story_issue = get_story_issue($post)) !== False && ($issue_stylesheet_url = Issue::get_issue_stylesheet_url($story_issue)) !== False ) {
			$output .= '<link rel="stylesheet" href="'.$issue_stylesheet_url.'" type="text/css" media="all" />';
		}
		elseif ( 
			($story_issue = get_story_issue($post)) !== False && 
			DEV_MODE == true && 
			($dev_issue_directory = get_post_meta($story_issue->ID, 'issue_dev_issue_asset_directory', TRUE)) !== False)
			{
				$dev_issue_home_stylesheet_url = THEME_DEV_URL.'/'.$dev_issue_directory.$story_issue->post_name.'.css';
				if (curl_exists($dev_issue_home_stylesheet_url)) {
					$output .= '<link rel="stylesheet" href="'.$dev_issue_home_stylesheet_url.'" type="text/css" media="all" />';
				}
		}
	}
	// Story stylesheet
	if( $post->post_type == 'story' ) {
		if ( ($story_stylesheet_url = Story::get_stylesheet_url($post)) !== False ) {
			$output .= '<link rel="stylesheet" href="'.$story_stylesheet_url.'" type="text/css" media="all" />';
		}
		elseif ( (DEV_MODE == true) && ($dev_issue_directory = get_post_meta($post->ID, 'story_dev_directory', TRUE)) !== NULL ) {
			$dev_story_stylesheet_url = THEME_DEV_URL.'/'.$dev_issue_directory.$post->post_name.'.css';
			if (curl_exists($dev_story_stylesheet_url)) {
				$output .= '<link rel="stylesheet" href="'.$dev_story_stylesheet_url.'" type="text/css" media="all" />';
			}
		}
	}
	// Page stylesheet
	if($post->post_type == 'page' && ($page_stylesheet_url = Page::get_stylesheet_url($post)) !== False) {
		$output .= '<link rel="stylesheet" href="'.$page_stylesheet_url.'" type="text/css" media="all" />';
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


/*
 * Whether or not the current story or issue is from 
 * Fall 2013 or earlier (whether it requires deprecated markup
 * for backwards compatibility.)
 */
function is_before_fall_2013($post) {
	$old_issues = array('fall-2013', 'summer-2013', 'spring-2013', 'fall-2012', 'summer-2012');
	$slug = null;

	if ($post->post_type == 'issue') {
		$slug = $post->post_name;
	}
	else if ($post->post_type == 'story') {
		$slug = get_story_issue($post)->post_name;
	}

	if ($slug !== null && in_array($slug, $old_issues)) {
		return true;
	}
	return false;
}

?>