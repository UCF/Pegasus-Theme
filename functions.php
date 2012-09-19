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

	$current_issue_term = get_term_by('slug', CURRENT_ISSUE_TERM_SLUG, 'issues');
	if($current_edition_term === FALSE) {
		return array();
	} else {
		return get_posts(array(
			'numberposts' => $limit,
			'post_type'   => 'story',
			'orderby'     => 'rand',
			'exclude'     => $exclude,
			'tax_query'   => array(
				'taxonomy' => 'issues',
				'field'    => 'id',
				'terms'    => $current_issue_term->term_id
			),
		));
	}
}

/*
 * Retrieve a list of stories for navigation. Exclude a story if we are on
 * its page otherwise pick 4 at random.
 */
function get_navigation_stories() {
	global $post;

	$exclude = array();

	if(is_front_page()) {
		$story_id = get_theme_option('front_page_story');
		if( ($story = get_post($story_id)) !== Fales) {
			$exclude = $story->ID;
		}
	} if($post->post_type == 'story') {
		$exclude[] = $post->ID;
	}
	return get_current_issue_stories($exclude, 4);
}

/*
 * Returns featured image URL of a specified post ID
 */
 /*
function get_featured_image_url($id) {
	$url = '';
	if(has_post_thumbnail($id)
		&& ($thumb_id = get_post_thumbnail_id($id)) !== False
		&& ($image = wp_get_attachment_image_src($thumb_id, 'single-post-thumbnail')) !== False) {
		return $image[0];
	}
	return $url;
}*/

/*
 * Returns a theme option value or NULL if it doesn't exist
 */
function get_theme_option($key) {
	global $theme_options;
	return isset($theme_options[$key]) ? $theme_options[$key] : NULL;
}

/*
 * Returns an array of choices for the front page features story site setting.
 */
function get_front_page_story_choices() {
	$choices = array();

	$stories = get_posts(array('post_type'=>'story', 'numberposts'=>-1));
	foreach($stories as $story) {
		$choices[$story->post_title] = $story->ID;
	}
	return $choices;
}

/*
 * Is the iPad app deployed or not
 */
function ipad_deployed() {
	$ipad_app_url = get_theme_option('ipad_app_url');
	return (is_null($ipad_app_url) || $ipad_app_url == '') ? False : True;
}

/*
 *	Returns current issue post type based on CURRENT_ISSUE_TERM_SLUG
 */
function get_current_issue() {
	$posts = get_posts(array(
		'post_type' => 'issue',
		'name'      => CURRENT_ISSUE_SLUG
	));

	if(count($posts) == 0) {
		die('There must be an Issue with a slug that mactches the CURRENT_ISSUE_SLUG constant value.');
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

?>