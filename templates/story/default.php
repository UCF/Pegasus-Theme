<?php disallow_direct_load('default.php');?>
<?php add_filter('the_content', 'kill_empty_p_tags', 999); ?>
<?php
	$header_img_id = get_post(get_post_meta($post->ID, 'story_default_header_img', TRUE))->ID;
	$header_img = wp_get_attachment_url($header_img_id);
?>

<article class="story story-default">
	<?php if ($header_img_id) { ?>
	<div class="container-wide story-header-image" style="background-image: url('<?=$header_img?>');">
		<img src="<?=$header_img?>" alt="<?=$post->post_title?>" title="<?=$post->post_title?>" />
	</div>
	<?php } ?>
	
	<div class="container">
		<div class="row title-wrap">
			<div class="span10 offset1">
				<h1><?=$post->post_title?></h1>
			</div>
		</div>
		<div class="row description-wrap">
			<div class="span10 offset1">
				<span class="description">
					<?=get_post_meta($post->ID, 'story_description', TRUE);?>
				</span>
				<div class="social-wrap">
					<?=display_social(get_permalink($post->ID), $post->post_title)?>
				</div>
			</div>
		</div>

		<div class="row content-wrap">
			<div class="span10 offset1">
				<?=the_content()?>
			</div>
		</div>
	</div>
</article>