<?php disallow_direct_load('single-issue.php');?>
<?php get_header(); the_post();?>
<?php display_markup_or_template($post); ?>
<?php get_footer();?>