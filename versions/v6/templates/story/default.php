<?php disallow_direct_load('default.php');?>
<?php add_filter('the_content', 'kill_empty_p_tags', 999); ?>

<article class="story story-default">
	<div class="container">
		<?php echo display_story_header_contents( $post ); ?>

		<div class="row content-wrap">
			<div class="col-lg-10 col-md-10 mb-5 mb-lg-2">
				<?php echo the_content(); ?>
			</div>
			<div class="col-lg-2 col-md-2 flex-lg-first">
				<?php include 'related-stories.php'; ?>
			</div>
		</div>
	</div>
</article>
