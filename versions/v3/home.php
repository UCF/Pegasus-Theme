<?php disallow_direct_load('home.php');?>
<?php $issue = get_current_issue(); ?>
<?php get_version_header(); ?>
<?php display_markup_or_template($issue); ?>
<?php get_version_footer();?>
