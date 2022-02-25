<?php
/**
 * Template Name: Full Width
 * Template Post Type: page, story
 */
?>
<?php disallow_direct_load('template-fullwidth.php');?>
<?php get_version_header(); the_post();?>
<article id="<?php echo $post->post_name; ?>">
	<?php the_content(); ?>
</article>
<?php get_version_footer();?>
