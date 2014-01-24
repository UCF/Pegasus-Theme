<?php disallow_direct_load('default.php');?>
<?php
	$header_img_id = get_post_meta($post->ID, 'story_default_header_img', TRUE);
	$header_img = get_post($header_img_id)->guid;
?>

<article class="story story-default">
	<div class="story-header-image" style="background-image: url('<?=$header_img?>'); filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?=$header_img?>',sizingMethod='scale')">
		<img class="clip-hide" src="<?=$header_img?>" alt="<?=$post->post_title?>" title="<?=$post->post_title?>" />
	</div>

	<div class="container">
		<div class="row">
			<div class="span10 offset1">
				<h1><?=$post->post_title?></h1>
			</div>
			<div class="span6 offset1">
				<span class="subtitle">
					<?=get_post_meta($post->ID, 'story_subtitle', TRUE);?>
				</span>
			</div>
			<div class="span3">
				social
			</div>
			<div class="span10 offset1">
				<?=the_content()?>
			</div>
		</div>
	</div>
</article>