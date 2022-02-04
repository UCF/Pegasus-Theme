<?php

/**
 * Gets the related stories for a particular post.
 *
 * @author Jim Barnes
 * @since 6.0.0
 * @param int $post_id The post ID to retrieve stories for
 * @return array|false The stories array or false if there is an error
 */
function related_stories_get_stories( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	if ( ! $post_id ) return false;

	$list_type = get_field( 'related_stories_list_type', $post_id );

	switch( $list_type ) {
		case 'pegasus-tag':
			$tag_id = get_field( 'related_stories_pegasus_tag', $post_id );
			return get_pegasus_stories( $post_id, $tag_id );
		case 'today-feed':
			$feed_url = get_field( 'today_section_topic', $post_id );
			return get_today_feed( $feed_url );
		case 'curated':
			$stories = get_field( 'related_stories_curated_stories', $post_id );
			return get_curated_stories( $stories );
		case 'auto':
		default:
			return get_default_related_stories( $post_id );
	}
}

/**
 * Gets related Pegasus stories using the first
 * few tags assigned to the story.
 *
 * @author Jim Barnes
 * @since 6.0.0
 * @param int $post_id The post ID to get related stories for
 * @return array
 */
function get_default_related_stories( $post_id ) {
	$options = get_option(THEME_OPTIONS_NAME);
	$retval = array();

	$terms = wp_get_post_terms( $post_id, 'post_tag' );
	if ( count( $terms ) < 1 ) return $retval;
	$rand_idx = rand( 0, count( $terms ) - 1 );

	$related_term = $terms[$rand_idx];

	$args = array(
		'post_type'      => 'story',
		'post__not_in'   => array( $post_id ),
		'tag_id'         => $related_term->term_id,
		'posts_per_page' => (int)$options['related_stories_count']
	);

	$posts = get_posts( $args );

	foreach( $posts as $p ) {
		$retval[] = array(
			'title'     => $p->post_title,
			'url'       => get_permalink( $p ),
			'thumbnail' => get_featured_image_url( $p->ID, 'single-post-thumbnail-300x200' )
		);
	}

	return $retval;
}

/**
 * Gets Pegasus stories based on the tag
 * passed in.
 *
 * @author Jim Barnes
 * @since 6.0.0
 * @param int $tag_id The tag ID to get stories for
 * @return array
 */
function get_pegasus_stories( $post_id, $tag_id ) {
	$options = get_option( THEME_OPTIONS_NAME );
	$retval = array();

	if ( ! $tag_id ) return $retval;

	$args = array(
		'post_type'      => 'story',
		'post__not_in'   => array( $post_id ),
		'tag_id'         => $tag_id,
		'posts_per_page' => (int)$options['related_stories_count']
	);

	$posts = get_posts( $args );

	foreach( $posts as $p ) {
		$retval[] = array(
			'title'     => $p->post_title,
			'url'       => get_permalink( $p ),
			'thumbnail' => get_featured_image_url( $p->ID, 'single-post-thumbnail-300x200' )
		);
	}

	return $retval;
}

/**
 * Gets a list of today stories based on the
 * feed URL passed in.
 *
 * @author Jim Barnes
 * @since 6.0.0
 * @param string $url The URL to find stories on
 * @return array
 */
function get_today_feed( $url ) {
	$options = get_option( THEME_OPTIONS_NAME );

	$path = parse_url( $url, PHP_URL_PATH );
	$split = explode( '/', $path );
	$retval = array();

	$is_tag = false;
	$term = false;

	foreach( $split as $part ) {
		switch( $part ) {
			case '':
			case 'news':
				break;
			case 'tag':
				$is_tag = true;
				break;
			default:
				$term = $part;
				break;
		}
	}

	$params = array();

	if ( ! $term ) return $retval;

	if ( $is_tag ) {
		$params['tag_slugs'] = $term;
	} else {
		$params['category_slugs'] = $term;
	}

	$params['per_page'] = (int)$options['related_stories_count'];

	$feed_url = $options['news_api_base_url'];

	$param_string = http_build_query( $params );

	$url = "$feed_url?$param_string";

	$args = array(
		'timeout' => 5
	);

	$response = wp_remote_get( $url, $args );

	if ( wp_remote_retrieve_response_code( $response ) >= 400 ) return $retval;

	$stories = json_decode( wp_remote_retrieve_body( $response ) );

	foreach( $stories as $story ) {
		$retval[] = array(
			'title'     => $story->title->rendered,
			'url'       => $story->link,
			'thumbnail' => $story->thumbnail
		);
	}

	return $retval;
}

/**
 * Returns an array of formatted stories.
 *
 * @author Jim Barnes
 * @since 6.0.0
 * @param array The array of stories from ACF
 * @return array
 */
function get_curated_stories( $stories ) {
	$options = get_option( THEME_OPTIONS_NAME );

	$retval = array();

	foreach( $stories as $story ) {
		switch( $story['story_type'] ) {
			case 'pegasus':
				$p = $story['pegasus_story'];
				$retval[] = array(
					'title'     => $p->post_title,
					'url'       => get_permalink( $p ),
					'thumbnail' => get_featured_image_url( $p->ID, 'single-post-thumbnail-300x200' )
				);
				break;
			case 'today':
				$post_array = get_today_story_from_url( $story['today_story_url'] );
				if ( $post_array ) $retval[] = $post_array;
				break;
		}
	}

	return $retval;
}

/**
 * Retrieves the JSON for a today story
 * based on the URL passed to it.
 *
 * @author Jim Barnes
 * @since 6.0.0
 * @param string $story_url The URL of the story to retrieve
 * @return array|false Returns the story array or false if it cannot.
 */
function get_today_story_from_url( $story_url ) {
	$options = get_option( THEME_OPTIONS_NAME );

	$path = parse_url( $story_url,  PHP_URL_PATH );
	$split_path = explode( '/', $path );

	$story_slug = false;

	foreach( $split_path as $part ) {
		switch( $part ) {
			case '':
			case 'news':
				break;
			default:
				$story_slug = $part;
				break;
		}
	}

	if ( ! $story_slug ) return false;

	$args = array(
		'timeout' => 5
	);

	$params = array(
		'slug' => $story_slug
	);

	$param_string = http_build_query( $params );
	$feed_url = $options['news_api_base_url'];
	$url = "$feed_url?$param_string";

	$response = wp_remote_get( $url, $args );
	if ( wp_remote_retrieve_response_code( $response ) >= 400 ) return false;

	$stories = json_decode( wp_remote_retrieve_body( $response ) );
	if ( count( $stories ) < 1) return false;

	$story = $stories[0];

	return array(
		'title'     => $story->title->rendered,
		'url'       => $story->link,
		'thumbnail' => $story->thumbnail
	);
}
