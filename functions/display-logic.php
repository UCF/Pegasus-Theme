<?php
/**
 * Display logic for version 6+.
 */


/**
 * Displays a single story on the front page.
 **/
function display_front_page_story( $story, $css_class='', $show_vertical=false, $thumbnail_size='frontpage-story-thumbnail', $heading='h3' ) {
	if ( !$story ) return false;

	$thumbnail = null;
	$thumbnail_id = get_front_page_story_thumbnail_id( $story );

	if ( $thumbnail_id ) {
		$thumbnail = wp_get_attachment_image(
			$thumbnail_id,
			$thumbnail_size,
			false,
			array(
				'class' => 'img-fluid hover-child-filter-brightness',
				'alt' => '' // Intentionally blank to avoid redundant story title announcement
			)
		);
	}

	$title = wptexturize( $story->post_title );

	$description = '';
	if ( $story_description = get_post_meta( $story->ID, 'story_description', true ) ) {
		$description = wptexturize( strip_tags( $story_description, '<b><em><i><u><strong>' ) );
	}
	elseif ( $story_subtitle = get_post_meta( $story->ID, 'story_subtitle', true ) ) {
		$description = wptexturize( strip_tags( $story_subtitle, '<b><em><i><u><strong>' ) );
	}

	$vertical = null;
	if ( $show_vertical ) {
		$vertical = get_the_category( $story->ID );
		if ( $vertical ) {
			$vertical = $vertical[0];
			$vertical = wptexturize( $vertical->name );
		}
	}

	ob_start();
?>
<article class="fp-feature <?php echo $css_class; ?> hover-parent">
	<div class="fp-feature-text-wrap">
		<<?php echo $heading; ?> class="fp-feature-title">
			<a class="fp-feature-link stretched-link" href="<?php echo get_permalink( $story->ID ); ?>">
				<?php echo $title; ?>
			</a>
		</<?php echo $heading; ?>>
		<div class="fp-feature-description">
			<?php echo $description; ?>
		</div>
	</div>
	<?php if ( $thumbnail ): ?>
	<div class="fp-feature-img-wrap">
		<a class="fp-feature-link" href="<?php echo get_permalink( $story->ID ); ?>">
		<?php echo $thumbnail; ?>

		<?php if ( $show_vertical && $vertical ): ?>
		<span class="fp-vertical badge badge-primary">
			<?php echo $vertical; ?>
		</span>
		<?php endif; ?>
		</a>
	</div>
	<?php endif; ?>
</article>
<?php 	return ob_get_clean();
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
* Displays social buttons (Facebook, Twitter, G+) for front page header.
*
* @return string
* @author RJ Bruneel
**/
function display_social_header() {
	global $wp;

	$link = home_url( add_query_arg( array(), $wp->request ) );
	$fb_url = 'http://www.facebook.com/sharer.php?u=' . $link;
	$twitter_url = 'https://twitter.com/intent/tweet?text=' . urlencode( 'Pegasus Magazine' ) . '&url=' . $link;

	ob_start();
?>
	<span class="social-icon-list-heading">Share</span>
	<ul class="social-icon-list">
		<li class="social-icon-list-item">
			<a target="_blank" class="sprite facebook" href="<?php echo $fb_url; ?>">Share Pegasus Magazine on Facebook</a>
		</li>
		<li class="social-icon-list-item">
			<a target="_blank" class="sprite twitter" href="<?php echo $twitter_url; ?>">Share Pegasus Magazine on Twitter</a>
		</li>
	</ul>
<?php     return ob_get_clean();
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

