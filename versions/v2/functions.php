<?php /**
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
 * Displays a list of stories in the current relevant issue.
 * List is swipe/touch friendly and spans the full width of the screen.
 */
function display_story_list($issue, $class=null) {
	$class = !empty($class) ? $class : '';
	if ($issue) {
		$stories = get_issue_stories($issue);
		ob_start();

		if ($stories) { ?>
			<div class="story-list <?php echo $class?>">
			<?php 			$count = 0;
			foreach ($stories as $story) {
				$count++;

				$title = $story->post_title;
				$subtitle = get_post_meta($story->ID, 'story_subtitle', TRUE);
				$thumb = get_featured_image_url($story->ID);
			?>
				<article<?php if ($count == count($stories)) { ?> class="last-child"<?php } ?>>
					<a href="<?php echo get_permalink($story)?>">
						<?php if ($thumb) { ?>
						<img class="lazy" data-original="<?php echo $thumb?>" alt="<?php echo $title?>" title="<?php echo $title?>" />
						<?php } ?>
						<h3 class="story-title"><?php echo $title?></h3>
						<?php if (!empty($subtitle)) { ?>
						<span class="subtitle"><?php echo $subtitle?></span>
						<?php } ?>
					</a>
				</article>
			<?php 			}
			?>
			</div>
		<?php 		}
		else {
		?>
			<p>No stories found.</p>
		<?php 		}

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
    <aside class="social" aria-label="Share">
        <a class="share-facebook" target="_blank" data-button-target="<?php echo $url?>" href="http://www.facebook.com/sharer.php?u=<?php echo $url?>" title="Like this story on Facebook">
            Like "<?php echo $title?>" on Facebook
        </a>
        <a class="share-twitter" target="_blank" data-button-target="<?php echo $url?>" href="https://twitter.com/intent/tweet?text=<?php echo $tweet_title?>&url=<?php echo $url?>" title="Tweet this story">
            Tweet "<?php echo $title?>" on Twitter
        </a>
    </aside>
    <?php     return ob_get_clean();
}


/**
 * Used in output_header_markup() to print default story template
 * style declarations.  $font is expected to be a value returned from
 * get_default_template_font_styles().
 **/
function get_default_template_font_css( $font ) {
	$output = '
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
	return $output;
}

?>
