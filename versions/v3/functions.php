<?php
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
						<?php if ( !empty( $subtitle ) ) { ?>
						<span class="subtitle"><?php echo strip_tags( $subtitle, '<b><em><i><u><strong>'); ?></span>
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


/**
 * Used in output_header_markup() to print default story template
 * style declarations.  $font is expected to be a value returned from
 * get_default_template_font_styles().
 **/
function get_default_template_font_css( $font ) {
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
	return $output;
}

?>
