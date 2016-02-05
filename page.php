<?php disallow_direct_load('page.php');?>
<?php get_version_header(); the_post();?>
<article>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<?php the_content(); ?>
			</div>
		</div>
	</div>
</article>
<?php get_version_footer();?>
