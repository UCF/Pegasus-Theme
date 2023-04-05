<?php /*
 * Displays a list of stories in the current relevant issue.
 * List is swipe/touch friendly and spans the full width of the screen.
 */
function display_story_list( $issue, $class=null ) {
	$class = !empty( $class ) ? $class : '';
	if ( $issue ) {
		$stories = get_issue_stories( $issue );
		ob_start();

		if ( $stories ) { ?>
			<div class="story-list <?php echo $class; ?>">
			<?php 			$count = 0;
			foreach ( $stories as $story ) {
				$count++;

				$title = wptexturize( $story->post_title );
				$subtitle = wptexturize( strip_tags( get_post_meta( $story->ID, 'story_subtitle', TRUE ) ) );
				$thumb = get_featured_image_url( $story->ID );
			?>
				<article<?php if ( $count == count( $stories ) ) { ?> class="last-child"<?php } ?>>
					<a href="<?php echo get_permalink( $story ); ?>">
						<?php if ( $thumb ) { ?>
						<img class="lazy" data-original="<?php echo $thumb; ?>" alt="<?php echo $title; ?>" title="<?php echo $title; ?>" />
						<?php } ?>
						<span class="h3 story-title"><?php echo $title; ?></span>
						<?php if ( !empty( $subtitle ) ) { ?>
						<span class="subtitle"><?php echo $subtitle; ?></span>
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
    <div class="social">
        <a class="share-facebook" target="_blank" data-button-target="<?php echo $url?>" href="http://www.facebook.com/sharer.php?u=<?php echo $url?>" title="Like this story on Facebook">
            Like "<?php echo $title?>" on Facebook
        </a>
        <a class="share-twitter" target="_blank" data-button-target="<?php echo $url?>" href="https://twitter.com/intent/tweet?text=<?php echo $tweet_title?>&url=<?php echo $url?>" title="Tweet this story">
            Tweet "<?php echo $title?>" on Twitter
        </a>
    </div>
    <?php
	return ob_get_clean();
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
		article.story blockquote {
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

function v3_add_kses_whitelisted_attributes( $allowedposttags, $context ) {
	if ( $context == 'post' ) {
		$allowedposttags['h3'] = array(
			'data-elevation-image' => true
		);

		$allowedposttags['div'] = array(
			'data-inc-us' => true,
			'data-inc-world' => true,
			'data-out-us' => true,
			'data-out-world' => true,
			'data-marker-img' => true
		);
	}
	return $allowedposttags;
}
add_filter( 'wp_kses_allowed_html', 'v3_add_kses_whitelisted_attributes', 11, 2 );

?>
