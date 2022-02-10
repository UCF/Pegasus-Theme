<?php
/*
 * Displays a list of stories in the current relevant issue.
 * List is swipe/touch friendly and spans the full width of the screen.
 */
function display_story_list( $issue, $class='', $stories=array() ) {
	if ( ! $issue ) return '';

	$class   = ! empty( $class ) ? $class : '';
	$stories = ! empty( $stories ) ? $stories : get_issue_stories( $issue );

	ob_start();
?>
	<?php if ( $stories ) : ?>
	<div class="story-list <?php echo $class; ?>">
		<?php
		foreach ( $stories as $story ) {
			echo display_story_callout( $story, '', false, 'single-post-thumbnail-3x2' );
		}
		?>
	</div>
	<?php else: ?>
	<p>No stories found.</p>
	<?php endif; ?>
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

function v6_add_kses_whitelisted_attributes( $allowedposttags, $context ) {
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
add_filter( 'wp_kses_allowed_html', 'v6_add_kses_whitelisted_attributes', 11, 2 );


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
			<div class="img-col col-md-10 offset-md-1 col-lg-7 offset-lg-0 <?php if ( $alternate ) { ?>push-lg-5<?php } ?>">
				<img class="photo-essay-img" src="<?php echo $image_url; ?>" alt="<?php echo $alt; ?>" title="<?php echo $title; ?>">
				<div class="carat"></div>
			</div>
			<div class="caption-col col-lg-4 <?php if ( $alternate ) { ?>pull-lg-7 offset-lg-1<?php } else { ?>offset-lg-0<?php  } ?>">
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
			<div class="img-col col-lg-12">
				<img class="photo-essay-img" src="<?php echo $image_url; ?>" alt="<?php echo $alt; ?>" title="<?php echo $title; ?>">
				<div class="carat"></div>
			</div>
			<div class="caption-col col-lg-12">
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
		$deck = wptexturize( $photo_essay->post_content );
		$header_contents = display_story_header_contents( $photo_essay, $deck );
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
				<div class="col-lg-10 col-md-10">
					<?php echo $photo_essay_markup; ?>
				</div>
				<div class="col-lg-2 col-md-2 navbar-col">
					<nav id="photo-essay-navbar" class="photo-essay-nav">
						<?php echo $nav_markup; ?>
						<a class="photo-essay-jump photo-essay-nav-link" id="photo-essay-jump-top" href="#">
							<span class="fas fa-long-arrow-alt-up fa-3x d-block" aria-hidden="true"></span>
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
 * Returns an attachment ID corresponding to the determined
 * header image for the given story.
 *
 * @since 5.0.0
 * @author Jo Dickson
 * @param object $story WP_Post object
 * @return int|null Attachment ID, or null if no image is available
 */
function get_story_header_image_id( $story ) {
	// Only return an image ID for Story posts using the
	// default story template:
	if (
		! $story instanceof WP_Post
		|| ! (
			$story instanceof WP_Post
			&& $story->post_type === 'story'
			&& ! get_post_meta( $story->ID, 'story_template', true )
		)
	) {
		return null;
	}

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
 * Deck is overridable to support usage on single photo essays.
 *
 * @since 5.0.0
 * @author Jo Dickson
 * @param object $post WP_Post object
 * @param string $deck Custom deck content to display for this post
 * @return string HTML content
 */
function display_story_header_contents( $post, $deck='' ) {
	$header_img = '';
	$header_img_id = get_story_header_image_id( $post );
	if ( $header_img_id ) {
		$header_img = wp_get_attachment_image(
			$header_img_id,
			'story-featured-image',
			false,
			array(
				'class' => 'img-fluid',
				'alt' => ''
			)
		);
	}
	if ( ! $deck ) {
		$deck = wptexturize( get_post_meta( $post->ID, 'story_description', true ) );
	}

	ob_start();
?>
	<div class="row">
		<div class="col-lg-10 offset-lg-1">
			<h1 class="mb-2 mb-lg-3"><?php echo wptexturize( $post->post_title ); ?></h1>
		</div>
	</div>
	<div class="row mb-4">
		<div class="col-lg-10 offset-lg-1">
			<span class="lead">
				<?php echo $deck; ?>
			</span>
		</div>
	</div>
	<?php if ( $header_img ) : ?>
	<div class="row mb-4">
		<div class="col-12">
			<?php echo $header_img; ?>
		</div>
	</div>
	<?php endif; ?>
<?php
	return ob_get_clean();
}

?>
