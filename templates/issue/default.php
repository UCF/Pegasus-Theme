<?php disallow_direct_load('default.php');?>
<?php add_filter('the_content', 'kill_empty_p_tags', 999); ?>

<h2><?=$post->post_title?></h2>
this is the default template.