<?php disallow_direct_load('default.php');?>
<?php add_filter('the_content', 'kill_empty_p_tags', 999); ?>
<?php 	$header_img_id = get_post_meta($post->ID, 'story_default_header_img', TRUE);
	$header_img = wp_get_attachment_url(get_post($header_img_id)->ID);
	$header_img_background_color = get_post_meta($post->ID, 'story_default_header_img_background_color', TRUE);
?>

<article class="story story-default">
	<?php 	// Deleting a header image from the media library does not
	// clear the story's meta value for the header image ID.
	// Check both these values to make sure a header image is set.
	if ($header_img_id && !empty($header_img)) {
	?>
	<div class="container-wide story-header-image" style="background-image: url('<?php echo $header_img?>');<?php echo (empty($header_img_background_color) ? '' : ' background-color: ' . $header_img_background_color . ';'); ?>">
		<img src="<?php echo $header_img?>" alt="<?php echo $post->post_title?>" title="<?php echo $post->post_title?>" />
	</div>
	<?php } ?>
	<div class="container">
		<div class="row title-wrap">
			<div class="col-md-10 col-sm-10 col-md-offset-1 col-sm-offset-1">
				<h1><?php echo wptexturize( $post->post_title ); ?></h1>
			</div>
		</div>
		<div class="row description-wrap">
			<div class="col-md-10 col-sm-10 col-md-offset-1 col-sm-offset-1">
				<div class="row">
					<div class="col-md-8 col-sm-12 col-xs-12 description-col">
						<span class="description">
							<?php echo wptexturize( get_post_meta( $post->ID, 'story_description', TRUE ) ); ?>
						</span>
					</div>
					<div class="col-md-4 col-sm-12 col-xs-12 description-col">
						<div class="social-wrap">
							<?php echo display_social(get_permalink($post->ID), $post->post_title); ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row content-wrap">
			<div class="col-md-10 col-sm-10 col-md-offset-1 col-sm-offset-1">
				<?php echo the_content(); ?>
			</div>
		</div>
	</div>
</article>
