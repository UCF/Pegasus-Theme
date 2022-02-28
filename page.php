<?php disallow_direct_load('page.php');?>
<?php get_version_header(); the_post();?>
<article id="<?php echo $post->post_name; ?>">
	<div class="container my-4 my-lg-5">
		<?php the_content(); ?>
	</div>
</article>
<?php get_version_footer();?>
