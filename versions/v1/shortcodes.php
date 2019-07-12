<?php function sc_search_form() {
	ob_start();
	?>
	<div class="search">
		<?php get_search_form()?>
	</div>
	<?php
	return ob_get_clean();
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
	$css_classes = '';
	$content = $content ? $content : '';
	$filename = ($attr['filename'] && $attr['filename'] != '') ? $attr['filename'] : null;
	$attachment_id = $attr['id'] ? intval($attr['id']) : null;

	$alt = $attr['alt'] ? $attr['alt'] : $content;
	$position = ($attr['position'] && $attr['position'] == ('left' || 'right' || 'center')) ? 'pull-'.$attr['position'] : '';

	// Set a fallback width if none is provided. Only add position
	// class to img/figure elements if width doesn't fall back to 100%:
	$width = $attr['width'];
	if ( !isset( $width ) ) {
		$width = '100%';
	}
	else {
		if ( !empty( $position ) ) {
			$css_classes .= $position.' ';
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
				$css_classes .= 'mobile-img-fluid ';
			}
		}
		else {
			$css_classes .= 'mobile-img-fluid ';
		}
	}


	$url = null;
	$html = '';

	// Attempt to get url by attachment ID first.
	if ($attachment_id) {
		$url = wp_get_attachment_image_src($attachment_id, 'full');
		$url = $url[0];
	}
	else if ($filename) {
		$url = sc_image(array('filename' => $filename));
	}
	if ($url) {
		if ($content) {
			$html .= '<figure class="'.$css_classes.'" style="height: auto;';
			if ( $width ) {
				$html .= ' max-width: '.$width.';';
			}
			$html .= '">';
			$html .= '<img src="'.$url.'" alt="'.$alt.'" title="'.$alt.'" style="height: auto;';
			if ( $width ) {
				$html .= ' width: '.$width.';';
			}
			$html .= '" />';
			$html .= '<p class="caption">'.$content.'</p>';
			$html .= '</figure>';
		}
		else {
			$html .= '<img class="'.$css_classes.'" src="'.$url.'" alt="'.$alt.'" title="'.$alt.'" style="height: auto;';
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
function sc_blockquote($attr, $content='') {
	$source = $attr['source'] ? $attr['source'] : null;
	$cite = $attr['cite'] ? $attr['cite'] : null;
	$color = $attr['color'] ? $attr['color'] : null;

	$html = '<blockquote';
	if ($source) {
		$html .= ' class="quote"';
	}

	if ($color) {
		$html .= ' style="color: ' . $color . '"';
	}

	$html .= '><p';
	if ($color) {
		$html .= ' style="color: ' . $color . '"';
	}
	$html .= '>'.$content.'</p>';

	if ($source || $cite) {
		$html .= '<small';
		if ($color) {
			$html .= ' style="color: ' . $color . '"';
		}
		$html .= '>';

		if ($source) {
			$html .= $source;
		}
		if ($cite) {
			$html .= '<cite title="'.$cite.'">'.$cite.'</cite>';
		}
		$html .= '</small>';
	}
	$html .= '</blockquote>';

	return $html;
}
add_shortcode('blockquote', 'sc_blockquote');


/**
 * Create a full-width callout box.
 **/
function sc_callout($attr, $content) {
	$bgcolor = $attr['background'] ? $attr['background'] : '#f0f0f0';
	$content = do_shortcode($content);

	// Close out our existing .span, .row and .container
	$html = '</div></div></div>';
	$html .= '<div class="container-wide callout" style="background-color: '.$bgcolor.';">';
	$html .= '<div class="container"><div class="row content-wrap"><div class="span10 offset1 callout-inner">';
	$html .= $content;
	$html .= '</div></div></div></div>';
	// Reopen standard .container, .row and .span
	$html .= '<div class="container"><div class="row content-wrap"><div class="span10 offset1">';

	return $html;
}
add_shortcode('callout', 'sc_callout');


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
function sc_sidebar($attr, $content) {
	$pull = ($attr['position'] == ('left' || 'right')) ? 'pull-'.$attr['position'] : 'pull-right';
	$bgcolor = $attr['background'] ? $attr['background'] : '#f0f0f0';
	$content = do_shortcode($content);

	$html = '<div class="span4 '.$pull.' sidebar">';
	$html .= '<section class="sidebar-inner" style="background-color: '.$bgcolor.';">'.$content.'</section>';
	$html .= '</div>';

	return $html;
}
add_shortcode('sidebar', 'sc_sidebar');


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
		'column_width'           => 'span4',
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
				<?php echo json_encode($params['column_count'])?>,
				<?php echo json_encode($params['column_width'])?>,
				<?php echo json_encode($search_data)?>
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
				<input type="text" class="span3" placeholder="<?php echo $params['default_search_text']?>" />
			</form>
		</div>
		<div class="post-type-search-results "></div>
		<?php  if($params['show_sorting']) { ?>
		<div class="btn-group post-type-search-sorting">
			<button class="btn<?php if($params['default_sorting'] == 'term') echo ' active';?>"><i class="icon-list-alt"></i></button>
			<button class="btn<?php if($params['default_sorting'] == 'alpha') echo ' active';?>"><i class="icon-font"></i></button>
		</div>
		<?php  } ?>
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
		<div class="<?php echo $id?>"<?php  if($hide) echo ' style="display:none;"'; ?>>
			<?php  foreach($section as $section_title => $section_posts) { ?>
				<?php  if(count($section_posts) > 0 || $params['show_empty_sections']) { ?>
					<div>
						<h3><?php echo esc_html($section_title)?></h3>
						<div class="row">
							<?php  if(count($section_posts) > 0) { ?>
								<?php  $posts_per_column = ceil(count($section_posts) / $params['column_count']); ?>
								<?php  foreach(range(0, $params['column_count'] - 1) as $column_index) { ?>
									<?php  $start = $column_index * $posts_per_column; ?>
									<?php  $end   = $start + $posts_per_column; ?>
									<?php  if(count($section_posts) > $start) { ?>
									<div class="<?php echo $params['column_width']?>">
										<ul>
										<?php  foreach(array_slice($section_posts, $start, $end) as $post) { ?>
											<li data-post-id="<?php echo $post->ID?>"><?php echo $post_type->toHTML($post)?></li>
										<?php  } ?>
										</ul>
									</div>
									<?php  } ?>
								<?php  } ?>
							<?php  } ?>
						</div>
					</div>
				<?php  } ?>
			<?php  } ?>
		</div>
		<?php
	}
	?> </div> <?php
	return ob_get_clean();
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
		'column_width' => 'span10 offset1',
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

	foreach ($issues_all as $issue) {
		//$featured_article = get_post_meta($issue->ID, 'issue_cover_story', TRUE);

		$issues_sorted[$issue->post_title] = get_posts(array(
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
			//'exclude' 	=> array(intval($featured_article)),
		));
	}

	ob_start();
	?>
	<div class="row post-type-search" id="archives">
		<div class="span8 offset2 post-type-search-header">
			<form class="post-type-search-form search-form" role="search" method="get" action="<?php echo home_url( '/' )?>">
				<label for="s">Search</label>
				<input type="text" name="s" class="search-field" id="s" placeholder="<?php echo $params['default_search_text']?>" />
			</form>
		</div>
		<div class="span12 post-type-search-results"></div>
		<div class="span10 offset1 post-type-search-term">
		<?php
		foreach($issues_sorted as $key => $posts) {
			$issue = get_page_by_title($key, 'OBJECT', 'issue');
			$featured_article_id = intval(get_post_meta($issue->ID, 'issue_cover_story', TRUE));
			$featured_article = get_post($featured_article_id);

			if ($posts) {
		?>
			<div class="row issue">
				<div class="span4">
					<h2 id="<?php echo $issue->post_name?>"><a href="<?php echo get_permalink($issue->ID)?>"><?php echo $issue->post_title?></a></h2>

					<?php if ($thumbnail = get_the_post_thumbnail($issue->ID, 'issue-thumbnail')) { ?>
						<a href="<?php echo get_permalink($issue->ID)?>">
							<?php echo $thumbnail?>
						</a>
					<?php } ?>

					<?php if ( $featured_article ) : ?>
						<h3>Featured Story</h3>
						<a class="featured-story" href="<?php echo get_permalink($featured_article->ID)?>">
							<h4><?php echo $featured_article->post_title?></h4>
							<?php if ( $f_desc = get_post_meta($featured_article->ID, 'story_description', true ) ) : ?>
								<span class="description"><?php echo $f_desc?></span>
							<?php else if ( $f_subtitle = get_post_meta($featured_article->ID, 'story_subtitle', true ) ) : ?>
								<span class="description"><?php echo $f_subtitle?></span>
							<?php endif; ?>
						</a>
						<?php endif; ?>
				</div>
				<div class="span6">
					<h3>More in This Issue</h3>
					<ul>
					<?php  foreach($posts as $post) { ?>
						<li data-post-id="<?php echo $post->ID?>"<?php if ($post->ID == $featured_article_id) {?> class="featured-story"<?php } ?>>
							<a href="<?php echo get_permalink($post->ID)?>">
								<h4><?php echo $post->post_title?></h4>
								<span class="results-story-issue"><?php echo $issue->post_title?></span>
								<?php if ($desc = get_post_meta($post->ID, 'story_description', TRUE)) { ?>
									<span class="description"><?php echo $desc?></span>
								<?php } else if ($subtitle = get_post_meta($post->ID, 'story_subtitle', TRUE)) { ?>
									<span class="description"><?php echo $subtitle?></span>
								<?php } ?>
							</a>
						</li>
					<?php  } ?>
					</ul>
				</div>
				<hr class="span10" />
			</div>
			<?php
			}
		}
		?>
		</div>
	</div>
	<?php 	return ob_get_clean();
}
add_shortcode('archive-search', 'sc_archive_search');


/**
 * Photo Essay Slider
 **/
function sc_photo_essay_slider( $atts, $content = null ) {
	$slug 		   = @$atts['slug'];
	$caption_color = $atts['caption_color'] ? $atts['caption_color'] : null;
	$recent 	   = get_posts( array(
		'numberposts' => 1,
		'post_type'   => 'photo_essay',
		'post_status' => 'publish',
	) );

	$mostrecent = $recent[0];

	if ( is_string( $slug ) ) {
		$essays = get_posts(array(
			'name' => $slug,
			'post_type' => 'photo_essay',
			'post_status' => 'publish',
			'posts_per_page' => 1
		) );
		if ( $essays ) {
			$essay = $essays[0];
		}
	} else {
		$essay = $mostrecent;
	}

	global $post;
	$is_fullscreen = false;
	if ( $post->post_type === 'story' && get_post_meta( $post->ID, 'story_template', true ) === 'photo_essay' ) {
		$is_fullscreen = true;
	}

	ob_start();

	if ( $essay ) {

		$slide_order 	= get_post_meta( $essay->ID, 'ss_slider_slideorder', true );
		// Get rid of blank array entries
		$slide_order	= array_filter( explode( ",", $slide_order ), 'strlen' );
		$slide_title 	= get_post_meta( $essay->ID, 'ss_slide_title', true );
		$slide_caption 	= get_post_meta( $essay->ID, 'ss_slide_caption', true);
		$slide_image	= get_post_meta( $essay->ID, 'ss_slide_image', true);
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
				$slide_count = count( $slide_order );
				$ss_half = floor( $slide_count / 2 ) + 1;
				$end = false;
				$i = $ss_half;
				if ( $is_fullscreen ) {
					$photo_essay_offset = 1;
				}
				while ( $end == false ) :
					if ( $i === $slide_count ) {
						$i = 0;
					}

					if ( $i === $ss_half - 1 ) {
						$end = true;
					}

					if ( $is_fullscreen && $i == 0 ) : ?>
						<div class="ss-slide-wrapper">
							<?php if ( ! empty( $slide_order ) ) :
									$s = $slide_order[0];
									$image = wp_get_attachment_image_src( $slide_image[$s], 'full' );
							?>
							<div class="ss-slide ss-first-slide ss-current ss-essay-intro-wrapper" data-id="1" data-width="<?php echo $image[1]; ?>" data-height="<?php echo $image[2]; ?>">

								<img src="<?php echo $image[0]; ?>" alt="<?php echo $slide_title[$s]; ?>" />

								<div class="ss-essay-intro">
									<div class="title-wrap">
										<h1><?php echo $post->post_title; ?></h1>
									</div>
									<div class="description-wrap">
										<span class="description"><?php echo get_post_meta( $post->ID, 'story_description', true ); ?></span>
										<a class="ss-control ss-play" href="#2"><i class="icon-caret-right"></i>
										<?php echo display_social( get_permalink( $post ), $post->post_title ); ?>
									</div>
								</div>
							</div>
							<?php endif; ?>
						</div>
					<?php endif;

					$s = $slide_order[$i];
					$image = wp_get_attachment_image_src( $slide_image[$s], 'full' );
					?>
					<div class="ss-slide-wrapper">
						<div class="ss-slide<?php echo $i + $photo_essay_offset === 0 ? ' ss-first-slide ss-current' : '' ?><?php echo $i === $slide_count - 1 ? ' ss-last-slide' : '' ?>" data-id="<?php echo $i + 1 + $photo_essay_offset; ?>" data-width="<?php echo $image[1]; ?>" data-height="<?php echo $image[2]; ?>">
							<img src="<?php echo $image[0]; ?>" alt="<?php echo $slide_title[$s]; ?>" />
						</div>
					</div>
				<?php 					$i++;
				endwhile;
				?>
				</div>

				<div class="ss-captions-wrapper">
				<?php 				$data_id = 0;
				if ($is_fullscreen) {
					$data_id++;
				?>
					<div class="ss-caption ss-current" data-id="<?php echo $data_id; ?>"></div>
				<?php 				}
				foreach ($slide_order as $s) :
					if ($s !== '') :
						$data_id++;
				?>
					<div class="ss-caption <?php echo $data_id === 1 ? ' ss-current' : ''; ?>" data-id="<?php echo $data_id; ?>">
						<p class="caption"<?php if ($caption_color) : ?> style="color: <?php echo $caption_color; ?>;"<?php endif; ?>><?php echo $slide_caption[$s]; ?></p>
					</div>
				<?php 					endif;
				endforeach;
				?>
				</div>

				<div class="ss-closing-overlay" style="display: none;">
					<div class="ss-slide" data-id="restart-slide">
						<a class="ss-control ss-restart" href="#1"><i class="repeat-alt-icon"></i><div>REPLAY<?php echo $is_fullscreen ? ':' : ''; ?></div></a>
						<?php if ($is_fullscreen) : ?>
							<div class="ss-title"><?php echo $post->post_title; ?></div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>

		<?php 	}

	return ob_get_clean();

}

add_shortcode('slideshow', 'sc_photo_essay_slider');


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

?>
