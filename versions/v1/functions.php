<?php

var_dump('test');

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
        <a class="share-googleplus" target="_blank" data-button-target="<?=$url?>" href="https://plus.google.com/share?url=<?=$url?>" title="Share this story on Google+">
            Share "<?=$title?>" on Google+
        </a>
    </aside>
    <?php
    return ob_get_clean();
}

?>
