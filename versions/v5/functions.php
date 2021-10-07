<?php
/*
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
			<?php
			$count = 0;
			foreach ( $stories as $story ) {
				$count++;

				$title = wptexturize( $story->post_title );
				$subtitle = wptexturize( strip_tags( get_post_meta( $story->ID, 'story_subtitle', TRUE ) ) );
				$thumb = get_featured_image_url( $story->ID, 'single-post-thumbnail-3x2' );
			?>
				<article<?php if ( $count == count( $stories ) ) { ?> class="last-child"<?php } ?>>
					<a href="<?php echo get_permalink( $story ); ?>">
						<?php if ( $thumb ) { ?>
						<img class="lazy" data-original="<?php echo $thumb; ?>" alt="" />
						<?php } ?>
						<h3 class="story-title"><?php echo $title; ?></h3>
						<?php if ( !empty( $subtitle ) ) { ?>
						<span class="subtitle"><?php echo $subtitle; ?></span>
						<?php } ?>
					</a>
				</article>
			<?php
			}
			?>
			</div>
		<?php
		} else {
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

function v5_add_kses_whitelisted_attributes( $allowedposttags, $context ) {
	if ( $context == 'post' ) {
		// $allowedposttags['h3'] = array(
		// 	'data-elevation-image' => true
		// );

		// $allowedposttags['div'] = array(
		// 	'data-inc-us' => true,
		// 	'data-inc-world' => true,
		// 	'data-out-us' => true,
		// 	'data-out-world' => true,
		// 	'data-marker-img' => true
		// );
	}
	return $allowedposttags;
}
add_filter( 'wp_kses_allowed_html', 'v5_add_kses_whitelisted_attributes', 11, 2 );


/**
 * Displays a single photo in a full photo essay or photo essay story.
 **/
function display_photo_essay_item( $orientation, $item_id, $image_url, $title, $alt, $caption, $alternate=false ) {
	ob_start();
?>
	<figure class="photo-essay-item photo-essay-item-<?php echo $orientation; ?> <?php if ( $alternate ) { ?>alternate<?php } ?>" id="<?php echo $item_id; ?>">
	<?php
	switch ( $orientation ):
		case 'portrait':
	?>
		<div class="row">
			<div class="img-col col-md-7 col-md-offset-0 col-sm-10 col-sm-offset-1 <?php if ( $alternate ) { ?>col-md-push-5<?php } ?>">
				<img class="photo-essay-img" src="<?php echo $image_url; ?>" alt="<?php echo $alt; ?>" title="<?php echo $title; ?>">
				<div class="carat"></div>
			</div>
			<div class="caption-col col-md-4 col-sm-12 <?php if ( $alternate ) { ?>col-md-pull-7 col-md-offset-1<?php } else { ?>col-md-offset-0<?php  } ?>">
				<figcaption class="photo-essay-caption">
					<?php echo $caption; ?>
				</figcaption>
			</div>
		</div>
	<?php
			break;
		case 'landscape':
		case 'square':
		default:
	?>
		<div class="row">
			<div class="img-col col-md-12">
				<img class="photo-essay-img" src="<?php echo $image_url; ?>" alt="<?php echo $alt; ?>" title="<?php echo $title; ?>">
				<div class="carat"></div>
			</div>
			<div class="caption-col col-md-12">
				<figcaption class="photo-essay-caption">
					<?php echo $caption; ?>
				</figcaption>
			</div>
		</div>
	<?php
			break;
	endswitch;
	?>
	</figure>
<?php
	return ob_get_clean();
}


/**
 * Displays a jump link on a photo essay.
 */
function display_photo_essay_navitem( $item_id, $image_thumb_url ) {
	ob_start();
?>
	<a class="photo-essay-nav-link" href="#<?php echo $item_id; ?>">
		<img class="photo-essay-nav-thumb" src="<?php echo $image_thumb_url; ?>" alt="Jump to image" title="Jump to image">
	</a>
<?php
	return ob_get_clean();
}


/**
 * Displays a scrollable full photo essay or photo essay story.
 */
function display_photo_essay( $photo_essay, $story=null ) {
	$slide_order = trim( get_post_meta( $photo_essay->ID, 'ss_slider_slideorder', TRUE ) );
	// Get rid of blank array entries
	$slide_order = array_filter( explode( ',', $slide_order ), 'strlen' );
	$captions = get_post_meta( $photo_essay->ID, 'ss_slide_caption', TRUE );
	$titles = get_post_meta( $photo_essay->ID, 'ss_slide_title', TRUE );
	$images = get_post_meta( $photo_essay->ID, 'ss_slide_image', TRUE );
	$photo_essay_markup = '';
	$nav_markup = '';

	$count = 0;
	foreach ( $slide_order as $i ) {
		$image = wp_get_attachment_image_src( $images[$i], 'full' );
		$image_url = $image[0];
		$image_w = $image[1];
		$image_h = $image[2];
		$image_thumb = wp_get_attachment_image_src( $images[$i], 'thumbnail' );
		$image_thumb_url = $image_thumb[0];
		$caption = isset( $captions[$i] ) ? wptexturize( do_shortcode( $captions[$i] ) ) : '';
		$title = wptexturize( $titles[$i] );
		$alt = $title ? $title : get_post_meta($images[$i], '_wp_attachment_image_alt', TRUE);
		$item_id = 'photo-' . sanitize_title( $title ? $title : $i );
		$orientation = '';
		$alternate = false;

		if ( $image_w > $image_h ) {
			$orientation = 'landscape';
		}
		else if ( $image_w < $image_h ) {
			$orientation = 'portrait';
		}
		else {
			$orientation = 'square';
		}

		// Alternate every other slide (odd-numbered indexes)
		if ( $count % 2 !== 0 ) {
			$alternate = true;
		}

		$photo_essay_markup .= display_photo_essay_item( $orientation, $item_id, $image_url, $title, $alt, $caption, $alternate );

		$nav_markup .= display_photo_essay_navitem( $item_id, $image_thumb_url );

		$count++;
	}

	if ( $story ) {
		$header_contents = display_story_header_contents( $story );
	} else {
		$header_img_id = $images[$slide_order[0]];
		$deck = wptexturize( $photo_essay->post_content );

		$header_contents = display_story_header_contents( $photo_essay, $header_img_id, $deck );
	}

	ob_start();
?>

	<section id="photo-essay-top" class="clearfix">
		<div class="container">
			<?php echo $header_contents; ?>
		</div>
	</section>

	<section class="photo-essay-contents">
		<div class="container">
			<div class="row">
				<div class="col-md-10 col-sm-10">
					<?php echo $photo_essay_markup; ?>
				</div>
				<div class="col-md-2 col-sm-2 navbar-col">
					<nav id="photo-essay-navbar" class="photo-essay-nav">
						<?php echo $nav_markup; ?>
						<a class="photo-essay-jump photo-essay-nav-link" id="photo-essay-jump-top" href="#">
							<span class="fa fa-long-arrow-up"></span>
							<span class="sr-only">Jump </span>to top
						</a>
					</nav>
				</div>
			</div>
		</div>
	</section>

	<div id="photo-essay-bottom"></div>
<?php
	return ob_get_clean();
}


/**
 * Displays a slideshow using photo essay photos.
 **/
function display_photo_essay_slideshow( $photo_essay, $slug=null, $caption_color=null ) {
	$slide_order = trim( get_post_meta( $photo_essay->ID, 'ss_slider_slideorder', TRUE ) );
	// Get rid of blank array entries
	$slide_order = array_filter( explode( ',', $slide_order ), 'strlen' );
	$slide_caption = get_post_meta( $photo_essay->ID, 'ss_slide_caption', TRUE );
	$slide_title = get_post_meta( $photo_essay->ID, 'ss_slide_title', TRUE );
	$slide_image = get_post_meta( $photo_essay->ID, 'ss_slide_image', TRUE );

	if ( !$slug ) {
		$slug = 'slideshow-' . $photo_essay->post_name;
	}

	$slide_count = count( $slide_order );
	$ss_half = floor( $slide_count / 2 ) + 1;
	$end = false;
	$i = $ss_half;
	$photo_essay_offset = 0;

	ob_start();
?>
	<section class="ss-content" id="<?php echo $slug; ?>">
		<div class="ss-nav-wrapper">
			<div class="ss-arrow-wrapper ss-arrow-wrapper-left">
				<a class="ss-arrow ss-arrow-prev ss-last"><div>&lsaquo;</div></a>
			</div>
			<div class="ss-arrow-wrapper ss-arrow-wrapper-right">
				<a class="ss-arrow ss-arrow-next" href="#2"><div>&rsaquo;</div></a>
			</div>

			<div class="ss-slides-wrapper">
			<?php
			while ( $end === false ) {
				if ( $i == $slide_count ) {
					$i = 0;
				}

				if ( $i == $ss_half - 1 ) {
					$end = true;
				}

				$s = $slide_order[$i];
				$image = wp_get_attachment_image_src( $slide_image[$s], 'full' );
				$alt = $slide_title[$s] ? $slide_title[$s] : get_post_meta($slide_image[$s], '_wp_attachment_image_alt', TRUE);
				?>
				<div class="ss-slide-wrapper">
					<div class="ss-slide<?php echo $i + $photo_essay_offset == 0 ? ' ss-first-slide ss-current' : ''; ?><?php echo $i == $slide_count - 1 ? ' ss-last-slide' : ''; ?>" data-id="<?php echo $i + 1 + $photo_essay_offset; ?>" data-width="<?php echo $image[1]; ?>" data-height="<?php echo $image[2]; ?>">
						<img src="<?php echo $image[0]; ?>" alt="<?php echo $alt; ?>" />
					</div>
				</div>
			<?php
				$i++;
			}
			?>
			</div>

			<div class="ss-captions-wrapper">
			<?php
			$data_id = 0;
			foreach ( $slide_order as $s ) {
				if ( $s !== '' ) {
					$data_id++;
			?>
				<div class="ss-caption <?php echo $data_id == 1 ? ' ss-current' : ''; ?>" data-id="<?php echo $data_id; ?>">
					<p class="caption"<?php if ( $caption_color ) { ?> style="color: <?php echo $caption_color; ?>;"<?php } ?>><?php echo wptexturize( $slide_caption[$s] ); ?></p>
				</div>
			<?php
				}
			}
			?>
			</div>

			<div class="ss-closing-overlay" style="display: none;">
				<div class="ss-slide" data-id="restart-slide">
					<a class="ss-control ss-restart" href="#1"><i class="repeat-alt-icon"></i><div>REPLAY</div></a>
				</div>
			</div>
		</div>
	</section>
<?php 	return ob_get_clean();
}


/*
 * Wrap YouTube, Facebook, Twitter and Instagram embeds
 */
function wrap_social_embeds( $html, $url ) {
	if ( strpos( $url, 'youtube.com' ) !== false || strpos( $url, 'youtu.be' ) !== false ) {
		return '<div class="embed-responsive embed-responsive-16by9">' . $html . '</div>';
	} elseif ( strpos( $url, 'facebook.com' ) !== false ) {
		return '<div class="text-center">' . $html . '</div>';
	} elseif ( strpos( $url, 'twitter.com' ) !== false
		|| strpos( $url, 'instagram.com' ) !== false ) {
		return '<div class="centered-embed">' . $html . '</div>';
	} else {
		return $html;
	}
}
add_filter( 'embed_oembed_html', 'wrap_social_embeds', 10, 3 );


/**
 * Returns an attachment ID corresponding to the determined
 * header image for the given story.
 *
 * @since 5.0.0
 * @author Jo Dickson
 * @param object $story WP_Post object
 * @return int|null Attachment ID, or null if no image is available
 */
function get_story_header_image_id( $story ) {
	if ( ! $story instanceof WP_Post ) { return null; }

	$header_img_id   = intval( get_post_meta( $story->ID, 'story_default_header_img', true ) );
	$featured_img_id = get_post_thumbnail_id( $story );
	$img_id          = null;

	// Make sure the attachment corresponding to the ID in
	// `story_default_header_img` still exists/hasn't been deleted:
	if ( $header_img_id ) {
		$header_img_attachment = get_post( $header_img_id );
		if ( $header_img_attachment ) {
			$img_id = $header_img_id;
		}
	} else {
		$img_id = $featured_img_id;
	}

	return $img_id;
}


/**
 * Returns markup for inner header contents for stories.
 * Header image ID and deck are overridable to support usage
 * on single photo essays.
 *
 * @since 5.0.0
 * @author Jo Dickson
 * @param object $post WP_Post object
 * @param int $header_img_id Custom attachment ID to reference when fetching the post's header image
 * @param string $deck Custom deck content to display for this post
 * @return string HTML content
 */
function display_story_header_contents( $post, $header_img_id=0, $deck='' ) {
	if ( ! $header_img_id ) {
		$header_img_id = get_story_header_image_id( $post );
	}
	$header_img = wp_get_attachment_image(
		$header_img_id,
		'story-featured-image',
		false,
		array(
			'class' => 'story-header-image',
			'alt' => ''
		)
	);
	if ( ! $deck ) {
		$deck = wptexturize( get_post_meta( $post->ID, 'story_description', true ) );
	}

	ob_start();
?>
	<div class="row header-img-wrap">
		<div class="col-md-10 col-sm-10 col-md-offset-1 col-sm-offset-1">
			<?php echo $header_img; ?>
		</div>
	</div>
	<div class="row title-wrap">
		<div class="col-md-10 col-sm-10 col-md-offset-1 col-sm-offset-1">
			<h1><?php echo wptexturize( $post->post_title ); ?></h1>
		</div>
	</div>
	<div class="row description-wrap">
		<div class="col-md-10 col-sm-10 col-md-offset-1 col-sm-offset-1">
			<div class="row">
				<div class="col-md-8 col-sm-12 col-xs-12 description-col">
					<span class="description">
						<?php echo $deck; ?>
					</span>
				</div>
				<div class="col-md-4 col-sm-12 col-xs-12 description-col">
					<div class="social-wrap">
						<?php echo display_social( get_permalink( $post->ID ), $post->post_title ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
	return ob_get_clean();
}

?>
