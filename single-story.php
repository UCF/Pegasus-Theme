<?php disallow_direct_load('single-story.php');?>
<?php get_header(); the_post();?>
<?php
	$dev_story_directory = get_post_meta($post->ID, 'story_dev_directory', TRUE);
	$story_template      = get_post_meta($post->ID, 'story_template', TRUE);
	$uploaded_html       = get_post_meta($post->ID, 'story_html', TRUE);
	$uploaded_html_url   = wp_get_attachment_url($uploaded_html);

	// If developer mode is on and this is a custom story,
	// try to use dev directory contents:
	if (
		DEV_MODE == 1 &&
		empty($post->post_content) &&
		$dev_story_directory !== False &&
		uses_custom_template($post)
	) {
		add_filter('the_content', 'kill_empty_p_tags', 999);

		// Uploaded HTML file should always take priority over dev directory contents
		if (!empty($uploaded_html) && !empty($uploaded_html_url)) {
			print apply_filters('the_content', file_get_contents($uploaded_html_url));
		}
		else {
			$dev_story_html_url = THEME_DEV_URL.'/'.$dev_story_directory.$post->post_name.'.html';
			if (curl_exists($dev_story_html_url)) {
				$content = file_get_contents($dev_story_html_url);
				print apply_filters('the_content', $content);
			}
		}
	}
	else {
		switch (get_post_meta($post->ID, 'story_template', TRUE)) {
			case 'default':
				require_once('templates/story/default.php');
				break;
			case 'photo_essay':
				require_once('templates/story/photo_essay.php');
				break;
			case 'custom':
				if (!is_fall_2013_or_older($post)) {
					// Kill automatic <p> tag insertion if this isn't an old story.
					// Don't want to accidentally screw up an old story that worked
					// around the <p> tag issue.
					add_filter('the_content', 'kill_empty_p_tags', 999);

					// If an uploaded HTML file is present, use it.  Otherwise, use
					// any content available in the WYSIWYG editor
					if (!empty($uploaded_html) && !empty($uploaded_html_url)) {
						print apply_filters('the_content', file_get_contents($uploaded_html_url));
					}
					else {
						the_content();
					}
				}
				else {
					the_content();
				}
				break;
			default: // field value is empty
				if (!is_fall_2013_or_older($post)) {
					// Field value should never be empty for not-old stories, but
					// fall back to default.php anyway
					require_once('templates/story/default.php');
				}
				else {
					// Use WYSIWYG editor contents for old stories that don't have a
					// field value set
					the_content();
				}
				break;
		}
	}
?>
<?php get_footer();?>
