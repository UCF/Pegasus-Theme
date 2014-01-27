<?php
function sc_search_form() {
	ob_start();
	?>
	<div class="search">
		<?get_search_form()?>
	</div>
	<?
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
		$sql = sprintf('SELECT * FROM %s WHERE post_title="%s" AND post_parent=%d ORDER BY post_date DESC', $wpdb->posts, $wpdb->escape($attr['filename']), $post_id);
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
		$sql = sprintf('SELECT * FROM %s WHERE post_title="%s" AND post_parent=%d ORDER BY post_date DESC', $wpdb->posts, $wpdb->escape($attr['filename']), $post_id);
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
		'show_sorting'           => true,
		'default_sorting'        => 'term',
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
				<?=json_encode($params['column_count'])?>,
				<?=json_encode($params['column_width'])?>,
				<?=json_encode($search_data)?>
			));
		}
	</script>
	<?

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
		'order'       => 'ASC'
	));
	foreach($by_alpha_posts as $post) {
		if(preg_match('/([a-zA-Z])/', $post->post_title, $matches) == 1) {
			$by_alpha[strtoupper($matches[1])][] = $post;
		} else {
			$by_alpha[$params['non_alpha_section_name']][] = $post;
		}
	}

	if($params['show_empty_sections']) {
		foreach(range('a', 'z') as $letter) {
			if(!isset($by_alpha[strtoupper($letter)])) {
				$by_alpha[strtoupper($letter)] = array();
			}
		}
	}
	ksort($by_alpha);

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
				<input type="text" class="span3" placeholder="<?=$params['default_search_text']?>" />
			</form>
		</div>
		<div class="post-type-search-results "></div>
		<? if($params['show_sorting']) { ?>
		<div class="btn-group post-type-search-sorting">
			<button class="btn<?if($params['default_sorting'] == 'term') echo ' active';?>"><i class="icon-list-alt"></i></button>
			<button class="btn<?if($params['default_sorting'] == 'alpha') echo ' active';?>"><i class="icon-font"></i></button>
		</div>
		<? } ?>
	<?

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
		<div class="<?=$id?>"<? if($hide) echo ' style="display:none;"'; ?>>
			<? foreach($section as $section_title => $section_posts) { ?>
				<? if(count($section_posts) > 0 || $params['show_empty_sections']) { ?>
					<div>
						<h3><?=esc_html($section_title)?></h3>
						<div class="row">
							<? if(count($section_posts) > 0) { ?>
								<? $posts_per_column = ceil(count($section_posts) / $params['column_count']); ?>
								<? foreach(range(0, $params['column_count'] - 1) as $column_index) { ?>
									<? $start = $column_index * $posts_per_column; ?>
									<? $end   = $start + $posts_per_column; ?>
									<? if(count($section_posts) > $start) { ?>
									<div class="<?=$params['column_width']?>">
										<ul>
										<? foreach(array_slice($section_posts, $start, $end) as $post) { ?>
											<li data-post-id="<?=$post->ID?>"><?=$post_type->toHTML($post)?></li>
										<? } ?>
										</ul>
									</div>
									<? } ?>
								<? } ?>
							<? } ?>
						</div>
					</div>
				<? } ?>
			<? } ?>
		</div>
		<?
	}
	?> </div> <?
	return ob_get_clean();
}
add_shortcode('post-type-search', 'sc_post_type_search');

?>