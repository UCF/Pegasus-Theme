<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta name="viewport" content="width=device-width">
		<?="\n".header_()."\n"?>
		<?php if(GA_ACCOUNT or CB_UID):?>

		<script type="text/javascript">
			var _sf_startpt = (new Date()).getTime();
			<?php if(GA_ACCOUNT):?>
			
			var GA_ACCOUNT = '<?=GA_ACCOUNT?>';
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', GA_ACCOUNT]);
			_gaq.push(['_setDomainName', 'none']);
			_gaq.push(['_setAllowLinker', true]);
			_gaq.push(['_trackPageview']);
			<?php endif;?>
			<?php if(CB_UID):?>
	
			var CB_UID = '<?=CB_UID?>';
			var CB_DOMAIN = '<?=CB_DOMAIN?>';
			<?php endif?>
		</script>
		<?php endif;?>
		
		<script type="text/javascript">
			var IPAD_DEPLOYED = <?=ipad_deployed() ? 'true' : 'false'?>;
		</script>

		<? if( is_home() && ( $issue_stylesheet_url = Issue::get_issue_stylesheet_url($post)) !== False) { ?>
			<link rel='stylesheet' href="<?=$issue_stylesheet_url?>" type='text/css' media='all' />
		<? } ?>
		
		<? if ( is_home() && ( $home_stylesheet_url = Issue::get_home_stylesheet_url($post)) !== False) { ?>
			<link rel='stylesheet' href="<?=$home_stylesheet_url?>" type='text/css' media='all' />
		<? } ?>

		<? if( $post->post_type == 'story' && ($story_issue = get_story_issue($post)) !== False && ($issue_stylesheet_url = Issue::get_issue_stylesheet_url($story_issue)) !== False ) { ?>
				<link rel='stylesheet' href="<?=$issue_stylesheet_url?>" type='text/css' media='all' />
		<? } ?>

		<? if($post->post_type == 'story' && ($story_stylesheet_url = Story::get_stylesheet_url($post)) !== False) { ?>
				
			<link rel='stylesheet' href="<?=$story_stylesheet_url?>" type='text/css' media='all' />
		<? } ?>

		<? if($post->post_type == 'page' && ($page_stylesheet_url = Page::get_stylesheet_url($post)) !== False) { ?>
			<link rel='stylesheet' href="<?=$page_stylesheet_url?>" type='text/css' media='all' />
		<? } ?>
		
	</head>
	<? extract(get_navigation_stories()); ?>
	<body class="<?=body_classes()?> <? if ($post->post_type == 'page') { print 'subpage'; }?>">
		<div id="ipad" class="modal">
			<div class="modal-header">
				<h3>Pegasus Magazine is available on the iPad!</h3>
				</div>
			<div class="modal-body">
				<a href="<?=get_theme_option('ipad_app_url')?>" class="btn btn-primary">Go to iTunes</a>
				<a href="#" class="btn" data-dismiss="modal">Continue to Web Version</a>
			</div>
			<div class="modal-footer">
			</div>
		</div>
		<div class="container wide header_stories">
			<div class="row">
				<div class="span12">
					<h3>More in this Issue</h3>
				</div>
				<div class="span12">
					<div class="row">
						<? foreach($top_stories as $story) {?>
						<div class="span3">
							<a href="<?=get_permalink($story->ID)?>">
								<div class="thumb">
									<img src="<?=get_featured_image_url($story->ID)?>" />
								</div>
								<div class="title">
									<span class="title_text"><?=apply_filters('the_title', $story->post_title)?><?php if (get_post_meta($story->ID, 'story_subtitle', True)) { ?>: </span><span class="subtitle_text"><?=get_post_meta($story->ID, 'story_subtitle', True)?></span><?php } else { ?></span><?php } ?>
								</div>
							</a>
							</div>
						<? } ?>
					</div>
				</div>
			</div>
			<div class="row bottom">
				<? foreach($bottom_stories as $story) {?>
					<div class="span2">
						<a href="<?=get_permalink($story->ID)?>">
							<div class="thumb">
								<img src="<?=get_featured_image_url($story->ID)?>" />
							</div>
							<div class="title">
								<span class="title_text"><?=apply_filters('the_title', $story->post_title)?></span>
							</div>
						</a>
					</div>
				<? } ?>
			</div>
		</div>
		<div class="container wide" id="header">
			<div class="row">
				<div class="span12">
					<div class="row" style="position:relative;">
						<a href="<?=site_url()?>" class="span3 title">
						PEGASUS
						</a>
						<div class="span5 issue">
							<?php $current_issue = get_current_issue(); echo $current_issue->post_title; ?>
						</div>
			
						<div class="span4 description">
							The Magazine of the University of Central Florida
						</div>
						<div class="toggle_story_nav">
							<a>&#9650;</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container" id="body_content">