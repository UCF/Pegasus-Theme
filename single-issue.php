<?php disallow_direct_load('single-issue.php');?>
<?php get_header(); the_post();?>
<?php
	if (
		$post->post_content == '' &&
		DEV_MODE == true && 
		($dev_issue_home_directory = get_post_meta($post->ID, 'issue_dev_home_asset_directory', TRUE)) !== False) 
		{
		$dev_issue_html_url = THEME_DEV_URL.'/'.$dev_issue_home_directory.'home.html';
		if (curl_exists($dev_issue_html_url)) {
			$content = file_get_contents($dev_issue_html_url);
			print apply_filters('the_content', $content);
		}
	}
	else {
		switch (get_post_meta($post->ID, 'issue_template', TRUE)) {
			case 'default':
				require_once('templates/issue/default.php');
				break;
			case 'custom':
			default:
				the_content();
				break;
		}
	}
?>
<?php get_footer();?>