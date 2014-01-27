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

?>