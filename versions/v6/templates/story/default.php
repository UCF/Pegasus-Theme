<?php disallow_direct_load('default.php');?>
<?php add_filter('the_content', 'kill_empty_p_tags', 999); ?>

<article class="story story-default">
	<div class="container">
		<?php echo display_story_header_contents( $post ); ?>

		<div class="row content-wrap">
			<div class="col-md-10 offset-md-1">
				<?php echo the_content(); ?>
			</div>
		</div>
	</div>
</article>
