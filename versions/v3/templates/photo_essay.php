<?php disallow_direct_load( 'photo_essay.php' ); ?>

<?php
// $story, $photo_essay inherited from shortcodes.php, single-photo_essay.php

$slide_order = trim( get_post_meta( $photo_essay->ID, 'ss_slider_slideorder', TRUE ) );
// Get rid of blank array entries
$slide_order = array_filter( explode( ',', $slide_order ), 'strlen' );
$captions = get_post_meta( $photo_essay->ID, 'ss_slide_caption', TRUE );
$titles = get_post_meta( $photo_essay->ID, 'ss_slide_title', TRUE );
$images = get_post_meta( $photo_essay->ID, 'ss_slide_image', TRUE );
?>

<section class="photo-essay-header clearfix">
	<img class="photo-essay-header-img" src="<?php echo $images[$slide_order[0]]; ?>" alt="<?php echo $titles[$slide_order[0]]; ?>" title="<?php echo $titles[$slide_order[0]]; ?>">
	<h1 class="photo-essay-title"><?php echo wptexturize( $photo_essay->post_title ); ?></h1>

	<?php if ( $story_description ): ?>
	<div class="photo-essay-description">
		<?php echo $story_description; ?>
	</div>
	<?php endif; ?>

	<?php if ( $story_social ): ?>
	<?php echo $story_social; ?>
	<?php endif; ?>
</section>

<?php
foreach ( $slide_order as $i ):
	$image = wp_get_attachment_image_src( $images[$i], 'full' );
	$image_url = $image[0];
	$image_w = $image[1];
	$image_h = $image[2];
	$caption = wptexturize( do_shortcode( $captions[$i] ) );
	$title = wptexturize( $titles[$i] );
	$orientation = '';

	if ( $image_w > $image_h ) {
		$orientation = 'landscape';
	}
	else if ( $image_w < $image_h ) {
		$orientation = 'portrait';
	}
	else {
		$orientation = 'square';
	}
?>
<figure class="photo-essay-item photo-essay-item-<?php echo $orientation; ?>">
	<img class="photo-essay-img img-responsive" src="<?php echo $image_url; ?>" alt="<?php echo $title; ?>" title="<?php echo $title; ?>">
	<figcaption class="photo-essay-caption">
		<?php echo $caption; ?>
	</figcaption>
</figure>
<?php endforeach; ?>
