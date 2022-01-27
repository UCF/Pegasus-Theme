<?php disallow_direct_load( 'single-photo_essay.php' ); ?>
<?php get_version_header(); the_post(); ?>

<article class="photo-essay">
	<?php echo display_photo_essay( $post ); ?>
</article>

<?php get_version_footer();?>
