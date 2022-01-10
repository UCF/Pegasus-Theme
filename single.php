<?php disallow_direct_load( 'single.php' ); ?>
<?php get_version_header(); the_post(); ?>

<div class="container page-content" id="<?php echo $post->post_name; ?>">
	<div class="row my-4">
		<div class="col-md-9">
			<article>
				<?php if ( ! is_front_page() ) : ?>
					<h1><?php the_title(); ?></h1>
				<?php endif; ?>
				<?php the_content();?>
			</article>
		</div>
		<div id="sidebar" class="col-lg-3">
			<?php echo get_sidebar(); ?>
		</div>
	</div>
</div>

<?php get_version_footer(); ?>
