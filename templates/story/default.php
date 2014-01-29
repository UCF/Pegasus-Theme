<?php disallow_direct_load('default.php');?>
<?php remove_filter('the_content', 'wpautop'); ?>
<?php
	$header_img_id = get_post_meta($post->ID, 'story_default_header_img', TRUE);
	$header_img = get_post($header_img_id)->guid;
?>

<article class="story story-default">
	<div class="container-wide story-header-image" style="background-image: url('<?=$header_img?>'); filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?=$header_img?>',sizingMethod='scale')">
		<img src="<?=$header_img?>" alt="<?=$post->post_title?>" title="<?=$post->post_title?>" />
	</div>

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