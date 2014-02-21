<?php disallow_direct_load('single-issue.php');?>
<?php get_header(); the_post();?>
<?php display_issue($post) ?>
<?php get_footer();?>