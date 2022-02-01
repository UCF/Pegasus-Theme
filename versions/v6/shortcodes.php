<?php
/**
 * Display social buttons for the current page/post.
 **/
function sc_social_buttons( $attr ) {
	global $post;

	$attr = shortcode_atts( array(
		'title' => $post->post_title,
		'url'   => get_permalink( $post->ID )
	), $attr, 'social_buttons' );

	$html = display_social( $attr['url'], $attr['title'] );
	return $html;
}
add_shortcode( 'social_buttons', 'sc_social_buttons' );


/**
 * Returns the site URL.
 **/
function sc_site_url( $attr ) {
	return site_url();
}
add_shortcode( 'site-url', 'sc_site_url' );


/**
 * Modified version of Generic post type search.
 * Generates searchable lists of stories, grouped by their respective issue.
 *
 * Note that unlike the generic post type search, this shortcode accepts
 * minimal customizable parameters (columns, post querying args, etc are not
 * accepted.)
 *
 * @return string
 * @author Jo Dickson
 **/
function sc_archive_search($params=array(), $content='') {
	// Set default params, override-able by user
	$defaults = array(
		'post_type_name'         => array('story'),
		'non_alpha_section_name' => 'Other',
		'default_search_text'	 => 'Search all stories...',
	);

	// Add user-set params
	$params = ($params === '') ? $defaults : array_merge($defaults, $params);

	// Set rest of non-user-editable params
	$params = array_merge($params, array(
		'taxonomy' => 'issues',
		'column_width' => 'col-md-10 offset-md-1',
		'column_count' => '1',
		'order_by' => 'title',
		'order' => 'ASC',
	));

	// Register if the search data with the JS PostTypeSearchDataManager
	// Format is array(post->ID=>terms) where terms include the post title
	// as well as all associated tag names
	$search_data = array();
	foreach(get_posts(array('numberposts' => -1, 'post_type' => $params['post_type_name'])) as $post) {
		$search_data[$post->ID] = array($post->post_title);
		foreach(wp_get_object_terms($post->ID, 'post_tag') as $term) {
			$search_data[$post->ID][] = $term->name;
		}
	}
	?>
	<script type="text/javascript">
		if(typeof PostTypeSearchDataManager != 'undefined') {
			PostTypeSearchDataManager.register(new PostTypeSearchData(
				<?php echo json_encode($params['column_count'])?>,
				<?php echo json_encode($params['column_width'])?>,
				<?php echo json_encode($search_data)?>
			));
		}
	</script>
	<?php
	// Get posts, split them up by issue:
	global $theme_options;

	// Get all past issues, excluding the current issue
	$issues_sorted = array();
	$current_issue = get_posts(array(
		'name' => $theme_options['current_issue_cover'],
		'post_type' => 'issue',
		'numberposts' => 1,
		'post_status' => 'publish'
	));

	$issues_all = get_posts(array(
		'post_type' => 'issue',
		'numberposts' => -1,
		'orderby' => 'post_date',
		'order' => 'desc',
		'post_status' => 'publish',
		'exclude' => array($current_issue[0]->ID),
		'date_query' => array(
			array(
				'before' => $current_issue[0]->post_date,
			),
		)
	));

	foreach ( $issues_all as $issue ) {
		// Do not exclude the featured story here!  It needs to be in the
		// "More in this issue" list to show up in search results.  Hide the
		// featured story in the list with css.
		$issues_sorted[$issue->post_title] = get_posts( array(
			'numberposts' => -1,
			'post_type'   => $params['post_type_name'],
			'tax_query'   => array(
				array(
					'taxonomy' => $params['taxonomy'],
					'field'    => 'slug',
					'terms'    => $issue->post_name
				)
			),
			'orderby'	=> $params['order_by'],
			'order'     => $params['order'],
		));
	}

	ob_start();
	?>
	<div class="row post-type-search">
		<div class="col-lg-8 offset-md-2 post-type-search-header mb-4 mb-lg-5">
			<form class="post-type-search-form search-form" role="search" method="get" action="<?php echo home_url( '/' ); ?>">
				<div class="form-group mb-0">
					<label class="form-control-label sr-only" for="s">Search</label>
					<input type="text" name="s" class="form-control form-control-search search-field" id="s" placeholder="<?php echo $params['default_search_text']; ?>">
				</div>
			</form>
		</div>
		<div class="col-lg-12 post-type-search-results"></div>
		<div class="col-lg-10 offset-lg-1 post-type-search-term">
		<?php
		$issue_count = 0;

		foreach( $issues_sorted as $key => $posts ) :
			$issue = get_page_by_title( $key, 'OBJECT', 'issue' );
			$featured_article_id = intval( get_post_meta( $issue->ID, 'issue_cover_story', TRUE ) );
			$featured_article = get_post( $featured_article_id );
			$issue_count++;

			if ( $posts ) :
		?>
			<div class="row issue">
				<div class="col-lg-5">
					<div class="position-relative mb-4">
						<h2 id="<?php echo $issue->post_name; ?>">
							<a class="stretched-link text-secondary" href="<?php echo get_permalink( $issue->ID ); ?>">
								<?php echo wptexturize( $issue->post_title ); ?>
							</a>
						</h2>

						<?php if ( $thumbnail = get_the_post_thumbnail( $issue->ID, 'issue-thumbnail' ) ) : ?>
							<?php echo $thumbnail; ?>
						<?php endif; ?>
					</div>

					<?php if ( $featured_article ) : ?>
						<div class="mb-4 mb-lg-0">
							<h3 class="text-default-aw font-size-sm font-weight-normal letter-spacing-1 mb-2 text-uppercase">Featured Story</h3>
							<a class="featured-story text-secondary" href="<?php echo get_permalink( $featured_article->ID ); ?>">
								<h4 class="font-slab-serif font-weight-bold"><?php echo wptexturize( $featured_article->post_title ); ?></h4>
								<?php if ( $f_desc = get_post_meta( $featured_article->ID, 'story_description', true ) ) : ?>
									<span class="description"><?php echo wptexturize( strip_tags( $f_desc, '<b><em><i><u><strong>' ) ); ?></span>
								<?php elseif ( $f_subtitle = get_post_meta( $featured_article->ID, 'story_subtitle', TRUE ) ) : ?>
									<span class="description"><?php echo wptexturize( strip_tags( $f_subtitle, '<b><em><i><u><strong>' ) ); ?></span>
								<?php endif; ?>
							</a>
						</div>
					<?php endif; ?>
				</div>
				<div class="col-lg-7">
					<h3 class="text-default-aw font-size-sm font-weight-normal letter-spacing-1 mb-2 mb-lg-3 text-uppercase">More in This Issue</h3>
					<ul class="list-unstyled">
					<?php foreach( $posts as $post ) { ?>
						<li data-post-id="<?php echo $post->ID; ?>" class="story-list-item position-relative<?php if ( $post->ID === $featured_article_id ) : ?> d-none<?php endif; ?>">
							<a class="stretched-link" href="<?php echo get_permalink( $post->ID ); ?>">
								<h4 class="listing-title mb-1"><?php echo wptexturize( $post->post_title ); ?></h4>
							</a>
							<span class="results-story-issue"><?php echo $issue->post_title; ?></span>
							<?php if ( $desc = get_post_meta( $post->ID, 'story_description', TRUE ) ) { ?>
								<span class="description"><?php echo wptexturize( strip_tags( $desc, '<b><em><i><u><strong>' ) ); ?></span>
							<?php } else if ( $subtitle = get_post_meta( $post->ID, 'story_subtitle', TRUE ) ) { ?>
								<span class="description"><?php echo wptexturize( strip_tags( $subtitle, '<b><em><i><u><strong>' ) ); ?></span>
							<?php } ?>
						</li>
					<?php } ?>
					</ul>
				</div>
			</div>
			<?php if ( $issue_count < count( $issues_sorted ) ): ?>
			<hr class="mt-1 mt-lg-4 mb-4 mb-lg-5">
			<?php
			endif;
			endif;
		endforeach;
		?>
		</div>
	</div>
	<?php 	return ob_get_clean();
}
add_shortcode('archive-search', 'sc_archive_search');


/**
 * Photo Essay
 **/
function sc_photo_essay( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			'slug' => ''
		),
		$atts,
		'photo_essay'
	);

	if ( !is_numeric( $atts['slug'] ) ) {
		$photo_essays = get_posts( array(
			'name' => $atts['slug'],
			'post_type' => 'photo_essay',
			'post_status' => 'publish',
			'posts_per_page' => 1
		));
		if ( $photo_essays ) {
			$photo_essay = $photo_essays[0];
		}
	} else {
		$recent = get_posts( array(
			'numberposts' => 1,
			'post_type' => 'photo_essay',
			'post_status' => 'publish',
		) );
		if ( $recent ) {
			$photo_essay = $recent[0];
		}
	}

	global $post;

	ob_start();

	if ( $photo_essay ) {
		echo display_photo_essay( $photo_essay, $post );
	}

	return ob_get_clean();

}
add_shortcode( 'photo_essay', 'sc_photo_essay' );


/**
 * Inserts a Google Remarketing tag.
 **/
function sc_remarketing_tag( $attr ) {
	$conversion_id = '';
	$img_src = '';

	if ( isset( $attr[ 'conversion_id' ] ) ) {
		$conversion_id = str_replace( array( '"', "'" ), '', $attr[ 'conversion_id' ] );
	} else {
		return '';
	}

	if ( isset( $attr[ 'img_src' ] ) ) {
		$img_src = str_replace( array( '"', "'" ), '', $attr[ 'img_src' ] );
	} else {
		return '';
	}

	ob_start();

	?>
	<script type="text/javascript">
		// <![CDATA[
		var google_conversion_id = <?php echo $conversion_id; ?>;
		var google_custom_params = window.google_tag_params;
		var google_remarketing_only = true;
		// ]]>
	</script>
	<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
	<noscript>
		<div style="display:inline;">
			<img height="1" width="1" style="border-style:none;" alt="" src="<?php echo $img_src; ?>" />
		</div>
	</noscript>
	<?php
	return ob_get_clean();
}

add_shortcode( 'google-remarketing', 'sc_remarketing_tag' );
