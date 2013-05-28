<?php disallow_direct_load('single-story.php');?>
<?php get_header(); the_post();?>
<?php
	if (
		$post->post_content == '' &&
		DEV_MODE == true && 
		($dev_story_directory = get_post_meta($post->ID, 'story_dev_directory', TRUE)) !== False) 
		{
		$dev_story_html_url = THEME_DEV_URL.'/'.$dev_story_directory.$post->post_name.'.html';
		if (curl_exists($dev_story_html_url)) {
			$content = file_get_contents($dev_story_html_url);
			print apply_filters('the_content', $content);
		}
	}
	else {
		the_content();
	}
?>
<?php get_footer();?>