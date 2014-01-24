<?php disallow_direct_load('home.php');?>
<?php $post = get_current_issue(); ?>
<?php get_header(); ?>
<?php display_issue($post) ?>
<?php get_footer();?>