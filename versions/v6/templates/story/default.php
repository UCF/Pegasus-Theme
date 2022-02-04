<?php disallow_direct_load('default.php');?>
<?php add_filter('the_content', 'kill_empty_p_tags', 999); ?>
<?php
$spotlight = get_field( 'sidebar_spotlight', $post );
?>

<article class="story <?php echo $post->post_status; ?> post-list-item"  aria-label="<?php echo esc_attr( get_the_title() ); ?>">
	<div class="container my-4">
		<?php echo display_story_header_contents( $post ); ?>

		<div class="row">
			<div class="col-lg-8 offset-xl-1 mb-3">
				<?php echo the_content(); ?>
			</div>
			<div class="story-sidebar col-lg-4 col-xl-3 flex-lg-first">
				<div class="row mb-4 mb-lg-5">
					<div class="col-6 col-lg-12 byline-issue mb-lg-5">
						<span class="d-block mb-2">TODO: Create byline field</span>
						<a class="text-secondary font-weight-bold" href="<?php echo get_permalink( get_relevant_issue( $post ) ); ?>">Fall 2021</a>
					</div>
					<div class="col-6 col-lg-12 social-wrap">
						<?php echo do_shortcode( '[ucf-social-links]' ); ?>
					</div>
				</div>
				<?php if ( $spotlight ) : ?>
				<div class="mb-4 mb-lg-5">
					<?php echo do_shortcode( '[ucf-spotlight slug="' . $spotlight->post_name . '"]' ); ?>
				</div>
				<?php endif; ?>
				<?php include 'related-stories.php'; ?>
			</div>
		</div>
	</div>
</article>
