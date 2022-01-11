<?php function sc_search_form() {
	ob_start();
	?>
	<div class="search">
		<?php get_search_form(); ?>
	</div>
	<?php 	return ob_get_clean();
}
add_shortcode('search_form', 'sc_search_form');


/*
 * Search for a image by file name and return its URL.
 *
 */
function sc_image($attr) {
	global $wpdb, $post;

	$post_id = wp_is_post_revision($post->ID);
	if($post_id === False) {
		$post_id = $post->ID;
	}

	$url = '';
	if(isset($attr['filename']) && $attr['filename'] != '') {
		$sql = sprintf('SELECT * FROM %s WHERE post_title="%s" AND post_parent=%d ORDER BY post_date DESC', $wpdb->posts, esc_sql( $attr['filename'] ), $post_id);
		$rows = $wpdb->get_results($sql);
		if(count($rows) > 0) {
			$obj = $rows[0];
			if($obj->post_type == 'attachment' && stripos($obj->post_mime_type, 'image/') == 0) {
				$url = wp_get_attachment_url($obj->ID);
			}
		}
	}
	return $url;
}
add_shortcode('image', 'sc_image');


/**
 * Same as [image], but returns markup safe to use within an element as
 * a background image
 **/
function sc_background_image( $attr ) {
	$attr = shortcode_atts( array(
		'filename' => false,
		'inline_css' => ''
	), $attr, 'sc_background_image' );

	if ( $attr['filename'] ) {
		return sprintf( 'style="background-image: url(%s); %s"', sc_image( $attr ), $attr['inline_css'] );
	}
	return '';
}
add_shortcode( 'background-image', 'sc_background_image' );


/*
 * Link to a static image. Requires extension
 */
function sc_static_image($attr) {
	$url = '';
	if(isset($attr['path']) && $attr['path'] != '') {
		$url = get_bloginfo('stylesheet_directory').$attr['path'];
	}
	return $url;
}
add_shortcode('static-image', 'sc_static_image');


/*
 * Search for some arbitrary media in the media library.
 */
function sc_get_media($attr) {
	global $wpdb, $post;

	$post_id = wp_is_post_revision($post->ID);
	if($post_id === False) {
		$post_id = $post->ID;
	}

	$url = '';
	if(isset($attr['filename']) && $attr['filename'] != '') {
		$sql = sprintf('SELECT * FROM %s WHERE post_title="%s" AND post_parent=%d ORDER BY post_date DESC', $wpdb->posts, esc_sql( $attr['filename'] ), $post_id);
		$rows = $wpdb->get_results($sql);
		if(count($rows) > 0) {
			$obj = $rows[0];
			if($obj->post_type == 'attachment') {
				$url = wp_get_attachment_url($obj->ID);
			}
		}
	}
	return $url;
}
add_shortcode('media', 'sc_get_media');


/**
 * Uses sc_image to get an image URL and display it
 * in an <img> tag with optional styling parameters.
 * Note: 'title' param is unused.
 **/
function sc_photo($attr, $content) {
	$attr = shortcode_atts( array(
		'css_class'  => '',
		'inline_css' => '',
		'filename'   => null,
		'id'         => null,
		'alt'        => $content,
		'position'   => '',
		'width'      => null
	), $attr );

	$css_classes = $attr['css_class'];
	$inline_css = $attr['inline_css'];
	$filename = $attr['filename'];
	$attachment_id = $attr['id'];
	$alt = $attr['alt'];
	$position = $attr['position'] === ( 'left' || 'right' || 'center' ) ? 'pull-' . $attr['position'] : '';

	// Set a fallback width if none is provided. Only add position
	// class to img/figure elements if width doesn't fall back to 100%:
	$width = $attr['width'];
	if ( !isset( $width ) ) {
		$width = '100%';
	}
	else {
		if ( !empty( $position ) ) {
			$css_classes .= ' ' . $position;
		}
	}

	// For images with a fixed width/position, check the image size;
	// if the width is greater than 140px wide (1/2 minimum supported
	// mobile screen width), or if we have no idea what the width is (it
	// was manually removed from the shortcode for some reason), force
	// the image to be fluid at mobile sizes.
	if ( !empty( $position ) || $width !== '100%' ) {
		if ( $width !== '100%' ) {
			$width_px = intval( str_replace( 'px', '', $width ) );
			if ( $width_px > 140 ) {
				$css_classes .= ' img-fluid';
			}
		}
		else {
			$css_classes .= ' img-fluid';
		}
	}


	$url = null;
	$html = '';

	// Attempt to get url by attachment ID first.
	if ( $attachment_id ) {
		$url = wp_get_attachment_image_src( $attachment_id, 'full' );
		if( $url ) {
			$url = $url[0];
		}
	}
	else if ( $filename ) {
		$url = sc_image( array( 'filename' => $filename ) );
	}
	if ( $url ) {
		if ( $content ) {
			$html .= '<figure class="'.$css_classes.'" style="height: auto;';
			if ( $width ) {
				$html .= ' max-width: '.$width.';';
			}
			$html .= '">';
			$html .= '<img src="'.$url.'" alt="'.$alt.'" title="'.$alt.'" style="height: auto; ';
			if ( $inline_css ) {
				$html .= $inline_css;
			}
			if ( $width ) {
				$html .= ' width: '.$width.';';
			}
			$html .= '" />';
			$html .= '<p class="caption">'.$content.'</p>';
			$html .= '</figure>';
		}
		else {
			$html .= '<img class="'.$css_classes.'" src="'.$url.'" alt="'.$alt.'" title="'.$alt.'" style="height: auto; ';
			if ( $inline_css ) {
				$html .= $inline_css;
			}
			if ( $width ) {
				$html .= ' width: '.$width.';';
			}
			$html .= '" />';
		}

	}

	return $html;
}
add_shortcode('photo', 'sc_photo');


/**
 * Return a <hr /> element
 **/
function sc_divider($attr) {
	return '<hr/>';
}
add_shortcode('divider', 'sc_divider');


/**
 * Wrap arbitrary text in .lead paragraph
 **/
function sc_lead($attr, $content='') {
	return '<p class="lead">'.$content.'</p>';
}
add_shortcode('lead', 'sc_lead');


/**
 * Wrap arbitrary text in <blockquote>
 **/
function sc_blockquote( $attr, $content = '' ) {
	$attr = shortcode_atts( array(
		'source'     => null,
		'cite'       => null,
		'color'      => null,
		'css_class'  => '',
		'inline_css' => ''
	), $attr );

	$source = $attr['source'];
	$cite = $attr['cite'];
	$color = $attr['color'];
	$class = $attr['css_class'];
	$inline_css = $attr['inline_css'];

	if ( $color ) {
		$inline_css .= ' color: ' . $color . ';';
	}

	$html = '<blockquote class="' . $class . '"';
	$html .= ' style="' . $inline_css . '"';
	$html .= '>';

	$html .= $content;

	if ( $source || $cite ) {
		// Wrap small in div to prevent wpautop from wrapping it in a p tag
		$html .= '<div><small>';

		if ($source) {
			$html .= $source;
		}
		if ($cite) {
			$html .= '<cite title="'.$cite.'">'.$cite.'</cite>';
		}

		$html .= '</small></div>';
	}
	$html .= '</blockquote>';

	return $html;
}
add_shortcode( 'blockquote', 'sc_blockquote' );


/**
 * Create a full-width callout box.
 **/
function sc_callout( $attr, $content ) {
	global $post;

	$attr = shortcode_atts( array(
		'background' => '#f0f0f0',
		'content_align' => '',
		'css_class' => '',
		'inline_css' => '',
		'affix' => false
	), $attr );

	$bgcolor = $attr['background'];
	$content_align = $attr['content_align'] ? 'text-' . $attr['content_align'] : '';
	$css_class = $attr['css_class'];
	$inline_css = $attr['inline_css'];
	$affix = filter_var( $attr['affix'], FILTER_VALIDATE_BOOLEAN );
	$content = do_shortcode( $content );

	$inline_css = 'background-color: ' . $bgcolor . ';' . $inline_css;
	if ( $affix ) {
		$css_class .= ' callout-affix';
	}

	if ( $post->post_type == 'page' ) {
		// Close out our existing .span, .row and .container
		$html = '</div></div></div>';
		$html .= '<div class="container-wide callout-outer"><div class="callout ' . $css_class . '" style="' . $inline_css . '">';
		$html .= '<div class="container"><div class="row content-wrap">';
		$html .= '<div class="col-md-12 callout-inner ' . $content_align . '">';
		$html .= $content;
		$html .= '</div></div></div></div></div>';
		// Reopen standard .container, .row and .span
		$html .= '<div class="container"><div class="row content-wrap"><div class="col-md-12">';
	}
	else {
		// Close out our existing .span, .row and .container
		$html = '</div></div></div>';
		$html .= '<div class="container-wide callout-outer"><div class="callout ' . $css_class . '" style="' . $inline_css . '">';
		$html .= '<div class="container"><div class="row content-wrap">';
		$html .= '<div class="col-md-10 col-sm-10 offset-md-1 offset-sm-1 callout-inner ' . $content_align . '">';
		$html .= $content;
		$html .= '</div></div></div></div></div>';
		// Reopen standard .container, .row and .span
		$html .= '<div class="container"><div class="row content-wrap"><div class="col-md-10 col-sm-10 offset-md-1 offset-sm-1">';
	}

	return $html;
}
add_shortcode( 'callout', 'sc_callout' );


/**
 * Wrap arbitrary text in .caption paragraph.
 * Destroy WordPress' existing caption shortcode.
 **/
remove_shortcode('wp_caption', 'img_caption_shortcode');
remove_shortcode('caption', 'img_caption_shortcode');

function sc_caption($attr, $content) {
	return '<p class="caption">'.$content.'</p>';
}
add_shortcode('caption', 'sc_caption');


/**
 * Create a floating sidebar.
 **/
function sc_sidebar( $attr, $content ) {
	$pull = ( $attr['position'] == ( 'left' || 'right' ) ) ? 'pull-' . $attr['position'] : 'pull-right';
	$bgcolor = $attr['background'] ? $attr['background'] : '#f0f0f0';
	$content_align = $attr['content_align'] ? 'text-' . $attr['content_align'] : '';
	$content = do_shortcode( $content );

	$html = '<div class="col-md-5 col-sm-6 ' . $pull . ' sidebar">';
	$html .= '<section class="sidebar-inner ' . $content_align . '" style="background-color: ' . $bgcolor . ';">' . $content . '</section>';
	$html .= '</div>';

	return $html;
}
add_shortcode( 'sidebar', 'sc_sidebar' );


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
function sc_site_url($attr) {
	return site_url();
}
add_shortcode('site-url', 'sc_site_url');


/**
 * Post search
 *
 * @return string
 * @author Chris Conover
 **/
function sc_post_type_search($params=array(), $content='') {
	$defaults = array(
		'post_type_name'         => 'post',
		'taxonomy'               => 'category',
		'show_empty_sections'    => false,
		'non_alpha_section_name' => 'Other',
		'column_width'           => 'col-md-4 col-sm-4',
		'column_count'           => '3',
		'order_by'               => 'title',
		'order'                  => 'ASC',
		'show_sorting'           => True,
		'default_sorting'        => 'term',
		'show_sorting'           => True
	);

	$params = ($params === '') ? $defaults : array_merge($defaults, $params);

	$params['show_empty_sections'] = (bool)$params['show_empty_sections'];
	$params['column_count']        = is_numeric($params['column_count']) ? (int)$params['column_count'] : $defaults['column_count'];
	$params['show_sorting']        = (bool)$params['show_sorting'];

	if(!in_array($params['default_sorting'], array('term', 'alpha'))) {
		$params['default_sorting'] = $default['default_sorting'];
	}

	// Resolve the post type class
	if(is_null($post_type_class = get_custom_post_type($params['post_type_name']))) {
		return '<p>Invalid post type.</p>';
	}
	$post_type = new $post_type_class;

	// Set default search text if the user didn't
	if(!isset($params['default_search_text'])) {
		$params['default_search_text'] = 'Find a '.$post_type->singular_name;
	}

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
				<?php echo json_encode($params['column_count']); ?>,
				<?php echo json_encode($params['column_width']); ?>,
				<?php echo json_encode($search_data); ?>
			));
		}
	</script>
	<?php
	// Split up this post type's posts by term
	$by_term = array();
	foreach(get_terms($params['taxonomy']) as $term) {
		$posts = get_posts(array(
			'numberposts' => -1,
			'post_type'   => $params['post_type_name'],
			'tax_query'   => array(
				array(
					'taxonomy' => $params['taxonomy'],
					'field'    => 'id',
					'terms'    => $term->term_id
				)
			),
			'orderby'     => $params['order_by'],
			'order'       => $params['order']
		));

		if(count($posts) == 0 && $params['show_empty_sections']) {
			$by_term[$term->name] = array();
		} else {
			$by_term[$term->name] = $posts;
		}
	}

	// Split up this post type's posts by the first alpha character
	$by_alpha = array();
	$by_alpha_posts = get_posts(array(
		'numberposts' => -1,
		'post_type'   => $params['post_type_name'],
		'orderby'     => 'title',
		'order'       => 'alpha'
	));
	foreach($by_alpha_posts as $post) {
		if(preg_match('/([a-zA-Z])/', $post->post_title, $matches) == 1) {
			$by_alpha[strtoupper($matches[1])][] = $post;
		} else {
			$by_alpha[$params['non_alpha_section_name']][] = $post;
		}
	}
	ksort($by_alpha);

	if($params['show_empty_sections']) {
		foreach(range('a', 'z') as $letter) {
			if(!isset($by_alpha[strtoupper($letter)])) {
				$by_alpha[strtoupper($letter)] = array();
			}
		}
	}

	$sections = array(
		'post-type-search-term'  => $by_term,
		'post-type-search-alpha' => $by_alpha,
	);

	ob_start();
	?>
	<div class="post-type-search">
		<div class="post-type-search-header">
			<form class="post-type-search-form" action="." method="get">
				<label style="display:none;">Search</label>
				<input type="text" class="col-md-3 col-sm-3" placeholder="<?php echo $params['default_search_text']?>" />
			</form>
		</div>
		<div class="post-type-search-results "></div>
		<?php if($params['show_sorting']) { ?>
		<div class="btn-group post-type-search-sorting">
			<button class="btn btn-default<?php if($params['default_sorting'] == 'term') echo ' active';?>"><i class="icon icon-list-alt"></i></button>
			<button class="btn btn-default<?php if($params['default_sorting'] == 'alpha') echo ' active';?>"><i class="icon icon-font"></i></button>
		</div>
		<?php } ?>
	<?php
	foreach($sections as $id => $section) {
		$hide = false;
		switch($id) {
			case 'post-type-search-alpha':
				if($params['default_sorting'] == 'term') {
					$hide = True;
				}
				break;
			case 'post-type-search-term':
				if($params['default_sorting'] == 'alpha') {
					$hide = True;
				}
				break;
		}
		?>
		<div class="<?php echo $id?>"<?php if($hide) echo ' style="display:none;"'; ?>>
			<?php foreach($section as $section_title => $section_posts) { ?>
				<?php if(count($section_posts) > 0 || $params['show_empty_sections']) { ?>
					<div>
						<h3><?php echo esc_html($section_title)?></h3>
						<div class="row">
							<?php if(count($section_posts) > 0) { ?>
								<?php $posts_per_column = ceil(count($section_posts) / $params['column_count']); ?>
								<?php foreach(range(0, $params['column_count'] - 1) as $column_index) { ?>
									<?php $start = $column_index * $posts_per_column; ?>
									<?php $end   = $start + $posts_per_column; ?>
									<?php if(count($section_posts) > $start) { ?>
									<div class="<?php echo $params['column_width']?>">
										<ul>
										<?php foreach(array_slice($section_posts, $start, $end) as $post) { ?>
											<li data-post-id="<?php echo $post->ID?>"><?php echo $post_type->toHTML($post)?></li>
										<?php } ?>
										</ul>
									</div>
									<?php } ?>
								<?php } ?>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
		<?php 	}
	?> </div> <?php 	return ob_get_clean();
}
add_shortcode('post-type-search', 'sc_post_type_search');


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
		'column_width' => 'col-md-10 col-sm-10 offset-md-1 offset-sm-1',
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
		<div class="col-md-8 col-sm-8 offset-md-2 offset-sm-2 post-type-search-header">
			<form class="post-type-search-form search-form" role="search" method="get" action="<?php echo home_url( '/' ); ?>">
				<label for="s">Search</label>
				<input type="text" name="s" class="search-field" id="s" placeholder="<?php echo $params['default_search_text']; ?>" />
			</form>
		</div>
		<div class="col-md-12 col-sm-12 post-type-search-results"></div>
		<div class="col-md-10 col-sm-10 offset-md-1 offset-sm-1 post-type-search-term">
		<?php 		$issue_count = 0;
		foreach( $issues_sorted as $key => $posts ) {
			$issue = get_page_by_title( $key, 'OBJECT', 'issue' );
			$featured_article_id = intval( get_post_meta( $issue->ID, 'issue_cover_story', TRUE ) );
			$featured_article = get_post( $featured_article_id );
			$issue_count++;

			if ( $posts ) {
		?>
			<div class="row issue">
				<div class="col-md-5 col-sm-5">
					<h2 id="<?php echo $issue->post_name; ?>">
						<a href="<?php echo get_permalink( $issue->ID ); ?>">
							<?php echo wptexturize( $issue->post_title ); ?>
						</a>
					</h2>

					<?php if ( $thumbnail = get_the_post_thumbnail( $issue->ID, 'issue-thumbnail' ) ) : ?>
						<a href="<?php echo get_permalink( $issue->ID ); ?>">
							<?php echo $thumbnail; ?>
						</a>
					<?php endif; ?>

					<?php if ( $featured_article ) : ?>
						<h3>Featured Story</h3>
						<a class="featured-story" href="<?php echo get_permalink( $featured_article->ID ); ?>">
							<h4><?php echo wptexturize( $featured_article->post_title ); ?></h4>
							<?php if ( $f_desc = get_post_meta( $featured_article->ID, 'story_description', true ) ) : ?>
								<span class="description"><?php echo wptexturize( strip_tags( $f_desc, '<b><em><i><u><strong>' ) ); ?></span>
							<?php elseif ( $f_subtitle = get_post_meta( $featured_article->ID, 'story_subtitle', TRUE ) ) : ?>
								<span class="description"><?php echo wptexturize( strip_tags( $f_subtitle, '<b><em><i><u><strong>' ) ); ?></span>
							<?php endif; ?>
						</a>
						<?php endif; ?>
				</div>
				<div class="col-md-7 col-sm-7">
					<h3>More in This Issue</h3>
					<ul>
					<?php foreach( $posts as $post ) { ?>
						<li data-post-id="<?php echo $post->ID; ?>"<?php if ( $post->ID == $featured_article_id ) { ?> class="featured-story"<?php } ?>>
							<a href="<?php echo get_permalink( $post->ID ); ?>">
								<h4><?php echo wptexturize( $post->post_title ); ?></h4>
								<span class="results-story-issue"><?php echo $issue->post_title; ?></span>
								<?php if ( $desc = get_post_meta( $post->ID, 'story_description', TRUE ) ) { ?>
									<span class="description"><?php echo wptexturize( strip_tags( $desc, '<b><em><i><u><strong>' ) ); ?></span>
								<?php } else if ( $subtitle = get_post_meta( $post->ID, 'story_subtitle', TRUE ) ) { ?>
									<span class="description"><?php echo wptexturize( strip_tags( $subtitle, '<b><em><i><u><strong>' ) ); ?></span>
								<?php } ?>
							</a>
						</li>
					<?php } ?>
					</ul>
				</div>
				<?php if ( $issue_count < count( $issues_sorted ) ): ?>
				<div class="col-md-12 col-sm-12">
					<hr>
				</div>
				<?php endif; ?>
			</div>
			<?php 			}
		}
		?>
		</div>
	</div>
	<?php 	return ob_get_clean();
}
add_shortcode('archive-search', 'sc_archive_search');


/**
 * Photo Essay Slideshow
 **/
function sc_photo_essay_slider( $atts, $content = null ) {
	$slug 		   = @$atts['slug'];
	$caption_color = $atts['caption_color'] ? $atts['caption_color'] : '#000';

	if ( is_string( $slug ) ) {
		$photo_essays = get_posts( array(
			'name' => $slug,
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

	ob_start();

	if ( $photo_essay ) {
		echo display_photo_essay_slideshow( $photo_essay, $slug, $caption_color );
	}

	return ob_get_clean();

}
add_shortcode( 'slideshow', 'sc_photo_essay_slider' );


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
 * Inserts an empty clearfix div.
 **/
function sc_clearfix( $attr ) {
	return '<div class="clearfix"></div>';
}
add_shortcode( 'clearfix', 'sc_clearfix' );


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


/**
 * Inserts a Bootstrap button.
 **/
function sc_button( $attr, $content='' ) {
	$attrs = shortcode_atts(
		array(
			'css_class' => 'btn-default',
			'inline_css' => '',
			'href' => '',
			'new_window' => false,
			'ga_interaction' => '',
			'ga_category' => '',
			'ga_action' => '',
			'ga_label' => ''
		),
		$attr,
		'button'
	);

	$link_attrs = '';
	if ( filter_var( $attrs['new_window'], FILTER_VALIDATE_BOOLEAN ) ) {
		$link_attrs .= 'target="_blank" ';
	}
	if ( $attrs['ga_interaction'] ) {
		$link_attrs .= 'data-ga-interaction="' . $attrs['ga_interaction'] . '" ';
	}
	if ( $attrs['ga_category'] ) {
		$link_attrs .= 'data-ga-category="' . $attrs['ga_category'] . '" ';
	}
	if ( $attrs['ga_action'] ) {
		$link_attrs .= 'data-ga-action="' . $attrs['ga_action'] . '" ';
	}
	if ( $attrs['ga_label'] ) {
		$link_attrs .= 'data-ga-label="' . $attrs['ga_label'] . '" ';
	}

	ob_start();
?>
	<a class="btn <?php echo $attrs['css_class']; ?>" style="<?php echo $attrs['inline_css']; ?>" href="<?php echo $attrs['href']; ?>" <?php echo $link_attrs; ?>>
		<?php echo do_shortcode( $content ); ?>
	</a>
<?php 	return ob_get_clean();
}
add_shortcode( 'button', 'sc_button' );


function sc_header_callout( $attr, $content='' ) {
	global $post;
	$attrs = shortcode_atts(
		array(
			'background' => '#fff',
			'background_image' => '',
			'content_align' => 'text-left',
			'css_class' => '',
			'inline_css' => ''
		),
		$attr,
		'header-callout'
	);

	$attrs['inline_css'] = 'background-color: ' . $bgcolor . '; background-image: url(\'' . $attrs['background_image'] . '\'); ' . $attrs['inline_css'];

	ob_start();
?>
	<?php if ( $post->post_type == 'page' ): ?>
		</div></div></div>
		<div class="container-wide callout callout-header <?php echo $attrs['css_class']; ?>" style="<?php echo $attrs['inline_css']; ?>">
			<div class="container">
				<div class="row content-wrap">
					<div class="col-md-12 callout-inner <?php echo $attrs['content_align']; ?>">
						<?php echo do_shortcode( $content ); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="container"><div class="row content-wrap"><div class="col-md-12">
	<?php else: ?>
		</div></div></div>
		<div class="container-wide callout callout-header <?php echo $attrs['css_class']; ?>" style="<?php echo $attrs['inline_css']; ?>">
			<div class="container">
				<div class="row content-wrap">
					<div class="col-md-10 col-sm-10 offset-md-1 offset-sm-1 callout-inner <?php echo $attrs['content_align']; ?>">
						<?php echo do_shortcode( $content ); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="container"><div class="row content-wrap"><div class="col-md-10 col-sm-10 offset-md-1 offset-sm-1">
	<?php endif; ?>
<?php 	return ob_get_clean();
}
add_shortcode( 'header-callout', 'sc_header_callout' );


/**
 * ChartJS.
 **/
function sc_chart( $attr ) {
	$id = $attr['id'] ? $attr['id'] : 'custom-chart';
	$type = $attr['type'] ? $attr['type'] : 'bar';
	$json = $attr['data'] ? $attr['data'] : '';
	$options = $attr['options'] ? $attr['options'] : '';

	if ( empty( $json ) ) {
		return;
	}

	$class = $attr['class'] ? 'custom-chart ' . $class : 'custom-chart';

	wp_enqueue_script('chart-js', THEME_COMPONENTS_URL.'/Chart.min.js', null, null, True);

	ob_start();

	?>
		<div id="<?php echo $id; ?>" class="<?php echo $class; ?>" data-chart-type="<?php echo $type; ?>" data-chart-data="<?php echo $json; ?>" <?php echo $options ? 'data-chart-options="' . $options . '"' : ''; ?>></div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'chart', 'sc_chart' );


/**
 * Displays a Bootstrap well.
 **/
function sc_well( $attr, $content='' ) {
	$attrs = shortcode_atts(
		array(
			'css_class' => '',
			'inline_css' => ''
		),
		$attr,
		'well'
	);

	ob_start();
?>
	<div class="well <?php echo $attrs['css_class']; ?>" style="<?php echo $attrs['inline_css']; ?>">
		<?php echo do_shortcode( $content ); ?>
	</div>
<?php 	return ob_get_clean();
}
add_shortcode( 'well', 'sc_well' );

?>
