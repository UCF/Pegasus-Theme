<?php disallow_direct_load('single-issue.php');?>
<?php get_version_header(); the_post();?>
<?php display_markup_or_template_v1($post); ?>
<?php get_version_footer();?>
