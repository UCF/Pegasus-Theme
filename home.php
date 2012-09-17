<?php disallow_direct_load('home.php');?>
<?php $post = get_current_issue(); ?>
<?php get_header(); ?>
<?php echo apply_filters('the_content', str_replace(']]>', ']]&gt;', $post->post_content)); ?>
<?php get_footer();?>