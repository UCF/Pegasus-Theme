<?php disallow_direct_load('home.php');?>
<?php $post = get_current_issue(); ?>
<?php get_header(); ?>
<?php
	if (
		$post->post_content == '' &&
		DEV_MODE == true && 
		($dev_issue_home_directory = get_post_meta($post->ID, 'issue_dev_home_asset_directory', TRUE)) !== False) 
		{
		$dev_issue_html_url = THEME_DEV_URL.'/'.$dev_issue_home_directory.'home.html';
		if (curl_exists($dev_issue_html_url)) {
			$content = file_get_contents($dev_issue_html_url);
			print apply_filters('the_content', str_replace(']]>', ']]&gt;', $content));
		}
	}
	else {
		the_content();
	}
?>
<?php get_footer();?>