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
	$issue_slugs  = array_map(
		create_function('$i', 'return preg_quote($i->post_name);'),
		get_posts(array('post_type' => 'issue', 'numberposts' => -1)
	));
	$story_slugs  = array_map(
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
	
	if (!is_404() && !is_search()) {
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
 * Get home page/story stylesheet markup for the header
 *
 * @return string
 * @author Jo Greybill
 */
function output_header_markup($post) {
	$output = '';

	// Page stylesheet
	if($post->post_type == 'page' && ($page_stylesheet_url = Page::get_stylesheet_url($post)) !== False) {
		$output .= '<link rel="stylesheet" href="'.$page_stylesheet_url.'" type="text/css" media="all" />';
	}

	if (!is_search() && !is_404()) {
		// Set necessary html, body element width+height for stories,
		// issues if they are not from fall 2013 or earlier
		if (!is_fall_2013_or_older($post)) {
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
		}

		// Story font declarations (default and custom templates)
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
				$font = get_template_title_styles($post);

				if ($font['url']) {
					$output .= '<link rel="stylesheet" href="'.$font['url'].'" type="text/css" media="all" />';
				}

				$output .= '<style type="text/css">';
				$output .= '
					main article h1,
					main article h2,
					main article h3,
					main article h4,
					main article h5,
					main article h6 {
						font-family: '.$font['family'].';
						font-weight: '.$font['weight'].';
						text-transform: '.$font['texttransform'].';
					}
					main article h1,
					main article h2,
					main article h3,
					main article h4,
					main article h5,
					main article h6,
					main article blockquote,
					main article blockquote p {
						color: '.$font['color'].';
					}
					main article .lead::first-letter { color: '.$font['color'].'; }
					main article .lead:first-letter { color: '.$font['color'].'; }
					main article h1 {
						font-size: '.$font['size-desktop'].';
						line-height: '.$font['size-desktop'].';
					}
					@media (max-width: 979px) {
						main article h1 {
							font-size: '.$font['size-tablet'].';
							line-height: '.$font['size-tablet'].';
						}
					}
					@media (max-width: 767px) {	
						main article h1 {
							font-size: '.$font['size-mobile'].';
							line-height: '.$font['size-mobile'].';
						}
					}
				';
				$output .= '</style>';
			}
		}

		// Issue font declarations (custom templates)
		if ($post->post_type == 'issue' && !uses_custom_template($post)) {
			$font = get_template_title_styles($post);

			if ($font['url']) {
				$output .= '<link rel="stylesheet" href="'.$font['url'].'" type="text/css" media="all" />';
			}

			$output .= '<style type="text/css">';
			$output .= '
				main h2 {
					color: '.$font['color'].';
					font-size: '.$font['size-desktop'].';
					line-height: '.$font['size-desktop'].';
					text-align: '.$font['textalign'].';
				}
				main h2,
				main h3 {
					font-family: '.$font['family'].';
					font-weight: '.$font['weight'].';
					text-transform: '.$font['texttransform'].';
				}
				@media (max-width: 979px) {
					main h2 {
						font-size: '.$font['size-tablet'].';
						line-height: '.$font['size-tablet'].';
					}
				}
				@media (max-width: 767px) {	
					main h2 {
						font-size: '.$font['size-mobile'].';
						line-height: '.$font['size-mobile'].';
					}
				}
			';
			$output .= '</style>';
		}

		// DEPRECATED:  Issue-wide stylesheet (on home/issue cover page)
		if( (is_home() || $post->post_type == 'issue') && (is_fall_2013_or_older($post)) ) {		
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
		// DEPRECATED:  Issue-wide stylesheet (on story)
		if( $post->post_type == 'story' && is_fall_2013_or_older($post) ) {
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

		// Custom issue page-specific stylesheet
		if ( (is_home() || $post->post_type == 'issue') && (uses_custom_template($post)) ) {
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
		
		// Custom story stylesheet
		if( $post->post_type == 'story' && uses_custom_template($post) ) {
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
function is_fall_2013_or_older($post) {
	$old_issues = unserialize(FALL_2013_OR_OLDER);
	$slug = null;

	if ($post->post_type == 'issue') {
		$slug = $post->post_name;
	}
	else if ($post->post_type == 'story') {
		$slug = get_story_issue($post)->post_name;
	}

	if (is_404() || is_search()) { $slug = null; }

	if ($slug !== null && in_array($slug, $old_issues)) {
		return true;
	}
	return false;
}


/**
 * Whether or not the current story or issue requires
 * a custom template (is from Fall 2013 or before, or 
 * has specifically designated a custom template)
 **/
function uses_custom_template($post) {
	$meta_field = $post->post_type.'_template';
	$template = get_post_meta($post->ID, $meta_field, TRUE);

	if (
		($template && !empty($template) && $template == 'custom') ||
		(empty($template) && is_fall_2013_or_older($post))
	) {
		return true;
	}
	return false;
}


/**
 * Get a non-custom story or issue's title font styling specs, based on 
 * the story/issue's selected title font family and color.
 *
 * See TEMPLATE_FONT_STYLES_BASE (functions/config.php) for options.
 *
 * @return array
 **/
function get_template_title_styles($post) {
	$template_fonts = unserialize(TEMPLATE_FONT_STYLES);
	$template_fonts_base = unserialize(TEMPLATE_FONT_STYLES_BASE);

	// Capture any available inputted values
	$post_meta = array(
		'family' => get_post_meta($post->ID, $post->post_type.'_default_font', TRUE),
		'color' => get_post_meta($post->ID, $post->post_type.'_default_color', TRUE),
	);
	if ($post->post_type == 'issue') {
		$post_meta['size-desktop'] = get_post_meta($post->ID, 'issue_default_fontsize_d', TRUE);
		$post_meta['size-tablet'] = get_post_meta($post->ID, 'issue_default_fontsize_t', TRUE);
		$post_meta['size-mobile'] = get_post_meta($post->ID, 'issue_default_fontsize_m', TRUE);
		$post_meta['textalign'] = get_post_meta($post->ID, 'issue_default_textalign', TRUE);
	}

	// Set base font styles.
	$styles = $template_fonts_base;
	// Override base styles with per-font defaults.
	if (!empty($post_meta['family']) && isset($template_fonts[$post_meta['family']])) {
		foreach ($template_fonts[$post_meta['family']] as $key => $val) {
			$styles[$key] = $val;
		}
	}

	// Override any default values with set post meta values.
	// Don't override 'family' option; it does not contain a valid CSS font-family
	// value (this is handled in the base style override loop above.)
	foreach ($post_meta as $key => $val) {
		if (!empty($val) && $key !== 'family') {
			$styles[$key] = $val;
		}
	}

	return $styles;
}


/**
* Displays social buttons (Facebook, Twitter, G+) for a post.
* Accepts a post URL and title as arguments.
*
* @return string
* @author Jo Dickson
**/
function display_social($url, $title) {
    $tweet_title = urlencode('Pegasus Magazine: '.$title);
    ob_start(); ?>
    <aside class="social">
        <a class="share-facebook" target="_blank" data-button-target="<?=$url?>" href="http://www.facebook.com/sharer.php?u=<?=$url?>" title="Like this story on Facebook">
            Like "<?=$title?>" on Facebook
        </a>
        <a class="share-twitter" target="_blank" data-button-target="<?=$url?>" href="https://twitter.com/intent/tweet?text=<?=$tweet_title?>&url=<?=$url?>" title="Tweet this story">
            Tweet "<?=$title?>" on Twitter
        </a>
        <a class="share-googleplus" target="_blank" data-button-target="<?=$url?>" href="https://plus.google.com/share?url=<?=$url?>" title="Share this story on Google+">
            Share "<?=$title?>" on Google+
        </a>
    </aside>
    <?php
    return ob_get_clean();
}


/**
 * Displays an issue cover.
 **/
function display_issue($post) {
	if (
		$post->post_content == '' &&
		DEV_MODE == true && 
		($dev_issue_home_directory = get_post_meta($post->ID, 'issue_dev_home_asset_directory', TRUE)) !== False &&
		uses_custom_template($post)
	) {
		$dev_issue_html_url = THEME_DEV_URL.'/'.$dev_issue_home_directory.'home.html';
		if (curl_exists($dev_issue_html_url)) {
			$content = file_get_contents($dev_issue_html_url);
			print apply_filters('the_content', $content);
		}
	}
	else {
		switch (get_post_meta($post->ID, 'issue_template', TRUE)) {
			case 'default':
				require_once('templates/issue/default.php');
				break;
			case 'custom':
			default:
				if (!is_fall_2013_or_older($post)) {
					// Kill automatic <p> tag insertion if this isn't an old story.
					// Don't want to accidentally screw up an old story that worked
					// around the <p> tag issue.
					add_filter('the_content', 'kill_empty_p_tags', 999);
				}
				the_content();
				break;
		}
	}
}


/**
 * Replace default WordPress markup for image insertion with
 * markup to generate a [photo] shortcode.
 **/
function editor_insert_image_as_shortcode($html, $id, $caption, $title, $align, $url, $size, $alt) {
    $s_id = $id;
    $s_title = '';
    $s_alt = $alt;
    $s_position = null;
    $s_width = '100%';
    $s_caption = '';

    $attachment = get_post($id);

    // Get usable image position
    if ($align && $align !== 'none') {
    	$s_position = $align;
    }
    // Get usable image width.
    // Assume that if a user sets an image alignment, they don't
    // want the image to be blown up to 100% width
    $attachment_src = wp_get_attachment_image_src($id, $size);
    if ($s_position) {
    	$s_width = $attachment_src[1].'px';
    }

    // Get usable image title (passed $title doesn't always work here?)
    $s_title = $attachment->post_title;
    // Get usable image caption
    $s_caption = $attachment->post_excerpt;

    // Create markup
    $html = '[photo id="'.$s_id.'" title="'.$s_title.'" alt="'.$s_alt.'" ';
    if ($s_position) {
    	$html .= 'position="'.$s_position.'" ';
    }
    $html .= 'width="'.$s_width.'"]'.$s_caption.'[/photo]';

    return $html;
}
add_filter('image_send_to_editor', 'editor_insert_image_as_shortcode', 10, 8); 


/**
 * Prevent WordPress from wrapping images with captions with a
 * [caption] shortcode.
 **/
add_filter('disable_captions', create_function('$a', 'return true;'));


/**
 * Set some default values when inserting photos in the Media Uploader.
 * Particularly, prevents images being linked to themselves by default.
 **/
function editor_default_photo_values() {
	update_option('image_default_align', 'none');
	update_option('image_default_link_type', 'none');
	update_option('image_default_size', 'full');
}
add_action('after_setup_theme', 'editor_default_photo_values');


/**
 * Set a 'story_template' meta field value for stories with an issue 
 * slug in the FALL_2013_OR_OLDER constant so that they are 'custom'
 * if they have no value.
 *
 * Runs only as necessary (when a given post is selected to be edited
 * in the WP admin.)
 *
 * This function exists primarily to help toggle necessary meta fields.
 * single-story.php will still handle old stories that do not have a set
 * 'story_template' value appropriately.
 **/
function set_template_for_fall_2013_or_earlier($post) {
	if ($post->post_type == 'story' && is_fall_2013_or_older($post)) {
		if (get_post_meta($post->ID, 'story_template', TRUE) == '') {
			update_post_meta($post->ID, 'story_template', 'custom');
		}
	}
}
add_action('edit_form_after_editor', 'set_template_for_fall_2013_or_earlier');


/**
 * Force the WYSIWYG editor's kitchen sink to always be open.
 **/
function unhide_kitchensink( $args ) {
	$args['wordpress_adv_hidden'] = false;
	return $args;
}
add_filter('tiny_mce_before_init', 'unhide_kitchensink');

?>