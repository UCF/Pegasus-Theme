<?php disallow_direct_load('page.php');?>
<?php get_header(); the_post();?>
<section class="container">
	<?php 
	$caption = get_post_meta($post->ID, 'ss_slide_caption');
	var_dump($caption);

	$image = get_post_meta($post->ID, 'ss_slide_image');
	var_dump($image);
	?>
</section>
<?php get_footer();?>