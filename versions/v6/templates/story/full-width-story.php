<?php disallow_direct_load('full-width-story.php');?>
<?php add_filter('the_content', 'kill_empty_p_tags', 999); ?>
<?php
$display_header = get_field( 'story_fw_display_standard_header' );
?>

<article class="story <?php echo $post->post_status; ?> post-list-item"  aria-label="<?php echo esc_attr( get_the_title() ); ?>">
	<?php if ( $display_header ) : ?>
	<div class="container my-4">
		<?php echo display_story_header_contents( $post ); ?>
	</div>
	<?php endif; ?>

	<div class="story-content">
		<?php echo the_content(); ?>
	</div>
</article>
