<?php disallow_direct_load('default.php');?>
<?php add_filter('the_content', 'kill_empty_p_tags', 999); ?>
<?php
$header_img_id = get_story_header_image_id( $post );
$header_img = '';
if ( $header_img_id ) {
	$header_img = wp_get_attachment_image(
		$header_img_id,
		'story-featured-image',
		false,
		array(
			'class' => 'img-responsive',
			'alt' => ''
		)
	);
}
?>

<article class="story story-default">
	<div class="container">
		<?php echo $header_img; ?>
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
