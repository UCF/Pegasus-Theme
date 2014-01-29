<?php disallow_direct_load('default.php');?>
<?php remove_filter('the_content', 'wpautop'); ?>

<h2><?=$post->post_title?></h2>
this is the default template.