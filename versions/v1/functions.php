<?php

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

	$top_stories     = get_issue_stories($issue, array('exclude' => $exclude, 'numberposts' => 4));
	$top_stories_ids = array_merge(array_map(create_function('$p', 'return $p->ID;'), $top_stories), $exclude);
	$bottom_stories  = get_issue_stories($issue, array('exclude' => $top_stories_ids, 'numberposts' => 6));
	return array('top_stories' => $top_stories, 'bottom_stories' => $bottom_stories);
}


/*
 * Displays a list of stories in the current relevant issue.
 * List is swipe/touch friendly and spans the full width of the screen.
 */
function display_story_list($issue, $class=null) {
	$class = !empty($class) ? $class : '';
	if ($issue) {
		$stories = get_issue_stories($issue);
		ob_start();

		if ($stories) { ?>
			<div class="story-list <?=$class?>">
			<?php
			$count = 0;
			foreach ($stories as $story) {
				$count++;

				$title = $story->post_title;
				$subtitle = get_post_meta($story->ID, 'story_subtitle', TRUE);
				$thumb = get_featured_image_url($story->ID);
			?>
				<article<?php if ($count == count($stories)) { ?> class="last-child"<?php } ?>>
					<a href="<?=get_permalink($story)?>">
						<?php if ($thumb) { ?>
						<img class="lazy" data-original="<?=$thumb?>" alt="<?=$title?>" title="<?=$title?>" />
						<?php } ?>
						<h3 class="story-title"><?=$title?></h3>
						<?php if (!empty($subtitle)) { ?>
						<span class="subtitle"><?=$subtitle?></span>
						<?php } ?>
					</a>
				</article>
			<?php
			}
			?>
			</div>
		<?php
		}
		else {
		?>
			<p>No stories found.</p>
		<?php
		}

		return ob_get_clean();
	}
	else { return null; }
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
    </aside>
    <?php
    return ob_get_clean();
}


/*
 * Get home page/story stylesheet markup for the header
 *
 * @return string
 * @author Jo Greybill
 */
function output_header_markup_v1($post) {
	$output = '';

	// Page stylesheet
	if ( $post->post_type == 'page' && !empty( $page_stylesheet_url ) ) {
		$page_stylesheet_url = Page::get_stylesheet_url( $post );
		if ( !empty( $page_stylesheet_url ) ) {
			$output .= '<link rel="stylesheet" href="'.$page_stylesheet_url.'" type="text/css" media="all" />';
		}
	}

	if (!is_search() && !is_404()) {
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
				$font = get_default_template_font_styles( $post );

				if ($font['url']) {
					$output .= '<link rel="stylesheet" href="'.$font['url'].'" type="text/css" media="all" />';
				}

				$output .= '<style type="text/css">';
				$output .= '
					article.story h1,
					article.story h2,
					article.story h3,
					article.story h4,
					article.story h5,
					article.story h6 {
						font-family: '.$font['font-family'].';
						font-weight: '.$font['font-weight'].';
						text-transform: '.$font['text-transform'].';
						font-style: '.$font['font-style'].';
						letter-spacing: '.$font['letter-spacing'].';
					}
					article.story .lead::first-letter {
						font-family: '.$font['font-family'].';
						font-weight: '.$font['font-weight'].';
						text-transform: '.$font['text-transform'].';
						font-style: '.$font['font-style'].';
						letter-spacing: '.$font['letter-spacing'].';
					}
					article.story .lead:first-letter {
						font-family: '.$font['font-family'].';
						font-weight: '.$font['font-weight'].';
						text-transform: '.$font['text-transform'].';
						font-style: '.$font['font-style'].';
						letter-spacing: '.$font['letter-spacing'].';
					}
					article.story h1,
					article.story h2,
					article.story h3,
					article.story h4,
					article.story h5,
					article.story h6,
					article.story blockquote,
					article.story blockquote p {
						color: '.$font['color'].';
					}
					article.story .lead::first-letter { color: '.$font['color'].'; }
					article.story .lead:first-letter { color: '.$font['color'].'; }
					article.story h1 {
						font-size: '.$font['size-desktop'].';
					}
			        article.story .ss-closing-overlay {
			            font-family: '.$font['font-family'].';
			            font-weight: '.$font['font-weight'].';
			            text-transform: '.$font['text-transform'].';
			        }
					@media (max-width: 979px) {
						article.story h1 {
							font-size: '.$font['size-tablet'].';
						}
					}
					@media (max-width: 767px) {
						article.story h1 {
							font-size: '.$font['size-mobile'].';
						}
					}
				';
				$output .= get_all_font_classes();
				$output .= '</style>';
			}
		}

		// DEPRECATED:  Issue-wide stylesheet (on issue cover page)
		if( (is_home() || $post->post_type == 'issue') ) {
			$issue_stylesheet_url = Issue::get_issue_stylesheet_url($post);
			$dev_issue_directory = get_post_meta($post->ID, 'issue_dev_issue_asset_directory', TRUE);
			if ( !empty($issue_stylesheet_url) ) {
				$output .= '<link rel="stylesheet" href="'.$issue_stylesheet_url.'" type="text/css" media="all" />';
			}
			elseif ( DEV_MODE == 1 && !empty($dev_issue_directory) ) {
				$dev_issue_stylesheet_url = THEME_DEV_URL.'/'.$dev_issue_directory.$post->post_name.'.css';
				if (curl_exists($dev_issue_stylesheet_url)) {
					$output .= '<link rel="stylesheet" href="'.$dev_issue_stylesheet_url.'" type="text/css" media="all" />';
				}
			}
		}
		// DEPRECATED:  Issue-wide stylesheet (on story)
		if( $post->post_type == 'story' ) {
			$story_issue = get_story_issue( $post );
			$issue_stylesheet_url = Issue::get_issue_stylesheet_url($story_issue);
			$dev_issue_directory = get_post_meta($story_issue->ID, 'issue_dev_issue_asset_directory', TRUE);
			if ( ($story_issue = get_story_issue($post)) !== False && !empty($issue_stylesheet_url)) {
				$output .= '<link rel="stylesheet" href="'.$issue_stylesheet_url.'" type="text/css" media="all" />';
			}
			elseif (
				($story_issue = get_story_issue($post)) !== False &&
				DEV_MODE == 1 &&
				!empty($dev_issue_directory))
				{
					$dev_issue_home_stylesheet_url = THEME_DEV_URL.'/'.$dev_issue_directory.$story_issue->post_name.'.css';
					if (curl_exists($dev_issue_home_stylesheet_url)) {
						$output .= '<link rel="stylesheet" href="'.$dev_issue_home_stylesheet_url.'" type="text/css" media="all" />';
					}
			}
		}

		// Custom issue page-specific stylesheet
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

		// Custom story stylesheet
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


/**
 * Displays an issue cover or story contents.  Accounts for whether or
 * not Developer Mode is on/off and what story/issue template is set.
 *
 * Note: as of v3, check_backward_compatibility() should set all v1 story template
 * values to 'custom' if they are not already set.
 *
 * Note: files pulled via file_get_contents() here should be requested over http.
 *
 * Markup priority: Uploaded HTML File -> WYSIWYG editor content -> dev directory content
 **/
function display_markup_or_template_v1($post) {
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
			// Use WYSIWYG editor contents for old stories that don't have a
			// field value set
			the_content();
		}
	}
}


/**
 * Adds various allowed tags to WP's allowed tags list.
 *
 * Add elements and attributes to this list if WordPress' filters refuse to
 * parse those elems/attributes, or shortcodes within them, as expected.
 *
 * NOTE - use add_kses_whitelisted_attributes() in the root functions.php
 * instead to apply elem/attr changes on all versions.
 **/
global $allowedposttags;

function v1_add_kses_whitelisted_attributes( $allowedposttags, $context ) {
	if ( $context == 'post' ) {
		$allowedposttags['div'] = array(
			'data-background-url' => true,
			'data-bg-large' => true,
			'data-bg-small' => true,
			'data-body-bg' => true,
			'data-img-url' => true,
		);
		$allowedposttags['li'] = array(
			'data-mobile-img' => true,
			'data-normal-img' => true
		);
	}
	return $allowedposttags;
}
add_filter( 'wp_kses_allowed_html', 'v1_add_kses_whitelisted_attributes', 11, 2 );

?>
