<?php disallow_direct_load('default.php');?>
<?php add_filter('the_content', 'kill_empty_p_tags', 999); ?>
<?php
$spotlight = get_field( 'sidebar_spotlight', $post );
$byline    = get_author_byline( $post );
?>

<article class="story <?php echo $post->post_status; ?> post-list-item"  aria-label="<?php echo esc_attr( get_the_title() ); ?>">
	<div class="container my-4">
		<?php echo display_story_header_contents( $post ); ?>

		<div class="row">
			<div class="story-content col-lg-8 mb-3">
				<?php echo the_content(); ?>
			</div>
			<div class="story-sidebar col-lg-4 pr-lg-4 pr-xl-5 flex-lg-first">
				<div class="row mb-4 mb-lg-5">
					<div class="col-6 col-lg-12 byline-issue mb-lg-5">
						<?php if ( $byline ) : ?>
						<span class="d-block mb-2"><?php echo $byline; ?></span>
						<?php endif; ?>
						<a class="text-default-aw" href="<?php echo get_permalink( get_relevant_issue( $post ) ); ?>"><?php echo get_the_title( get_relevant_issue( $post ) ); ?></a>
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
