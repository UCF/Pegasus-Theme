<?php disallow_direct_load('single-story.php');?>
<?php get_header(); the_post();?>
<?php
	$dev_story_directory = get_post_meta($post->ID, 'story_dev_directory', TRUE);
	$story_template = get_post_meta($post->ID, 'story_template', TRUE);

	// If developer mode is on and this is a custom story,
	// try to use dev directory contents:
	if (
		DEV_MODE == true && 
		$post->post_content == '' &&
		$dev_story_directory !== False &&
		uses_custom_template($post)
	) {
		$dev_story_html_url = THEME_DEV_URL.'/'.$dev_story_directory.$post->post_name.'.html';
		if (curl_exists($dev_story_html_url)) {
			$content = file_get_contents($dev_story_html_url);
			print apply_filters('the_content', $content);
		}
	}
	else {
		switch (get_post_meta($post->ID, 'story_template', TRUE)) {
			case 'default':
				require_once('templates/story/default.php');
				break;
			case 'gallery':
				require_once('templates/story/gallery.php');
				break;
			case 'custom':
			default:
				the_content();
				break;
		}
	}
?>
<?php get_footer();?>