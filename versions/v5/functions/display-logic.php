<?php
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
				'class' => 'fp-feature-img center-block img-fluid',
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
<article class="fp-feature <?php echo $css_class; ?>">
	<a class="fp-feature-link" href="<?php echo get_permalink( $story->ID ); ?>">
		<?php if ( $thumbnail ): ?>
		<div class="fp-feature-img-wrap">
			<?php echo $thumbnail; ?>

			<?php if ( $show_vertical && $vertical ): ?>
			<span class="fp-vertical">
				<?php echo $vertical; ?>
			</span>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</a>
	<div class="fp-feature-text-wrap">
		<<?php echo $heading; ?> class="fp-feature-title">
			<a class="fp-feature-link" href="<?php echo get_permalink( $story->ID ); ?>">
				<?php echo $title; ?>
			</a>
		</<?php echo $heading; ?>>
		<div class="fp-feature-description">
			<?php echo $description; ?>
		</div>
	</div>
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
<article class="fp-today-feed-item">
	<a class="fp-today-item-link" href="<?php echo $url; ?>">
		<div class="publish-date"><?php echo $publish_date; ?></div>
		<?php echo $title; ?>
	</a>
</article>
<?php 	return ob_get_clean();
}


/**
 * Displays a single event item on the front page.
 **/
function display_front_page_event( $event ) {
	$start = strtotime( $event['starts'] );
	$description = substr( strip_tags( $event['description'] ), 0, 250 );
	if ( strlen( $description ) === 250 ) {
		$description .= '...';
	}

	ob_start();
?>
<div class="fp-event">
	<div class="fp-event-when">
		<span class="fp-event-day"><?php echo date( 'D', $start ); ?></span>
		<span class="fp-event-date"><?php echo date( 'd', $start ); ?></span>
		<span class="fp-event-month"><?php echo date( 'M', $start ); ?></span>
	</div>
	<div class="fp-event-content">
		<span class="fp-vertical"><?php echo $event['category']; ?></span>
		<span class="fp-event-title">
			<a class="fp-event-link" href="<?php echo $event['url']; ?>"><?php echo $event['title']; ?></a>
		</span>
		<div class="fp-event-description">
			<?php echo $description; ?>
		</div>
	</div>
</div>
<?php 	return ob_get_clean();
}


/**
 * Displays a single featured gallery on the front page.
 **/
function display_front_page_gallery( $gallery, $css_class='' ) {
	if ( !$gallery ) { return false; }

	$title = wptexturize( $gallery->post_title );

	$vertical = get_the_category( $gallery->ID );
	if ( $vertical ) {
		$vertical = $vertical[0];
		$vertical = wptexturize( $vertical->name );
	}

	$thumbnail = null;
	if ( get_relevant_version( $gallery ) >= 5 ) {
		// Version 5+: fetch featured image ID
		$thumbnail_id = get_post_thumbnail_id( $gallery );
		$thumbnail_size = 'frontpage-featured-gallery-thumbnail-3x2';
	} else {
		// Version 4 and prior: get the ID from the
		// `story_frontpage_gallery_thumb` meta field
		$thumbnail_id = get_post_meta( $gallery->ID, 'story_frontpage_gallery_thumb', true );
		$thumbnail_size = 'frontpage-featured-gallery-thumbnail';
	}

	if ( $thumbnail_id ) {
		$thumbnail = wp_get_attachment_image(
			$thumbnail_id,
			$thumbnail_size,
			false,
			array(
				'class' => 'img-responsive center-block fp-gallery-img',
				'alt' => '' // Intentionally blank to avoid redundant story title announcement
			)
		);
	}

	ob_start();
?>
	<article class="fp-gallery <?php echo $css_class; ?>">
		<a class="fp-gallery-link" href="<?php echo get_permalink( $gallery->ID ); ?>">
			<h2 class="fp-heading fp-gallery-heading"><?php echo $title; ?></h2><?php if ( $vertical ): ?><span class="fp-vertical"><?php echo $vertical; ?></span><?php endif; ?>
			<?php if ( $thumbnail ): ?>
				<?php echo $thumbnail; ?>
			<?php endif; ?>
		</a>
	</article>
<?php 	return ob_get_clean();
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
	<a class="fp-issue-link" href="<?php echo get_permalink( $current_issue->ID ); ?>">
		<h2 class="h3 fp-subheading fp-issue-title">In This Issue</h2>

		<?php if ( $current_issue_thumbnail ): ?>
		<img class="img-responsive center-block fp-issue-img" src="<?php echo $current_issue_thumbnail; ?>" alt="<?php echo $current_issue_title; ?>" title="<?php echo $current_issue_title; ?>">
		<?php endif; ?>
	</a>

	<?php if ( $current_issue_title ): ?>
	<div class="fp-issue-title">
		<?php echo $current_issue_title; ?>
	</div>
	<?php endif; ?>
<?php 	return ob_get_clean();
}
