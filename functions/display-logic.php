<?php
/**
 * Display logic for version 6+.
 */


/**
 * Displays a story callout/link.
 *
 * @since 6.0.0
 * @author Cadie Stockman
 * @return string HTML markup for the story
 **/
function display_story_callout( $story, $css_class='', $show_category=false, $thumbnail_size='frontpage-story-thumbnail', $heading='h3' ) {
	if ( ! $story ) return false;

	$thumbnail = null;
	$thumbnail_id = get_front_page_story_thumbnail_id( $story );

	if ( $thumbnail_id ) {
		$thumbnail = wp_get_attachment_image(
			$thumbnail_id,
			$thumbnail_size,
			false,
			array(
				'class' => 'img-fluid w-100 hover-child-filter-brightness',
				'alt' => '' // Intentionally blank to avoid redundant story title announcement
			)
		);
	}

	$title = wptexturize( $story->post_title );

	$description = '';
	if ( $story_subtitle = get_post_meta( $story->ID, 'story_subtitle', true ) ) {
		$description = wptexturize( strip_tags( $story_subtitle, '<b><em><i><u><strong><a>' ) );
	} elseif ( $story_description = get_post_meta( $story->ID, 'story_description', true ) ) {
		$description = wptexturize( strip_tags( $story_description, '<b><em><i><u><strong><a>' ) );
	}

	$category = null;
	if ( $show_category ) {
		$category = get_the_category( $story->ID );
		if ( $category ) {
			$category = $category[0];
			$category = wptexturize( $category->name );
		}
	}

	ob_start();
?>
<article class="story-callout <?php echo $css_class; ?> hover-parent">
	<a class="story-callout-link" href="<?php echo get_permalink( $story->ID ); ?>">
		<div class="story-callout-text-wrap">
			<<?php echo $heading; ?> class="story-callout-title">
				<?php echo $title; ?>
			</<?php echo $heading; ?>>
			<div class="story-callout-description">
				<?php echo $description; ?>
			</div>
		</div>

		<?php if ( $thumbnail ): ?>
		<div class="story-callout-img-wrap">
			<?php echo $thumbnail; ?>

			<?php if ( $show_category && $category ): ?>
			<span class="story-callout-category badge badge-primary">
				<?php echo $category; ?>
			</span>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</a>
</article>
<?php
return ob_get_clean();
}


/**
 * Displays a single Today article on the front page.
 **/
function display_front_page_today_story( $article ) {
	$url = $article->get_link();
	$title = $article->get_title();
	$publish_date = $article->get_date('m/d');

	ob_start();
?>
<article aria-label="<?php echo esc_attr( $title ); ?>">
	<a href="<?php echo $url; ?>">
		<time class="fp-today-item-date" datetime="<?php echo $publish_date; ?>">
			<?php echo $publish_date; ?>
		</time>
		<strong class="fp-today-item-title">
			<?php echo $title; ?>
		</strong>
	</a>
</article>
<?php
	return ob_get_clean();
}


/**
 * Displays the current issue's thumbnail and description, for use in the
 * "In This Issue" section of the front page.
 **/
function display_front_page_issue_details() {
	$current_issue = get_current_issue();
	$current_issue_title = wptexturize( $current_issue->post_title );
	$current_issue_thumbnail = get_featured_image_url( $current_issue->ID, 'full' );
	$current_issue_cover_story = get_post_meta( $current_issue->ID, 'issue_cover_story', true );

	ob_start();
?>
	<div class="fp-issue position-relative mb-4 mb-md-0 hover-parent">
		<h2 class="fp-issue-title text-uppercase my-3">In This Issue</h2>

		<?php if ( $current_issue_thumbnail ): ?>
		<img class="img-fluid hover-child-filter-brightness" src="<?php echo $current_issue_thumbnail; ?>" alt="">
		<?php endif; ?>

		<?php if ( $current_issue_title ): ?>
		<a class="fp-issue-title stretched-link d-block font-weight-bold text-secondary text-uppercase my-2 my-lg-3" href="<?php echo get_permalink( $current_issue->ID ); ?>">
			<?php echo $current_issue_title; ?>
		</a>
		<?php endif; ?>

		<hr class="hr-primary hr-3 w-25 w-sm-50 mt-0">
	</div>

<?php
	return ob_get_clean();
}


/**
 * Displays events markup for the Pegasus homepage.
 * Ported over from Today-Child-Theme.
 *
 * @since 6.0.0
 * @author Jo Dickson
 * @return string HTML markup for the events list
 */
function get_home_events() {
	$content   = '';
	$attrs     = array_filter( array(
		'feed_url' => get_theme_option( 'front_page_events_feed_url', '' ),
		'layout'   => 'modern_date',
		'limit'    => 3
	) );
	$attr_str  = '';

	$attrs['title'] = '';

	foreach ( $attrs as $key => $val ) {
		$attr_str .= ' ' . $key . '="' . $val . '"';
	}

	$content = do_shortcode( '[ucf-events' . $attr_str . ']No events found.[/ucf-events]' );

	return $content;
}


/**
 * Displays Featured Gallery markup for the Pegasus homepage.
 * Ported over from the Today-Child-Theme.
 *
 * @since 6.0.0
 * @author Jo Dickson
 * @param string Post ID for gallery
 * @return string HTML markup for the featured gallery
 */
function get_home_gallery( $gallery ) {

	ob_start();
?>
	<?php
	if ( $gallery ) :
		$gallery        = get_post( $gallery );
		$vertical       = get_the_category( $gallery->ID )[0] ?? '';
		$thumbnail      = '';
		$thumbnail_id   = get_post_thumbnail_id( $gallery );
		$thumbnail_size = 'frontpage-featured-gallery-thumbnail-3x2';

		if ( $thumbnail_id ) {
			$thumbnail = wp_get_attachment_image(
				$thumbnail_id,
				$thumbnail_size,
				false,
				array(
					'class' => 'img-fluid fp-gallery-img hover-child-filter-brightness d-block mx-auto mt-2',
					'alt' => '' // Intentionally blank to avoid redundant story title announcement
				)
			);
		}
	?>
	<div class="card border-0 bg-faded mx-auto hover-parent">
		<div class="card-block p-4">
			<a class="stretched-link" href="<?php echo get_permalink( $gallery ); ?>">
				<h2 class="text-secondary"><?php echo $gallery->post_title; ?></h2>
			</a>

			<?php if ( $vertical ) : ?>
			<span class="badge badge-primary"><?php echo wptexturize( $vertical->name ); ?></span>
			<?php endif; ?>

			<?php echo $thumbnail; ?>
			</a>
		</div>
	</div>
	<?php endif; ?>
<?php
	return trim( ob_get_clean() );
}
